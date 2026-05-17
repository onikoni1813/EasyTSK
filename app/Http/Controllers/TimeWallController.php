<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TimeWallController extends Controller
{
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

        $timewallKey = Setting::get('timewall_api_key', '');

        $iframeUrl = null;
        if ($timewallKey) {
            $iframeUrl = "https://timewall.io/users/login?oid={$timewallKey}&uid={$user->id}&tab=clicks";
        }

        return view('tasks.timewall', compact('user', 'timewallKey', 'iframeUrl'));
    }

    /**
     * Postback webhook called by TimeWall server after user withdrawal.
     * Route: GET /api/postback/timewall  (no auth middleware — server-to-server)
     */
    public function postback(Request $request)
    {
        $secretKey = Setting::get('timewall_secret_key', '');

        // ── Security 1: Signature Verification ──────────────────────────────
        $received = $request->input('signature', '');
        // TimeWall signature: HMAC-SHA256(user_id + campaign_id + reward, secret_key)
        $data = $request->input('user_id') .
                $request->input('campaign_id') .
                $request->input('reward');
        $expected = hash_hmac('sha256', $data, $secretKey);

        if ($received !== $expected) {
            Log::warning('TimeWall Fake Postback Attempt', $request->all());
            return response('Invalid signature', 403);
        }

        // ── Security 2: Duplicate Transaction Block ──────────────────────────
        $twTxId = $request->input('transaction_id');
        if (Transaction::where('provider', 'timewall')->where('external_transaction_id', $twTxId)->exists()) {
            return response('Duplicate transaction', 200); // ACK but don't credit
        }

        $userId = (int) $request->input('user_id');
        $rewardUsd = (float) $request->input('reward', 0);

        $user = User::find($userId);
        if (! $user) {
            return response('User not found', 404);
        }

        // ── Profit Calculation ──────────────────────────────────────────────
        $usdToUsdRate = (float) Setting::get('timewall_usd_to_points_rate', 10000);
        $pointConversionRate = (int) Setting::get('point_conversion_rate', 100);
        $platformSharePercent = (float) Setting::get('timewall_platform_share_percent', 20);

        // How many raw points TimeWall is sending for this $reward USD
        $totalPoints = (int) round($rewardUsd * $usdToUsdRate);

        // Admin keeps a percentage
        $adminProfitPoints = (int) round($totalPoints * ($platformSharePercent / 100));
        $userPoints = $totalPoints - $adminProfitPoints;

        $userRewardBdt = $userPoints / $pointConversionRate;
        $adminProfitBdt = $adminProfitPoints / $pointConversionRate;

        // ── Credit User ─────────────────────────────────────────────────────
        if (!$user->first_earning_at) {
            $user->first_earning_at = now();
        }
        $user->increment('points', $userPoints);
        $user->increment('total_earned_bdt', $userRewardBdt);
        $user->increment('total_earned_lifetime', $userRewardBdt);
        $user->increment('total_admin_profit_generated', $adminProfitBdt);

        Transaction::create([
            'user_id' => $user->id,
            'amount_points' => $userPoints,
            'amount_bdt' => $userRewardBdt,
            'admin_profit' => $adminProfitBdt,
            'user_reward' => $userPoints,
            'type' => 'task_completion',
            'source' => 'TimeWall',
            'provider' => 'timewall',
            'external_transaction_id' => $twTxId,
            'timewall_transaction_id' => $twTxId,
            'description' => 'TimeWall: '.$request->input('campaign_id', 'offer'),
            'status' => 'completed',
        ]);

        // ── Referral Unlock Check ───────────────────────────────────────────
        app(ReferralService::class)->handleProfitGenerated($user);

        Log::info("TimeWall postback: user #{$userId} credited {$userPoints} pts, admin profit {$adminProfitBdt} BDT");

        return response('OK', 200);
    }
}
