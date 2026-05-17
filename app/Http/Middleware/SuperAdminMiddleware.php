<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Only allows users with is_admin = true (full super admins).
     * Sub-admins (is_sub_admin only) are blocked.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->is_admin) {
            abort(403, 'Unauthorized: Super admin access required.');
        }

        return $next($request);
    }
}
