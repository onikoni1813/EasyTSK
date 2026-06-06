<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offerwalls', function (Blueprint $table) {
            $table->id();

            // ── Identity ────────────────────────────────────────────────────
            $table->string('name');                    // "CPAGrip", "Torox"
            $table->string('slug')->unique();          // "cpagrip" — URL-safe
            $table->string('display_name');            // "বোনাস ওয়াল" — user sees
            $table->text('description')->nullable();
            $table->string('icon_emoji', 10)->default('🎁');
            $table->string('color', 30)->default('indigo'); // theme color class
            $table->boolean('is_active')->default(false);
            $table->integer('sort_order')->default(0);

            // ── Integration Config ──────────────────────────────────────────
            $table->text('iframe_url_template')->nullable(); // "https://offers.example.com/?appid={api_key}&userid={user_id}"
            $table->string('api_key')->nullable();
            $table->string('secret_key')->nullable();

            // ── Postback Config ─────────────────────────────────────────────
            $table->enum('postback_method', ['GET', 'POST', 'ANY'])->default('ANY');
            $table->enum('security_type', ['hmac_sha256', 'secret_match', 'ip_whitelist', 'none'])->default('secret_match');
            $table->string('security_field')->default('secret'); // request field for signature/secret
            $table->json('hmac_fields')->nullable();             // ["user_id", "campaign_id", "reward"]
            $table->json('ip_whitelist')->nullable();            // ["1.2.3.4"]

            // ── Field Mapping ───────────────────────────────────────────────
            $table->string('field_user_id')->default('user_id');
            $table->string('field_reward')->default('reward');
            $table->string('field_transaction_id')->default('transaction_id');
            $table->string('field_campaign_id')->nullable()->default('campaign_id');

            // ── Reward Calculation ──────────────────────────────────────────
            $table->enum('reward_type', ['usd', 'points', 'custom'])->default('usd');
            $table->decimal('conversion_rate', 12, 4)->default(10000); // $1 USD = 10000 points
            $table->decimal('platform_share_pct', 5, 2)->default(20); // admin keeps 20%

            // ── Task Lock ───────────────────────────────────────────────────
            $table->boolean('requires_task_lock')->default(true); // mandatory tasks must be done first

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offerwalls');
    }
};
