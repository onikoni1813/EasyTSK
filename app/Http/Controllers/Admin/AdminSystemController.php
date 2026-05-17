<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class AdminSystemController extends Controller
{
    public function index()
    {
        if (!auth()->user()->is_admin) { abort(403); }

        // Example cron command: * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
        $projectPath = base_path();
        $phpPath = PHP_BINARY ?? 'php';
        $cronCommand = "* * * * * cd {$projectPath} && {$phpPath} artisan schedule:run >> /dev/null 2>&1";

        return view('admin.system.index', compact('cronCommand'));
    }

    public function clearCache()
    {
        if (!auth()->user()->is_admin) { abort(403); }

        try {
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            return back()->with('success', '✅ Application cache cleared successfully!');
        } catch (\Exception $e) {
            return back()->with('error', '❌ System error: ' . $e->getMessage());
        }
    }

    /**
     * WARNING: Running 'optimize' from a web request can cause file permission issues
     * and cache corruption. This should only be run via CLI. The method now only
     * clears caches safely and warns the admin.
     */
    public function optimize()
    {
        if (!auth()->user()->is_admin) { abort(403); }

        try {
            Artisan::call('optimize:clear');
            return back()->with('warning', '⚠️ Caches cleared. For full optimization, run "php artisan optimize" via CLI/SSH.');
        } catch (\Exception $e) {
            return back()->with('error', '❌ System error: ' . $e->getMessage());
        }
    }

    public function clearNotifications()
    {
        if (!auth()->user()->is_admin) { abort(403); }

        try {
            // Clear notifications older than 30 days
            DB::table('user_notifications')->where('created_at', '<', now()->subDays(30))->delete();
            // Also database notifications table if used
            DB::table('notifications')->where('created_at', '<', now()->subDays(30))->delete();
            
            return back()->with('success', '✅ Old system notifications cleared!');
        } catch (\Exception $e) {
            return back()->with('error', '❌ System error: ' . $e->getMessage());
        }
    }
}
