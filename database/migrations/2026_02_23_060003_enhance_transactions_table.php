<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('task_id')->nullable()->after('reference_id')->comment('Source task ID');
            $table->decimal('user_reward', 15, 2)->default(0)->after('task_id')->comment('What user earned');
            $table->string('source', 50)->nullable()->after('user_reward')->comment('TimeWall, Adsterra, Custom, Referral');
            $table->string('timewall_transaction_id')->nullable()->unique()->comment('De-dup TimeWall postbacks');
            $table->string('status', 20)->default('completed')->comment('completed, pending, rejected');
        });

        // Add proof_hash to submissions for image de-dup
        Schema::table('submissions', function (Blueprint $table) {
            $table->string('proof_hash', 64)->nullable()->after('proof_image')->comment('MD5 hash of uploaded image for duplicate detection');
            $table->string('proof_image')->nullable()->change();
            $table->index('proof_hash');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['task_id', 'user_reward', 'source', 'timewall_transaction_id', 'status']);
        });
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn(['proof_hash']);
        });
    }
};
