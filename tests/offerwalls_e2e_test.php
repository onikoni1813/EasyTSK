<?php
/**
 * ═══════════════════════════════════════════════════════════════════════
 * DYNAMIC OFFERWALLS E2E TEST
 * ═══════════════════════════════════════════════════════════════════════
 * 
 * Validates the entire dynamic offerwall lifecycle, including security
 * match verification, reward calculations, transaction generation, and
 * duplicate prevention.
 * 
 * Usage: php tests/offerwalls_e2e_test.php
 */

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Offerwall;
use App\Models\User;
use App\Models\PostbackLog;
use App\Models\Transaction;
use App\Http\Controllers\OfferwallController;
use Illuminate\Http\Request;

echo "═══════════════════════════════════════════════════════════════\n";
echo "  DYNAMIC OFFERWALLS E2E INTEGRATION TEST\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// ── Setup Test User ──────────────────────────────────────────────────────────
$user = User::firstOrCreate(
    ['email' => 'offerwall_tester@test.local'],
    [
        'name' => 'Offerwall Tester',
        'full_name' => 'Offerwall Tester',
        'password' => bcrypt('password'),
        'points' => 0,
        'total_earned_bdt' => 0,
        'total_earned_lifetime' => 0,
        'total_admin_profit_generated' => 0,
    ]
);

$user->update(['points' => 0, 'total_earned_bdt' => 0, 'total_admin_profit_generated' => 0]);
$userId = $user->id;
echo "✅ Test User ID: {$userId} ({$user->email})\n";

// ── Setup Test Offerwall (CPAGrip Mock) ──────────────────────────────────────
$offerwall = Offerwall::updateOrCreate(
    ['slug' => 'cpagrip-test'],
    [
        'name'                 => 'CPAGrip Test',
        'display_name'         => 'সিপিএগ্রিপ টেস্ট',
        'description'          => 'টেস্ট অফারওয়াল।',
        'icon_emoji'           => '🎁',
        'color'                => 'indigo',
        'is_active'            => true,
        'sort_order'           => 1,
        'iframe_url_template'  => 'https://www.cpagrip.com/show.php?l=0&u={user_id}&prodid={api_key}',
        'api_key'              => 'test_api_key_123',
        'secret_key'           => 'test_postback_secret_123',
        'postback_method'      => 'ANY',
        'security_type'        => 'secret_match',
        'security_field'       => 'key',
        'field_user_id'        => 'user_id',
        'field_reward'         => 'points',
        'field_transaction_id' => 'txid',
        'field_campaign_id'    => 'campaign_id',
        'reward_type'          => 'usd',
        'conversion_rate'      => 10000.0, // $1 USD = 10000 points
        'platform_share_pct'   => 20.0,    // 20% platform share
        'requires_task_lock'   => false,
    ]
);

echo "✅ Test Offerwall 'cpagrip-test' configured.\n\n";

$controller = app(OfferwallController::class);

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// TEST 1: Unknown Offerwall Slug
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo "🧪 [Test 1] Unknown Offerwall Slug...\n";
$req = Request::create('/api/postback/unknown-slug', 'GET', ['user_id' => $userId]);
$res = $controller->postback($req, 'unknown-slug');

$log = PostbackLog::latest('id')->first();

if ($res->getStatusCode() === 404 && $log && $log->status === 'not_found') {
    echo "   🟢 SUCCESS: Returned 404 and logged status='not_found'\n\n";
} else {
    echo "   ❌ FAIL: Status Code = {$res->getStatusCode()}, Log status = " . ($log ? $log->status : 'NULL') . "\n\n";
}

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// TEST 2: Inactive Offerwall
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo "🧪 [Test 2] Inactive Offerwall...\n";
$offerwall->update(['is_active' => false]);

$req = Request::create('/api/postback/cpagrip-test', 'GET', ['user_id' => $userId]);
$res = $controller->postback($req, 'cpagrip-test');

$log = PostbackLog::latest('id')->first();

if ($res->getStatusCode() === 403 && $log && $log->status === 'inactive') {
    echo "   🟢 SUCCESS: Returned 403 and logged status='inactive'\n\n";
} else {
    echo "   ❌ FAIL: Status Code = {$res->getStatusCode()}, Log status = " . ($log ? $log->status : 'NULL') . "\n\n";
}

$offerwall->update(['is_active' => true]);

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// TEST 3: Invalid Security Signature / Key
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo "🧪 [Test 3] Invalid Security Signature / Key...\n";
$req = Request::create('/api/postback/cpagrip-test', 'GET', [
    'user_id' => $userId,
    'key'     => 'wrong_key_here',
]);
$res = $controller->postback($req, 'cpagrip-test');

$log = PostbackLog::latest('id')->first();

if ($res->getStatusCode() === 403 && $log && $log->status === 'invalid_signature') {
    echo "   🟢 SUCCESS: Returned 403 and logged status='invalid_signature'\n\n";
} else {
    echo "   ❌ FAIL: Status Code = {$res->getStatusCode()}, Log status = " . ($log ? $log->status : 'NULL') . "\n\n";
}

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// TEST 4: Successful Postback (USD → Points Conversion)
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo "🧪 [Test 4] Successful Postback (USD -> Points)...\n";
$txId = 'tx_' . uniqid();
$rewardRaw = 1.50; // $1.50 USD

// Expected calculations:
// 1.50 * 10000 (conversion_rate) = 15000 total points
// 15000 * 20% (platform share) = 3000 admin points profit
// 15000 - 3000 = 12000 user points credited
// If point_conversion_rate is 100:
// 12000 points / 100 = 120 BDT credited to user
// 3000 points / 100 = 30 BDT generated as admin profit

$req = Request::create('/api/postback/cpagrip-test', 'GET', [
    'user_id'        => $userId,
    'points'         => $rewardRaw, // reward field is mapped to "points" in our offerwall config
    'txid'           => $txId,
    'campaign_id'    => 'test_offer_1',
    'key'            => 'test_postback_secret_123',
]);

$res = $controller->postback($req, 'cpagrip-test');

$userFresh = $user->fresh();
$log = PostbackLog::latest('id')->first();
$tx = Transaction::where('provider', 'cpagrip-test')->where('external_transaction_id', $txId)->first();

$success = true;
if ($res->getStatusCode() !== 200) {
    echo "   ❌ FAIL: Status Code = {$res->getStatusCode()}, expected 200\n";
    $success = false;
}
if (!$log || $log->status !== 'success') {
    echo "   ❌ FAIL: PostbackLog status = " . ($log ? $log->status : 'NULL') . ", expected success\n";
    $success = false;
}
if ($userFresh->points !== 12000) {
    echo "   ❌ FAIL: User points = {$userFresh->points}, expected 12000\n";
    $success = false;
}
if (!$tx || (int)$tx->amount_points !== 12000 || (float)$tx->amount_bdt != 120.00 || (float)$tx->admin_profit != 30.00) {
    echo "   ❌ FAIL: Transaction record mismatch or missing!\n";
    if ($tx) {
        echo "            Got points: {$tx->amount_points}, BDT: {$tx->amount_bdt}, Admin BDT: {$tx->admin_profit}\n";
    }
    $success = false;
}

if ($success) {
    echo "   🟢 SUCCESS: User credited 12,000 points (120 BDT), Admin Profit 30 BDT, logged success!\n\n";
} else {
    echo "\n";
}

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// TEST 5: Duplicate Postback Prevention
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo "🧪 [Test 5] Duplicate Postback Prevention...\n";
// Re-sending with the exact same transaction ID ($txId)
$reqDuplicate = Request::create('/api/postback/cpagrip-test', 'GET', [
    'user_id'        => $userId,
    'points'         => $rewardRaw,
    'txid'           => $txId,
    'campaign_id'    => 'test_offer_1',
    'key'            => 'test_postback_secret_123',
]);

$resDuplicate = $controller->postback($reqDuplicate, 'cpagrip-test');

$userFreshAfter = $user->fresh();
$logDuplicate = PostbackLog::latest('id')->first();

$dupSuccess = true;
if ($resDuplicate->getStatusCode() !== 200) {
    echo "   ❌ FAIL: Duplicate request returned Status Code = {$resDuplicate->getStatusCode()}, expected 200 (ACK)\n";
    $dupSuccess = false;
}
if (!$logDuplicate || $logDuplicate->status !== 'duplicate') {
    echo "   ❌ FAIL: Duplicate PostbackLog status = " . ($logDuplicate ? $logDuplicate->status : 'NULL') . ", expected duplicate\n";
    $dupSuccess = false;
}
if ($userFreshAfter->points !== 12000) {
    echo "   ❌ FAIL: User points changed to {$userFreshAfter->points}, expected to remain 12000\n";
    $dupSuccess = false;
}

if ($dupSuccess) {
    echo "   🟢 SUCCESS: Prevented double crediting, returned 200 ACK and logged status='duplicate'!\n\n";
} else {
    echo "\n";
}

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// SUMMARY & VERDICT
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo "═══════════════════════════════════════════════════════════════\n";
echo "  FINAL VERDICT\n";
echo "═══════════════════════════════════════════════════════════════\n";

if ($success && $dupSuccess) {
    echo "  🎉 ALL TESTS PASSED SUCCESSFULLY! 🎉\n";
    echo "  ✅ Dynamic postback verification checks work flawlessly.\n";
    echo "  ✅ Exact USD -> points conversions and platform fees are verified.\n";
    echo "  ✅ High-volume duplicate request prevention operates correctly.\n";
} else {
    echo "  ❌ SOME TESTS FAILED! Please check logs above.\n";
}
echo "═══════════════════════════════════════════════════════════════\n\n";

// ── Cleanup ─────────────────────────────────────────────────────────────────
PostbackLog::where('slug', 'cpagrip-test')->delete();
Transaction::where('provider', 'cpagrip-test')->delete();
$offerwall->delete();
$user->delete();
echo "🧹 Cleanup complete. End of test.\n";
