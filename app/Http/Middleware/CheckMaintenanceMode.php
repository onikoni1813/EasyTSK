<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isMaintenance = \App\Models\Setting::get('maintenance_mode', '0') == '1';

        if ($isMaintenance) {
            $adminPrefix = env('ADMIN_PREFIX', 'admin');

            // Allow admin routes, authentication routes, and administrators to bypass
            if ($request->is("$adminPrefix*") || $request->is('login') || $request->is('register') || $request->is('logout') || ($request->user() && $request->user()->is_admin)) {
                return $next($request);
            }

            return response()->view('errors.maintenance', [
                'message' => \App\Models\Setting::get('maintenance_message', 'আমাদের সিস্টেমের কিছু জরুরি রক্ষণাবেক্ষণ কাজ চলছে। আমরা খুব শীঘ্রই ফিরে আসছি।'),
            ], 503);
        }

        return $next($request);
    }
}
