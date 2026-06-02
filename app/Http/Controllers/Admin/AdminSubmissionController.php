<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Transaction;
use App\Models\UserNotification;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminSubmissionController extends Controller
{
    public function index()
    {
        $submissions = Submission::with(['task', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(50); // Ajax grid: 50 per page for speed

        return view('admin.submissions.index', compact('submissions'));
    }

    public function approve(Request $request, Submission $submission)
    {
        try {
            DB::transaction(function () use ($submission) {
                // Lock and atomically check-and-update within transaction
                $fresh = Submission::where('id', $submission->id)
                    ->where('status', 'pending')
                    ->lockForUpdate()
                    ->first();

                if (!$fresh) {
                    throw new \RuntimeException('Already processed.');
                }

                $fresh->update([
                    'status' => 'approved',
                    'moderated_by' => Auth::id(),
                    'moderated_at' => now(),
                ]);

                $task = $fresh->task;
                $user = $fresh->user;

                $rate = (int) setting('point_conversion_rate', 100);

                $totalPoints    = (int) $task->points;                    // মোট পয়েন্ট
                $adminProfit    = (int) ($task->admin_profit ?? 0);        // প্ল্যাটফর্ম কাটবে
                $userPoints     = max(0, $totalPoints - $adminProfit);     // ইউসার পাবে
                $userRewardBdt  = $userPoints / $rate;
                $adminProfitBdt = $adminProfit / $rate;

                // Credit user (deducted from total)
                $user->increment('points', $userPoints);
                $user->increment('total_earned_bdt', $userRewardBdt);
                $user->increment('total_earned_lifetime', $userRewardBdt);
                $user->increment('total_admin_profit_generated', $adminProfitBdt);

                // Moderator salary
                /** @var \App\Models\User $moderator */
                $moderator = Auth::user();
                if ($moderator->is_moderator) {
                    $salary = (float) setting('moderator_salary_per_task', 0.05);
                    $moderator->increment('moderator_earnings_bdt', $salary);
                    $moderator->increment('total_reviews_done');
                }

                Transaction::create([
                    'user_id'       => $user->id,
                    'amount_points' => $userPoints,
                    'amount_bdt'    => $userRewardBdt,
                    'admin_profit'  => $adminProfitBdt,
                    'user_reward'   => $userPoints,
                    'type'          => 'task_completion',
                    'source'        => ucfirst($task->type),
                    'task_id'       => $task->id,
                    'reference_id'  => $fresh->id,
                    'description'   => 'Task approved: '.$task->title,
                    'status'        => 'completed',
                ]);

                UserNotification::create([
                    'user_id' => $user->id,
                    'type'    => 'success',
                    'title'   => '✅ টাস্ক অ্যাপ্রুভড!',
                    'message' => '"'.$task->title.'" সফলভাবে যাচাই হয়েছে। আপনার অ্যাকাউন্টে '.$userPoints.' পয়েন্ট যোগ হয়েছে।'.($adminProfit > 0 ? ' (প্ল্যাটফর্ম রিজার্ভ: '.$adminProfit.' PTS)' : ''),
                ]);

                // ── Referral unlock check ─────────────────────────────────────
                app(ReferralService::class)->handleProfitGenerated($user);
            });
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Approved! Points credited.']);
        }

        return redirect()->back()->with('success', 'টাস্কটি সফলভাবে অ্যাপ্রুভড করা হয়েছে।');
    }

    public function reject(Request $request, Submission $submission)
    {
        $request->validate(['rejection_reason' => 'required|string|max:500']);

        try {
            DB::transaction(function () use ($submission, $request) {
                // Lock and atomically check-and-update within transaction
                $fresh = Submission::where('id', $submission->id)
                    ->where('status', 'pending')
                    ->lockForUpdate()
                    ->first();

                if (!$fresh) {
                    throw new \RuntimeException('Already processed.');
                }

                $fresh->update([
                    'status' => 'rejected',
                    'admin_notes' => $request->rejection_reason,
                    'moderated_by' => Auth::id(),
                    'moderated_at' => now(),
                ]);

                // Restore quota so the task becomes available again
                $fresh->task->increment('quota_remaining');

                $user = $fresh->user;
                $user->decrement('trust_score', 5);

                /** @var \App\Models\User $moderator */
                $moderator = Auth::user();
                if ($moderator->is_moderator) {
                    $moderator->increment('total_reviews_done');
                }

                UserNotification::create([
                    'user_id' => $user->id,
                    'type' => 'warning',
                    'title' => '❌ টাস্ক রিজেক্ট হয়েছে',
                    'message' => '"'.$fresh->task->title.'" রিজেক্ট হয়েছে। কারণ: '.$request->rejection_reason,
                ]);
            });
        } catch (\RuntimeException $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Rejected.']);
        }

        return redirect()->route('admin.submissions.index')->with('success', 'টাস্কটি রিজেক্ট করা হয়েছে।');
    }
}
