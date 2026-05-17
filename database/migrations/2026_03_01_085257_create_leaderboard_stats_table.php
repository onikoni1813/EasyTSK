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
        Schema::create('leaderboard_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // 'earning' or 'referral'
            $table->string('period'); // 'weekly' or 'monthly'
            $table->decimal('total_amount_or_count', 15, 2)->default(0);
            $table->timestamp('last_updated_at')->useCurrent();
            $table->timestamps();

            // Constraint so we can easily update/upsert records
            $table->unique(['user_id', 'type', 'period']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaderboard_stats');
    }
};
