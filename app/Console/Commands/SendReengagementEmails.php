<?php

namespace App\Console\Commands;

use App\Mail\ReengagementEmail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReengagementEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-reengagement-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send re-engagement emails to users who signed up but haven\'t earned points in 24 hours.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::where('points', 0)
            ->where('created_at', '<', now()->subHours(24))
            ->where('created_at', '>', now()->subHours(48)) // Safeguard
            ->where('is_admin', false)
            ->get();

        $count = 0;
        /** @var User $user */
        foreach ($users as $user) {
            try {
                Mail::to($user->email)->send(new ReengagementEmail($user));
                $count++;
            } catch (\Exception $e) {
                $this->error("Failed to send to {$user->email}: " . $e->getMessage());
            }
        }

        $this->info("Successfully sent {$count} re-engagement emails.");
    }
}
