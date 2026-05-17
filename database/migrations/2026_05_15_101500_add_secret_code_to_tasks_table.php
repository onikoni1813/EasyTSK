<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('secret_code', 64)->nullable()->after('settings')
                ->comment('Static secret code for YouTube/Facebook video tasks. If null, dynamic md5 formula is used.');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('secret_code');
        });
    }
};