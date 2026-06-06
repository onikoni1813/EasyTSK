<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class UpdateLeaderboard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leaderboard:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates top earners and referrers and saves them to the leaderboard_stats table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Leaderboard Update...');
        $now = now();

        $periods = [
            'weekly' => [
                'start' => Carbon::now()->startOfWeek(),
                'end' => Carbon::now()->endOfWeek(),
            ],
            'monthly' => [
                'start' => Carbon::now()->startOfMonth(),
                'end' => Carbon::now()->endOfMonth(),
            ]
        ];

        // Use a staging collection to build new data, then atomically replace
        // within a transaction (delete old + insert new) instead of TRUNCATE
        // which causes an implicit commit in MySQL.
        $newRows = [];

        foreach ($periods as $periodName => $dates) {
            // 1. Top Earners (Weekly/Monthly)
            // whereIn existing users — deleted users এর transaction ignore হবে
            $existingUserIds = DB::table('users')->pluck('id');

            $topEarners = DB::table('transactions')
                ->select('user_id', DB::raw('SUM(amount_bdt) as total_amount'))
                ->whereBetween('created_at', [$dates['start'], $dates['end']])
                ->whereIn('type', ['task_completion', 'referral_bonus', 'offerwall_completion'])
                ->where('status', 'completed')
                ->whereIn('user_id', $existingUserIds)  // ← deleted user skip
                ->groupBy('user_id')
                ->orderByDesc('total_amount')
                ->limit(50)
                ->get();

            foreach ($topEarners as $earner) {
                $newRows[] = [
                    'user_id' => $earner->user_id,
                    'type' => 'earning',
                    'period' => $periodName,
                    'total_amount_or_count' => $earner->total_amount,
                    'last_updated_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // 2. Top Referrers (Weekly/Monthly)
            $topReferrers = DB::table('users')
                ->select('referred_by as user_id', DB::raw('COUNT(id) as total_referrals'))
                ->whereNotNull('referred_by')
                ->whereBetween('created_at', [$dates['start'], $dates['end']])
                ->groupBy('referred_by')
                ->orderByDesc('total_referrals')
                ->limit(50)
                ->get();

            foreach ($topReferrers as $referrer) {
                // Only legitimate existing users
                if ($referrer->user_id && $existingUserIds->contains($referrer->user_id)) {
                    $newRows[] = [
                        'user_id'               => $referrer->user_id,
                        'type'                  => 'referral',
                        'period'                => $periodName,
                        'total_amount_or_count' => $referrer->total_referrals,
                        'last_updated_at'       => $now,
                        'created_at'            => $now,
                        'updated_at'            => $now,
                    ];
                }
            }
        }

        // Atomic replace: delete all old rows and insert new ones in a transaction
        DB::transaction(function () use ($newRows) {
            DB::table('leaderboard_stats')->delete();
            if (!empty($newRows)) {
                DB::table('leaderboard_stats')->insert($newRows);
            }
        });

        $this->info('Leaderboard Updated Successfully!');
    }
}
