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
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->decimal('receivable_amount', 15, 2)->after('amount_bdt')->default(0);
            $table->decimal('fee_amount', 15, 2)->after('receivable_amount')->default(0);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('receivable_amount', 15, 2)->after('amount_bdt')->default(0);
            $table->decimal('fee_amount', 15, 2)->after('receivable_amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropColumn(['receivable_amount', 'fee_amount']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['receivable_amount', 'fee_amount']);
        });
    }
};
