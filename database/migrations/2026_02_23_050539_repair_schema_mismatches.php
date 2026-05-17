<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_referral_unlocked')->default(false)->after('referred_by');
            $table->decimal('moderator_earnings_bdt', 15, 2)->default(0)->after('moderator_balance');
            $table->integer('total_reviews_done')->default(0)->after('moderator_earnings_bdt');
        });

        Schema::table('withdrawals', function (Blueprint $table) {
            $table->renameColumn('payment_method', 'method');
            $table->renameColumn('account_number', 'account');
            $table->foreignId('moderated_by')->nullable()->constrained('users')->after('admin_notes');
            $table->timestamp('moderated_at')->nullable()->after('moderated_by');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_referral_unlocked', 'moderator_earnings_bdt', 'total_reviews_done']);
        });

        Schema::table('withdrawals', function (Blueprint $table) {
            $table->renameColumn('method', 'payment_method');
            $table->renameColumn('account', 'account_number');
            $table->dropForeign(['moderated_by']);
            $table->dropColumn(['moderated_by', 'moderated_at']);
        });
    }
};
