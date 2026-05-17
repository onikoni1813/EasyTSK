<?php

namespace App\Console\Commands;

use App\Models\Submission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupOldScreenshots extends Command
{
    protected $signature = 'screenshots:cleanup {--dry-run : List files without deleting}';

    protected $description = 'Delete proof screenshots older than 7 days (approved/rejected submissions)';

    public function handle(): int
    {
        $cutoff = now()->subDays(7);
        $dryRun = $this->option('dry-run');

        $submissions = Submission::whereIn('status', ['approved', 'rejected'])
            ->where('moderated_at', '<', $cutoff)
            ->whereNotNull('proof_image')
            ->get();

        if ($submissions->isEmpty()) {
            $this->info('No old screenshots to clean.');

            return 0;
        }

        $deleted = 0;
        $listed = 0;

        foreach ($submissions as $sub) {
            $path = $sub->proof_image;
            if (Storage::disk('public')->exists($path)) {
                if ($dryRun) {
                    $this->line("[DRY-RUN] Would delete: {$path}");
                    $listed++;
                } else {
                    Storage::disk('public')->delete($path);
                    $sub->update(['proof_image' => null]);
                    $deleted++;
                }
            }
        }

        if ($dryRun) {
            $this->info("Dry run complete. {$listed} file(s) would be deleted.");
        } else {
            $this->info("✓ Deleted {$deleted} screenshot file(s).");
        }

        return 0;
    }
}
