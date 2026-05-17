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
        Schema::table('task_domains', function (Blueprint $table) {
            $table->longText('ad_code')->nullable()->after('domain');
            $table->string('direct_link')->nullable()->after('ad_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_domains', function (Blueprint $table) {
            $table->dropColumn(['ad_code', 'direct_link']);
        });
    }
};
