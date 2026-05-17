<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        $tasks = Task::where('is_active', true)
            ->whereNotIn('type', ['timewall', 'adsterra'])
            ->where('quota_remaining', '>', 0)
            ->whereDoesntHave('submissions', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->whereIn('status', ['pending', 'approved'])
                      ->where(function ($q) {
                          $q->whereRaw('`tasks`.`cooldown_hours` = 0')
                            ->orWhereRaw('`submissions`.`created_at` >= DATE_SUB(NOW(), INTERVAL `tasks`.`cooldown_hours` HOUR)');
                      });
            })
            ->latest()
            ->paginate(15);

        return view('tasks.index', compact('tasks'));
    }

    public function show(Task $task)
    {
        abort_if(! $task->is_active || in_array($task->type, ['timewall', 'adsterra']), 404);

        // Block access if quota is full
        if ($task->quota_remaining <= 0) {
            return redirect()->route('tasks.index')
                ->with('info', '⛔ এই টাস্কের স্লট পূর্ণ হয়ে গেছে। অন্য একটি টাস্ক বেছে নিন।');
        }

        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        $alreadySubmitted = $user->submissions()
            ->where('task_id', $task->id)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($task) {
                if ($task->cooldown_hours > 0) {
                    $query->whereRaw('created_at >= DATE_SUB(NOW(), INTERVAL ? HOUR)', [$task->cooldown_hours]);
                }
            })
            ->exists();

        if ($alreadySubmitted) {
            $msg = $task->cooldown_hours > 0 
                ? "আপনি ইতিমধ্যে এই টাস্কটি সম্পন্ন করেছেন। অনুগ্রহ করে {$task->cooldown_hours} ঘণ্টা পর আবার চেষ্টা করুন।" 
                : "আপনি ইতিমধ্যে এই টাস্কটি সম্পন্ন করেছেন।";
            return redirect()->route('tasks.index')->with('info', $msg);
        }

        // Dynamic Link & Ad Replacement
        $activeDomains = \App\Models\TaskDomain::where('is_active', true)->get();
        if ($activeDomains->count() > 0) {
            $randomDomainModel = $activeDomains->random();
            $randomDomain = $randomDomainModel->domain;
            $replacements = [
                '{sub_domain}' => rtrim($randomDomain, '/'),
                '{user_id}' => $user->id,
                '{task_id}' => $task->id,
                '{ad_code_1}' => $randomDomainModel->ad_code_1 ?? '',
                '{ad_code_2}' => $randomDomainModel->ad_code_2 ?? '',
                '{ad_code_3}' => $randomDomainModel->ad_code_3 ?? '',
                '{direct_link}' => $randomDomainModel->direct_link ?? '',
            ];
            $task->description = strtr($task->description, $replacements);
            if ($task->external_link) {
                $task->external_link = strtr($task->external_link, $replacements);
            }
        }

        // Auto append uid and tid if not exists
        if ($task->external_link) {
            if (!str_contains($task->external_link, 'uid=')) {
                $separator = str_contains($task->external_link, '?') ? '&' : '?';
                $task->external_link .= $separator . 'uid=' . $user->id;
            }
            if (!str_contains($task->external_link, 'tid=')) {
                $separator = str_contains($task->external_link, '?') ? '&' : '?';
                $task->external_link .= $separator . 'tid=' . $task->id;
            }
        }

        return view('tasks.show', compact('task'));
    }

    public function submit(Request $request, Task $task)
    {
        abort_if(! $task->is_active, 403);

        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        // Build dynamic validation rules based on task's required proofs
        $rules = [];
        if ($task->requires_text_proof) {
            $rules['proof_text'] = 'required|string|min:3|max:500';
        }
        if ($task->requires_image_proof) {
            $rules['proof_image'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096';
        }

        $request->validate($rules);

        // ── Auto-Verify Secret Code (Static or Dynamic) ──────────────────────
        if ($task->requires_text_proof) {
            if ($task->secret_code) {
                // Static code verification (YouTube/Facebook video tasks)
                $expectedCode = $task->secret_code;
            } else {
                // Dynamic code verification (subdomain tasks with md5 formula)
                // 🔒 Read from .env — NOT from DB settings (DB is visible in admin panel)
                $secretSalt = env('TASK_SECRET_SALT', 'MicroJobV1Secret!');
                $expectedCode = substr(md5($user->id . $task->id . $secretSalt), 0, 8);
            }
            
            if (trim($request->proof_text) !== $expectedCode) {
                return back()->withInput()->with('error', '❌ ভুল সিক্রেট কোড! ব্লগ সাইট থেকে সঠিক কোডটি কপি করে পেস্ট করুন। আবার চেষ্টা করুন।');
            }
        }

        // ── Image Hash (compute before transaction) ──────────────────────────
        $imagePath = null;
        $imageHash = null;

        if ($request->hasFile('proof_image')) {
            $image = $request->file('proof_image');
            $imageHash = md5_file($image->getRealPath());
            $imagePath = $image->store('proofs', 'public');
        }

        // ═══ CRITICAL SECTION: DB Transaction + Row Lock ═══════════════════
        // Uses SELECT ... FOR UPDATE on the task row to serialize concurrent
        // submissions for the SAME task. Prevents:
        //   1. Duplicate submissions (race between check + insert)
        //   2. Quota going negative (race between check + decrement)
        //   3. Image hash duplicate (race between check + insert)
        //   4. Partial auto-approve failures (all-or-nothing)
        // ════════════════════════════════════════════════════════════════════
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use (
                $task, $user, $request, $imagePath, $imageHash, &$status, &$successMessage
            ) {
                // Lock the task row — blocks other concurrent submits for THIS task
                $lockedTask = Task::where('id', $task->id)->lockForUpdate()->firstOrFail();

                // ── Quota Check (BEFORE submission, under lock) ─────────────
                if ($lockedTask->quota_remaining <= 0) {
                    throw new \RuntimeException('দুঃখিত! এই টাস্কের কোটা শেষ হয়ে গেছে। অন্য টাস্ক চেষ্টা করুন।');
                }

                // ── Duplicate Submission Check (under lock) ─────────────────
                $alreadyDone = $user->submissions()
                    ->where('task_id', $task->id)
                    ->whereIn('status', ['pending', 'approved'])
                    ->where(function ($query) use ($task) {
                        if ($task->cooldown_hours > 0) {
                            $query->whereRaw('created_at >= DATE_SUB(NOW(), INTERVAL ? HOUR)', [$task->cooldown_hours]);
                        }
                    })
                    ->exists();

                if ($alreadyDone) {
                    $msg = $task->cooldown_hours > 0
                        ? "আপনি ইতিমধ্যে এই টাস্কটি সম্পন্ন করেছেন। অনুগ্রহ করে {$task->cooldown_hours} ঘণ্টা পর আবার চেষ্টা করুন।"
                        : "আপনি ইতিমধ্যে এই টাস্কটি সম্পন্ন করেছেন।";
                    throw new \RuntimeException($msg);
                }

                // ── Anti-Cheat: Image Hash Duplicate Check (under lock) ────
                if ($imageHash) {
                    $isDuplicate = Submission::where('proof_hash', $imageHash)->exists();
                    if ($isDuplicate) {
                        throw new \RuntimeException('❌ এই স্ক্রিনশটটি আগে কেউ ব্যবহার করেছে। নতুন স্ক্রিনশট আপলোড করুন। (Duplicate image detected!)');
                    }
                }

                // ── Auto-Approve Logic ──────────────────────────────────────
                $status = 'pending';
                $successMessage = '✅ টাস্কটি সাবমিট হয়েছে! পুরস্কার: ' . number_format($task->points) . ' PTS। আমাদের টিম ২৪ ঘণ্টার মধ্যে রিভিউ করবে।';

                if ($task->requires_text_proof) {
                    $status = 'approved';
                    
                    $pointConversionRate = (int) \App\Models\Setting::get('point_conversion_rate', 100);
                    $userPoints = (int) $task->points;
                    // admin_profit is stored as BDT (not points) — use directly, no conversion
                    $adminProfitBdt = (float) ($task->admin_profit ?? 0);
                    $userRewardBdt = $userPoints / $pointConversionRate;

                    // Lock user row for atomic balance update
                    $lockedUser = User::where('id', $user->id)->lockForUpdate()->firstOrFail();

                    if (!$lockedUser->first_earning_at) {
                        $lockedUser->first_earning_at = now();
                        $lockedUser->save();
                    }
                    $lockedUser->increment('points', $userPoints);
                    $lockedUser->increment('total_earned_bdt', $userRewardBdt);
                    $lockedUser->increment('total_earned_lifetime', $userRewardBdt);
                    $lockedUser->increment('total_admin_profit_generated', $adminProfitBdt);

                    // Log transaction
                    \App\Models\Transaction::create([
                        'user_id' => $user->id,
                        'amount_points' => $userPoints,
                        'amount_bdt' => $userRewardBdt,
                        'admin_profit' => $adminProfitBdt,
                        'user_reward' => $userPoints,
                        'type' => 'task_completion',
                        'source' => 'Custom Task',
                        'task_id' => $task->id,
                        'description' => 'Auto-Approved Task: '.$task->title,
                        'status' => 'completed',
                    ]);

                    // Referral unlock check
                    app(\App\Services\ReferralService::class)->handleProfitGenerated($lockedUser);
                    
                    $successMessage = '🎉 অভিনন্দন! সিক্রেট কোড সফলভাবে যাচাই করা হয়েছে। আপনার অ্যাকাউন্টে ' . number_format($task->points) . ' PTS যোগ করা হয়েছে।';
                }

                // ── Create Submission ───────────────────────────────────────
                Submission::create([
                    'task_id' => $task->id,
                    'user_id' => $user->id,
                    'status' => $status,
                    'proof_text' => $request->proof_text,
                    'proof_image' => $imagePath,
                    'proof_hash' => $imageHash,
                ]);

                // ── Decrement Quota (atomic under lock) ────────────────────
                $lockedTask->decrement('quota_remaining');
            });

            return redirect()->route('tasks.index')
                ->with('success', $successMessage);

        } catch (\RuntimeException $e) {
            // User-facing errors (duplicate, quota exhausted, duplicate image)
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            // Deadlock retry or unexpected errors
            if (str_contains($e->getMessage(), 'Deadlock')) {
                // InnoDB deadlock — tell user to retry
                return back()->with('warning', '⚠️ সিস্টেম ব্যস্ত ছিল। দয়া করে আবার "Submit" বাটনে ক্লিক করুন।');
            }
            \Illuminate\Support\Facades\Log::error('Task submit failed', [
                'user_id' => $user->id,
                'task_id' => $task->id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', '❌ একটি অপ্রত্যাশিত সমস্যা হয়েছে। দয়া করে আবার চেষ্টা করুন।');
        }
    }
}
