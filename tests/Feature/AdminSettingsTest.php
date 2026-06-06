<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Setting;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_page_loads_and_updates_successfully()
    {
        // 1. Create or find the admin user
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
            'is_admin' => 1,
        ]);

        // 2. Act as admin and visit settings index
        $response = $this->actingAs($admin)
            ->get(route('admin.settings.index'));

        $response->assertStatus(200);
        $response->assertDontSee('TimeWall Protocol');
        $response->assertDontSee('Adsterra Gateway');
        $response->assertDontSee('Monlix Offerwall');
        $response->assertSee('Social Media Tasks');

        // 3. Post to settings update
        $postData = [
            'point_conversion_rate' => 100,
            'signup_bonus_points' => 200,
            'referral_bonus_amount' => 10,
            'referral_unlock_target' => 20,
            'custom_platform_share_percent' => 20,
            'moderator_salary_per_task' => 0.05,
            'fake_member_offset' => 1000,
            'fake_paid_offset' => 5000,
            'fake_today_tasks_offset' => 200,
            'kyc_threshold' => 500,
            'withdrawal_cooldown_hours' => 24,
            'site_name' => 'EasyTSK Test',
            'blog_timer_seconds' => 30,
        ];

        // 4. Save and assert redirection back with success message (no validation exceptions)
        $response = $this->actingAs($admin)
            ->post(route('admin.settings.update'), $postData);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        // Verify the setting value got saved correctly
        $this->assertEquals('EasyTSK Test', Setting::get('site_name'));
    }
}
