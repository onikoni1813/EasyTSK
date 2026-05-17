<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class CheckVpn
{
    public function handle(Request $request, Closure $next)
    {
        // Don't block admin routes
        if ($request->is(config('app.admin_prefix', 'admin') . '*')) {
            return $next($request);
        }

        if (Setting::get('vpn_check_enabled', '0') !== '1') {
            return $next($request);
        }

        // Capture Real IP (Cloudflare support)
        $ip = $request->server('HTTP_CF_CONNECTING_IP') 
            ?? $request->server('HTTP_X_FORWARDED_FOR') 
            ?? $request->ip();

        // Handle multiple IPs in X-Forwarded-For
        if (str_contains($ip, ',')) {
            $ip = trim(explode(',', $ip)[0]);
        }

        // Whitelist localhost/private IPs for testing
        if (in_array($ip, ['127.0.0.1', '::1']) || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            return $next($request);
        }

        // Caching the result for 24 hours to reduce API calls
        $cacheKey = "vpn_check_{$ip}";
        $isVpn = Cache::remember($cacheKey, 86400, function () use ($ip) {
            $apiKey = Setting::get('proxycheck_api_key');
            if (!$apiKey) return false;

            try {
                $response = Http::timeout(5)->get("https://proxycheck.io/v2/{$ip}?key={$apiKey}&vpn=1");
                $data = $response->json();

                if (isset($data[$ip]['proxy']) && $data[$ip]['proxy'] === 'yes') {
                    return true;
                }
            } catch (\Exception $e) {
                Log::error("VPN Check Error for IP {$ip}: " . $e->getMessage());
            }

            return false;
        });

        if ($isVpn) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'VPN usage is prohibited for tasks.'], 403);
            }
            return response()->view('errors.vpn', [], 403);
        }

        return $next($request);
    }
}
