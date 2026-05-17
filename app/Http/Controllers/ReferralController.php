<?php

namespace App\Http\Controllers;

use App\Models\Referral;

class ReferralController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $referrals = Referral::with('referredUser')
            ->where('referrer_id', $user->id)
            ->latest()
            ->paginate(20);

        // Query totals separately from ALL referrals, not just the paginated 20
        $baseQuery = Referral::where('referrer_id', $user->id);
        $totalUnlocked = (clone $baseQuery)->where('status', 'Unlocked')->count();
        $totalLocked = (clone $baseQuery)->where('status', 'Locked')->count();
        $totalProfit = (clone $baseQuery)->sum('profit_generated');
        $totalBonus = (clone $baseQuery)->where('status', 'Unlocked')->sum('bonus_points');

        $referralLink = route('register').'?ref='.$user->referral_code;

        return view('referrals.index', compact(
            'referrals',
            'referralLink',
            'totalUnlocked',
            'totalLocked',
            'totalProfit',
            'totalBonus'
        ));
    }
}
