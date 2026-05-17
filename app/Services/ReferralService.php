<?php

namespace App\Services;

use App\Models\Referral;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Facades\DB;

class ReferralService
{
    /**
     * Called whenever a user earns from any task.
     * Unlocks referral bonus if the referred user hits the total_earned_lifetime target.
     */
    public function handleProfitGenerated(User $earner): void
    {
        DB::transaction(function () use ($earner) {
            $referral = Referral::where('referred_user_id', $earner->id)
                ->where('status', 'Locked')
                ->lockForUpdate()
                ->first();

            if (! $referral) {
                return; // No pending referral for this user
            }

            if ($earner->total_earned_lifetime >= $referral->unlock_threshold) {
                $this->unlockBonus($referral);
            }
        });
    }

    /**
     * Transfer locked bonus to main balance and record transaction.
     */
    protected function unlockBonus(Referral $referral): void
    {
        $referrer = $referral->referrer;

        if (! $referrer) {
            return;
        }

        $bonusPoints = $referral->bonus_points;

        $pointConversionRate = max(1, (int) setting('point_conversion_rate', 100));
        $bonusBdt = round($bonusPoints / $pointConversionRate, 2);

        // Update referral status
        $referral->update([
            'status' => 'Unlocked',
            'unlocked_at' => now(),
        ]);

        $referrer->increment('points', $bonusPoints);
        $referrer->increment('total_earned_bdt', $bonusBdt);

        $referrer->update(['is_referral_unlocked' => true]);

        // Record transaction
        Transaction::create([
            'user_id' => $referrer->id,
            'amount_points' => $bonusPoints,
            'amount_bdt' => $bonusBdt,
            'admin_profit' => 0,
            'user_reward' => $bonusPoints,
            'type' => 'referral_bonus',
            'source' => 'Referral',
            'description' => 'রেফারেল বোনাস আনলক - '.$bonusPoints.' পয়েন্ট',
            'status' => 'completed',
        ]);

        // Notify the referrer
        UserNotification::create([
            'user_id' => $referrer->id,
            'type' => 'success',
            'title' => '🎉 রেফারেল বোনাস আনলক!',
            'message' => 'Congratulations! Your referral bonus has been auto-unlocked! '.$referral->referredUser->name.' কাজ করে আপনার '.$bonusPoints.' পয়েন্ট আনলক করেছে! মেইন ব্যালেন্সে যোগ হয়েছে।',
        ]);
    }

    /**
     * Create a referral record when a new user registers via referral link.
     */
    public function createReferral(User $newUser, User $referrer): void
    {
        DB::transaction(function () use ($newUser, $referrer) {
            $threshold = (float) setting('referral_unlock_target', 20);
            $bonusAmount = (int) setting('referral_bonus_amount', 500);

            $existing = Referral::where('referred_user_id', $newUser->id)
                ->lockForUpdate()
                ->first();

            if (! $existing) {
                Referral::create([
                    'referred_user_id' => $newUser->id,
                    'referrer_id' => $referrer->id,
                    'profit_generated' => 0,
                    'status' => 'Locked',
                    'bonus_points' => $bonusAmount,
                    'unlock_threshold' => $threshold,
                ]);
            }

            UserNotification::create([
                'user_id' => $referrer->id,
                'type' => 'info',
                'title' => '👥 নতুন রেফারেল!',
                'message' => $newUser->name.' আপনার রেফারেল লিংক দিয়ে যোগ দিয়েছেন। তারা ৳ '.$threshold.' আয় করলে আপনার '.$bonusAmount.' পয়েন্ট বোনাস অটো-আনলক হবে!',
            ]);
        });
    }
}
