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
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->boolean('is_read')->default(false)->after('status'); // Admin read status
        });

        Schema::table('ticket_messages', function (Blueprint $table) {
            $table->boolean('is_read')->default(false)->after('is_admin_reply'); // User read status for admin replies
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->dropColumn('is_read');
        });

        Schema::table('ticket_messages', function (Blueprint $table) {
            $table->dropColumn('is_read');
        });
    }
};
