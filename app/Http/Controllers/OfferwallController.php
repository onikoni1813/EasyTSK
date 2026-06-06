<?php

namespace App\Http\Controllers;

use App\Models\Offerwall;
use App\Models\PostbackLog;
use App\Models\Transaction;
use App\Models\User;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OfferwallController extends Controller
{
    /**
     * Show a specific offerwall page (iframe).
     */
    public function show(Offerwall $offerwall)
    {
        if (! $offerwall->is_active) {
            return redirect()->route('tasks.index')->with('error', 'এই মডিউলটি বর্তমানে নিষ্ক্রিয়।');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // ── TASK LOCK LOGIC ─────────────────────────────────────────────────
        if ($offerwall->requires_task_lock) {
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
                return redirect()->route('tasks.index')->with('warning', '🔒 এই সেকশনটি এখনো আনলক হয়নি! আগে "সোশ্যাল টাস্ক" ট্যাবে গিয়ে সব ম্যান্ডেটরি টাস্ক কমপ্লিট করুন।');
            }
        }

        $iframeUrl = $offerwall->getIframeUrl($user);

        $widgetScript = null;
        if ($offerwall->widget_script) {
            $widgetScript = str_replace(
                ['{user_id}', '{USER_ID}', '[user_id]', '[USER_ID]'],
                $user->id,
                $offerwall->widget_script
            );
        }

        return view('offerwalls.show', compact('offerwall', 'user', 'iframeUrl', 'widgetScript'));
    }


    /**
     * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     * UNIVERSAL POSTBACK HANDLER
     * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     *
     * Route: GET|POST /api/postback/{slug}
     * No auth — server-to-server callback from ad networks.
     *
     * This single endpoint handles ALL offerwalls dynamically.
     * The offerwall's database config determines:
     *   - How to verify the request (HMAC, secret match, IP whitelist)
     *   - Which request fields contain user_id, reward, transaction_id
     *   - How to calculate reward (USD→points conversion, platform share)
     */
    public function postback(Request $request, string $slug)
    {
        $offerwall = Offerwall::where('slug', $slug)->first();

        // ── Step 1: Offerwall exists? ───────────────────────────────────────
        if (! $offerwall) {
            PostbackLog::create([
                'slug'        => $slug,
                'status'      => 'not_found',
                'ip_address'  => $request->ip(),
                'raw_payload' => $request->all(),
                'error_message' => "Offerwall with slug '{$slug}' not found",
            ]);
            return response('Unknown offerwall', 404);
        }

        if (! $offerwall->is_active) {
            PostbackLog::create([
                'offerwall_id' => $offerwall->id,
                'slug'         => $slug,
                'status'       => 'inactive',
                'ip_address'   => $request->ip(),
                'raw_payload'  => $request->all(),
                'error_message' => 'Offerwall is inactive',
            ]);
            return response('Offerwall inactive', 403);
        }

        // ── Step 2: Security Verification ───────────────────────────────────
        $securityResult = $this->verifyPostbackSecurity($request, $offerwall);
        if ($securityResult !== true) {
            PostbackLog::create([
                'offerwall_id' => $offerwall->id,
                'slug'         => $slug,
                'status'       => 'invalid_signature',
                'ip_address'   => $request->ip(),
                'raw_payload'  => $request->all(),
                'error_message' => $securityResult,
            ]);
            Log::warning("Postback security failed [{$slug}]: {$securityResult}", $request->all());
            return response('Security verification failed', 403);
        }

        // ── Step 3: Extract fields using mapping ────────────────────────────
        $externalUserId  = $request->input($offerwall->field_user_id);
        $rewardRaw       = (float) $request->input($offerwall->field_reward, 0);
        $txId            = $request->input($offerwall->field_transaction_id);
        $campaignId      = $offerwall->field_campaign_id
                            ? $request->input($offerwall->field_campaign_id, 'offer')
                            : 'offer';

        // ── Step 4: Duplicate check ─────────────────────────────────────────
        if ($txId && Transaction::where('provider', $slug)->where('external_transaction_id', $txId)->exists()) {
            PostbackLog::create([
                'offerwall_id'            => $offerwall->id,
                'slug'                    => $slug,
                'user_id'                 => (int) $externalUserId ?: null,
                'status'                  => 'duplicate',
                'ip_address'              => $request->ip(),
                'reward_raw'              => $rewardRaw,
                'external_transaction_id' => $txId,
                'raw_payload'             => $request->all(),
            ]);
            return response('OK', 200); // ACK but don't credit
        }

        // ── Step 5: Find user ───────────────────────────────────────────────
        $user = User::find((int) $externalUserId);
        if (! $user) {
            PostbackLog::create([
                'offerwall_id'            => $offerwall->id,
                'slug'                    => $slug,
                'status'                  => 'user_not_found',
                'ip_address'              => $request->ip(),
                'reward_raw'              => $rewardRaw,
                'external_transaction_id' => $txId,
                'raw_payload'             => $request->all(),
                'error_message'           => "User #{$externalUserId} not found",
            ]);
            return response('User not found', 404);
        }

        // ── Step 6: Calculate reward ────────────────────────────────────────
        $pointConversionRate = max(1, (int) setting('point_conversion_rate', 100));

        if ($offerwall->reward_type === 'usd') {
            // Network sends USD → convert to our points
            $totalPoints = (int) round($rewardRaw * $offerwall->conversion_rate);
        } elseif ($offerwall->reward_type === 'points') {
            // Network sends their points → convert to our points
            $totalPoints = (int) round($rewardRaw * $offerwall->conversion_rate);
        } else {
            // Custom: raw value IS our points
            $totalPoints = (int) round($rewardRaw);
        }

        if ($totalPoints <= 0) {
            PostbackLog::create([
                'offerwall_id'            => $offerwall->id,
                'slug'                    => $slug,
                'user_id'                 => $user->id,
                'status'                  => 'reward_too_low',
                'ip_address'              => $request->ip(),
                'reward_raw'              => $rewardRaw,
                'external_transaction_id' => $txId,
                'raw_payload'             => $request->all(),
                'error_message'           => "Calculated points = {$totalPoints}",
            ]);
            return response('Reward too low', 200);
        }

        // Admin share
        $adminProfitPoints = (int) round($totalPoints * ($offerwall->platform_share_pct / 100));
        $userPoints = $totalPoints - $adminProfitPoints;

        $userRewardBdt = round($userPoints / $pointConversionRate, 2);
        $adminProfitBdt = round($adminProfitPoints / $pointConversionRate, 2);

        // ── Step 7: Credit User (DB Transaction) ────────────────────────────
        DB::transaction(function () use ($user, $userPoints, $userRewardBdt, $adminProfitBdt, $offerwall, $txId, $campaignId) {
            if (! $user->first_earning_at) {
                $user->first_earning_at = now();
                $user->save();
            }

            $user->increment('points', $userPoints);
            $user->increment('total_earned_bdt', $userRewardBdt);
            $user->increment('total_earned_lifetime', $userRewardBdt);
            $user->increment('total_admin_profit_generated', $adminProfitBdt);

            Transaction::create([
                'user_id'                 => $user->id,
                'amount_points'           => $userPoints,
                'amount_bdt'              => $userRewardBdt,
                'admin_profit'            => $adminProfitBdt,
                'user_reward'             => $userPoints,
                'type'                    => 'task_completion',
                'source'                  => $offerwall->name,
                'provider'                => $offerwall->slug,
                'external_transaction_id' => $txId,
                'description'             => "{$offerwall->name}: {$campaignId}",
                'status'                  => 'completed',
            ]);
        });

        // ── Step 8: Referral unlock check ───────────────────────────────────
        app(ReferralService::class)->handleProfitGenerated($user);

        // ── Step 9: Log success ─────────────────────────────────────────────
        PostbackLog::create([
            'offerwall_id'            => $offerwall->id,
            'slug'                    => $slug,
            'user_id'                 => $user->id,
            'status'                  => 'success',
            'ip_address'              => $request->ip(),
            'reward_raw'              => $rewardRaw,
            'points_credited'         => $userPoints,
            'external_transaction_id' => $txId,
            'raw_payload'             => $request->all(),
        ]);

        Log::info("Postback [{$slug}]: user #{$user->id} credited {$userPoints} pts (admin: {$adminProfitBdt} BDT)");

        return response('OK', 200);
    }

    /**
     * Verify postback security based on offerwall configuration.
     *
     * @return true|string  True if valid, error message string if invalid.
     */
    private function verifyPostbackSecurity(Request $request, Offerwall $offerwall): true|string
    {
        return match ($offerwall->security_type) {
            'hmac_sha256'  => $this->verifyHmac($request, $offerwall),
            'secret_match' => $this->verifySecretMatch($request, $offerwall),
            'ip_whitelist' => $this->verifyIpWhitelist($request, $offerwall),
            'none'         => true,
            default        => 'Unknown security type: ' . $offerwall->security_type,
        };
    }

    private function verifyHmac(Request $request, Offerwall $offerwall): true|string
    {
        if (empty($offerwall->secret_key)) {
            return 'HMAC secret key not configured';
        }

        $received = $request->input($offerwall->security_field, '');
        if (empty($received)) {
            return 'Missing signature field: ' . $offerwall->security_field;
        }

        // Concatenate the configured HMAC fields
        $hmacFields = $offerwall->hmac_fields ?? [];
        $data = '';
        foreach ($hmacFields as $field) {
            $data .= $request->input($field, '');
        }

        $expected = hash_hmac('sha256', $data, $offerwall->secret_key);

        if (! hash_equals($expected, $received)) {
            return 'HMAC signature mismatch';
        }

        return true;
    }

    private function verifySecretMatch(Request $request, Offerwall $offerwall): true|string
    {
        if (empty($offerwall->secret_key)) {
            return true; // No secret configured = skip check
        }

        $received = $request->input($offerwall->security_field, '');
        if ($received !== $offerwall->secret_key) {
            return 'Secret mismatch on field: ' . $offerwall->security_field;
        }

        return true;
    }

    private function verifyIpWhitelist(Request $request, Offerwall $offerwall): true|string
    {
        $whitelist = $offerwall->ip_whitelist ?? [];
        if (empty($whitelist)) {
            return true; // No whitelist = allow all
        }

        if (! in_array($request->ip(), $whitelist)) {
            return 'IP not whitelisted: ' . $request->ip();
        }

        return true;
    }
}
