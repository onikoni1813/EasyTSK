<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Exception;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'point_conversion_rate' => Setting::get('point_conversion_rate', 100),
            'signup_bonus_points'   => Setting::get('signup_bonus_points', 200),
            'referral_bonus_amount' => Setting::get('referral_bonus_amount', 10),
            'referral_unlock_target' => Setting::get('referral_unlock_target', 20),
            'timewall_api_key' => Setting::get('timewall_api_key', ''),
            'timewall_secret_key' => Setting::get('timewall_secret_key', ''),
            'timewall_display_name' => Setting::get('timewall_display_name', 'Premium Tasks'),
            'timewall_usd_to_points_rate' => Setting::get('timewall_usd_to_points_rate', 10000),
            'timewall_platform_share_percent' => Setting::get('timewall_platform_share_percent', 20),
            'monlix_secret_key' => Setting::get('monlix_secret_key', ''),
            'monlix_display_name' => Setting::get('monlix_display_name', 'Bonus Wall'),
            'monlix_conversion_rate' => Setting::get('monlix_conversion_rate', 0.01),
            'is_monlix_active' => Setting::get('is_monlix_active', false),
            'is_timewall_active' => Setting::get('is_timewall_active', false),
            'is_adsterra_active' => Setting::get('is_adsterra_active', false),
            'adsterra_popunder_enabled' => Setting::get('adsterra_popunder_enabled', false),
            'adsterra_popunder_script' => Setting::get('adsterra_popunder_script', ''),
            'is_social_tasks_active' => Setting::get('is_social_tasks_active', true),
            'adsterra_display_name' => Setting::get('adsterra_display_name', 'Ads View'),
            'adsterra_direct_link' => Setting::get('adsterra_direct_link', ''),
            'adsterra_timer_seconds' => Setting::get('adsterra_timer_seconds', 30),
            'adsterra_platform_share_percent' => Setting::get('adsterra_platform_share_percent', 30),
            'custom_platform_share_percent' => Setting::get('custom_platform_share_percent', 20),
            'moderator_salary_per_task' => Setting::get('moderator_salary_per_task', 0.05),
            'notice_board_text' => Setting::get('notice_board_text', ''),
            'notice_board_enabled' => Setting::get('notice_board_enabled', false),
            'notice_board_urgent' => Setting::get('notice_board_urgent', false),
            'require_kyc_for_withdrawal' => Setting::get('require_kyc_for_withdrawal', false),
            'kyc_threshold' => Setting::get('kyc_threshold', 500),
            'withdrawal_cooldown_hours' => Setting::get('withdrawal_cooldown_hours', 24),
            'fake_member_offset' => Setting::get('fake_member_offset', 1000),
            'fake_paid_offset' => Setting::get('fake_paid_offset', 5000),
            'fake_today_tasks_offset' => Setting::get('fake_today_tasks_offset', 200),

            // SMTP (stored but handled by .env in production)
            'mail_from_address' => Setting::get('mail_from_address', ''),
            'mail_from_name' => Setting::get('mail_from_name', 'EasyTSK'),
            'site_name' => Setting::get('site_name', config('app.name')),
            'site_logo' => Setting::get('site_logo', ''),
            'site_favicon' => Setting::get('site_favicon', ''),
            'pwa_icon' => Setting::get('pwa_icon', ''),
            'maintenance_mode' => Setting::get('maintenance_mode', false),
            'maintenance_message' => Setting::get('maintenance_message', 'আমাদের সিস্টেমের কিছু জরুরি রক্ষণাবেক্ষণ কাজ চলছে। আমরা খুব শীঘ্রই ফিরে আসছি।'),
            'header_script' => Setting::get('header_script', ''),
            'body_script' => Setting::get('body_script', ''),
            'vpn_check_enabled' => Setting::get('vpn_check_enabled', false),
            'proxycheck_api_key' => Setting::get('proxycheck_api_key', ''),
            'youtube_tutorial_link' => Setting::get('youtube_tutorial_link', ''),
            'telegram_channel_link' => Setting::get('telegram_channel_link', ''),
            // Tawk.to Live Chat
            'tawkto_enabled'     => Setting::get('tawkto_enabled', false),
            'tawkto_property_id' => Setting::get('tawkto_property_id', ''),
            'tawkto_widget_id'   => Setting::get('tawkto_widget_id', '1xxxxxxxx'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'point_conversion_rate'  => 'required|integer|min:1',
            'signup_bonus_points'    => 'required|integer|min:0',
            'referral_bonus_amount' => 'required|numeric|min:0',
            'referral_unlock_target' => 'required|numeric|min:0',
            'timewall_usd_to_points_rate' => 'nullable|integer|min:1',
            'timewall_platform_share_percent' => 'nullable|numeric|min:0|max:100',
            'monlix_secret_key' => 'nullable|string',
            'monlix_conversion_rate' => 'nullable|numeric|min:0',
            'adsterra_timer_seconds' => 'nullable|integer|min:5|max:120',
            'blog_timer_seconds' => 'required|integer|min:10|max:300',
            'adsterra_platform_share_percent' => 'nullable|numeric|min:0|max:100',
            'custom_platform_share_percent' => 'required|numeric|min:0|max:100',
            'moderator_salary_per_task' => 'required|numeric|min:0',
            'fake_member_offset' => 'required|integer|min:0',
            'fake_paid_offset' => 'required|numeric|min:0',
            'fake_today_tasks_offset' => 'required|integer|min:0',

            'kyc_threshold' => 'required|numeric|min:0',
            'withdrawal_cooldown_hours' => 'required|integer|min:0',
            'notice_board_text' => 'nullable|string|max:1000',
            'timewall_api_key' => 'nullable|string',
            'timewall_secret_key' => 'nullable|string',
            'timewall_display_name' => 'nullable|string|max:50',
            'monlix_display_name' => 'nullable|string|max:50',
            'adsterra_display_name' => 'nullable|string|max:50',
            'adsterra_direct_link' => 'nullable|string|url',
            'site_name' => 'required|string|max:255',
            'site_logo' => 'nullable|image|max:2048',
            'site_favicon' => 'nullable|image|max:512',
            'pwa_icon' => 'nullable|image|max:1024',
            'maintenance_message' => 'nullable|string|max:1000',
            // header_script and body_script accept raw HTML/JS for ad codes — restricted to super-admin only
            'header_script' => 'nullable|string',
            'body_script' => 'nullable|string',
            'adsterra_popunder_script' => 'nullable|string',
            'youtube_tutorial_link' => 'nullable|url|max:255',
            'tawkto_property_id' => 'nullable|string|max:100',
            'tawkto_widget_id'   => 'nullable|string|max:100',
            'telegram_channel_link' => 'nullable|url|max:255',
        ]);

        // Restrict header_script and body_script to super-admin only (stored XSS risk)
        $data = $request->all();
        if (!auth()->user()->is_admin) {
            unset($data['header_script'], $data['body_script']);
        }

        $settingKeys = [
            'point_conversion_rate',
            'signup_bonus_points',
            'referral_bonus_amount',
            'referral_unlock_target',
            'timewall_api_key',
            'timewall_secret_key',
            'timewall_display_name',
            'timewall_usd_to_points_rate',
            'timewall_platform_share_percent',
            'monlix_display_name',
            'monlix_secret_key',
            'monlix_conversion_rate',
            'adsterra_display_name',
            'adsterra_direct_link',
            'adsterra_timer_seconds',
            'adsterra_platform_share_percent',
            'blog_timer_seconds',
            'custom_platform_share_percent',
            'moderator_salary_per_task',
            'notice_board_text',
            'notice_board_enabled',
            'notice_board_urgent',
            'require_kyc_for_withdrawal',
            'fake_member_offset',
            'fake_paid_offset',
            'fake_today_tasks_offset',

            'kyc_threshold',
            'withdrawal_cooldown_hours',
            'mail_from_address',
            'mail_from_name',
            'site_name',
            'maintenance_message',
            'header_script',
            'body_script',
            'adsterra_popunder_script',
            'proxycheck_api_key',
            'youtube_tutorial_link',
            'telegram_channel_link',
            'tawkto_property_id',
            'tawkto_widget_id',
        ];

        foreach ($settingKeys as $key) {
            if (array_key_exists($key, $data)) {
                Setting::set($key, $data[$key]);
            }
        }

        // Handle checkboxes
        foreach ([
            'notice_board_enabled', 
            'notice_board_urgent', 
            'require_kyc_for_withdrawal', 
            'maintenance_mode', 
            'is_social_tasks_active',
            'is_monlix_active',
            'is_timewall_active',
            'is_adsterra_active',
            'adsterra_popunder_enabled',
            'vpn_check_enabled',
            'tawkto_enabled',
        ] as $checkbox) {
            Setting::set($checkbox, $request->has($checkbox) ? '1' : '0');
        }

        // Handle File Uploads
        if ($request->hasFile('site_logo')) {
            $path = $request->file('site_logo')->store('site', 'public');
            Setting::set('site_logo', $path);
        }

        if ($request->hasFile('site_favicon')) {
            $path = $request->file('site_favicon')->store('site', 'public');
            Setting::set('site_favicon', $path);
        }

        if ($request->hasFile('pwa_icon')) {
            $path = $request->file('pwa_icon')->store('site', 'public');
            Setting::set('pwa_icon', $path);
        }

        return back()->with('success', '✅ সব সেটিং সেভ হয়েছে!');
    }

    /**
     * Manual cleanup: delete old proof screenshots
     */
    public function cleanupScreenshots()
    {
        $deleted = 0;
        $cutoff = now()->subDays(7);

        $oldSubmissions = Submission::whereIn('status', ['approved', 'rejected'])
            ->where('moderated_at', '<', $cutoff)
            ->whereNotNull('proof_image')
            ->get();

        foreach ($oldSubmissions as $sub) {
            /** @var \App\Models\Submission $sub */
            if (Storage::disk('public')->exists($sub->proof_image)) {
                Storage::disk('public')->delete($sub->proof_image);
                $sub->update(['proof_image' => null]);
                $deleted++;
            }
        }

        return back()->with('success', "✅ {$deleted} পুরনো স্ক্রিনশট মুছে ফেলা হয়েছে।");
    }

    public function emailSettings()
    {
        $settings = [
            'mail_host' => Setting::get('mail_host', config('mail.mailers.smtp.host')),
            'mail_port' => Setting::get('mail_port', config('mail.mailers.smtp.port')),
            'mail_username' => Setting::get('mail_username', config('mail.mailers.smtp.username')),
            'mail_password' => Setting::get('mail_password', config('mail.mailers.smtp.password')),
            'mail_encryption' => Setting::get('mail_encryption', config('mail.mailers.smtp.encryption')),
            'mail_from_address' => Setting::get('mail_from_address', config('mail.from.address')),
        ];

        return view('admin.settings.email', compact('settings'));
    }

    public function updateEmailSettings(Request $request)
    {
        $request->validate([
            'mail_host' => 'required',
            'mail_port' => 'required|integer',
            'mail_username' => 'nullable',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable',
            'mail_from_address' => 'required|email',
        ]);

        Setting::set('mail_host', $request->mail_host);
        Setting::set('mail_port', $request->mail_port);
        Setting::set('mail_username', $request->mail_username);
        Setting::set('mail_password', $request->mail_password);
        Setting::set('mail_encryption', $request->mail_encryption);
        Setting::set('mail_from_address', $request->mail_from_address);

        return back()->with('success', '✅ ইমেইল সেটিংস আপডেট হয়েছে!');
    }

    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            Mail::raw('This is a test email from ' . config('app.name') . ' SMTP Protocol. If you received this, your connection is successful!', function ($message) use ($request) {
                $message->to($request->test_email)
                    ->subject('SMTP Connection Test Results - ' . config('app.name'));
            });

            return back()->with('success', '✅ টেস্ট ইমেইল পাঠানো হয়েছে! আপনার ইনবক্স চেক করুন।');
        } catch (Exception $e) {
            return back()->with('error', '❌ ইমেইল পাঠাতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }
}
