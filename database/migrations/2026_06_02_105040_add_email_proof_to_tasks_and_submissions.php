<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add requires_email_proof to tasks table
        Schema::table('tasks', function (Blueprint $table) {
            if (! Schema::hasColumn('tasks', 'requires_email_proof')) {
                $table->boolean('requires_email_proof')->default(false)->after('requires_image_proof');
            }
        });

        // Add proof_email to submissions table
        Schema::table('submissions', function (Blueprint $table) {
            if (! Schema::hasColumn('submissions', 'proof_email')) {
                $table->string('proof_email', 255)->nullable()->after('proof_text');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'requires_email_proof')) {
                $table->dropColumn('requires_email_proof');
            }
        });

        Schema::table('submissions', function (Blueprint $table) {
            if (Schema::hasColumn('submissions', 'proof_email')) {
                $table->dropColumn('proof_email');
            }
        });
    }
};
