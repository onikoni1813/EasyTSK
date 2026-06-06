<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Normalize all existing domains from full URL format to bare hostname
        // e.g., "https://sub.domain.com/" → "sub.domain.com"
        DB::table('task_domains')->orderBy('id')->chunk(100, function ($domains) {
            foreach ($domains as $domain) {
                $normalized = $domain->domain;
                $normalized = rtrim($normalized, '/');
                
                if (str_contains($normalized, '://')) {
                    $parsed = parse_url($normalized);
                    $host = $parsed['host'] ?? '';
                    if (!empty($host)) {
                        $normalized = strtolower(trim($host));
                    }
                }
                
                if (str_contains($normalized, ':')) {
                    $normalized = explode(':', $normalized)[0];
                }
                
                $normalized = strtolower(trim($normalized));
                
                if ($normalized !== $domain->domain) {
                    // Check uniqueness before updating
                    $exists = DB::table('task_domains')
                        ->where('domain', $normalized)
                        ->where('id', '!=', $domain->id)
                        ->exists();
                    
                    if (!$exists) {
                        DB::table('task_domains')
                            ->where('id', $domain->id)
                            ->update(['domain' => $normalized]);
                    }
                }
            }
        });
    }

    public function down(): void
    {
        // No reverse — this is a data normalization
    }
};