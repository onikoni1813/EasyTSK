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
        Schema::table('tasks', function (Blueprint $table) {
            $table->integer('quota_max')->default(100)->after('admin_profit');
            $table->integer('quota_remaining')->default(100)->after('quota_max');
            $table->boolean('requires_text_proof')->default(false)->after('type');
            $table->boolean('requires_image_proof')->default(true)->after('requires_text_proof');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['quota_max', 'quota_remaining', 'requires_text_proof', 'requires_image_proof']);
        });
    }
};
