<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add new instruction_images JSON column
        Schema::table('tasks', function (Blueprint $table) {
            if (! Schema::hasColumn('tasks', 'instruction_images')) {
                $table->json('instruction_images')->nullable()->after('instruction_image');
            }
        });

        // Migrate existing single image data into the new JSON column
        DB::table('tasks')
            ->whereNotNull('instruction_image')
            ->where('instruction_image', '!=', '')
            ->get()
            ->each(function ($task) {
                DB::table('tasks')->where('id', $task->id)->update([
                    'instruction_images' => json_encode([$task->instruction_image]),
                ]);
            });

        // Drop old column
        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'instruction_image')) {
                $table->dropColumn('instruction_image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (! Schema::hasColumn('tasks', 'instruction_image')) {
                $table->string('instruction_image')->nullable()->after('external_link');
            }
        });

        // Restore first image from JSON back to single column
        DB::table('tasks')
            ->whereNotNull('instruction_images')
            ->get()
            ->each(function ($task) {
                $images = json_decode($task->instruction_images, true);
                DB::table('tasks')->where('id', $task->id)->update([
                    'instruction_image' => $images[0] ?? null,
                ]);
            });

        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'instruction_images')) {
                $table->dropColumn('instruction_images');
            }
        });
    }
};
