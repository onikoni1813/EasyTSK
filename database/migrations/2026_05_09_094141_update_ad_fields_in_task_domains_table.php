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
            if (Schema::hasColumn('task_domains', 'ad_code')) {
                $table->renameColumn('ad_code', 'ad_code_1');
            } else {
                if (!Schema::hasColumn('task_domains', 'ad_code_1')) {
                    $table->longText('ad_code_1')->nullable()->after('domain');
                }
            }
        });

        Schema::table('task_domains', function (Blueprint $table) {
            if (!Schema::hasColumn('task_domains', 'ad_code_2')) {
                $table->longText('ad_code_2')->nullable()->after('ad_code_1');
            }
            if (!Schema::hasColumn('task_domains', 'ad_code_3')) {
                $table->longText('ad_code_3')->nullable()->after('ad_code_2');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_domains', function (Blueprint $table) {
            $table->renameColumn('ad_code_1', 'ad_code');
            $table->dropColumn(['ad_code_2', 'ad_code_3']);
        });
    }
};
