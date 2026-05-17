<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Sub-admin role
            $table->boolean('is_sub_admin')->default(false)->after('is_moderator');
            // JSON column: array of permitted panel sections
            // e.g. ["submissions", "withdrawals", "users", "tasks", "kyc", "support"]
            $table->json('sub_admin_permissions')->nullable()->after('is_sub_admin');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_sub_admin', 'sub_admin_permissions']);
        });
    }
};
