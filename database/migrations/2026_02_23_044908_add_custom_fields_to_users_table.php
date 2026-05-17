<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false);
            $table->unsignedBigInteger('points')->default(0);
            $table->decimal('total_earned_bdt', 15, 2)->default(0);
            $table->decimal('locked_points', 15, 2)->default(0); // For referral lock
            $table->string('referral_code')->unique()->nullable();
            $table->foreignId('referred_by')->nullable()->constrained('users');
            $table->string('bkash_number')->nullable();
            $table->string('nagad_number')->nullable();
            $table->string('full_name')->nullable();
            $table->boolean('is_moderator')->default(false);
            $table->decimal('moderator_balance', 15, 2)->default(0);
            $table->integer('trust_score')->default(100); // 0-100
            $table->decimal('total_admin_profit_generated', 15, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'points',
                'total_earned_bdt',
                'locked_points',
                'referral_code',
                'referred_by',
                'bkash_number',
                'nagad_number',
                'full_name',
                'is_moderator',
                'moderator_balance',
                'trust_score',
                'total_admin_profit_generated',
            ]);
        });
    }
};
