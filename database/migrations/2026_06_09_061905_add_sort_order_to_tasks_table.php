<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedSmallInteger('sort_order')->default(0)->after('id');
        });

        // Auto-fill existing tasks with their id-based order (1, 2, 3...)
        $tasks = DB::table('tasks')->orderBy('id')->get();
        foreach ($tasks as $i => $task) {
            DB::table('tasks')->where('id', $task->id)->update(['sort_order' => $i + 1]);
        }
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
