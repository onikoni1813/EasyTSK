<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class AdminToolkitController extends Controller
{
    /**
     * Display the shared hosting toolkit page.
     */
    public function index()
    {
        return view('admin.toolkit.index');
    }

    /**
     * Run database migrations.
     */
    public function migrate()
    {
        Artisan::call('migrate', ['--force' => true]);
        return redirect()->route('admin.toolkit.index')->with('status', '✅ Migrations complete!');
    }

    /**
     * Run database seeders.
     */
    public function seed()
    {
        Artisan::call('db:seed', ['--force' => true]);
        return redirect()->route('admin.toolkit.index')->with('status', '✅ Seeders complete!');
    }

    /**
     * Create storage symlink.
     */
    public function storageLink()
    {
        Artisan::call('storage:link');
        return redirect()->route('admin.toolkit.index')->with('status', '✅ Storage link created!');
    }

    /**
     * Clear all caches.
     */
    public function clearCache()
    {
        Artisan::call('optimize:clear');
        return redirect()->route('admin.toolkit.index')->with('status', '✅ Cache cleared!');
    }
}