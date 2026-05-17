<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\TaskDomain;
use Illuminate\Http\Request;

class BlogPostApiController extends Controller
{
    /**
     * Detect the requesting subdomain from available sources.
     * Priority: query param > Origin header > Referer header > Host header
     */
    /**
     * Extract bare hostname from any URL or host string.
     * "https://sub1.easytsk.com/foo" → "sub1.easytsk.com"
     * "sub1.easytsk.com"           → "sub1.easytsk.com"
     */
    private function normalizeHost(string $raw): string
    {
        // If it looks like a full URL (has ://), parse it
        if (str_contains($raw, '://')) {
            $parsed = parse_url($raw);
            $host = $parsed['host'] ?? '';
            if (!empty($host)) {
                return strtolower(trim($host));
            }
        }

        // Bare hostname — strip any path/trailing slash AND port
        $host = explode('/', $raw)[0];
        // Strip port if present (e.g. "demo-sub.localhost:8000" → "demo-sub.localhost")
        if (str_contains($host, ':')) {
            $host = explode(':', $host)[0];
        }
        return strtolower(trim($host));
    }

    /**
     * Detect the requesting subdomain from available sources.
     * Priority: query param > Origin header > Referer header > Host header
     * Always returns a bare hostname (e.g. "sub1.easytsk.com") for consistent DB matching.
     */
    private function detectDomain(Request $request): string
    {
        // 1. Explicit query parameter (highest priority)
        $domain = $request->query('domain');
        if (!empty($domain)) {
            return $this->normalizeHost($domain);
        }

        // 2. Origin header — set by browsers for cross-origin AJAX/fetch requests
        $origin = $request->header('Origin');
        if (!empty($origin)) {
            return $this->normalizeHost($origin);
        }

        // 3. Referer header — fallback for non-AJAX requests
        $referer = $request->header('Referer');
        if (!empty($referer)) {
            return $this->normalizeHost($referer);
        }

        // 4. Host header — last resort (this is the API server, not the subdomain)
        $host = $request->getHost();
        if (!empty($host)) {
            return $this->normalizeHost($host);
        }

        return '';
    }

    /**
     * Build a CORS-flavoured JSON response.
     */
    private function corsResponse(mixed $data, int $status = 200, string $origin = ''): \Illuminate\Http\JsonResponse
    {
        $response = response()->json($data, $status);

        if (!empty($origin)) {
            $response->header('Access-Control-Allow-Origin', $origin);
        } else {
            $response->header('Access-Control-Allow-Origin', '*');
        }
        $response->header('Access-Control-Allow-Methods', 'GET, OPTIONS');
        $response->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With');
        $response->header('Access-Control-Max-Age', '86400');

        return $response;
    }

    /**
     * Handle CORS preflight (OPTIONS) requests.
     */
    public function handleOptions(Request $request): \Illuminate\Http\JsonResponse
    {
        $origin = $request->header('Origin', '*');
        return $this->corsResponse(['status' => 'ok'], 200, $origin);
    }

    public function show(Request $request, mixed $id)
    {
        // Handle CORS preflight
        if ($request->isMethod('options')) {
            return $this->handleOptions($request);
        }

        $post = BlogPost::find($id);

        if (!$post) {
            return $this->corsResponse(['error' => 'Post not found'], 404, $request->header('Origin') ?? '');
        }

        // ── Auto-detect domain from multiple sources ──
        $domainHost = $this->detectDomain($request);
        // Preserve original Origin for CORS header (needs scheme, e.g. "https://sub1.easytsk.com")
        $corsOrigin = $request->header('Origin', $request->query('domain', '*'));
        
        $uid = $request->query('uid', '');
        $tid = $request->query('tid', '');
        $adCode1 = '';
        $adCode2 = '';
        $adCode3 = '';
        $directLink = '';

        if ($domainHost) {
            // Direct DB query to avoid N+1 loading all domains
            $taskDomain = TaskDomain::where('is_active', true)
                ->whereRaw('LOWER(domain) = ?', [strtolower($domainHost)])
                ->first();

            if ($taskDomain) {
                $adCode1 = $taskDomain->ad_code_1 ?? '';
                $adCode2 = $taskDomain->ad_code_2 ?? '';
                $adCode3 = $taskDomain->ad_code_3 ?? '';
                $directLink = $taskDomain->direct_link ?? '';
            }
        }

        // Dynamically substitute placeholders inside the post content if present
        $content = $post->content;
        
        // Backward compatibility for {ad_code}
        if (str_contains($content, '{ad_code}')) {
            $content = str_replace('{ad_code}', $adCode1, $content);
        }
        
        if (str_contains($content, '{ad_code_1}')) {
            $content = str_replace('{ad_code_1}', $adCode1, $content);
        }
        if (str_contains($content, '{ad_code_2}')) {
            $content = str_replace('{ad_code_2}', $adCode2, $content);
        }
        if (str_contains($content, '{ad_code_3}')) {
            $content = str_replace('{ad_code_3}', $adCode3, $content);
        }
        // Substitute placeholders in direct_link too (for chaining domains)
        $directLink = str_replace(['{user_id}', '{task_id}'], [$uid, $tid], $directLink);

        if (str_contains($content, '{direct_link}')) {
            $content = str_replace('{direct_link}', $directLink, $content);
        }
        if (str_contains($content, '{user_id}')) {
            $content = str_replace('{user_id}', $uid, $content);
        }
        if (str_contains($content, '{task_id}')) {
            $content = str_replace('{task_id}', $tid, $content);
        }
        // Dynamic secret code — same formula as TaskController::submit()
        // Compute even if not in content, so blog subdomain can use it via API
        // 🔒 Read from .env — NOT from DB settings (DB is visible in admin panel)
        $secretSalt = env('TASK_SECRET_SALT', 'MicroJobV1Secret!');
        $secretCode = substr(md5($uid . $tid . $secretSalt), 0, 8);

        if (str_contains($content, '{secret_code}')) {
            $content = str_replace('{secret_code}', $secretCode, $content);
        }

        // If no domain direct_link found, try the blog's own adsterra_link as fallback
        if (empty($directLink)) {
            // Look up from blog_posts.local_ad_link if post has one
            // (This requires the blog to sync its adsterra_link to the main DB)
            $directLink = $post->adsterra_link ?? '';
        }

        // Timer duration from admin panel setting or default 30s
        $timerSeconds = (int) setting('blog_timer_seconds', 30);

        return $this->corsResponse([
            'id' => $post->id,
            'title' => $post->title,
            'content' => $content,
            'ad_code_1' => $adCode1,
            'ad_code_2' => $adCode2,
            'ad_code_3' => $adCode3,
            'direct_link' => $directLink,
            'secret_code' => $secretCode,
            'timer_seconds' => $timerSeconds,
            'created_at' => $post->created_at->toIso8601String()
        ], 200, $corsOrigin);
    }

    public function latest(Request $request)
    {
        // Handle CORS preflight
        if ($request->isMethod('options')) {
            return $this->handleOptions($request);
        }

        $post = BlogPost::latest()->first();

        if (!$post) {
            return $this->corsResponse(['error' => 'No posts found'], 404, $request->header('Origin') ?? '');
        }

        return $this->show($request, $post->id);
    }
}
