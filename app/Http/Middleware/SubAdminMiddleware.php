<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubAdminMiddleware
{
    /**
     * Allow full admins AND sub-admins to pass.
     * Optionally checks a specific permission if provided as argument.
     */
    public function handle(Request $request, Closure $next, ?string $permission = null): Response
    {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */

        // Full admins always pass
        if ($user->is_admin) {
            return $next($request);
        }

        // Must be sub-admin
        if (! $user->is_sub_admin) {
            abort(403, 'Sub-admin access required.');
        }

        // If a specific permission is required, check it
        if ($permission && ! $user->hasPermission($permission)) {
            abort(403, "You don't have permission to access: {$permission}");
        }

        return $next($request);
    }
}
