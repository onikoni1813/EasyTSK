<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        $middleware->prependToGroup('web', [
            \App\Http\Middleware\CheckMaintenanceMode::class,
        ]);
        // CaptureTrackingParameters MUST run AFTER StartSession (which is in 'web' group)
        // so that session reads/writes are properly persisted.
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\CaptureTrackingParameters::class,
        ]);
        $middleware->alias([
            'admin'       => \App\Http\Middleware\AdminMiddleware::class,
            'sub_admin'   => \App\Http\Middleware\SubAdminMiddleware::class,
            'vpn'         => \App\Http\Middleware\CheckVpn::class,
            'email.verified' => \App\Http\Middleware\EnsureEmailVerified::class,
        ]);
    })
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule) {
        $schedule->command('app:send-reengagement-emails')->dailyAt('10:00');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'CSRF token mismatch. Please reload the page.'], 419);
            }

            // Redirect based on auth status
            if (\Illuminate\Support\Facades\Auth::check()) {
                return redirect()->route('dashboard')->with('error', 'আপনার সেশন টাইমআউট হয়েছে। দয়া করে আবার চেষ্টা করুন।');
            }

            return redirect()->route('home')->with('error', 'আপনার সেশন টাইমআউট হয়েছে। দয়া করে আবার চেষ্টা করুন।');
        });
    })->create();
