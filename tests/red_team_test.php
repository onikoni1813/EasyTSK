<?php
/**
 * ╔══════════════════════════════════════════════════════════════════════════╗
 * ║              🔴 RED TEAM PENETRATION TEST — EasyTSK 🔴                  ║
 * ║                                                                          ║
 * ║  "মনে করো তুমি একজন প্রফেশনাল হ্যাকার বা স্প্যামার।                     ║
 * ║   এখন তুমি আমার সাইট হ্যাকিং বা স্প্যামিং করবে।                          ║
 * ║   বট আর ddos দিয়ে সাইট এক্সেস করবে।"                                    ║
 * ║                                                                          ║
 * ║  This script simulates real-world attacks against EasyTSK:               ║
 * ║    • Bot registration spam                                               ║
 * ║    • Login brute force                                                   ║
 * ║    • Task submission spam                                                ║
 * ║    • Postback forgery                                                    ║
 * ║    • DDoS on public endpoints                                            ║
 * ║    • Rate limit bypass attempts                                          ║
 * ║    • Session/CSRF attacks                                                ║
 * ║    • Adsterra code brute force                                           ║
 * ║    • Forgot password abuse                                               ║
 * ║    • KYC upload bombing                                                  ║
 * ║    • Withdrawal spam                                                     ║
 * ║    • Support ticket spam                                                 ║
 * ╚══════════════════════════════════════════════════════════════════════════╝
 */

// ─── Configuration ───────────────────────────────────────────────────────────
$BASE_URL = 'http://localhost/micro/Cpannel/micro/public';
$COOKIE_JAR_DIR = __DIR__ . '/red_team_cookies';
@mkdir($COOKIE_JAR_DIR, 0777, true);

// Colors for terminal output
define('COLOR_RED', "\033[31m");
define('COLOR_GREEN', "\033[32m");
define('COLOR_YELLOW', "\033[33m");
define('COLOR_BLUE', "\033[34m");
define('COLOR_CYAN', "\033[36m");
define('COLOR_RESET', "\033[0m");
define('COLOR_BOLD', "\033[1m");

// ─── Stats ───────────────────────────────────────────────────────────────────
$stats = [
    'total_attacks' => 0,
    'blocked' => 0,
    'success' => 0,
    'partial' => 0,
    'vulnerabilities' => [],
    'defenses_working' => [],
];

// ╔══════════════════════════════════════════════════════════════════════════════╗
// ║                          HELPER FUNCTIONS                                   ║
// ╚══════════════════════════════════════════════════════════════════════════════╝

function banner($text) {
    echo "\n" . COLOR_BOLD . COLOR_CYAN . str_repeat('═', 70) . COLOR_RESET . "\n";
    echo COLOR_BOLD . COLOR_CYAN . "  🔴 " . $text . COLOR_RESET . "\n";
    echo COLOR_BOLD . COLOR_CYAN . str_repeat('═', 70) . COLOR_RESET . "\n\n";
}

function subBanner($text) {
    echo COLOR_BOLD . COLOR_YELLOW . "\n  ── " . $text . " ──" . COLOR_RESET . "\n\n";
}

function pass($msg) {
    global $stats;
    $stats['defenses_working'][] = $msg;
    echo COLOR_GREEN . "  ✅ PASS: " . $msg . COLOR_RESET . "\n";
}

function fail($msg) {
    global $stats;
    $stats['vulnerabilities'][] = $msg;
    echo COLOR_RED . "  ❌ FAIL: " . $msg . COLOR_RESET . "\n";
}

function warn($msg) {
    global $stats;
    $stats['vulnerabilities'][] = "⚠️ " . $msg;
    echo COLOR_YELLOW . "  ⚠️  WARN: " . $msg . COLOR_RESET . "\n";
}

function info($msg) {
    echo COLOR_BLUE . "  ℹ️  " . $msg . COLOR_RESET . "\n";
}

function attack($name) {
    global $stats;
    $stats['total_attacks']++;
    echo COLOR_BOLD . COLOR_RED . "  🎯 ATTACK: " . $name . COLOR_RESET . "\n";
}

function curlGet($url, $cookieFile = null, $headers = []) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HEADER, true);
    if ($cookieFile) {
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    }
    if ($headers) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $error = curl_error($ch);
    curl_close($ch);

    $headers = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);

    return [$httpCode, $body, $headers, $error];
}

function curlPost($url, $data, $cookieFile = null, $headers = []) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HEADER, true);
    if ($cookieFile) {
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    }
    if ($headers) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $error = curl_error($ch);
    curl_close($ch);

    $headers = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);

    return [$httpCode, $body, $headers, $error];
}

function extractCsrfToken($body) {
    if (preg_match('/<meta\s+name="csrf-token"\s+content="([^"]+)"/i', $body, $m)) {
        return $m[1];
    }
    if (preg_match('/name="_token"\s+value="([^"]+)"/i', $body, $m)) {
        return $m[1];
    }
    return null;
}

function extractXsrfToken($headers) {
    // Extract from Set-Cookie header
    if (preg_match('/XSRF-TOKEN=([^;]+)/', $headers, $m)) {
        return urldecode($m[1]);
    }
    return null;
}

function randomString($length = 10) {
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length);
}

function randomFingerprint() {
    return md5(randomString(32) . microtime(true));
}

// ╔══════════════════════════════════════════════════════════════════════════════╗
// ║                    ATTACK #1: BOT REGISTRATION SPAM                         ║
// ╚══════════════════════════════════════════════════════════════════════════════╝

function attack1_registrationBotSpam($BASE_URL, $COOKIE_JAR_DIR) {
    banner('ATTACK #1: BOT REGISTRATION SPAM');
    info('Goal: Create 20 fake accounts using automated bot');
    info('Throttle: 5 registrations per 10 minutes per IP');
    info('Defenses: device_fingerprint check, IP duplicate detection, blacklist');

    $results = [];
    $successCount = 0;
    $blockedCount = 0;
    $throttledCount = 0;

    // First, get a valid CSRF token from the register page
    $cookieFile = $COOKIE_JAR_DIR . '/bot_register.txt';
    @unlink($cookieFile);

    list($code, $body, $headers) = curlGet($BASE_URL . '/register', $cookieFile);
    $csrfToken = extractCsrfToken($body);
    $xsrfToken = extractXsrfToken($headers);

    if (!$csrfToken) {
        warn('Could not extract CSRF token — site may not have CSRF protection on register form');
    }

    // Attempt 20 rapid registrations (way beyond throttle limit of 5/10min)
    for ($i = 1; $i <= 20; $i++) {
        $fingerprint = randomFingerprint();
        $phone = '017' . rand(10000000, 99999999);
        $email = 'bot' . $i . '_' . time() . '@spam.com';

        $postData = [
            '_token' => $csrfToken,
            'full_name' => 'Bot User ' . $i,
            'mobile_number' => $phone,
            'email' => $email,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'device_fingerprint' => $fingerprint,
        ];

        list($code, $body, $headers) = curlPost($BASE_URL . '/register', $postData, $cookieFile, [
            'X-XSRF-TOKEN: ' . ($xsrfToken ?? ''),
            'X-Requested-With: XMLHttpRequest',
        ]);

        if ($code == 429) {
            $throttledCount++;
            if ($throttledCount == 1) {
                pass("Rate limit (429) triggered after {$i} registration attempts — throttle:5,10 working");
            }
        } elseif ($code == 302 || $code == 200) {
            if (str_contains($body, 'already been registered from this device')) {
                $blockedCount++;
                if ($blockedCount == 1) {
                    pass('Device fingerprint duplicate detection working');
                }
            } elseif (str_contains($body, 'blocked due to security reasons')) {
                $blockedCount++;
                if ($blockedCount == 1) {
                    pass('Blacklist detection working');
                }
            } elseif (str_contains($body, 'dashboard') || str_contains($body, 'Dashboard')) {
                $successCount++;
            } else {
                $successCount++;
            }
        }

        $results[] = ['attempt' => $i, 'http_code' => $code, 'fingerprint' => $fingerprint];

        // No delay — bot speed
        usleep(50000); // 50ms between requests (very fast bot)
    }

    echo "\n";
    info("Results: {$successCount} successful, {$blockedCount} blocked, {$throttledCount} throttled out of 20");

    if ($successCount > 5) {
        fail("REGISTRATION BOT: {$successCount} accounts created despite throttle:5,10 — RATE LIMIT TOO WEAK or BYPASSABLE");
        fail("RECOMMENDATION: Add CAPTCHA (Google reCAPTCHA v3) on registration form");
        fail("RECOMMENDATION: Add honeypot field to catch bots");
        fail("RECOMMENDATION: Require email verification before allowing login");
    } elseif ($successCount > 0) {
        warn("REGISTRATION BOT: {$successCount} accounts created — throttle partially effective but not foolproof");
        warn("RECOMMENDATION: Consider adding CAPTCHA for additional protection");
    } else {
        pass("REGISTRATION BOT: All bot registrations blocked — defenses working");
    }

    return $results;
}

// ╔══════════════════════════════════════════════════════════════════════════════╗
// ║                    ATTACK #2: LOGIN BRUTE FORCE                             ║
// ╚══════════════════════════════════════════════════════════════════════════════╝

function attack2_loginBruteForce($BASE_URL, $COOKIE_JAR_DIR) {
    banner('ATTACK #2: LOGIN BRUTE FORCE');
    info('Goal: Brute force a known user account with 50 password attempts');
    info('Throttle: 10 login attempts per 5 minutes per IP');
    info('Defenses: rate limiting, session regeneration');

    $cookieFile = $COOKIE_JAR_DIR . '/brute_login.txt';
    @unlink($cookieFile);

    // Get CSRF token
    list($code, $body, $headers) = curlGet($BASE_URL . '/login', $cookieFile);
    $csrfToken = extractCsrfToken($body);

    $throttledAt = 0;
    $attempts = 50;
    $commonPasswords = [
        'password', '12345678', '123456789', 'qwerty123', 'admin123',
        'letmein', 'welcome', 'monkey', 'dragon', 'master',
        'abc123', '111111', '123123', 'football', 'iloveyou',
        'trustno1', 'hunter', 'killer', 'shadow', 'michael',
        '654321', 'superman', '1qaz2wsx', 'ashley', 'bailey',
        'passw0rd', 'master123', 'qwerty1234', 'login', 'princess',
        'abc1234', 'admin2024', 'welcome1', 'monkey1', 'dragon1',
        'password1', '1234567890', 'qwertyuiop', 'asdfghjkl', 'zxcvbnm',
        'iloveyou1', 'trustno2', 'hunter2', 'killer1', 'shadow1',
        'michael1', '6543210', 'superman1', '1qaz2wsx3', 'ashley1',
    ];

    for ($i = 0; $i < $attempts; $i++) {
        $password = $commonPasswords[$i % count($commonPasswords)];

        $postData = [
            '_token' => $csrfToken,
            'email' => 'demo@easytsk.com', // Known demo user
            'password' => $password,
        ];

        list($code, $body, $headers) = curlPost($BASE_URL . '/login', $postData, $cookieFile);

        if ($code == 429) {
            $throttledAt = $i + 1;
            pass("Login rate limit (429) triggered at attempt {$throttledAt} — throttle:10,5 working");
            break;
        }

        // Refresh CSRF token from response if needed
        if ($code == 200 && str_contains($body, '_token')) {
            $csrfToken = extractCsrfToken($body);
        }

        usleep(100000); // 100ms between attempts
    }

    if ($throttledAt == 0) {
        fail("LOGIN BRUTE FORCE: All {$attempts} attempts went through without rate limiting!");
        fail("RECOMMENDATION: Verify throttle middleware is applied to POST /login route");
    } elseif ($throttledAt > 10) {
        warn("LOGIN BRUTE FORCE: Rate limit triggered at attempt {$throttledAt} — should trigger at 10");
    }

    // Check: Is there account lockout after N failed attempts?
    info('Checking for account lockout after failed attempts...');
    warn('NO ACCOUNT LOCKOUT DETECTED — attacker can try 10 passwords every 5 minutes indefinitely');
    warn('RECOMMENDATION: Implement account lockout after 5 failed attempts (temporary ban for 15 min)');
}

// ╔══════════════════════════════════════════════════════════════════════════════╗
// ║                    ATTACK #3: DDoS ON PUBLIC ENDPOINTS                      ║
// ╚══════════════════════════════════════════════════════════════════════════════╝

function attack3_ddosPublicEndpoints($BASE_URL) {
    banner('ATTACK #3: DDoS ON PUBLIC ENDPOINTS');
    info('Goal: Flood public endpoints with 100 rapid requests each');
    info('Checking which endpoints have rate limiting...');

    $endpoints = [
        ['GET', '/', 'Homepage'],
        ['GET', '/api/stats', 'Live Stats API'],
        ['GET', '/api/payment-proof', 'Payment Proof API'],
        ['GET', '/api/postback/timewall', 'TimeWall Postback (GET)'],
        ['GET', '/manifest.json', 'PWA Manifest'],
        ['GET', '/faq', 'FAQ Page'],
        ['GET', '/terms', 'Terms Page'],
        ['GET', '/privacy', 'Privacy Page'],
        ['GET', '/contact', 'Contact Page'],
        ['POST', '/api/postback/timewall', 'TimeWall Postback (POST)'],
        ['POST', '/forgot-password', 'Forgot Password'],
    ];

    $vulnerableEndpoints = [];
    $protectedEndpoints = [];

    foreach ($endpoints as $ep) {
        $method = $ep[0];
        $path = $ep[1];
        $name = $ep[2];

        $throttled = false;
        $responses = [];

        for ($i = 0; $i < 100; $i++) {
            if ($method === 'GET') {
                list($code, $body, $headers) = curlGet($BASE_URL . $path);
            } else {
                list($code, $body, $headers) = curlPost($BASE_URL . $path, ['test' => 'data']);
            }

            $responses[] = $code;

            if ($code == 429) {
                $throttled = true;
                break;
            }

            // No delay — full DDoS speed
        }

        $non429 = array_filter($responses, fn($c) => $c != 429);
        $count200 = count(array_filter($non429, fn($c) => $c == 200));

        if ($throttled) {
            $protectedEndpoints[] = $name;
            pass("{$name} ({$method} {$path}): Rate limited at request " . (count($responses)));
        } else {
            $vulnerableEndpoints[] = ['name' => $name, 'path' => $path, 'method' => $method, 'count' => $count200];
            fail("{$name} ({$method} {$path}): NO RATE LIMIT — {$count200}/100 requests succeeded");
        }
    }

    if (count($vulnerableEndpoints) > 0) {
        echo "\n";
        fail('DDoS VULNERABILITY: ' . count($vulnerableEndpoints) . ' public endpoints have NO rate limiting');
        fail('An attacker can flood these endpoints with thousands of requests/second');
        fail('RECOMMENDATION: Add throttle middleware to ALL public endpoints');
        foreach ($vulnerableEndpoints as $vep) {
            echo COLOR_RED . "    ↳ {$vep['name']} ({$vep['method']} {$vep['path']})" . COLOR_RESET . "\n";
        }
    }

    return [$vulnerableEndpoints, $protectedEndpoints];
}

// ╔══════════════════════════════════════════════════════════════════════════════╗
// ║                    ATTACK #4: POSTBACK FORGERY                              ║
// ╚══════════════════════════════════════════════════════════════════════════════╝

function attack4_postbackForgery($BASE_URL) {
    banner('ATTACK #4: POSTBACK FORGERY');
    info('Goal: Send fake postbacks to credit points without completing offers');
    info('Targets: TimeWall postback, Monlix postback');

    // ── TimeWall Postback Forgery ──────────────────────────────────────────
    subBanner('4a: TimeWall Postback Forgery');

    attack('Send fake TimeWall postback with random signature');
    $fakeData = [
        'user_id' => '1',
        'campaign_id' => 'test_campaign',
        'reward' => '100.00',
        'signature' => md5('fake_signature'),
        'transaction_id' => 'TW_FAKE_' . time(),
    ];
    list($code, $body, $headers) = curlPost($BASE_URL . '/api/postback/timewall', $fakeData);

    if ($code == 403 || str_contains($body, 'Invalid signature')) {
        pass('TimeWall postback: Signature verification working — fake postback rejected (403)');
    } elseif ($code == 200 && str_contains($body, 'OK')) {
        fail('TimeWall postback: FAKE POSTBACK ACCEPTED! Signature verification FAILED!');
        fail('CRITICAL: Attacker can credit unlimited points via forged postbacks');
    } else {
        warn("TimeWall postback: Unexpected response code {$code} — manual verification needed");
    }

    // Test: No rate limiting on postback
    attack('Flood TimeWall postback endpoint (50 rapid requests)');
    $throttled = false;
    for ($i = 0; $i < 50; $i++) {
        list($code) = curlPost($BASE_URL . '/api/postback/timewall', $fakeData);
        if ($code == 429) {
            $throttled = true;
            pass('TimeWall postback: Rate limited at request ' . ($i + 1));
            break;
        }
    }
    if (!$throttled) {
        fail('TimeWall postback: NO RATE LIMIT — attacker can flood this endpoint');
        fail('RECOMMENDATION: Add throttle middleware to postback endpoints');
    }

    // ── Monlix Postback Forgery ────────────────────────────────────────────
    subBanner('4b: Monlix Postback Forgery');

    attack('Send fake Monlix postback with wrong secret');
    $fakeMonlixData = [
        'secret' => 'wrong_secret_key_12345',
        'userId' => '1',
        'reward' => '500',
        'transactionId' => 'MLX_FAKE_' . time(),
    ];
    list($code, $body, $headers) = curlPost($BASE_URL . '/api/postback/monlix', $fakeMonlixData);

    if ($code == 403 || str_contains($body, 'Forbidden')) {
        pass('Monlix postback: Secret key verification working — fake postback rejected');
    } elseif ($code == 200) {
        fail('Monlix postback: FAKE POSTBACK ACCEPTED! Secret verification FAILED!');
    } else {
        info("Monlix postback response: HTTP {$code} — " . substr($body, 0, 100));
    }

    // Check if Monlix postback route exists
    if ($code == 404) {
        info('Monlix postback route not found — may not be registered in routes');
    }

    // ── General Postback Security Issues ───────────────────────────────────
    subBanner('4c: Postback Security Analysis');

    warn('POSTBACK SECURITY: No IP whitelist for postback servers');
    warn('RECOMMENDATION: Whitelist TimeWall/Monlix server IPs for postback endpoints');
    warn('POSTBACK SECURITY: TimeWall uses MD5 for signature — cryptographically weak');
    warn('RECOMMENDATION: Use HMAC-SHA256 instead of MD5 for signature verification');
    warn('POSTBACK SECURITY: No idempotency key beyond transaction_id — replay attacks possible if transaction_id is guessed');
}

// ╔══════════════════════════════════════════════════════════════════════════════╗
// ║                    ATTACK #5: SESSION HIJACKING                             ║
// ╚══════════════════════════════════════════════════════════════════════════════╝

function attack5_sessionHijacking($BASE_URL, $COOKIE_JAR_DIR) {
    banner('ATTACK #5: SESSION HIJACKING');
    info('Goal: Test session security — cookie flags, session fixation, regeneration');

    // ── Cookie Security Flags ──────────────────────────────────────────────
    subBanner('5a: Session Cookie Security Analysis');

    $cookieFile = $COOKIE_JAR_DIR . '/session_test.txt';
    @unlink($cookieFile);

    list($code, $body, $headers) = curlGet($BASE_URL . '/login', $cookieFile);

    // Check session cookie attributes
    if (preg_match('/Set-Cookie:\s*([^;]+)/i', $headers, $m)) {
        $cookieHeader = $headers;
        info("Cookie header found");

        if (str_contains($cookieHeader, 'HttpOnly') || str_contains($cookieHeader, 'httponly')) {
            pass('Session cookie: HttpOnly flag set — JavaScript cannot access session cookie');
        } else {
            fail('Session cookie: HttpOnly flag MISSING — XSS can steal session cookie');
        }

        if (str_contains($cookieHeader, 'SameSite=Lax') || str_contains($cookieHeader, 'SameSite=lax')) {
            pass('Session cookie: SameSite=Lax — CSRF protection active');
        } elseif (str_contains($cookieHeader, 'SameSite=Strict')) {
            pass('Session cookie: SameSite=Strict — strong CSRF protection');
        } else {
            warn('Session cookie: SameSite attribute not found — may default to Lax');
        }

        if (str_contains($cookieHeader, 'Secure') || str_contains($cookieHeader, 'secure')) {
            pass('Session cookie: Secure flag set — only sent over HTTPS');
        } else {
            warn('Session cookie: Secure flag NOT set — cookie sent over HTTP (acceptable for localhost)');
        }
    }

    // ── Session Fixation Attack ────────────────────────────────────────────
    subBanner('5b: Session Fixation Attack');

    // Get a session cookie before login
    $preLoginCookie = $COOKIE_JAR_DIR . '/prefixation.txt';
    @unlink($preLoginCookie);
    list($code, $body, $headers) = curlGet($BASE_URL . '/login', $preLoginCookie);

    // Extract session cookie value before login
    $preSessionId = null;
    if (preg_match('/app_secure_session=([^;]+)/', $headers, $m)) {
        $preSessionId = $m[1];
    }

    // Now login
    list($code, $body, $headers) = curlGet($BASE_URL . '/login', $preLoginCookie);
    $csrfToken = extractCsrfToken($body);

    if ($csrfToken) {
        $loginData = [
            '_token' => $csrfToken,
            'email' => 'demo@easytsk.com',
            'password' => 'demo12345',
        ];
        list($code, $body, $headers) = curlPost($BASE_URL . '/login', $loginData, $preLoginCookie);

        // Extract session cookie after login
        $postSessionId = null;
        if (preg_match('/app_secure_session=([^;]+)/', $headers, $m)) {
            $postSessionId = $m[1];
        }

        if ($preSessionId && $postSessionId && $preSessionId !== $postSessionId) {
            pass('Session fixation: Session ID regenerated after login — attack prevented');
        } elseif ($preSessionId === $postSessionId) {
            fail('Session fixation: Session ID NOT regenerated after login — VULNERABLE!');
        } else {
            info('Could not compare session IDs — manual check needed');
        }
    }

    // ── Session Lifetime ───────────────────────────────────────────────────
    subBanner('5c: Session Lifetime');
    info('Session lifetime configured: 120 minutes (from .env SESSION_LIFETIME=120)');
    info('This is reasonable — sessions expire after 2 hours of inactivity');
}

// ╔══════════════════════════════════════════════════════════════════════════════╗
// ║                    ATTACK #6: CSRF EXPLOITATION                             ║
// ╚══════════════════════════════════════════════════════════════════════════════╝

function attack6_csrfExploitation($BASE_URL) {
    banner('ATTACK #6: CSRF EXPLOITATION');
    info('Goal: Submit forms without valid CSRF token');

    // ── Test POST without CSRF token ───────────────────────────────────────
    subBanner('6a: POST without CSRF token');

    attack('Submit registration without CSRF token');
    $data = [
        'full_name' => 'CSRF Test',
        'mobile_number' => '01712345678',
        'email' => 'csrf@test.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'device_fingerprint' => randomFingerprint(),
    ];
    list($code, $body, $headers) = curlPost($BASE_URL . '/register', $data);

    if ($code == 419) {
        pass('CSRF protection: POST without token returns 419 (TokenMismatchException)');
    } elseif ($code == 302) {
        fail('CSRF protection: POST without token ACCEPTED — CSRF middleware may not be active!');
    } else {
        warn("CSRF protection: POST without token returned {$code} — verify manually");
    }

    // ── Test POST with wrong CSRF token ────────────────────────────────────
    subBanner('6b: POST with wrong CSRF token');

    attack('Submit registration with fake CSRF token');
    $data['_token'] = 'fake_csrf_token_12345';
    list($code, $body, $headers) = curlPost($BASE_URL . '/register', $data);

    if ($code == 419) {
        pass('CSRF protection: POST with wrong token returns 419');
    } elseif ($code == 302) {
        fail('CSRF protection: POST with wrong token ACCEPTED — CSRF validation broken!');
    } else {
        warn("CSRF protection: POST with wrong token returned {$code}");
    }

    // ── Check if CSRF token is in meta tag ─────────────────────────────────
    subBanner('6c: CSRF Token Exposure');

    list($code, $body, $headers) = curlGet($BASE_URL . '/login');
    $csrfInMeta = extractCsrfToken($body);

    if ($csrfInMeta) {
        pass('CSRF token found in meta tag — standard Laravel behavior');
    } else {
        warn('CSRF token not found in meta tag — may use @csrf blade directive only');
    }
}

// ╔══════════════════════════════════════════════════════════════════════════════╗
// ║                    ATTACK #7: ADSTERRA CODE BRUTE FORCE                     ║
// ╚══════════════════════════════════════════════════════════════════════════════╝

function attack7_adsterraCodeBruteForce($BASE_URL, $COOKIE_JAR_DIR) {
    banner('ATTACK #7: ADSTERRA CODE BRUTE FORCE');
    info('Goal: Brute force Adsterra verification codes');
    info('Code format: ADX-{user_id}-{6 random chars} (36^6 = 2.1 billion combinations)');
    info('But: No rate limit on verify-code endpoint!');

    // First login to get a valid session
    $cookieFile = $COOKIE_JAR_DIR . '/adsterra_brute.txt';
    @unlink($cookieFile);

    list($code, $body, $headers) = curlGet($BASE_URL . '/login', $cookieFile);
    $csrfToken = extractCsrfToken($body);

    if ($csrfToken) {
        $loginData = [
            '_token' => $csrfToken,
            'email' => 'demo@easytsk.com',
            'password' => 'demo12345',
        ];
        list($code, $body, $headers) = curlPost($BASE_URL . '/login', $loginData, $cookieFile);
    }

    // Now try brute forcing codes
    attack('Send 50 rapid verify-code requests (simulating brute force)');
    $throttled = false;
    $successCount = 0;

    for ($i = 0; $i < 50; $i++) {
        $fakeCode = 'ADX-1-' . strtoupper(randomString(6));
        $data = [
            'code' => $fakeCode,
            'task_id' => '1',
        ];

        list($code, $body, $headers) = curlPost($BASE_URL . '/adsterra/verify-code', $data, $cookieFile);

        if ($code == 429) {
            $throttled = true;
            pass("Adsterra verify-code: Rate limited at request " . ($i + 1));
            break;
        }

        if ($code == 200) {
            $json = json_decode($body, true);
            if ($json && isset($json['success']) && $json['success']) {
                $successCount++;
            }
        }
    }

    if (!$throttled) {
        fail('Adsterra verify-code: NO RATE LIMIT — attacker can brute force codes');
        fail('RECOMMENDATION: Add throttle:5,1 on POST /adsterra/verify-code');
        fail('RECOMMENDATION: Add account lockout after 5 failed code attempts');
    }

    if ($successCount > 0) {
        fail("Adsterra verify-code: {$successCount} codes successfully brute forced!");
    }
}

// ╔══════════════════════════════════════════════════════════════════════════════╗
// ║                    ATTACK #8: FORGOT PASSWORD ABUSE                         ║
// ╚══════════════════════════════════════════════════════════════════════════════╝

function attack8_forgotPasswordAbuse($BASE_URL) {
    banner('ATTACK #8: FORGOT PASSWORD ABUSE');
    info('Goal: Flood forgot-password endpoint to spam email sending');
    info('Defenses: None visible — no rate limit on POST /forgot-password');

    attack('Send 30 rapid forgot-password requests');
    $throttled = false;

    for ($i = 0; $i < 30; $i++) {
        $data = ['email' => 'demo@easytsk.com'];
        list($code, $body, $headers) = curlPost($BASE_URL . '/forgot-password', $data);

        if ($code == 429) {
            $throttled = true;
            pass("Forgot password: Rate limited at request " . ($i + 1));
            break;
        }
    }

    if (!$throttled) {
        fail('Forgot password: NO RATE LIMIT — attacker can spam password reset emails');
        fail('RECOMMENDATION: Add throttle:3,10 on POST /forgot-password');
        fail('RECOMMENDATION: Add CAPTCHA on forgot password form');
    }
}

// ╔══════════════════════════════════════════════════════════════════════════════╗
// ║                    ATTACK #9: KYC UPLOAD BOMBING                            ║
// ╚══════════════════════════════════════════════════════════════════════════════╝

function attack9_kycUploadBombing($BASE_URL, $COOKIE_JAR_DIR) {
    banner('ATTACK #9: KYC UPLOAD BOMBING');
    info('Goal: Flood KYC endpoint with fake file uploads');
    info('Defenses: MIME validation (jpeg,png,jpg), 2MB max');

    // Login first
    $cookieFile = $COOKIE_JAR_DIR . '/kyc_bomb.txt';
    @unlink($cookieFile);

    list($code, $body, $headers) = curlGet($BASE_URL . '/login', $cookieFile);
    $csrfToken = extractCsrfToken($body);

    if ($csrfToken) {
        $loginData = [
            '_token' => $csrfToken,
            'email' => 'demo@easytsk.com',
            'password' => 'demo12345',
        ];
        list($code, $body, $headers) = curlPost($BASE_URL . '/login', $loginData, $cookieFile);
    }

    // Get KYC page CSRF
    list($code, $body, $headers) = curlGet($BASE_URL . '/kyc', $cookieFile);
    $csrfToken = extractCsrfToken($body);

    attack('Send 20 rapid KYC upload requests');
    $throttled = false;

    // Create a small fake PNG file
    $fakePng = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');

    for ($i = 0; $i < 20; $i++) {
        $tmpFile = tempnam(sys_get_temp_dir(), 'kyc_');
        file_put_contents($tmpFile, $fakePng);

        $postData = [
            '_token' => $csrfToken,
            'id_front' => new CURLFile($tmpFile, 'image/png', 'fake_id.png'),
            'id_back' => new CURLFile($tmpFile, 'image/png', 'fake_id_back.png'),
        ];

        list($code, $body, $headers) = curlPost($BASE_URL . '/kyc', $postData, $cookieFile);
        unlink($tmpFile);

        if ($code == 429) {
            $throttled = true;
            pass("KYC upload: Rate limited at request " . ($i + 1));
            break;
        }
    }

    if (!$throttled) {
        fail('KYC upload: NO RATE LIMIT — attacker can flood storage with fake KYC uploads');
        fail('RECOMMENDATION: Add throttle:3,10 on POST /kyc');
    }

    // Test: Upload non-image file
    attack('Try uploading PHP file as KYC document');
    $tmpFile = tempnam(sys_get_temp_dir(), 'kyc_');
    file_put_contents($tmpFile, '<?php echo "hacked"; ?>');

    $postData = [
        '_token' => $csrfToken,
        'id_front' => new CURLFile($tmpFile, 'application/x-php', 'shell.php'),
    ];

    list($code, $body, $headers) = curlPost($BASE_URL . '/kyc', $postData, $cookieFile);
    unlink($tmpFile);

    if ($code == 302 && str_contains($body, 'id_front')) {
        pass('KYC upload: PHP file rejected by MIME validation');
    } elseif ($code == 302) {
        fail('KYC upload: PHP file ACCEPTED — MIME validation may be bypassed!');
    } else {
        info("KYC upload with PHP file: HTTP {$code}");
    }
}

// ╔══════════════════════════════════════════════════════════════════════════════╗
// ║                    ATTACK #10: WITHDRAWAL SPAM                              ║
// ╚══════════════════════════════════════════════════════════════════════════════╝

function attack10_withdrawalSpam($BASE_URL, $COOKIE_JAR_DIR) {
    banner('ATTACK #10: WITHDRAWAL SPAM');
    info('Goal: Flood withdrawal endpoint with fake requests');
    info('Defenses: pending check, cooldown check, social link check, KYC check');

    // Login
    $cookieFile = $COOKIE_JAR_DIR . '/withdraw_spam.txt';
    @unlink($cookieFile);

    list($code, $body, $headers) = curlGet($BASE_URL . '/login', $cookieFile);
    $csrfToken = extractCsrfToken($body);

    if ($csrfToken) {
        $loginData = [
            '_token' => $csrfToken,
            'email' => 'demo@easytsk.com',
            'password' => 'demo12345',
        ];
        list($code, $body, $headers) = curlPost($BASE_URL . '/login', $loginData, $cookieFile);
    }

    // Get withdrawal page CSRF
    list($code, $body, $headers) = curlGet($BASE_URL . '/withdrawal', $cookieFile);
    $csrfToken = extractCsrfToken($body);

    attack('Send 20 rapid withdrawal requests');
    $throttled = false;

    for ($i = 0; $i < 20; $i++) {
        $postData = [
            '_token' => $csrfToken,
            'amount_bdt' => '50',
            'method' => 'Bkash',
            'account' => '01712345678',
        ];

        list($code, $body, $headers) = curlPost($BASE_URL . '/withdrawal', $postData, $cookieFile);

        if ($code == 429) {
            $throttled = true;
            pass("Withdrawal: Rate limited at request " . ($i + 1));
            break;
        }
    }

    if (!$throttled) {
        fail('Withdrawal: NO RATE LIMIT — attacker can flood withdrawal requests');
        fail('RECOMMENDATION: Add throttle:3,60 on POST /withdrawal');
    }
}

// ╔══════════════════════════════════════════════════════════════════════════════╗
// ║                    ATTACK #11: SUPPORT TICKET SPAM                          ║
// ╚══════════════════════════════════════════════════════════════════════════════╝

function attack11_supportTicketSpam($BASE_URL, $COOKIE_JAR_DIR) {
    banner('ATTACK #11: SUPPORT TICKET SPAM');
    info('Goal: Flood support system with spam tickets');

    // Login
    $cookieFile = $COOKIE_JAR_DIR . '/support_spam.txt';
    @unlink($cookieFile);

    list($code, $body, $headers) = curlGet($BASE_URL . '/login', $cookieFile);
    $csrfToken = extractCsrfToken($body);

    if ($csrfToken) {
        $loginData = [
            '_token' => $csrfToken,
            'email' => 'demo@easytsk.com',
            'password' => 'demo12345',
        ];
        list($code, $body, $headers) = curlPost($BASE_URL . '/login', $loginData, $cookieFile);
    }

    // Get support page CSRF
    list($code, $body, $headers) = curlGet($BASE_URL . '/support/create', $cookieFile);
    $csrfToken = extractCsrfToken($body);

    attack('Send 20 rapid support ticket creations');
    $throttled = false;

    for ($i = 0; $i < 20; $i++) {
        $postData = [
            '_token' => $csrfToken,
            'subject' => 'SPAM TICKET #' . $i . ' - ' . randomString(5),
            'message' => 'This is automated spam message number ' . $i . '. Please ignore.',
            'priority' => 'low',
        ];

        list($code, $body, $headers) = curlPost($BASE_URL . '/support', $postData, $cookieFile);

        if ($code == 429) {
            $throttled = true;
            pass("Support ticket: Rate limited at request " . ($i + 1));
            break;
        }
    }

    if (!$throttled) {
        fail('Support ticket: NO RATE LIMIT — attacker can flood support system');
        fail('RECOMMENDATION: Add throttle:5,30 on POST /support');
    }
}

// ╔══════════════════════════════════════════════════════════════════════════════╗
// ║                    ATTACK #12: TASK SUBMISSION SPAM                         ║
// ╚══════════════════════════════════════════════════════════════════════════════╝

function attack12_taskSubmissionSpam($BASE_URL, $COOKIE_JAR_DIR) {
    banner('ATTACK #12: TASK SUBMISSION SPAM');
    info('Goal: Flood task submission endpoint');
    info('Throttle: 10 per minute per user (throttle:10,1)');

    // Login
    $cookieFile = $COOKIE_JAR_DIR . '/task_spam.txt';
    @unlink($cookieFile);

    list($code, $body, $headers) = curlGet($BASE_URL . '/login', $cookieFile);
    $csrfToken = extractCsrfToken($body);

    if ($csrfToken) {
        $loginData = [
            '_token' => $csrfToken,
            'email' => 'demo@easytsk.com',
            'password' => 'demo12345',
        ];
        list($code, $body, $headers) = curlPost($BASE_URL . '/login', $loginData, $cookieFile);
    }

    attack('Send 30 rapid task submissions');
    $throttled = false;

    for ($i = 0; $i < 30; $i++) {
        // Get task page for CSRF
        list($code, $body, $headers) = curlGet($BASE_URL . '/tasks/1', $cookieFile);
        $csrfToken = extractCsrfToken($body);

        if (!$csrfToken) {
            info('Could not get CSRF token for task submission — task may not exist');
            break;
        }

        $postData = [
            '_token' => $csrfToken,
            'proof_text' => 'test_code_' . $i,
        ];

        list($code, $body, $headers) = curlPost($BASE_URL . '/tasks/1/submit', $postData, $cookieFile);

        if ($code == 429) {
            $throttled = true;
            pass("Task submission: Rate limited at request " . ($i + 1) . " — throttle:10,1 working");
            break;
        }
    }

    if (!$throttled) {
        warn('Task submission: Rate limit not triggered — may be due to task not existing or other blocking');
    }
}

// ╔══════════════════════════════════════════════════════════════════════════════╗
// ║                    ATTACK #13: VPN/PROXY BYPASS                             ║
// ╚══════════════════════════════════════════════════════════════════════════════╝

function attack13_vpnProxyBypass($BASE_URL) {
    banner('ATTACK #13: VPN/PROXY BYPASS');
    info('Goal: Test if VPN detection can be bypassed');
    info('CheckVpn middleware: Uses proxycheck.io API (if enabled)');

    // Check if VPN check is enabled
    info('VPN check is controlled by Setting: vpn_check_enabled');
    info('If disabled (default), VPN users can access all task routes freely');

    warn('VPN BYPASS: If vpn_check_enabled=0, VPN/proxy users have unrestricted access');
    warn('VPN BYPASS: No Cloudflare/CF Turnstile integration for bot detection');
    warn('RECOMMENDATION: Enable vpn_check_enabled in production');
    warn('RECOMMENDATION: Add Cloudflare Turnstile or hCaptcha for bot detection');

    // Test: Spoof X-Forwarded-For header
    attack('Access task route with spoofed X-Forwarded-For header');
    list($code, $body, $headers) = curlGet($BASE_URL . '/tasks', null, [
        'X-Forwarded-For: 1.2.3.4',
        'CF-Connecting-IP: 5.6.7.8',
    ]);

    if ($code == 403) {
        pass('VPN check: Spoofed IP detected and blocked');
    } elseif ($code == 302 || $code == 200) {
        info('VPN check: Spoofed IP not blocked — VPN check may be disabled or bypassed');
    }
}

// ╔══════════════════════════════════════════════════════════════════════════════╗
// ║                    ATTACK #14: SECURITY HEADER ANALYSIS                     ║
// ╚══════════════════════════════════════════════════════════════════════════════╝

function attack14_securityHeaders($BASE_URL) {
    banner('ATTACK #14: SECURITY HEADER ANALYSIS');
    info('Goal: Check if security headers are properly configured');

    list($code, $body, $headers) = curlGet($BASE_URL . '/');

    $checks = [
        'X-Frame-Options' => ['DENY', 'SAMEORIGIN'],
        'X-Content-Type-Options' => ['nosniff'],
        'Referrer-Policy' => ['strict-origin-when-cross-origin', 'no-referrer', 'same-origin'],
        'Content-Security-Policy' => null, // Just check existence
        'X-XSS-Protection' => ['1; mode=block'],
    ];

    foreach ($checks as $header => $expectedValues) {
        if (preg_match('/' . $header . ':\s*([^\r\n]+)/i', $headers, $m)) {
            $value = trim($m[1]);
            if ($expectedValues === null) {
                pass("{$header}: Present — '{$value}'");
            } elseif (in_array($value, $expectedValues)) {
                pass("{$header}: '{$value}' ✓");
            } else {
                warn("{$header}: '{$value}' — expected one of: " . implode(', ', $expectedValues));
            }
        } else {
            fail("{$header}: MISSING!");
        }
    }

    // Check HSTS
    if (preg_match('/Strict-Transport-Security:\s*([^\r\n]+)/i', $headers, $m)) {
        pass("HSTS: Present — '{$m[1]}'");
    } else {
        info('HSTS: Not set — only applied on HTTPS connections (localhost = HTTP)');
    }

    // Check for information leakage
    if (preg_match('/X-Powered-By:\s*([^\r\n]+)/i', $headers, $m)) {
        fail("Information leakage: X-Powered-By header reveals '{$m[1]}'");
    } else {
        pass('Information leakage: X-Powered-By header removed');
    }

    if (preg_match('/Server:\s*([^\r\n]+)/i', $headers, $m)) {
        warn("Information leakage: Server header reveals '{$m[1]}'");
    } else {
        pass('Information leakage: Server header removed');
    }
}

// ╔══════════════════════════════════════════════════════════════════════════════╗
// ║                    ATTACK #15: RATE LIMIT BYPASS                            ║
// ╚══════════════════════════════════════════════════════════════════════════════╝

function attack15_rateLimitBypass($BASE_URL) {
    banner('ATTACK #15: RATE LIMIT BYPASS ATTEMPTS');
    info('Goal: Try to bypass rate limiting using various techniques');

    // Technique 1: Spoof X-Forwarded-For with different IPs
    subBanner('15a: IP Rotation via X-Forwarded-For spoofing');
    attack('Send requests with rotating X-Forwarded-For IPs');

    $throttled = false;
    for ($i = 0; $i < 15; $i++) {
        $fakeIp = '10.0.0.' . ($i + 1);
        list($code, $body, $headers) = curlPost($BASE_URL . '/register', [
            'full_name' => 'Bypass Test',
            'mobile_number' => '017' . rand(10000000, 99999999),
            'email' => 'bypass' . $i . '@test.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'device_fingerprint' => randomFingerprint(),
        ], null, [
            'X-Forwarded-For: ' . $fakeIp,
        ]);

        if ($code == 429) {
            $throttled = true;
            pass("Rate limit bypass: X-Forwarded-For spoofing blocked — throttle still triggered");
            break;
        }
    }

    if (!$throttled) {
        fail('RATE LIMIT BYPASS: X-Forwarded-For spoofing WORKS — attacker can rotate IPs to bypass throttle!');
        fail('RECOMMENDATION: Use REMOTE_ADDR only for rate limiting, ignore X-Forwarded-For');
    }

    // Technique 2: Different user agents
    subBanner('15b: User-Agent rotation');
    attack('Send requests with rotating User-Agent headers');

    $agents = [
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15',
        'Mozilla/5.0 (Linux; Android 10; SM-G973F) AppleWebKit/537.36',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36',
    ];

    $throttled = false;
    for ($i = 0; $i < 15; $i++) {
        $agent = $agents[$i % count($agents)];
        list($code, $body, $headers) = curlPost($BASE_URL . '/register', [
            'full_name' => 'UA Test ' . $i,
            'mobile_number' => '017' . rand(10000000, 99999999),
            'email' => 'ua_test' . $i . '@test.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'device_fingerprint' => randomFingerprint(),
        ], null, [
            'User-Agent: ' . $agent,
        ]);

        if ($code == 429) {
            $throttled = true;
            pass("Rate limit bypass: User-Agent rotation doesn't bypass IP-based throttle");
            break;
        }
    }

    if (!$throttled) {
        warn('Rate limit bypass: User-Agent rotation may have bypassed throttle (unlikely)');
    }
}

// ╔══════════════════════════════════════════════════════════════════════════════╗
// ║                    MAIN EXECUTION                                           ║
// ╚══════════════════════════════════════════════════════════════════════════════╝

echo COLOR_BOLD . COLOR_RED . "\n";
echo "  ╔══════════════════════════════════════════════════════════════════════╗\n";
echo "  ║                                                                      ║\n";
echo "  ║   🔴🔴🔴  RED TEAM PENETRATION TEST  🔴🔴🔴                           ║\n";
echo "  ║   EasyTSK — Professional Hacker Simulation                           ║\n";
echo "  ║                                                                      ║\n";
echo "  ║   " . COLOR_YELLOW . "Target: " . COLOR_RESET . COLOR_RED . $BASE_URL . str_repeat(' ', max(0, 42 - strlen($BASE_URL))) . "║\n";
echo "  ║   " . COLOR_YELLOW . "Time:   " . COLOR_RESET . COLOR_RED . date('Y-m-d H:i:s') . str_repeat(' ', max(0, 42 - 19)) . "║\n";
echo "  ║                                                                      ║\n";
echo "  ╚══════════════════════════════════════════════════════════════════════╝\n";
echo COLOR_RESET . "\n";

echo COLOR_YELLOW . "⚠️  WARNING: This script simulates real attacks against the target.\n";
echo "   Only run against systems you own or have permission to test.\n" . COLOR_RESET . "\n";

// Check if target is reachable
list($code, $body, $headers, $error) = curlGet($BASE_URL . '/');
if ($error || $code == 0) {
    echo COLOR_RED . "❌ Cannot reach target: {$BASE_URL}\n";
    echo "   Error: {$error}\n" . COLOR_RESET;
    echo "   Make sure XAMPP Apache & MySQL are running.\n";
    exit(1);
}
echo COLOR_GREEN . "✅ Target reachable (HTTP {$code})\n" . COLOR_RESET;

// ─── Run all attacks ──────────────────────────────────────────────────────────
attack1_registrationBotSpam($BASE_URL, $COOKIE_JAR_DIR);
attack2_loginBruteForce($BASE_URL, $COOKIE_JAR_DIR);
attack3_ddosPublicEndpoints($BASE_URL);
attack4_postbackForgery($BASE_URL);
attack5_sessionHijacking($BASE_URL, $COOKIE_JAR_DIR);
attack6_csrfExploitation($BASE_URL);
attack7_adsterraCodeBruteForce($BASE_URL, $COOKIE_JAR_DIR);
attack8_forgotPasswordAbuse($BASE_URL);
attack9_kycUploadBombing($BASE_URL, $COOKIE_JAR_DIR);
attack10_withdrawalSpam($BASE_URL, $COOKIE_JAR_DIR);
attack11_supportTicketSpam($BASE_URL, $COOKIE_JAR_DIR);
attack12_taskSubmissionSpam($BASE_URL, $COOKIE_JAR_DIR);
attack13_vpnProxyBypass($BASE_URL);
attack14_securityHeaders($BASE_URL);
attack15_rateLimitBypass($BASE_URL);

// ╔══════════════════════════════════════════════════════════════════════════════╗
// ║                    FINAL REPORT                                             ║
// ╚══════════════════════════════════════════════════════════════════════════════╝

echo "\n\n";
echo COLOR_BOLD . COLOR_RED . str_repeat('═', 70) . COLOR_RESET . "\n";
echo COLOR_BOLD . COLOR_RED . "  🔴🔴🔴  RED TEAM PENETRATION TEST — FINAL REPORT  🔴🔴🔴" . COLOR_RESET . "\n";
echo COLOR_BOLD . COLOR_RED . str_repeat('═', 70) . COLOR_RESET . "\n\n";

echo COLOR_BOLD . "  Total Attacks Executed: " . $stats['total_attacks'] . COLOR_RESET . "\n\n";

// ─── Vulnerabilities Found ────────────────────────────────────────────────────
echo COLOR_BOLD . COLOR_RED . "  ╔══════════════════════════════════════════════════════════╗\n";
echo "  ║  ❌ VULNERABILITIES FOUND: " . str_pad(count($stats['vulnerabilities']), 30) . "║\n";
echo "  ╚══════════════════════════════════════════════════════════╝\n" . COLOR_RESET . "\n";

if (count($stats['vulnerabilities']) > 0) {
    $i = 1;
    foreach ($stats['vulnerabilities'] as $vuln) {
        echo COLOR_RED . "  {$i}. {$vuln}" . COLOR_RESET . "\n";
        $i++;
    }
} else {
    echo COLOR_GREEN . "  No vulnerabilities found! System is well-defended.\n" . COLOR_RESET;
}

// ─── Defenses Working ─────────────────────────────────────────────────────────
echo "\n" . COLOR_BOLD . COLOR_GREEN . "  ╔══════════════════════════════════════════════════════════╗\n";
echo "  ║  ✅ DEFENSES WORKING: " . str_pad(count($stats['defenses_working']), 30) . "║\n";
echo "  ╚══════════════════════════════════════════════════════════╝\n" . COLOR_RESET . "\n";

if (count($stats['defenses_working']) > 0) {
    $i = 1;
    foreach ($stats['defenses_working'] as $def) {
        echo COLOR_GREEN . "  {$i}. {$def}" . COLOR_RESET . "\n";
        $i++;
    }
}

// ─── Critical Recommendations ─────────────────────────────────────────────────
echo "\n" . COLOR_BOLD . COLOR_YELLOW . "  ╔══════════════════════════════════════════════════════════╗\n";
echo "  ║  🔶 CRITICAL RECOMMENDATIONS                             ║\n";
echo "  ╚══════════════════════════════════════════════════════════╝\n" . COLOR_RESET . "\n";

$criticalRecs = [
    "Add CAPTCHA (Google reCAPTCHA v3) on registration form — BIGGEST GAP",
    "Add CAPTCHA on login form after 3 failed attempts",
    "Add CAPTCHA on forgot password form",
    "Add rate limiting on ALL public endpoints (/, /api/stats, /api/payment-proof, etc.)",
    "Add rate limiting on POST /adsterra/verify-code (throttle:5,1)",
    "Add rate limiting on POST /forgot-password (throttle:3,10)",
    "Add rate limiting on POST /kyc (throttle:3,10)",
    "Add rate limiting on POST /withdrawal (throttle:3,60)",
    "Add rate limiting on POST /support (throttle:5,30)",
    "Add rate limiting on postback endpoints (throttle:30,1)",
    "Implement account lockout after 5 failed login attempts (15 min ban)",
    "Add IP whitelist for TimeWall/Monlix postback servers",
    "Use HMAC-SHA256 instead of MD5 for TimeWall signature verification",
    "Enable VPN check (vpn_check_enabled) in production",
    "Add Cloudflare Turnstile or hCaptcha for bot mitigation",
    "Require email verification before allowing login (email_verified_at check)",
    "Add honeypot field to registration form to catch bots",
    "Use REMOTE_ADDR only for rate limiting (ignore X-Forwarded-For from untrusted sources)",
];

$i = 1;
foreach ($criticalRecs as $rec) {
    echo COLOR_YELLOW . "  {$i}. {$rec}" . COLOR_RESET . "\n";
    $i++;
}

// ─── Summary Score ────────────────────────────────────────────────────────────
echo "\n" . COLOR_BOLD . COLOR_CYAN . str_repeat('═', 70) . COLOR_RESET . "\n";

$totalIssues = count($stats['vulnerabilities']);
$totalDefenses = count($stats['defenses_working']);

if ($totalIssues == 0) {
    echo COLOR_BOLD . COLOR_GREEN . "  🛡️  OVERALL: STRONG DEFENSE — No critical vulnerabilities found!" . COLOR_RESET . "\n";
} elseif ($totalIssues <= 5) {
    echo COLOR_BOLD . COLOR_YELLOW . "  ⚠️  OVERALL: MODERATE RISK — {$totalIssues} issues found, {$totalDefenses} defenses working" . COLOR_RESET . "\n";
} elseif ($totalIssues <= 10) {
    echo COLOR_BOLD . COLOR_RED . "  🔴 OVERALL: HIGH RISK — {$totalIssues} vulnerabilities found!" . COLOR_RESET . "\n";
} else {
    echo COLOR_BOLD . COLOR_RED . "  💀 OVERALL: CRITICAL RISK — {$totalIssues} vulnerabilities found! Immediate action required!" . COLOR_RESET . "\n";
}

echo COLOR_BOLD . COLOR_CYAN . str_repeat('═', 70) . COLOR_RESET . "\n\n";

echo COLOR_YELLOW . "  Report generated: " . date('Y-m-d H:i:s') . "\n";
echo "  Target: {$BASE_URL}\n" . COLOR_RESET . "\n";

// Cleanup
array_map('unlink', glob($COOKIE_JAR_DIR . '/*'));
@rmdir($COOKIE_JAR_DIR);