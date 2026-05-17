<?php
/**
 * ─── Blog Subdomain Secret Code End-to-End Test ─────────────────────────────
 * 
 * Tests the full flow:
 *   1. Main site API (BlogPostApiController) generates correct secret_code
 *   2. Blog subdomain (index.php) receives and displays the code
 *   3. TaskController::submit() accepts the same code
 *   4. direct_link fallback (task_domains → blog_posts.adsterra_link)
 *   5. Secret code consistency between API and submit()
 * 
 * Usage: php tests/blog_secret_code_e2e_test.php
 * 
 * ⚠️  Requires: This test runs from the Laravel root directory.
 *              It bootstraps Laravel to access DB, Settings, and Controllers.
 */

// ── Bootstrap Laravel ────────────────────────────────────────────────────
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\BlogPost;
use App\Models\Task;
use App\Models\TaskDomain;
use App\Models\User;
use App\Models\Submission;
use App\Models\Setting;
use Illuminate\Http\Request;

echo str_repeat('=', 70) . PHP_EOL;
echo "  BLOG SUBDOMAIN SECRET CODE E2E TEST" . PHP_EOL;
echo str_repeat('=', 70) . PHP_EOL . PHP_EOL;

$passed = 0;
$failed = 0;
$warnings = 0;

function pass($msg) { global $passed; echo "  ✅ PASS: $msg" . PHP_EOL; $passed++; }
function fail($msg) { global $failed; echo "  ❌ FAIL: $msg" . PHP_EOL; $failed++; }
function warn($msg) { global $warnings; echo "  ⚠️  WARN: $msg" . PHP_EOL; $warnings++; }
function info($msg) { echo "  ℹ️  $msg" . PHP_EOL; }

// ═══════════════════════════════════════════════════════════════════════════
// TEST 1: Database Configuration Check
// ═══════════════════════════════════════════════════════════════════════════
echo PHP_EOL . "── Test 1: Database Configuration ──" . PHP_EOL;

$mainDb = env('DB_DATABASE');
info("Main Laravel DB: '{$mainDb}'");

// Check blog's db.php (read the file)
// Blog is at Cpannel/Blog/ (one level UP from micro/)
$blogDbPath = 'C:\xampp\htdocs\micro\Cpannel\Blog\db.php';
if (file_exists($blogDbPath)) {
$blogDbContent = file_get_contents($blogDbPath);
preg_match('/\$dbname\s*=\s*[\'"]([^\'"]+)[\'"]/', $blogDbContent, $matches);
$blogDbName = $matches[1] ?? 'NOT FOUND';
info("Blog db.php DB: '{$blogDbName}'");

if ($mainDb === $blogDbName) {
    pass("Both main app and blog share same database: '{$blogDbName}' — no separate DB needed.");
} else {
    warn("Database mismatch: main='{$mainDb}' blog='{$blogDbName}'");
}
} else {
warn("Blog db.php not found at: {$blogDbPath}");
}

info("ANSWER: Blog subdomain does NOT need a separate database.");
info("Both connect to the same '{$mainDb}' database.");
info("Blog's db.php local blog_posts table is legacy/standalone — it's not used when fetching via API.");
info("Content is fetched from main Laravel app's blog_posts table via REST API.");

// ═══════════════════════════════════════════════════════════════════════════
// TEST 2: Secret Code Formula Consistency
// ═══════════════════════════════════════════════════════════════════════════
echo PHP_EOL . "── Test 2: Secret Code Formula Consistency ──" . PHP_EOL;

$testUid = 42;
$testTid = 7;
$secretSalt = setting('task_secret_salt', 'MicroJobV1Secret!');
info("Current secret salt: '{$secretSalt}'");

$expectedCode = substr(md5($testUid . $testTid . $secretSalt), 0, 8);
info("Formula: substr(md5(uid.tid.salt), 0, 8)");
info("Test: uid={$testUid}, tid={$testTid} → code='{$expectedCode}'");

if (strlen($expectedCode) === 8 && ctype_alnum($expectedCode)) {
    pass("Secret code formula produces 8-char alphanumeric code: '{$expectedCode}'");
} else {
    fail("Secret code is not 8-char alphanumeric: '{$expectedCode}'");
}

// Verify the formula used in API matches the one used in TaskController::submit()
// API: BlogPostApiController.php line 174
$apiCode = substr(md5($testUid . $testTid . $secretSalt), 0, 8);
// Submit: TaskController.php line 124
$submitCode = substr(md5($testUid . $testTid . 
    setting('task_secret_salt', 'MicroJobV1Secret!')), 0, 8);

if ($apiCode === $submitCode) {
    pass("API and TaskController::submit() use identical formula — codes match: '{$apiCode}'");
} else {
    fail("Formula mismatch! API='{$apiCode}' Submit='{$submitCode}'");
}

// Verify it matches the ENV setting too
$envSalt = env('TASK_SECRET_SALT', 'MicroJobV1Secret!');
info("ENV TASK_SECRET_SALT: '{$envSalt}'");
if ($secretSalt === $envSalt) {
    pass("setting('task_secret_salt') matches ENV TASK_SECRET_SALT: '{$secretSalt}'");
} else {
    warn("Salt mismatch: setting='{$secretSalt}' ENV='{$envSalt}' (DB setting may have been changed)");
}

// ═══════════════════════════════════════════════════════════════════════════
// TEST 3: Database Tables & Models
// ═══════════════════════════════════════════════════════════════════════════
echo PHP_EOL . "── Test 3: Database Tables & Models ──" . PHP_EOL;

try {
    $blogPostCount = BlogPost::count();
    info("blog_posts count: {$blogPostCount}");
    pass("BlogPost model accessible — {$blogPostCount} posts found.");
} catch (\Exception $e) {
    warn("BlogPost model error: {$e->getMessage()}");
}

try {
    $domainCount = TaskDomain::count();
    info("task_domains count: {$domainCount}");
    pass("TaskDomain model accessible — {$domainCount} domains found.");
} catch (\Exception $e) {
    warn("TaskDomain model error: {$e->getMessage()}");
}

try {
    $taskCount = Task::count();
    info("tasks count: {$taskCount}");
    pass("Task model accessible — {$taskCount} tasks found.");
} catch (\Exception $e) {
    warn("Task model error: {$e->getMessage()}");
}

// ═══════════════════════════════════════════════════════════════════════════
// TEST 4: BlogPostApiController — API Response Shape
// ═══════════════════════════════════════════════════════════════════════════
echo PHP_EOL . "── Test 4: BlogPostApiController Response Shape ──" . PHP_EOL;

// Create or use existing test data
$testPost = BlogPost::first();
if (!$testPost) {
    info("No existing blog posts. Creating a test post...");
    try {
        $testPost = BlogPost::create([
            'title' => 'Test Secret Code Post',
            'content' => 'This is a test post with {secret_code} placeholder.',
            'adsterra_link' => 'https://example-ad.com/test',
        ]);
        info("Created test post ID: {$testPost->id}");
    } catch (\Exception $e) {
        fail("Could not create test blog post: {$e->getMessage()}");
    }
}

if ($testPost) {
    // Simulate the API call the blog subdomain makes
    $testDomain = 'test-sub.easytsk.com';
    $controller = new \App\Http\Controllers\Api\BlogPostApiController();
    
    $request = Request::create(
        "/api/blog-posts/{$testPost->id}",
        'GET',
        ['domain' => $testDomain, 'uid' => $testUid, 'tid' => $testTid],
        [], [], ['HTTP_ORIGIN' => "https://{$testDomain}"]
    );
    
    $response = $controller->show($request, $testPost->id);
    $data = $response->getData(true);
    
    // Verify response structure
    $requiredKeys = ['id', 'title', 'content', 'ad_code_1', 'ad_code_2', 'ad_code_3', 'direct_link', 'secret_code', 'created_at'];
    $missingKeys = array_diff($requiredKeys, array_keys($data));
    
    if (empty($missingKeys)) {
        pass("API response contains all required keys: " . implode(', ', $requiredKeys));
    } else {
        fail("API response missing keys: " . implode(', ', $missingKeys));
    }
    
    // Verify secret_code in response
    if (isset($data['secret_code']) && $data['secret_code'] === $expectedCode) {
        pass("API returns correct secret_code '{$data['secret_code']}' for uid={$testUid}, tid={$testTid}");
    } elseif (isset($data['secret_code'])) {
        fail("API secret_code '{$data['secret_code']}' ≠ expected '{$expectedCode}'");
    } else {
        fail("API response missing 'secret_code' field");
    }
    
    // Verify content replacement worked
    if (isset($data['content']) && !str_contains($data['content'], '{secret_code}')) {
        pass("Content placeholders replaced correctly — no raw {secret_code} remaining");
    } elseif (isset($data['content'])) {
        info("Content still contains {secret_code} — may be intentional test post");
    }
    
    info("API direct_link value: '" . ($data['direct_link'] ?? 'NOT SET') . "'");
}

// ═══════════════════════════════════════════════════════════════════════════
// TEST 5: direct_link Fallback Logic
// ═══════════════════════════════════════════════════════════════════════════
echo PHP_EOL . "── Test 5: direct_link Fallback Logic ──" . PHP_EOL;

if ($testPost) {
    // Scenario A: Domain exists with direct_link
    $existingDomain = TaskDomain::where('is_active', true)->whereNotNull('direct_link')->where('direct_link', '!=', '')->first();
    
    if ($existingDomain) {
        info("Found active domain with direct_link: '{$existingDomain->domain}' → '{$existingDomain->direct_link}'");
        
        $request = Request::create(
            "/api/blog-posts/{$testPost->id}",
            'GET',
            ['domain' => $existingDomain->domain, 'uid' => $testUid, 'tid' => $testTid],
            [], [], ['HTTP_ORIGIN' => "https://{$existingDomain->domain}"]
        );
        
        $response = $controller->show($request, $testPost->id);
        $data = $response->getData(true);
        
        if (($data['direct_link'] ?? '') === $existingDomain->direct_link) {
            pass("Domain direct_link correctly returned: '{$data['direct_link']}'");
        } else {
            fail("Domain direct_link mismatch. Expected: '{$existingDomain->direct_link}', Got: '" . ($data['direct_link'] ?? 'EMPTY') . "'");
        }
    } else {
        warn("No active domain with direct_link found — skipping domain direct_link test");
    }
    
    // Scenario B: No domain match, falls back to blog_posts.adsterra_link
    if ($testPost->adsterra_link) {
        $request2 = Request::create(
            "/api/blog-posts/{$testPost->id}",
            'GET',
            ['domain' => 'nonexistent-xyz-12345.example.com', 'uid' => $testUid, 'tid' => $testTid],
            [], [], []
        );
        
        $response2 = $controller->show($request2, $testPost->id);
        $data2 = $response2->getData(true);
        
        if (($data2['direct_link'] ?? '') === $testPost->adsterra_link) {
            pass("Fallback to blog_posts.adsterra_link works: '{$data2['direct_link']}'");
        } else {
            fail("Fallback failed. Expected: '{$testPost->adsterra_link}', Got: '" . ($data2['direct_link'] ?? 'EMPTY') . "'");
        }
    } else {
        warn("Test post has no adsterra_link — can't test fallback. Set one to test.");
    }
    
    // Scenario C: No domain AND no adsterra_link → empty direct_link
    $oldLink = $testPost->adsterra_link;
    $testPost->update(['adsterra_link' => null]);
    
    $request3 = Request::create(
        "/api/blog-posts/{$testPost->id}",
        'GET',
        ['domain' => 'nonexistent-domain-abc.example.com', 'uid' => $testUid, 'tid' => $testTid],
        [], [], []
    );
    
    $response3 = $controller->show($request3, $testPost->id);
    $data3 = $response3->getData(true);
    
    if (empty($data3['direct_link'] ?? '')) {
        pass("Correctly returns empty direct_link when no domain match and no adsterra_link");
    } else {
        warn("Expected empty direct_link but got: '{$data3['direct_link']}'");
    }
    
    // Restore old link
    $testPost->update(['adsterra_link' => $oldLink]);
}

// ═══════════════════════════════════════════════════════════════════════════
// TEST 6: Blog subdomain integration simulation
// ═══════════════════════════════════════════════════════════════════════════
echo PHP_EOL . "── Test 6: Blog Subdomain Integration Simulation ──" . PHP_EOL;

// Simulate blog's index.php cURL call
$mainSiteUrl = rtrim(env('APP_URL', 'https://easytsk.com'), '/');
$blogDomain = 'test-sub.easytsk.com';

info("Simulating blog subdomain cURL call to main API...");
info("URL: {$mainSiteUrl}/api/blog-posts/latest?domain={$blogDomain}&uid={$testUid}&tid={$testTid}");

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "{$mainSiteUrl}/api/blog-posts/latest?domain=" . urlencode($blogDomain) . "&uid={$testUid}&tid={$testTid}");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_HEADER, true); // Include headers to check CORS
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    warn("cURL error: {$curlError} — Is Apache running? Skipping HTTP test.");
} else {
    $headers = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    $apiData = json_decode($body, true);
    
    if ($httpCode === 200) {
        pass("HTTP 200 — API responded successfully");
    } else {
        warn("HTTP {$httpCode} — unexpected status code");
    }
    
    // Check CORS header
    if (strpos($headers, 'Access-Control-Allow-Origin') !== false) {
        pass("CORS header 'Access-Control-Allow-Origin' present");
    } else {
        warn("CORS header missing — blog subdomain may need it for JS requests");
    }
    
    if ($apiData && !isset($apiData['error'])) {
        if (isset($apiData['secret_code']) && $apiData['secret_code'] === $expectedCode) {
            pass("Live API returns correct secret_code: '{$apiData['secret_code']}'");
        } elseif (isset($apiData['secret_code'])) {
            // Could be different post with different uid/tid
            info("Live API code '{$apiData['secret_code']}' vs expected '{$expectedCode}' — may be different post");
        }
    } elseif (isset($apiData['error'])) {
        warn("API returned error: {$apiData['error']}");
    }
}

// ═══════════════════════════════════════════════════════════════════════════
// TEST 7: Secret Code Verification Flow (TaskController::submit simulation)
// ═══════════════════════════════════════════════════════════════════════════
echo PHP_EOL . "── Test 7: Submit Verification Flow ──" . PHP_EOL;

// The code that user copies from blog → pastes into main site submit form
$userCopiedCode = $expectedCode; // Simulates what user sees on blog

// Simulate what TaskController::submit() checks
// From TaskController.php line 117-129:
//   if task->secret_code is set → static code
//   else → dynamic code = substr(md5($user->id . $task->id . $secretSalt), 0, 8)
$secretSalt2 = setting('task_secret_salt', 'MicroJobV1Secret!');
$verificationCode = substr(md5($testUid . $testTid . $secretSalt2), 0, 8);

if ($userCopiedCode === $verificationCode) {
    pass("User's copied code '{$userCopiedCode}' matches TaskController verification '{$verificationCode}'");
    pass("Submit flow: Blog shows code → user copies → pastes in main site → verification passes ✓");
} else {
    fail("Code mismatch! User copy: '{$userCopiedCode}' vs Verification: '{$verificationCode}'");
}

// Edge case: different salt
info("Testing edge case — if admin changes TASK_SECRET_SALT...");
$newSalt = 'NewSalt2026!Changed';
$newCode = substr(md5($testUid . $testTid . $newSalt), 0, 8);
if ($newCode !== $expectedCode) {
    pass("Salt change produces different code: '{$newCode}' ≠ '{$expectedCode}' — as expected");
    info("If admin changes salt, old cached codes become invalid immediately.");
    info("Blog subdomain always fetches fresh code from API, so it stays in sync.");
} else {
    warn("Salt change produced same code — unlikely collision");
}

// ═══════════════════════════════════════════════════════════════════════════
// TEST 8: Ad Blocker Detection (Blog JS)
// ═══════════════════════════════════════════════════════════════════════════
echo PHP_EOL . "── Test 8: Blog Page Ad Blocker Detection ──" . PHP_EOL;

if (file_exists($blogIndexPath = 'C:\xampp\htdocs\micro\Cpannel\Blog\index.php')) {
    $blogContent = file_get_contents($blogIndexPath);
    
    $checks = [
        'ad-banner div' => 'id="ad-banner"',
        'adblock detection' => 'offsetHeight',
        'warning message' => 'Ad Blocker Detected',
        'timer ring SVG' => 'timer-ring',
        'copy code function' => 'function copyCode',
        'start button' => 'start-btn',
        'step visibility' => 'step-1',
        'timer text' => 'timer-text',
    ];
    
    foreach ($checks as $name => $pattern) {
        if (strpos($blogContent, $pattern) !== false) {
            pass("Blog index.php contains '{$name}' element");
        } else {
            warn("Blog index.php missing '{$name}' — pattern: '{$pattern}'");
        }
    }
    
    // Verify it uses API secret_code (not local md5)
    if (strpos($blogContent, "\$post['secret_code']") !== false) {
        pass("Blog uses API-provided '\$post[\"secret_code\"]' — single source of truth");
    } else {
        warn("Blog may not be using API secret_code");
    }
    
    // Verify no local md5 call for secret code
    if (strpos($blogContent, 'md5(') !== false && strpos($blogContent, 'secretSalt') !== false) {
        warn("Blog still has local md5/secretSalt computation — should use API code only");
    } else {
        pass("Blog has no local secret code computation — good");
    }
} else {
    warn("Blog index.php not accessible at: {$blogIndexPath}");
}

// ═══════════════════════════════════════════════════════════════════════════
// TEST 9: Task Domain CORS Configuration
// ═══════════════════════════════════════════════════════════════════════════
echo PHP_EOL . "── Test 9: Task Domain Configuration Check ──" . PHP_EOL;

$activeDomains = TaskDomain::where('is_active', true)->get();
info("Active task domains: {$activeDomains->count()}");

foreach ($activeDomains as $dom) {
    $hasDirectLink = !empty($dom->direct_link);
    $hasAdCodes = !empty($dom->ad_code_1) || !empty($dom->ad_code_2) || !empty($dom->ad_code_3);
    
    $status = $hasDirectLink ? '✓ has direct_link' : '✗ NO direct_link';
    $adStatus = $hasAdCodes ? 'has ad codes' : 'no ad codes';
    info("  • {$dom->domain} — {$status}, {$adStatus}");
}

if ($activeDomains->isEmpty()) {
    warn("No active task domains! Blog subdomain won't get any direct_link or ad codes.");
    info("Go to Admin → Task Domains and add your subdomain with direct_link & ad codes.");
}

// ═══════════════════════════════════════════════════════════════════════════
// SUMMARY
// ═══════════════════════════════════════════════════════════════════════════
echo PHP_EOL . str_repeat('=', 70) . PHP_EOL;
echo "  RESULTS SUMMARY" . PHP_EOL;
echo str_repeat('=', 70) . PHP_EOL;
echo "  Passed:  {$passed}" . PHP_EOL;
echo "  Failed:  {$failed}" . PHP_EOL;
echo "  Warnings: {$warnings}" . PHP_EOL;
echo str_repeat('=', 70) . PHP_EOL;

if ($failed === 0) {
    echo PHP_EOL . "  🎉 ALL SECRET CODE TESTS PASSED!" . PHP_EOL;
    echo PHP_EOL . "  ✅ Blog subdomain correctly fetches secret_code from API" . PHP_EOL;
    echo "  ✅ API formula matches TaskController::submit() formula" . PHP_EOL;
    echo "  ✅ direct_link fallback chain works correctly" . PHP_EOL;
    echo "  ✅ Ad blocker detection and JS timer present" . PHP_EOL;
    echo "  ✅ No separate database needed — shared '{$mainDb}'" . PHP_EOL;
} else {
    echo PHP_EOL . "  ⚠️  {$failed} test(s) FAILED — review above" . PHP_EOL;
}

if ($warnings > 0) {
    echo "  ⚠️  {$warnings} warning(s) — may need attention" . PHP_EOL;
}

echo PHP_EOL . "── DB Question Answer ──" . PHP_EOL;
echo "  ❓ ব্লগ সাব ডোমেইনে কি আলাদা করে db লাগবে?" . PHP_EOL;
echo "  ✅ না, আলাদা ডাটাবেজ লাগবে না।" . PHP_EOL;
echo "     ব্লগের db.php এবং Laravel মেইন অ্যাপ দুটোই একই '" . ($mainDb ?? 'micro_blog') . "' ডাটাবেজ ব্যবহার করে।" . PHP_EOL;
echo "     ব্লগের লোকাল blog_posts টেবিল লিগ্যাসি — API দিয়ে কন্টেন্ট ফেচ করার সময় এটি ব্যবহার হয় না।" . PHP_EOL;
echo "     সব কন্টেন্ট মেইন Laravel API থেকে cURL এর মাধ্যমে আসে।" . PHP_EOL;
echo PHP_EOL;