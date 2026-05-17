<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // submissions: remove legacy 'image_hash' column (replaced by 'proof_hash')
        Schema::table('submissions', function (Blueprint $table) {
            if (Schema::hasColumn('submissions', 'image_hash')) {
                $table->dropColumn('image_hash');
            }
        });

        // tasks: remove legacy 'image_proof_required' column (replaced by 'requires_image_proof')
        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'image_proof_required')) {
                $table->dropColumn('image_proof_required');
            }
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->string('image_hash')->nullable();
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->string('image_proof_required')->default(true);
        });
    }
};
