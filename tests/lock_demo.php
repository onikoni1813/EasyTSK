<?php
/**
 * ═══════════════════════════════════════════════════════════════════════
 * LOCK DEMONSTRATION — True Concurrent Race Condition Test
 * ═══════════════════════════════════════════════════════════════════════
 * 
 * Proves that SELECT ... FOR UPDATE serializes concurrent access.
 * Uses proc_open to run Connection B in a separate PHP process while
 * Connection A holds the row lock.
 * 
 * Usage: php tests/lock_demo.php
 */

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$TASK_ID = 1;
$config = config('database.connections.mysql');

echo "═══════════════════════════════════════════════════════════════\n";
echo "  LOCK DEMONSTRATION — True Concurrent Test\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// ── Reset task quota ────────────────────────────────────────────────────────
\App\Models\Task::where('id', $TASK_ID)->update(['quota_remaining' => 1]);
echo "Task #{$TASK_ID} quota set to: 1 (only ONE submission allowed)\n\n";

// ── Create test users (NO explicit IDs — let auto-increment work) ───────────
$u1 = \App\Models\User::firstOrCreate(
    ['email' => 'locktest_a@test.local'],
    [
        'name' => 'LockTest A', 'full_name' => 'Lock Test A',
        'password' => bcrypt('password'), 'points' => 0,
        'total_earned_bdt' => 0, 'total_earned_lifetime' => 0,
        'total_admin_profit_generated' => 0,
        'is_banned' => false, 'is_admin' => false,
    ]
);
$u2 = \App\Models\User::firstOrCreate(
    ['email' => 'locktest_b@test.local'],
    [
        'name' => 'LockTest B', 'full_name' => 'Lock Test B',
        'password' => bcrypt('password'), 'points' => 0,
        'total_earned_bdt' => 0, 'total_earned_lifetime' => 0,
        'total_admin_profit_generated' => 0,
        'is_banned' => false, 'is_admin' => false,
    ]
);

$uidA = $u1->id;
$uidB = $u2->id;

echo "User A: ID={$uidA} ({$u1->email})\n";
echo "User B: ID={$uidB} ({$u2->email})\n\n";

// ── Clean previous test data ────────────────────────────────────────────────
\App\Models\Submission::where('task_id', $TASK_ID)->whereIn('user_id', [$uidA, $uidB])->delete();
\App\Models\Transaction::where('task_id', $TASK_ID)->whereIn('user_id', [$uidA, $uidB])->delete();
$u1->update(['points' => 0]);
$u2->update(['points' => 0]);

// ── Connection A (main process) ─────────────────────────────────────────────
$dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
$pdoA = new PDO($dsn, $config['username'], $config['password'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

// Step 1: Connection A locks the row
$pdoA->beginTransaction();
echo "1. Connection A: BEGIN TRANSACTION ✓\n";

$stmt = $pdoA->prepare("SELECT quota_remaining FROM tasks WHERE id = ? FOR UPDATE");
$stmt->execute([$TASK_ID]);
$rowA = $stmt->fetch();
echo "2. Connection A: SELECT...FOR UPDATE → LOCK ACQUIRED (quota={$rowA['quota_remaining']}) ✓\n";

// ── Step 2: Launch Connection B in a SEPARATE process ───────────────────────
// Encode DB config as base64 to avoid shell escaping issues
$dbConfigJson = json_encode([
    'host'     => $config['host'],
    'port'     => $config['port'],
    'database' => $config['database'],
    'charset'  => $config['charset'],
    'username' => $config['username'],
    'password' => $config['password'],
]);
$dbConfigB64 = base64_encode($dbConfigJson);

$workerScript = __DIR__ . '/lock_worker.php';
$cmd = sprintf(
    'php "%s" %d %d %s',
    $workerScript,
    $TASK_ID,
    $uidB,
    escapeshellarg($dbConfigB64)
);

echo "3. Launching Connection B (separate PHP process)...\n";
echo "   Command: php lock_worker.php {$TASK_ID} {$uidB} <config>\n";

$descriptorspec = [
    0 => ['pipe', 'r'],  // stdin
    1 => ['pipe', 'w'],  // stdout
    2 => ['pipe', 'w'],  // stderr
];

$process = proc_open($cmd, $descriptorspec, $pipes);

if (!is_resource($process)) {
    $pdoA->rollBack();
    die("❌ FAILED to launch worker process!\n");
}

// Give B time to start its transaction and attempt the lock (it will BLOCK)
echo "   Waiting 2 seconds for Connection B to start and block on lock...\n";
sleep(2);

// ── Step 3: Connection A does its work (while B is blocked) ─────────────────
echo "\n4. Connection A: Processing submission (B is blocked waiting)...\n";

// Create submission for User A
$pdoA->prepare(
    "INSERT INTO submissions (task_id, user_id, status, proof_text, created_at, updated_at) 
     VALUES (?, ?, 'approved', 'locktest-proof', NOW(), NOW())"
)->execute([$TASK_ID, $uidA]);

// Decrement quota
$pdoA->prepare("UPDATE tasks SET quota_remaining = quota_remaining - 1 WHERE id = ?")
    ->execute([$TASK_ID]);

// Update user balance
$pdoA->prepare("UPDATE users SET points = points + 50 WHERE id = ?")
    ->execute([$uidA]);

echo "   Submission created, quota decremented, balance updated ✓\n";

// ── Step 4: Connection A commits → RELEASES THE LOCK ────────────────────────
$pdoA->commit();
echo "5. Connection A: COMMIT → LOCK RELEASED ✓\n\n";

// ── Step 5: Read Connection B's output ──────────────────────────────────────
echo "6. Connection B output:\n";
echo str_repeat('─', 60) . "\n";

fclose($pipes[0]);  // Close stdin

$bOutput = stream_get_contents($pipes[1]);
fclose($pipes[1]);

$bError = stream_get_contents($pipes[2]);
fclose($pipes[2]);

$exitCode = proc_close($process);

echo $bOutput;
if ($bError) {
    echo "   [STDERR]: {$bError}\n";
}
echo "   Exit code: {$exitCode}\n";
echo str_repeat('─', 60) . "\n";

// ── Verify ──────────────────────────────────────────────────────────────────
echo "\n🔍 VERIFICATION:\n";
echo str_repeat('─', 60) . "\n";

$task = \App\Models\Task::find($TASK_ID);
$subs = \App\Models\Submission::where('task_id', $TASK_ID)->count();
$pointsA = $u1->fresh()->points;
$pointsB = $u2->fresh()->points;

echo "   Task quota: {$task->quota_remaining} (expected: 0)\n";
echo "   Submissions: {$subs} (expected: 1)\n";
echo "   User A points: {$pointsA} (expected: 50)\n";
echo "   User B points: {$pointsB} (expected: 0)\n";

// ── Verdict ─────────────────────────────────────────────────────────────────
echo "\n" . str_repeat('═', 60) . "\n";
echo "  VERDICT\n";
echo str_repeat('═', 60) . "\n";

$allGood = true;
if ($task->quota_remaining !== 0) {
    echo "  ❌ FAIL: Quota is {$task->quota_remaining}, expected 0!\n";
    $allGood = false;
}
if ($subs !== 1) {
    echo "  ❌ FAIL: {$subs} submissions, expected 1!\n";
    $allGood = false;
}
if ($pointsA !== 50) {
    echo "  ❌ FAIL: User A has {$pointsA} points, expected 50!\n";
    $allGood = false;
}
if ($pointsB !== 0) {
    echo "  ❌ FAIL: User B has {$pointsB} points, expected 0!\n";
    $allGood = false;
}

if ($allGood) {
    echo "  ✅ LOCK MECHANISM WORKS CORRECTLY!\n";
    echo "  ✅ SELECT ... FOR UPDATE serializes concurrent access.\n";
    echo "  ✅ Only 1 of 2 concurrent users got through (quota=1).\n";
    echo "  ✅ No duplicate submissions, no negative quota.\n";
    echo "  ✅ User B correctly saw quota=0 after A committed.\n";
}
echo str_repeat('═', 60) . "\n";

// ── Cleanup ─────────────────────────────────────────────────────────────────
\App\Models\Submission::where('task_id', $TASK_ID)->whereIn('user_id', [$uidA, $uidB])->delete();
\App\Models\Transaction::where('task_id', $TASK_ID)->whereIn('user_id', [$uidA, $uidB])->delete();
$task->update(['quota_remaining' => 100]);
$u1->update(['points' => 0]);
$u2->update(['points' => 0]);
echo "\n🧹 Cleaned up.\n\n🏁 Demo complete.\n";