<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminKYCController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminSubmissionController;
use App\Http\Controllers\Admin\AdminSupportController;
use App\Http\Controllers\Admin\AdminSystemController;
use App\Http\Controllers\Admin\AdminTaskController;
use App\Http\Controllers\Admin\AdminBlogPostController;
use App\Http\Controllers\Admin\AdminTaskDomainController;
use App\Http\Controllers\Admin\AdminTickerController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminWithdrawalController;
use App\Http\Controllers\Admin\AdminWithdrawalMethodController;
use App\Http\Controllers\AdsterraController;
use App\Http\Controllers\KYCController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TimeWallController;
use App\Http\Controllers\WithdrawalController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ─── Public Routes ─────────────────────────────────────────────────────────
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('welcome');
})->name('home')->middleware('throttle:60,1'); // 60 req/min — DDoS protection

Route::get('/terms', fn () => view('legal.terms'))->name('terms')->middleware('throttle:30,1');
Route::get('/privacy', fn () => view('legal.privacy'))->name('privacy')->middleware('throttle:30,1');
Route::get('/faq', fn () => view('legal.faq'))->name('faq')->middleware('throttle:30,1');
Route::get('/contact', fn () => view('legal.contact'))->name('contact')->middleware('throttle:30,1');
Route::get('/manifest.json', [App\Http\Controllers\PWAController::class, 'manifest'])->name('manifest')->middleware('throttle:30,1');

// ─── Public API (AJAX for landing page) ────────────────────────────────────
Route::get('/api/stats', [AdsterraController::class, 'stats'])->name('api.stats')->middleware('throttle:30,1');
Route::get('/api/payment-proof', [AdsterraController::class, 'paymentProof'])->name('api.payment_proof')->middleware('throttle:30,1');

// ─── TimeWall S2S Postback (no auth – server to server) ────────────────────
Route::post('/api/postback/timewall', [TimeWallController::class, 'postback'])
    ->middleware('throttle:30,1')  // Max 30 postback attempts per minute
    ->name('timewall.postback');
Route::get('/api/postback/timewall', [TimeWallController::class, 'postback'])
    ->middleware('throttle:30,1'); // TimeWall may use GET

// Custom Maintenance
Route::get('/module-maintenance', function () {
    return view('errors.maintenance');
})->name('module.maintenance');

// ─── Authenticated User Routes ──────────────────────────────────────────────
Route::middleware(['auth', 'email.verified'])->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Protected Task Modules (VPN Shield)
    Route::middleware(['vpn'])->group(function () {
        // Tasks
        Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
        Route::post('/tasks/{task}/submit', [TaskController::class, 'submit'])
            ->middleware('throttle:10,1')  // Max 10 submissions per minute per user
            ->name('tasks.submit');

        // TimeWall
        Route::get('/timewall', [TimeWallController::class, 'index'])->name('timewall.index');

        // Monlix
        Route::get('/monlix', [App\Http\Controllers\MonlixController::class, 'index'])->name('monlix.index');

        // Adsterra
        Route::get('/adsterra', [AdsterraController::class, 'index'])->name('adsterra.index');
        Route::get('/adsterra/gateway/{task}', [AdsterraController::class, 'gateway'])->name('adsterra.gateway');
        Route::post('/adsterra/generate-code/{task}', [AdsterraController::class, 'generateCode'])->name('adsterra.generate_code');
        Route::post('/adsterra/verify-code', [AdsterraController::class, 'verifyCode'])
            ->middleware('throttle:5,1')  // Max 5 code attempts per minute
            ->name('adsterra.verify_code');
    });

    // Notifications
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

    // Withdrawals
    Route::get('/withdrawal', [WithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::post('/withdrawal', [WithdrawalController::class, 'store'])
        ->middleware('throttle:3,60')  // Max 3 withdrawal requests per hour
        ->name('withdrawals.store');

    // Referrals
    Route::get('/referrals', [ReferralController::class, 'index'])->name('referrals.index');

    // Activity History
    Route::get('/activity', [ActivityController::class, 'index'])->name('activity.index');

    // Notifications
    Route::post('/notifications/mark-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark_read');
    Route::get('/api/notifications/poll', [NotificationController::class, 'poll'])->name('notifications.poll');
    Route::post('/notifications/dismiss-withdrawal/{id}', [NotificationController::class, 'dismissWithdrawal'])->name('notifications.dismiss_withdrawal');
    Route::post('/notifications/dismiss-ticket/{id}', [NotificationController::class, 'dismissTicket'])->name('notifications.dismiss_ticket');

    // KYC
    Route::get('/kyc', [KYCController::class, 'index'])->name('kyc.index');
    Route::post('/kyc', [KYCController::class, 'store'])
        ->middleware('throttle:3,10')  // Max 3 KYC uploads per 10 min
        ->name('kyc.store');

    // Support
    Route::get('/support', [SupportController::class, 'index'])->name('support.index');
    Route::get('/support/create', [SupportController::class, 'create'])->name('support.create');
    Route::post('/support', [SupportController::class, 'store'])
        ->middleware('throttle:5,30')  // Max 5 tickets per 30 min
        ->name('support.store');
    Route::get('/support/{ticket}', [SupportController::class, 'show'])->name('support.show');
    Route::post('/support/{ticket}/reply', [SupportController::class, 'reply'])->name('support.reply');
    Route::get('/api/support/{ticket}/messages', [App\Http\Controllers\SupportDataController::class, 'getMessages'])->name('api.support.messages');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── Admin Routes ───────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix(config('app.admin_prefix', 'admin'))->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Users (CRM)
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/adjust-balance', [AdminUserController::class, 'adjustBalance'])->name('users.adjust_balance');
    Route::post('/users/{user}/warn', [AdminUserController::class, 'warn'])->name('users.warn');
    Route::post('/users/{user}/ban', [AdminUserController::class, 'ban'])->name('users.ban');
    Route::post('/users/{user}/unban', [AdminUserController::class, 'unban'])->name('users.unban');
    Route::post('/users/{user}/set-role', [AdminUserController::class, 'setRole'])->name('users.set_role');
    Route::post('/users/{user}/update-permissions', [AdminUserController::class, 'updatePermissions'])->name('users.update_permissions');

    // Fraud Logs (Security Radar)
    Route::get('/fraud-logs', [App\Http\Controllers\Admin\AdminFraudController::class, 'index'])->name('fraud.index');
    Route::post('/fraud-logs/{log}/ban', [App\Http\Controllers\Admin\AdminFraudController::class, 'banOriginalUser'])->name('fraud.ban');
    Route::delete('/fraud-logs/{log}', [App\Http\Controllers\Admin\AdminFraudController::class, 'destroy'])->name('fraud.destroy');

    // Referral Logs
    Route::get('/referrals/logs', [App\Http\Controllers\Admin\AdminReferralController::class, 'index'])->name('referrals.index');

    // Tasks
    Route::get('/tasks', [AdminTaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [AdminTaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [AdminTaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}/edit', [AdminTaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [AdminTaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [AdminTaskController::class, 'destroy'])->name('tasks.destroy');

    // Domains
    Route::get('/domains', [AdminTaskDomainController::class, 'index'])->name('domains.index');
    Route::post('/domains', [AdminTaskDomainController::class, 'store'])->name('domains.store');
    Route::put('/domains/{domain}', [AdminTaskDomainController::class, 'update'])->name('domains.update');
    Route::post('/domains/{domain}/toggle', [AdminTaskDomainController::class, 'toggle'])->name('domains.toggle');
    Route::delete('/domains/{domain}', [AdminTaskDomainController::class, 'destroy'])->name('domains.destroy');

    // Central Blog Posts
    Route::get('/posts', [AdminBlogPostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [AdminBlogPostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [AdminBlogPostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [AdminBlogPostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [AdminBlogPostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [AdminBlogPostController::class, 'destroy'])->name('posts.destroy');

    // Submissions (Ajax)
    Route::get('/submissions', [AdminSubmissionController::class, 'index'])->name('submissions.index');
    Route::post('/submissions/{submission}/approve', [AdminSubmissionController::class, 'approve'])->name('submissions.approve');
    Route::post('/submissions/{submission}/reject', [AdminSubmissionController::class, 'reject'])->name('submissions.reject');

    // Withdrawals
    Route::get('/withdrawals', [AdminWithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::post('/withdrawals/{withdrawal}/approve', [AdminWithdrawalController::class, 'approve'])->name('withdrawals.approve');
    Route::post('/withdrawals/{withdrawal}/reject', [AdminWithdrawalController::class, 'reject'])->name('withdrawals.reject');
    Route::post('/withdrawals/{withdrawal}/confiscate', [AdminWithdrawalController::class, 'confiscate'])->name('withdrawals.confiscate');

    // Withdrawal Methods (Dynamic)
    Route::resource('/withdrawal-methods', AdminWithdrawalMethodController::class)->names('withdrawal-methods');
    Route::post('/withdrawal-methods/{withdrawalMethod}/toggle', [AdminWithdrawalMethodController::class, 'toggle'])->name('withdrawal-methods.toggle');

    // KYC
    Route::get('/kyc', [AdminKYCController::class, 'index'])->name('kyc.index');
    Route::get('/kyc/{user}/view-image/{side}', [AdminKYCController::class, 'viewImage'])->name('kyc.view');
    Route::post('/kyc/{user}/approve', [AdminKYCController::class, 'approve'])->name('kyc.approve');
    Route::post('/kyc/{user}/reject', [AdminKYCController::class, 'reject'])->name('kyc.reject');

    // Support
    Route::get('/support', [AdminSupportController::class, 'index'])->name('support.index');
    Route::get('/support/{ticket}', [AdminSupportController::class, 'show'])->name('support.show');
    Route::post('/support/{ticket}/reply', [AdminSupportController::class, 'reply'])->name('support.reply');
    Route::post('/support/{ticket}/close', [AdminSupportController::class, 'close'])->name('support.close');
    Route::get('/api/support/unread-count', [App\Http\Controllers\Admin\AdminSupportDataController::class, 'getUnreadCount'])->name('support.unread_count');

    // Live Ticker HUD
    Route::get('/ticker', [AdminTickerController::class, 'index'])->name('ticker.index');
    Route::post('/ticker', [AdminTickerController::class, 'update'])->name('ticker.update');

    // Leaderboard
    Route::get('/leaderboard', [App\Http\Controllers\Admin\AdminLeaderboardController::class, 'index'])->name('leaderboard.index');

    // Settings
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/cleanup-screenshots', [AdminSettingsController::class, 'cleanupScreenshots'])->name('settings.cleanup');

    // Email Settings
    Route::get('/settings/email', [AdminSettingsController::class, 'emailSettings'])->name('settings.email');
    Route::post('/settings/email', [AdminSettingsController::class, 'updateEmailSettings'])->name('settings.email.update');
    Route::post('/settings/email/test', [AdminSettingsController::class, 'testEmail'])->name('settings.email.test');

    // System Manager / Cron
    Route::get('/system', [App\Http\Controllers\Admin\AdminSystemController::class, 'index'])->name('system.index');
    Route::post('/system/clear-cache', [App\Http\Controllers\Admin\AdminSystemController::class, 'clearCache'])->name('system.clear_cache');
    Route::post('/system/optimize', [App\Http\Controllers\Admin\AdminSystemController::class, 'optimize'])->name('system.optimize');
    Route::post('/system/clear-notifications', [App\Http\Controllers\Admin\AdminSystemController::class, 'clearNotifications'])->name('system.clear_notifications');

    // Shared Hosting Toolkit
    Route::prefix('toolkit')->name('toolkit.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AdminToolkitController::class, 'index'])->name('index');
        Route::get('/migrate', [App\Http\Controllers\Admin\AdminToolkitController::class, 'migrate'])->name('migrate');
        Route::get('/seed', [App\Http\Controllers\Admin\AdminToolkitController::class, 'seed'])->name('seed');
        Route::get('/storage-link', [App\Http\Controllers\Admin\AdminToolkitController::class, 'storageLink'])->name('storage-link');
        Route::get('/clear-cache', [App\Http\Controllers\Admin\AdminToolkitController::class, 'clearCache'])->name('clear-cache');
    });
});

require __DIR__.'/auth.php';
