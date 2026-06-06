<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            // একাধিক secret code JSON array এ
            if (! Schema::hasColumn('submissions', 'proof_texts')) {
                $table->json('proof_texts')->nullable()->after('proof_text');
            }
            // একাধিক image path JSON array এ
            if (! Schema::hasColumn('submissions', 'proof_images')) {
                $table->json('proof_images')->nullable()->after('proof_image');
            }
            // একাধিক image hash JSON array এ
            if (! Schema::hasColumn('submissions', 'proof_hashes')) {
                $table->json('proof_hashes')->nullable()->after('proof_hash');
            }
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn(['proof_texts', 'proof_images', 'proof_hashes']);
        });
    }
};
