<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('postback_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offerwall_id')->nullable()->constrained('offerwalls')->nullOnDelete();
            $table->string('slug')->nullable();                // offerwall slug at time of request
            $table->foreignId('user_id')->nullable();          // credited user (null if not found)
            $table->string('status');                          // success, failed, duplicate, invalid_signature, user_not_found
            $table->string('ip_address')->nullable();
            $table->decimal('reward_raw', 12, 6)->default(0);  // raw reward from network
            $table->integer('points_credited')->default(0);    // points given to user
            $table->string('external_transaction_id')->nullable();
            $table->text('error_message')->nullable();
            $table->json('raw_payload')->nullable();           // full request data for debugging
            $table->timestamps();

            $table->index(['offerwall_id', 'created_at']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('postback_logs');
    }
};
