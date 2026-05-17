<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Submission;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $rate = (int) Setting::get('point_conversion_rate', 100);

        // ── Financial Radar ────────────────────────────────────────────────
        $totalAdminProfit = Transaction::sum('admin_profit');

        $systemLiabilityPoints = User::where('is_admin', false)
            ->sum(\Illuminate\Support\Facades\DB::raw('points + pending_points + locked_points'));
        $systemLiabilityBdt = $systemLiabilityPoints / $rate;

        $pendingWithdrawalsTotal = Withdrawal::where('status', 'pending')->sum('amount_bdt');
        $pendingWithdrawalsCount = Withdrawal::where('status', 'pending')->count();

        $totalPaidOut = Withdrawal::where('status', 'approved')->sum('amount_bdt');

        // ── Activity Radar ─────────────────────────────────────────────────
        $newUsersToday = User::where('is_admin', false)->whereDate('created_at', today())->count();
        $tasksCompletedToday = Submission::where('status', 'approved')->whereDate('updated_at', today())->count();
        $tasksPendingReview = Submission::where('status', 'pending')->count();
        $bannedUsersCount = User::where('is_banned', true)->count();
        $totalUsers = User::where('is_admin', false)->count();

        // ── Recent Transactions for mini feed ─────────────────────────────
        $recentTransactions = Transaction::with('user')
            ->whereIn('type', ['task_completion', 'timewall', 'adsterra'])
            ->latest()
            ->limit(10)
            ->get();

        // ── Pending items to action ────────────────────────────────────────
        $pendingKyc = User::where('kyc_status', 'pending')->count();
        $unreadSupportCount = \App\Models\SupportTicket::where('is_read', false)->count();

        // ── Week chart data ────────────────────────────────────────────────
        $weekProfit = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weekProfit[] = [
                'date' => $date->format('D'),
                'profit' => (float) Transaction::whereDate('created_at', $date)->sum('admin_profit'),
            ];
        }

        // ── Marketing ROI Radar ───────────────────────────────────────────
        $topSources = User::whereNotNull('utm_source')
            ->select('utm_source', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('utm_source')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        $topCampaigns = User::whereNotNull('utm_campaign')
            ->select('utm_campaign', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('utm_campaign')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        $profitBySource = Transaction::join('users', 'transactions.user_id', '=', 'users.id')
            ->whereNotNull('users.utm_source')
            ->select('users.utm_source', \Illuminate\Support\Facades\DB::raw('sum(transactions.admin_profit) as total_profit'))
            ->groupBy('users.utm_source')
            ->orderByDesc('total_profit')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'financialRadar' => [
                'totalAdminProfit' => $totalAdminProfit,
                'systemLiabilityBdt' => $systemLiabilityBdt,
                'pendingWithdrawalsTotal' => $pendingWithdrawalsTotal,
            ],
            'activityRadar' => [
                'newUsersToday' => $newUsersToday,
                'tasksCompletedToday' => $tasksCompletedToday,
                'tasksPendingReview' => $tasksPendingReview,
                'totalUsers' => $totalUsers,
                'unreadSupportCount' => $unreadSupportCount,
            ],
            'marketingRadar' => [
                'topSources' => $topSources,
                'topCampaigns' => $topCampaigns,
                'profitBySource' => $profitBySource,
            ],
            'chartData' => [
                'labels' => collect($weekProfit)->pluck('date'),
                'values' => collect($weekProfit)->pluck('profit'),
            ],
            'recentTransactions' => $recentTransactions,
            'pendingKyc' => $pendingKyc,
        ]);
    }
}
