<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Full admins — always pass
        if ($user->is_admin) {
            return $next($request);
        }

        // Sub-admins — allow into the panel (individual routes check specific permissions)
        if ($user->is_sub_admin) {
            return $next($request);
        }

        abort(403, 'Unauthorized access to System Nexus.');
    }
}
