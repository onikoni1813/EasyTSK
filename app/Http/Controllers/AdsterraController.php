<?php

namespace App\Http\Controllers;

use App\Models\AdsterraCode;
use App\Models\Setting;
use App\Models\Task;
use App\Models\Transaction;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdsterraController extends Controller
{
    /**
     * Adsterra tasks list (shown on tasks > adsterra tab)
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // ── TASK LOCK LOGIC: Check if user has available MANDATORY regular tasks ──
        // Optional tasks (is_optional = true) do NOT block offerwall unlock
        $hasAvailableMandatoryTasks = \App\Models\Task::where('is_active', true)
            ->where('is_optional', false)
            ->whereNotIn('type', ['timewall', 'adsterra'])
            ->where('quota_remaining', '>', 0)
            ->whereDoesntHave('submissions', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->whereIn('status', ['pending', 'approved']);
            })
            ->exists();

        if ($hasAvailableMandatoryTasks) {
            return redirect()->route('tasks.index')->with('warning', '🔒 এই সেকশনটি এখনো আনলক হয়নি! আগে "সোশ্যাল টাস্ক" ট্যাবে গিয়ে সব ম্যান্ডেটরি টাস্ক কমপ্লিট করুন, তারপর প্রিমিয়াম টাস্ক, বোনাস ওয়াল এবং অ্যাড ভিউ আনলক হবে।');
        }

        $tasks = Task::where('type', 'adsterra')
            ->where('is_active', true)
            ->where('quota_remaining', '>', 0)
            ->whereDoesntHave('submissions', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->get();

        return view('tasks.adsterra', compact('tasks'));
    }

    /**
     * Gateway page: show timer + "Generate Code" button
     */
    public function gateway(Request $request, Task $task)
    {
        abort_if($task->type !== 'adsterra' || ! $task->is_active, 404);

        // Check if user already completed this task
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $alreadyDone = $user->submissions()
            ->where('task_id', $task->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($alreadyDone) {
            return redirect()->route('adsterra.index')
                ->with('error', 'আপনি এই টাস্কটি ইতিমধ্যে সম্পন্ন করেছেন।');
        }

        $adsterraLink = $task->external_link ?: Setting::get('adsterra_direct_link', '#');
        $timerSeconds = (int) Setting::get('adsterra_timer_seconds', 30);

        return view('tasks.adsterra-gateway', compact('task', 'adsterraLink', 'timerSeconds'));
    }

    /**
     * Ajax: Generate a unique code and store it
     */
    public function generateCode(Request $request, Task $task)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Delete old unused codes for this user+task
        AdsterraCode::where('user_id', $user->id)->where('task_id', $task->id)->delete();

        $code = strtoupper('ADX-'.$user->id.'-'.Str::random(6));
        $expiresAt = now()->addMinutes(10);

        AdsterraCode::create([
            'user_id' => $user->id,
            'task_id' => $task->id,
            'code' => $code,
            'is_used' => false,
            'expires_at' => $expiresAt,
        ]);

        return response()->json(['code' => $code, 'expires_at' => $expiresAt->toIso8601String()]);
    }

    /**
     * Verify submitted code and credit points
     */
    public function verifyCode(Request $request)
    {
        $request->validate(['code' => 'required|string', 'task_id' => 'required|integer']);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $codeRecord = AdsterraCode::where('code', $request->code)
            ->where('user_id', $user->id)
            ->where('task_id', $request->task_id)
            ->where('is_used', false)
            ->first();

        if (! $codeRecord || $codeRecord->isExpired()) {
            return response()->json(['success' => false, 'message' => 'কোডটি ভুল বা মেয়াদ উত্তীর্ণ হয়েছে।'], 422);
        }

        $task = Task::find($request->task_id);
        if (! $task || ! $task->is_active || $task->quota_remaining <= 0) {
            return response()->json(['success' => false, 'message' => 'টাস্কটি আর উপলব্ধ নেই।'], 422);
        }

        // Check if already submitted
        $alreadyDone = $user->submissions()->where('task_id', $task->id)->exists();
        if ($alreadyDone) {
            return response()->json(['success' => false, 'message' => 'আপনি ইতিমধ্যে এই টাস্কটি সম্পন্ন করেছেন।'], 422);
        }

        $pointConversionRate = (int) Setting::get('point_conversion_rate', 100);

        $userPoints = (int) $task->points;
        $adminProfitPoints = (int) ($task->admin_profit ?? 0);
        $adminProfitBdt = $adminProfitPoints / $pointConversionRate;

        // Mark code as used
        $codeRecord->update(['is_used' => true]);

        // Decrement quota atomically to prevent race conditions
        \App\Models\Task::where('id', $task->id)->where('quota_remaining', '>', 0)->decrement('quota_remaining');

        // Credit user
        $userRewardBdt = $userPoints / $pointConversionRate;
        if (!$user->first_earning_at) {
            $user->first_earning_at = now();
        }
        $user->increment('points', $userPoints);
        $user->increment('total_earned_bdt', $userRewardBdt);
        $user->increment('total_earned_lifetime', $userRewardBdt);
        $user->increment('total_admin_profit_generated', $adminProfitBdt);

        // Create submission record
        $user->submissions()->create([
            'task_id' => $task->id,
            'status' => 'approved', // Auto-approved for Adsterra
            'proof_text' => 'Adsterra Code: '.$codeRecord->code,
        ]);

        // Log transaction
        Transaction::create([
            'user_id' => $user->id,
            'amount_points' => $userPoints,
            'amount_bdt' => $userPoints / $pointConversionRate,
            'admin_profit' => $adminProfitBdt,
            'user_reward' => $userPoints,
            'type' => 'task_completion',
            'source' => 'Adsterra',
            'task_id' => $task->id,
            'description' => 'Adsterra Task: '.$task->title,
            'status' => 'completed',
        ]);

        // Referral unlock check
        app(ReferralService::class)->handleProfitGenerated($user);

        return response()->json([
            'success' => true,
            'points_earned' => $userPoints,
            'message' => '✅ '.$userPoints.' পয়েন্ট আপনার অ্যাকাউন্টে যোগ হয়েছে!',
        ]);
    }

    /**
     * Ajax endpoint to get live stats for landing page
     */
    public function stats()
    {
        $fakeOffset = (int) Setting::get('fake_member_offset', 1000);
        $fakePaidOffset = (float) Setting::get('fake_paid_offset', 5000);
        $fakeTodayOffset = (int) Setting::get('fake_today_tasks_offset', 200);

        // Real Data
        $realMembers = \App\Models\User::where('is_admin', false)->count();
        $realPaid = \App\Models\Withdrawal::where('status', 'approved')->sum('amount_bdt');
        $realTodayTasks = \App\Models\Submission::whereDate('created_at', today())->where('status', 'approved')->count();

        // Dynamic Growth Factor (Organic feel: grows slightly based on hour of day)
        $hourSeed = (int) date('G'); // 0-23
        $dynamicMembers = $fakeOffset + ($hourSeed * 2);
        $dynamicTasks = $fakeTodayOffset + ($hourSeed * 5);

        $totalMembers = $realMembers + $dynamicMembers;
        $totalPaid = $realPaid + $fakePaidOffset;
        $todayTasks = $realTodayTasks + $dynamicTasks;

        return response()->json([
            'raw' => [
                'members' => $totalMembers,
                'paid' => $totalPaid,
                'tasks' => $todayTasks,
            ],
            'formatted' => [
                'members' => number_format($totalMembers).'+',
                'paid' => '৳ '.number_format($totalPaid, 0, '.', ',').'+',
                'today_tasks' => number_format($todayTasks).'+',
            ],
        ]);
    }

    /**
     * Live payment proof ticker (last 5 approved withdrawals)
     */
    public function paymentProof()
    {
        $proofs = \App\Models\Withdrawal::with('user')
            ->where('status', 'approved')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($w) {
                return [
                    'type' => 'payment',
                    'name' => $w->user->masked_name,
                    'amount' => '৳ '.number_format($w->amount_bdt, 0),
                    'method' => $w->method,
                    'time' => $w->updated_at->diffForHumans(),
                ];
            })->toArray();

        // Inject custom messages
        $customRaw = Setting::get('ticker_custom_messages', '');
        if ($customRaw) {
            $customLines = explode("\n", str_replace("\r", '', $customRaw));
            foreach ($customLines as $line) {
                if (trim($line)) {
                    $proofs[] = [
                        'type' => 'custom',
                        'content' => trim($line),
                    ];
                }
            }
        }

        shuffle($proofs);

        return response()->json($proofs);
    }
}
