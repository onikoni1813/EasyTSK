<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'telegram_username')) {
                $table->string('telegram_username')->nullable()->after('trust_score')->comment('Telegram username');
            }
            if (! Schema::hasColumn('users', 'facebook_link')) {
                $table->string('facebook_link')->nullable()->after('telegram_username')->comment('Facebook profile URL');
            }
            if (! Schema::hasColumn('users', 'rocket_number')) {
                $table->string('rocket_number')->nullable()->after('nagad_number')->comment('Rocket mobile banking number');
            }
            if (! Schema::hasColumn('users', 'pending_points')) {
                $table->unsignedBigInteger('pending_points')->default(0)->after('locked_points')->comment('Points locked during pending withdrawal');
            }
            if (! Schema::hasColumn('users', 'is_banned')) {
                $table->boolean('is_banned')->default(false)->after('is_moderator');
            }
            if (! Schema::hasColumn('users', 'ban_reason')) {
                $table->string('ban_reason')->nullable()->after('is_banned');
            }
            if (! Schema::hasColumn('users', 'is_referral_unlocked')) {
                $table->boolean('is_referral_unlocked')->default(false)->after('total_admin_profit_generated');
            }
            if (! Schema::hasColumn('users', 'moderator_earnings_bdt')) {
                $table->integer('moderator_earnings_bdt')->default(0)->after('is_referral_unlocked');
            }
            if (! Schema::hasColumn('users', 'total_reviews_done')) {
                $table->integer('total_reviews_done')->default(0)->after('moderator_earnings_bdt');
            }
            if (! Schema::hasColumn('users', 'last_ip')) {
                $table->string('last_ip')->nullable()->after('total_reviews_done');
            }
            if (! Schema::hasColumn('users', 'country')) {
                $table->string('country')->nullable()->after('last_ip');
            }
            if (! Schema::hasColumn('users', 'registration_ip')) {
                $table->string('registration_ip')->nullable()->after('country');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'facebook_link',
                'telegram_username',
                'rocket_number',
                'pending_points',
                'is_banned',
                'ban_reason',
                'is_referral_unlocked',
                'moderator_earnings_bdt',
                'total_reviews_done',
                'last_ip',
                'country',
                'registration_ip',
            ]);
        });
    }
};
