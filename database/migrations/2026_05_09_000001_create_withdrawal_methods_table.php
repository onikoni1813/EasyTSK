<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawal_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();           // bkash, nagad, rocket, recharge
            $table->string('label');                    // Display: Bkash, Nagad...
            $table->string('icon_emoji')->default('💳');
            $table->string('color_class')->default('primary'); // for UI theming
            $table->boolean('is_active')->default(true);
            $table->decimal('min_amount', 10, 2)->default(50);
            $table->enum('charge_type', ['fixed', 'percent'])->default('percent');
            $table->decimal('charge_value', 10, 2)->default(0);
            $table->string('account_label')->default('একাউন্ট নম্বর');
            $table->string('account_placeholder')->default('017XXXXXXXX');
            $table->integer('account_min_length')->default(10);
            $table->integer('account_max_length')->default(20);
            $table->integer('sort_order')->default(0);
            $table->text('instructions')->nullable();   // extra note for users
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawal_methods');
    }
};
