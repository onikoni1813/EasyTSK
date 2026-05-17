<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaderboardStat;
use Illuminate\Http\Request;

class AdminLeaderboardController extends Controller
{
    /**
     * Display leaderboard stats from the tiny pre-calculated table.
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'weekly'); // weekly or monthly

        $topEarners = LeaderboardStat::with('user')
            ->where('type', 'earning')
            ->where('period', $period)
            ->orderByDesc('total_amount_or_count')
            ->limit(50)
            ->get();

        $topReferrers = LeaderboardStat::with('user')
            ->where('type', 'referral')
            ->where('period', $period)
            ->orderByDesc('total_amount_or_count')
            ->limit(50)
            ->get();

        return view('admin.leaderboard.index', compact('topEarners', 'topReferrers', 'period'));
    }
}
