<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ResetUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'users:reset
                            {--force : Skip confirmation prompt}
                            {--admin-email=admin@easytsk.test : Admin email}
                            {--admin-password=Admin@1234 : Admin password}
                            {--user-email=user@easytsk.test : Test user email}
                            {--user-password=User@1234 : Test user password}';

    protected $description = 'Safely delete ALL users and related data, then create fresh Admin + Test User';

    public function handle(): int
    {
        $this->newLine();
        $this->line('╔══════════════════════════════════════════════╗');
        $this->line('║      EasyTSK — Safe User Reset Tool            ║');
        $this->line('╚══════════════════════════════════════════════╝');
        $this->newLine();

        // ── Count current data ────────────────────────────────────────────────
        $userCount       = User::count();
        $submissionCount = DB::table('submissions')->count();
        $withdrawalCount = DB::table('withdrawals')->count();
        $referralCount   = DB::table('referrals')->count();
        $txCount         = DB::table('transactions')->count();
        $notifCount      = DB::table('user_notifications')->count();
        $kycCount        = User::whereNotNull('id_front_path')->count();

        $this->warn('⚠️  নিচের সব ডেটা মুছে যাবে:');
        $this->table(
            ['Table', 'Count'],
            [
                ['👤 Users',         $userCount],
                ['📋 Submissions',   $submissionCount],
                ['💸 Withdrawals',   $withdrawalCount],
                ['👥 Referrals',     $referralCount],
                ['💰 Transactions',  $txCount],
                ['🔔 Notifications', $notifCount],
                ['🪪 KYC Records',   $kycCount],
            ]
        );

        $this->newLine();

        // ── Confirmation ──────────────────────────────────────────────────────
        if (! $this->option('force')) {
            if (! $this->confirm('⚠️  আপনি কি নিশ্চিত? সব Users ও Related ডেটা মুছে যাবে!', false)) {
                $this->error('❌ অপারেশন বাতিল করা হয়েছে।');
                return 1;
            }

            $confirm2 = $this->ask('🔐 নিশ্চিত করতে "DELETE ALL" টাইপ করুন');
            if ($confirm2 !== 'DELETE ALL') {
                $this->error('❌ সঠিক কনফার্মেশন দেওয়া হয়নি। অপারেশন বাতিল।');
                return 1;
            }
        }

        $this->newLine();
        $this->info('🔄 ডেটা মুছা শুরু হচ্ছে...');

        // ── Safe Delete (FK-safe order: child tables first, then parent) ──────
        $tables = [
            'user_notifications',
            'referrals',
            'transactions',
            'withdrawals',
            'submissions',
            'support_tickets',
            'ticket_messages',
            'kyc_documents',
            'adsterra_codes',
            'fraud_logs',
            'blacklists',
            'leaderboard_stats',
            'notifications',
            'sessions',
            'personal_access_tokens',
            'activity_log',
        ];

        foreach ($tables as $table) {
            try {
                DB::table($table)->truncate();
                $this->line("  ✅ Cleared: {$table}");
            } catch (\Exception $e) {
                // Table may not exist — skip silently
                $this->line("  ⏭️  Skipped (not found): {$table}");
            }
        }

        // Delete users last (parent table)
        DB::table('users')->truncate();
        $this->line('  ✅ Cleared: users');

        // ── Create Admin ──────────────────────────────────────────────────────
        $this->newLine();
        $this->info('👑 Admin User তৈরি করা হচ্ছে...');

        $adminEmail    = $this->option('admin-email');
        $adminPassword = $this->option('admin-password');

        $admin = User::create([
            'name'               => 'Admin',
            'full_name'          => 'System Administrator',
            'email'              => $adminEmail,
            'password'           => Hash::make($adminPassword),
            'is_admin'           => true,
            'is_moderator'       => true,
            'email_verified_at'  => now(),
            'referral_code'      => strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8)),
            'trust_score'        => 100,
            'points'             => 0,
            'registration_ip'    => '127.0.0.1',
        ]);

        $this->line("  ✅ Admin Created → ID: {$admin->id}");

        // ── Create Test User ──────────────────────────────────────────────────
        $this->info('👤 Test User তৈরি করা হচ্ছে...');

        $userEmail    = $this->option('user-email');
        $userPassword = $this->option('user-password');

        $testUser = User::create([
            'name'              => 'Test User',
            'full_name'         => 'Test User Account',
            'email'             => $userEmail,
            'password'          => Hash::make($userPassword),
            'is_admin'          => false,
            'is_moderator'      => false,
            'email_verified_at' => now(),
            'referral_code'     => strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8)),
            'trust_score'       => 100,
            'points'            => 5000,
            'total_earned_bdt'  => 50.00,
            'bkash_number'      => '01712345678',
            'nagad_number'      => '01712345678',
            'facebook_link'     => 'https://facebook.com/testuser',
            'telegram_username' => 'testuser',
            'registration_ip'   => '127.0.0.1',
        ]);

        $this->line("  ✅ Test User Created → ID: {$testUser->id}");

        // ── Summary ───────────────────────────────────────────────────────────
        $this->newLine();
        $this->line('╔══════════════════════════════════════════════╗');
        $this->line('║             ✅ সম্পন্ন হয়েছে!              ║');
        $this->line('╚══════════════════════════════════════════════╝');
        $this->newLine();

        $this->table(
            ['Role', 'Email', 'Password'],
            [
                ['👑 Admin',     $adminEmail,    $adminPassword],
                ['👤 Test User', $userEmail,     $userPassword],
            ]
        );

        $adminPrefix = env('ADMIN_PREFIX', 'admin');
        $this->newLine();
        $this->info("🔗 Admin Panel: http://localhost/{$adminPrefix}");
        $this->info('🔗 Login Page:  http://localhost/login');
        $this->newLine();

        return 0;
    }
}
