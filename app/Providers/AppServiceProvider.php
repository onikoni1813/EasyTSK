<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\Transaction;
use App\Observers\TransactionObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Transaction::observe(TransactionObserver::class);

        Gate::define('access-moderator', function ($user) {
            return $user->is_moderator || $user->is_admin;
        });

        // Dynamic SMTP Configuration
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $mailHost = Setting::get('mail_host');
                if ($mailHost) {
                    $config = [
                        'transport'  => 'smtp',
                        'host'       => $mailHost,
                        'port'       => Setting::get('mail_port', 587),
                        'encryption' => Setting::get('mail_encryption', 'tls'),
                        'username'   => Setting::get('mail_username'),
                        'password'   => Setting::get('mail_password'),
                        'timeout'    => null,
                    ];

                    config(['mail.mailers.smtp' => array_merge(config('mail.mailers.smtp'), $config)]);
                    config(['mail.from.address'  => Setting::get('mail_from_address', 'noreply@' . request()->getHost())]);
                    config(['mail.from.name'     => Setting::get('site_name', config('app.name'))]);

                    // ── Critical Fix ──────────────────────────────────────────
                    // .env এর MAIL_MAILER=log override করে smtp-তে সেট করো
                    config(['mail.default' => 'smtp']);
                    config(['mail.mailer'  => 'smtp']);
                }
            }
        } catch (\Exception $e) {
            // Silently fail during migrations or if DB is not ready
        }
    }
}
