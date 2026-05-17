<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class TaskRotateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:rotate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rotate active tasks by resetting their remaining quota to max quota every 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting task rotation (resetting quotas)...');

        $updatedCount = Task::where('is_active', true)
            ->update([
                'quota_remaining' => DB::raw('quota_max')
            ]);

        $this->info("Successfully rotated/reset quota for {$updatedCount} active tasks.");
    }
}
