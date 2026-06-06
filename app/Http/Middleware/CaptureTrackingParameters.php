<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CaptureTrackingParameters
{
    /**
     * Capture UTM and referral tracking parameters from the URL
     * and persist them in both session and cookie for later use.
     *
     * IMPORTANT: This middleware must run AFTER StartSession (use appendToGroup, not prependToGroup).
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

        // ── BEFORE $next(): Store tracking params in session ────────────────
        // This ensures session data is available during request processing
        // AND will be persisted when StartSession saves in its "after" phase.
        foreach ($trackingParams as $param) {
            if ($request->has($param)) {
                $value = $request->input($param);

                if ($param === 'ref') {
                    // Normalize referral code to uppercase consistently
                    $value = strtoupper(trim($value));
                }

                // Store in session (available for this request + future requests)
                session([$param => $value]);
            }
        }

        // ── Process the request ─────────────────────────────────────────────
        $response = $next($request);

        // ── AFTER $next(): Attach cookies to the response ───────────────────
        // Cookies need the response object, so they must be set after $next().
        foreach ($trackingParams as $param) {
            if ($request->has($param)) {
                $value = $request->input($param);

                if ($param === 'ref') {
                    $value = strtoupper(trim($value));
                }

                // Also store in cookie for longer persistence (30 days)
                $response->withCookie(cookie($param, $value, 60 * 24 * 30));
            }
        }

        return $response;
    }
}
