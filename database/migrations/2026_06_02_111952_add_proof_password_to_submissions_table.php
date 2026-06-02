<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            if (! Schema::hasColumn('submissions', 'proof_password')) {
                $table->string('proof_password', 255)->nullable()->after('proof_email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            if (Schema::hasColumn('submissions', 'proof_password')) {
                $table->dropColumn('proof_password');
            }
        });
    }
};
