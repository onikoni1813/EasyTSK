<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'point_conversion_rate' => '100', // 100 points = 1 BDT
            'min_withdrawal' => '50',
            'notice_board_enabled' => '1',
            'notice_board_text' => "স্বাগতম! আমাদের প্ল্যাটফর্মে কাজ শুরু করার জন্য আপনাকে ধন্যবাদ।\nনির্ভুলভাবে কাজ করুন এবং বোনাস বুঝে নিন।",
            'referral_bonus_points' => '500',
            'timewall_api_key' => '',
            'timewall_secret_key' => '',
            'timewall_usd_to_points_rate' => '10000',
            'timewall_platform_share_percent' => '20',
            'require_kyc_for_withdrawal' => '0',
            'blog_timer_seconds' => '30', // Blog secret code timer duration (seconds)
        ];

        foreach ($settings as $key => $value) {
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
