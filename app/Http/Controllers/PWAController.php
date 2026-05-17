<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class PWAController extends Controller
{
    public function manifest()
    {
        $siteName = Setting::get('site_name', config('app.name'));
        $iconPath = Setting::get('pwa_icon');
        $iconUrl = $iconPath ? asset('storage/' . $iconPath) : asset('assets/img/icon-512.png');

        $manifest = [
            "name" => $siteName,
            "short_name" => $siteName,
            "start_url" => "/",
            "display" => "standalone",
            "background_color" => "#0a0a0a",
            "theme_color" => "#00c853",
            "icons" => [
                [
                    "src" => $iconUrl,
                    "sizes" => "512x512",
                    "type" => "image/png",
                    "purpose" => "any maskable"
                ]
            ]
        ];

        return response()->json($manifest);
    }
}
