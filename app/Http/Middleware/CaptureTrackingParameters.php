<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CaptureTrackingParameters
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $trackingParams = [
            'utm_source',
            'utm_medium',
            'utm_campaign',
            'utm_term',
            'utm_content',
            'ref'
        ];

        $response = $next($request);

        foreach ($trackingParams as $param) {
            if ($request->has($param)) {
                $value = $request->input($param);
                // Store in session (persists for 2 hours or until session ends)
                session([$param => $value]);
                
                // Also store in cookie for longer persistence (30 days)
                if ($param === 'ref') {
                    // Normalize referral code
                    $value = strtoupper($value);
                }
                // Use withCookie on response instead of queue() for middleware reliability
                $response->withCookie(cookie($param, $value, 60 * 24 * 30));
            }
        }

        return $response;
    }
}
