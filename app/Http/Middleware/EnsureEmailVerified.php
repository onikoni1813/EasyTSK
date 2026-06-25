<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Middleware: Require email verification (Disabled).
 */
class EnsureEmailVerified
{
    public function handle(Request $request, Closure $next)
    {
        // Email verification completely disabled for all users
        return $next($request);
    }
}