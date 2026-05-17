<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MonlixController extends Controller
{
    public function index()
    {
        $monlixId = Setting::get('monlix_api_key', ''); // Note: user didn't ask for API key but we need one for iframe
        $isActive = Setting::get('is_monlix_active', '0') == '1';

        if (!$isActive) {
            return redirect()->route('tasks.index')->with('error', 'Monlix is currently disabled.');
        }

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

        $iframeUrl = "https://offers.monlix.com/?appid={$monlixId}&userid={$user->id}";

        return view('tasks.monlix', compact('user', 'monlixId', 'iframeUrl'));
    }

    public function postback(Request $request)
    {
        $secretKey = Setting::get('monlix_secret_key', '');
        $isActive = Setting::get('is_monlix_active', '0') == '1';

        if (!$isActive) {
            return response('Monlix is disabled', 403);
        }

        // ── Security Check ──────────────────────────────────────────────────
        if (empty($secretKey)) {
            Log::warning('Monlix secret key not configured', $request->all());
            return response('Server configuration error', 500);
        }

        $receivedSecret = $request->input('secret');
        if ($receivedSecret !== $secretKey) {
            Log::warning('Monlix invalid secret', $request->all());
            return response('Forbidden', 403);
        }

        // ── Security 2: Duplicate Transaction Block ──────────────────────────
        $txId = $request->input('transactionId');
        if (Transaction::where('provider', 'monlix')->where('external_transaction_id', $txId)->exists()) {
            return response('OK', 200); // Already processed
        }

        $userId = (int) $request->input('userId');
        $rewardPoints = (float) $request->input('reward', 0); // Monlix Points

        $user = User::find($userId);
        if (!$user) {
            return response('User not found', 404);
        }

        // ── Conversion ──────────────────────────────────────────────────────
        $conversionRate = (float) Setting::get('monlix_conversion_rate', 0.01);
        $pointConversionRate = (int) Setting::get('point_conversion_rate', 100);

        // Convert Monlix points to our system's "Points"
        $finalPoints = (int) round($rewardPoints * $conversionRate);
        $rewardBdt = $finalPoints / $pointConversionRate;

        if ($finalPoints <= 0) {
             return response('Reward too low', 200);
        }

        // ── Credit User ─────────────────────────────────────────────────────
        if (!$user->first_earning_at) {
            $user->first_earning_at = now();
        }
        $user->increment('points', $finalPoints);
        $user->increment('total_earned_bdt', $rewardBdt);
        $user->increment('total_earned_lifetime', $rewardBdt);

        Transaction::create([
            'user_id' => $user->id,
            'amount_points' => $finalPoints,
            'amount_bdt' => $rewardBdt,
            'admin_profit' => 0, // Share logic could be added here if needed
            'user_reward' => $finalPoints,
            'type' => 'task_completion',
            'source' => 'Monlix',
            'provider' => 'monlix',
            'external_transaction_id' => $txId,
            'description' => 'Monlix: Task Completion',
            'status' => 'completed',
        ]);

        // Referral Unlock Check
        app(ReferralService::class)->handleProfitGenerated($user);

        Log::info("Monlix postback: user #{$userId} credited {$finalPoints} pts");

        return response('OK', 200);
    }
}
