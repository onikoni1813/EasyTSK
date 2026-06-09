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
            ->where(function ($q) {
                // quota_remaining = -1 means unlimited (always show)
                $q->where('quota_remaining', '=', -1)
                  ->orWhere('quota_remaining', '>', 0);
            })
            ->whereDoesntHave('submissions', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->whereIn('status', ['pending', 'approved'])
                      ->where(function ($q) {
                          $q->whereRaw('`tasks`.`cooldown_hours` = 0')
                            ->orWhereRaw('`submissions`.`created_at` >= DATE_SUB(NOW(), INTERVAL `tasks`.`cooldown_hours` HOUR)');
                      });
            })
            ->orderBy('sort_order')
            ->paginate(15);

        // Build set of locked task IDs for this user
        $lockedTaskIds = $tasks->filter(fn($t) => ! $t->isUnlockedFor($user))
                               ->pluck('id')
                               ->toArray();

        return view('tasks.index', compact('tasks', 'lockedTaskIds'));
    }

    public function show(Task $task)
    {
        abort_if(! $task->is_active || in_array($task->type, ['timewall', 'adsterra']), 404);

        // Block access if quota is full (-1 = unlimited, always allow)
        if ($task->quota_remaining !== -1 && $task->quota_remaining <= 0) {
            return redirect()->route('tasks.index')
                ->with('info', '⛔ এই টাস্কের স্লট পূর্ণ হয়ে গেছে। অন্য একটি টাস্ক বেছে নিন।');
        }

        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        // ── Sequential Lock Check ────────────────────────────────
        if (! $task->isUnlockedFor($user)) {
            return redirect()->route('tasks.index')
                ->with('info', '🔒 এই টাস্কটি এখনো লক আছে। আগের টাস্কটি সম্পন্ন করুন এবং Approve হলে এটি আনলক হবে।');
        }
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

        // ── Sequential Lock Check (server-side guard) ─────────────────────
        if (! $task->isUnlockedFor($user)) {
            return back()->with('error', '\ud83d\udd12 \u098f\u0987 \u099f\u09be\u09b8\u09cd\u0995\u099f\u09bf \u098f\u0996\u09a8\u09cb \u09b2\u0995 \u0986\u099b\u09c7\u0964 \u0986\u0997\u09c7\u09b0 \u099f\u09be\u09b8\u09cd\u0995\u099f\u09bf Approved \u09b9\u09b2\u09c7 \u098f\u099f\u09bf \u0986\u09a8\u09b2\u0995 \u09b9\u09ac\u09c7\u0964');
        }

        // Build dynamic validation rules based on task's required proofs
        $rules = [];
        $codeCount  = max(1, (int) ($task->secret_code_count ?? 1));
        $imageCount = max(1, (int) ($task->image_proof_count ?? 1));

        if ($task->requires_text_proof) {
            for ($i = 0; $i < $codeCount; $i++) {
                $rules["proof_codes.{$i}"] = 'required|string|min:3|max:64';
            }
        }
        if ($task->requires_image_proof) {
            for ($i = 0; $i < $imageCount; $i++) {
                $rules["proof_images.{$i}"] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096';
            }
        }
        if ($task->requires_email_proof) {
            $rules['proof_email']    = 'required|email|max:255';
            $rules['proof_password'] = 'required|string|min:6|max:255';
        }

        $request->validate($rules);

        // ── Secret Code Verification (Multiple) ──────────────────────────────
        if ($task->requires_text_proof) {
            $submittedCodes = $request->input('proof_codes', []);
            $secretSalt     = env('TASK_SECRET_SALT', 'MicroJobV1Secret!');
            $storedCodes    = $task->secret_codes ?? [];

            foreach ($submittedCodes as $idx => $submittedCode) {
                $submittedCode = trim($submittedCode);
                $isValid = false;

                // Check against stored static code for this slot
                if (isset($storedCodes[$idx]) && $storedCodes[$idx] !== '') {
                    $isValid = ($submittedCode === $storedCodes[$idx]);
                } elseif ($task->secret_code) {
                    // Fallback: single static code
                    $isValid = ($submittedCode === $task->secret_code);
                } else {
                    // Dynamic code
                    $expectedCode    = substr(md5($user->id . $task->id . $secretSalt), 0, 8);
                    $seoFallbackCode = substr(md5(date('Y-m-d') . $secretSalt), 0, 8);
                    $isValid = ($submittedCode === $expectedCode || $submittedCode === $seoFallbackCode);
                }

                if (!$isValid) {
                    $num = $idx + 1;
                    return back()->withInput()->with('error', "❌ সিক্রেট কোড #{$num} ভুল! সঠিক কোডটি কপি করে পেস্ট করুন।");
                }
            }
        }

        // ── Multiple Images (compute hashes before transaction) ───────────────
        $imagePaths = [];
        $imageHashes = [];
        // Legacy single-image fields (kept for backward compat)
        $imagePath = null;
        $imageHash = null;

        if ($task->requires_image_proof && $request->hasFile('proof_images')) {
            foreach ($request->file('proof_images') as $image) {
                $hash = md5_file($image->getRealPath());
                $path = $image->store('proofs', 'public');
                $imagePaths[]  = $path;
                $imageHashes[] = $hash;
            }
            // Legacy compat: first image
            if (!empty($imagePaths)) {
                $imagePath = $imagePaths[0];
                $imageHash = $imageHashes[0];
            }
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
                $task, $user, $request, $imagePath, $imageHash,
                $imagePaths, $imageHashes, &$status, &$successMessage
            ) {
                // Lock the task row — blocks other concurrent submits for THIS task
                $lockedTask = Task::where('id', $task->id)->lockForUpdate()->firstOrFail();

                // ── Quota Check (BEFORE submission, under lock) ─────────────
                // quota_remaining = -1 means UNLIMITED (no limit)
                if ($lockedTask->quota_remaining !== -1 && $lockedTask->quota_remaining <= 0) {
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

                // ── Anti-Cheat: All Image Hash Duplicate Checks (under lock) ──
                foreach ($imageHashes as $hash) {
                    if (Submission::where('proof_hash', $hash)->orWhereJsonContains('proof_hashes', $hash)->exists()) {
                        throw new \RuntimeException('❌ একটি স্ক্রিনশট আগে কেউ ব্যবহার করেছে। নতুন স্ক্রিনশট আপলোড করুন। (Duplicate image detected!)');
                    }
                }

                // ── Auto-Approve Logic ──────────────────────────────────────
                // শুধু Secret Code একা থাকলেই auto-approve হবে।
                // Image proof বা Email proof থাকলে admin review-এ যাবে।
                $status = 'pending';
                $successMessage = '✅ টাস্কটি সাবমিট হয়েছে! পুরস্কার: ' . number_format($task->points) . ' PTS। আমাদের টিম ২৪ ঘণ্টার মধ্যে রিভিউ করবে।';

                $onlySecretCode = $task->requires_text_proof
                    && !$task->requires_image_proof
                    && !$task->requires_email_proof;

                if ($onlySecretCode) {
                    $status = 'approved';

                    $pointConversionRate = (int) \App\Models\Setting::get('point_conversion_rate', 100);
                    $totalPoints    = (int) $task->points;
                    $adminProfit    = (int) ($task->admin_profit ?? 0);
                    $userPoints     = max(0, $totalPoints - $adminProfit);
                    $adminProfitBdt = $adminProfit / $pointConversionRate;
                    $userRewardBdt  = $userPoints / $pointConversionRate;

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
                        'user_id'       => $user->id,
                        'amount_points' => $userPoints,
                        'amount_bdt'    => $userRewardBdt,
                        'admin_profit'  => $adminProfitBdt,
                        'user_reward'   => $userPoints,
                        'type'          => 'task_completion',
                        'source'        => 'Custom Task',
                        'task_id'       => $task->id,
                        'description'   => 'Auto-Approved Task: '.$task->title,
                        'status'        => 'completed',
                    ]);

                    // Referral unlock check
                    app(\App\Services\ReferralService::class)->handleProfitGenerated($lockedUser);

                    $successMessage = '🎉 অভিনন্দন! সিক্রেট কোড সফলভাবে যাচাই করা হয়েছে। আপনার অ্যাকাউন্টে ' . number_format($userPoints) . ' PTS যোগ করা হয়েছে।' . ($adminProfit > 0 ? ' (প্ল্যাটফর্ম রিজার্ভ: ' . $adminProfit . ' PTS)' : '');
                }

                // ── Create Submission ───────────────────────────────────────
                $submittedCodes = $request->input('proof_codes', []);
                Submission::create([
                    'task_id'        => $task->id,
                    'user_id'        => $user->id,
                    'status'         => $status,
                    // Legacy single-proof (backward compat)
                    'proof_text'     => !empty($submittedCodes) ? implode(' | ', $submittedCodes) : null,
                    'proof_email'    => $request->proof_email,
                    'proof_password' => $request->proof_password,
                    'proof_image'    => $imagePath,
                    'proof_hash'     => $imageHash,
                    // New multi-proof JSON
                    'proof_texts'    => !empty($submittedCodes)  ? $submittedCodes  : null,
                    'proof_images'   => !empty($imagePaths)      ? $imagePaths      : null,
                    'proof_hashes'   => !empty($imageHashes)     ? $imageHashes     : null,
                ]);

                // ── Decrement Quota (atomic under lock) ────────────────────
                // -1 = unlimited, do not decrement
                if ($lockedTask->quota_remaining !== -1) {
                    $lockedTask->decrement('quota_remaining');
                }
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
