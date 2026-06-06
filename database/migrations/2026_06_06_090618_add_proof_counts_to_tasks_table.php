<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // কতটি secret code দিতে হবে (default 1)
            if (! Schema::hasColumn('tasks', 'secret_code_count')) {
                $table->unsignedTinyInteger('secret_code_count')->default(1)->after('secret_code');
            }
            // আলাদা আলাদা secret code গুলো JSON এ (যদি count > 1)
            if (! Schema::hasColumn('tasks', 'secret_codes')) {
                $table->json('secret_codes')->nullable()->after('secret_code_count');
            }
            // কতটি image proof দিতে হবে (default 1)
            if (! Schema::hasColumn('tasks', 'image_proof_count')) {
                $table->unsignedTinyInteger('image_proof_count')->default(1)->after('requires_image_proof');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['secret_code_count', 'secret_codes', 'image_proof_count']);
        });
    }
};
