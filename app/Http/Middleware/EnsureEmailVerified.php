<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Middleware: Require email verification for users who have an email set.
 * 
 * Since email is optional in this system, we only gate users who
 * actually provided an email address but haven't verified it yet.
 * Users without an email (mobile-only) are allowed through.
 */
class EnsureEmailVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        // Only gate users who have an email set but not verified.
        // Since email is optional (mobile-only users allowed), we check email_verified_at directly.
        if ($user->email && !$user->email_verified_at) {
            // Allow access to verification-related routes + logout
            if ($request->routeIs('verification.*') || $request->routeIs('logout')) {
                return $next($request);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your email address is not verified.',
                    'verify_url' => route('verification.notice'),
                ], 403);
            }

            return redirect()->route('verification.notice')
                ->with('warning', 'দয়া করে আপনার ইমেইল ভেরিফাই করুন। ভেরিফিকেশন লিংক আপনার ইমেইলে পাঠানো হয়েছে।');
        }

        return $next($request);
    }
}