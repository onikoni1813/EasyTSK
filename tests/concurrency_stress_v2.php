<?php
/**
 * ═══════════════════════════════════════════════════════════════════════
 * 50-USER CONCURRENCY STRESS TEST v2
 * ═══════════════════════════════════════════════════════════════════════
 * 
 * Scenario: 50 users press "Submit" at the SAME time on a task with quota=5.
 * Expected: Only 5 succeed, 45 get "quota_exhausted". No duplicates.
 * 
 * Uses proc_open to launch 50 independent PHP processes simultaneously.
 * Each worker uses SELECT ... FOR UPDATE (same as TaskController::submit).
 * 
 * Usage: php tests/concurrency_stress_v2.php
 */

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$TASK_ID   = 1;
$QUOTA     = 5;   // Only 5 submissions allowed
$WORKERS   = 50;  // 50 users try simultaneously
$config    = config('database.connections.mysql');

echo "═══════════════════════════════════════════════════════════════\n";
echo "  50-USER CONCURRENCY STRESS TEST\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "  Task ID: {$TASK_ID}\n";
echo "  Quota:   {$QUOTA} (only {$QUOTA} submissions allowed)\n";
echo "  Workers: {$WORKERS} (all launch simultaneously)\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// ── Setup ───────────────────────────────────────────────────────────────────
$task = \App\Models\Task::find($TASK_ID);
if (!$task) {
    die("❌ Task #{$TASK_ID} not found!\n");
}
echo "Task: \"{$task->title}\" (type={$task->type})\n";

// Reset quota
$task->update(['quota_remaining' => $QUOTA]);
echo "Quota reset to: {$QUOTA}\n\n";

// Create 50 test users
echo "Creating {$WORKERS} test users...\n";
$userIds = [];
for ($i = 1; $i <= $WORKERS; $i++) {
    $email = "stress{$i}@test.local";
    $user = \App\Models\User::firstOrCreate(
        ['email' => $email],
        [
            'name'       => "StressUser {$i}",
            'full_name'  => "Stress User {$i}",
            'password'   => bcrypt('password'),
            'points'     => 0,
            'total_earned_bdt' => 0,
            'total_earned_lifetime' => 0,
            'total_admin_profit_generated' => 0,
            'is_banned'  => false,
            'is_admin'   => false,
        ]
    );
    $userIds[] = $user->id;
}
echo "   Created/Found: " . count($userIds) . " users\n";

// Clean previous test submissions
\App\Models\Submission::where('task_id', $TASK_ID)
    ->whereIn('user_id', $userIds)->delete();
\App\Models\Transaction::where('task_id', $TASK_ID)
    ->whereIn('user_id', $userIds)->delete();
// Reset points
\App\Models\User::whereIn('id', $userIds)->update(['points' => 0]);
echo "   Cleaned previous test data\n\n";

// ── Prepare DB config for workers ───────────────────────────────────────────
$dbConfigJson = json_encode([
    'host'     => $config['host'],
    'port'     => $config['port'],
    'database' => $config['database'],
    'charset'  => $config['charset'],
    'username' => $config['username'],
    'password' => $config['password'],
]);
$dbConfigB64 = base64_encode($dbConfigJson);
$workerScript = __DIR__ . '/stress_worker.php';

// ── Launch all 50 workers SIMULTANEOUSLY ────────────────────────────────────
echo "🚀 Launching {$WORKERS} workers simultaneously...\n";
$t0 = microtime(true);

$processes = [];
$pipesList = [];

for ($i = 0; $i < $WORKERS; $i++) {
    $uid = $userIds[$i];
    $cmd = sprintf(
        'php "%s" %d %d %s',
        $workerScript,
        $TASK_ID,
        $uid,
        escapeshellarg($dbConfigB64)
    );

    $descriptorspec = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ];

    $process = proc_open($cmd, $descriptorspec, $pipes);
    
    if (is_resource($process)) {
        $processes[] = $process;
        $pipesList[] = $pipes;
        // Close stdin immediately
        fclose($pipes[0]);
    }
}

$launchTime = round((microtime(true) - $t0) * 1000, 2);
echo "   All {$WORKERS} workers launched in {$launchTime}ms\n";

// ── Collect results ─────────────────────────────────────────────────────────
echo "⏳ Waiting for all workers to complete...\n";
$t0 = microtime(true);

$results = [
    'success'         => 0,
    'quota_exhausted' => 0,
    'already_submitted'=> 0,
    'lock_timeout'    => 0,
    'deadlock'        => 0,
    'error'           => 0,
];
$waitTimes = [];

for ($i = 0; $i < count($processes); $i++) {
    $output = stream_get_contents($pipesList[$i][1]);
    fclose($pipesList[$i][1]);
    fclose($pipesList[$i][2]);
    
    $exitCode = proc_close($processes[$i]);
    
    $data = json_decode($output, true);
    
    if ($data && isset($data['success'])) {
        if ($data['success']) {
            $results['success']++;
        } else {
            $msg = $data['message'] ?? 'unknown';
            if (str_contains($msg, 'quota_exhausted')) {
                $results['quota_exhausted']++;
            } elseif (str_contains($msg, 'already_submitted')) {
                $results['already_submitted']++;
            } elseif (str_contains($msg, 'lock_timeout')) {
                $results['lock_timeout']++;
            } elseif (str_contains($msg, 'deadlock')) {
                $results['deadlock']++;
            } else {
                $results['error']++;
            }
        }
        if (isset($data['wait_ms'])) {
            $waitTimes[] = $data['wait_ms'];
        }
    } else {
        $results['error']++;
    }
}

$totalTime = round((microtime(true) - $t0) * 1000, 2);

// ── Report ──────────────────────────────────────────────────────────────────
echo "\n═══════════════════════════════════════════════════════════════\n";
echo "  RESULTS\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "  Total workers:     {$WORKERS}\n";
echo "  Total time:        {$totalTime}ms\n";
echo str_repeat('─', 60) . "\n";
echo "  ✅ Approved:       {$results['success']} (expected: {$QUOTA})\n";
echo "  🚫 Quota exhausted: {$results['quota_exhausted']} (expected: " . ($WORKERS - $QUOTA) . ")\n";
echo "  ⚠️  Already submitted: {$results['already_submitted']}\n";
echo "  ⏱️  Lock timeout:  {$results['lock_timeout']}\n";
echo "  💀 Deadlock:       {$results['deadlock']}\n";
echo "  ❌ Errors:         {$results['error']}\n";

if ($waitTimes) {
    $avgWait = round(array_sum($waitTimes) / count($waitTimes), 2);
    $maxWait = round(max($waitTimes), 2);
    $minWait = round(min($waitTimes), 2);
    echo str_repeat('─', 60) . "\n";
    echo "  Lock wait times:\n";
    echo "    Min: {$minWait}ms | Avg: {$avgWait}ms | Max: {$maxWait}ms\n";
}

// ── Database verification ───────────────────────────────────────────────────
echo "\n🔍 DATABASE VERIFICATION:\n";
echo str_repeat('─', 60) . "\n";

$task = \App\Models\Task::find($TASK_ID);
$subCount = \App\Models\Submission::where('task_id', $TASK_ID)
    ->whereIn('user_id', $userIds)->count();
$usersWithPoints = \App\Models\User::whereIn('id', $userIds)
    ->where('points', '>', 0)->count();
$totalPoints = \App\Models\User::whereIn('id', $userIds)->sum('points');

echo "  Task quota remaining: {$task->quota_remaining} (expected: 0)\n";
echo "  Submissions created:  {$subCount} (expected: {$QUOTA})\n";
echo "  Users with points:    {$usersWithPoints} (expected: {$QUOTA})\n";
echo "  Total points awarded: {$totalPoints} (expected: " . ($QUOTA * 50) . ")\n";

// ── Verdict ─────────────────────────────────────────────────────────────────
echo "\n" . str_repeat('═', 60) . "\n";
echo "  VERDICT\n";
echo str_repeat('═', 60) . "\n";

$allGood = true;

if ((int) $results['success'] !== $QUOTA) {
    echo "  ❌ FAIL: {$results['success']} approved, expected {$QUOTA}!\n";
    $allGood = false;
}
if ((int) $task->quota_remaining !== 0) {
    echo "  ❌ FAIL: Quota is {$task->quota_remaining}, expected 0!\n";
    $allGood = false;
}
if ((int) $subCount !== $QUOTA) {
    echo "  ❌ FAIL: {$subCount} submissions, expected {$QUOTA}!\n";
    $allGood = false;
}
if ((int) $totalPoints !== $QUOTA * 50) {
    echo "  ❌ FAIL: {$totalPoints} points, expected " . ($QUOTA * 50) . "!\n";
    $allGood = false;
}
if ($results['deadlock'] > 0) {
    echo "  ⚠️  WARNING: {$results['deadlock']} deadlocks detected (InnoDB resolved them)\n";
    // Deadlocks are handled by retry — not a failure
}

if ($allGood) {
    echo "  ✅ CONCURRENCY TEST PASSED!\n";
    echo "  ✅ {$WORKERS} concurrent users, only {$QUOTA} got through (quota={$QUOTA}).\n";
    echo "  ✅ No duplicate submissions.\n";
    echo "  ✅ No negative quota.\n";
    echo "  ✅ SELECT ... FOR UPDATE correctly serialized access.\n";
    echo "  ✅ DB::transaction() + lockForUpdate() pattern is PROVEN.\n";
}
echo str_repeat('═', 60) . "\n";

// ── Cleanup ─────────────────────────────────────────────────────────────────
echo "\n🧹 Cleaning up...\n";
\App\Models\Submission::where('task_id', $TASK_ID)
    ->whereIn('user_id', $userIds)->delete();
\App\Models\Transaction::where('task_id', $TASK_ID)
    ->whereIn('user_id', $userIds)->delete();
\App\Models\User::whereIn('id', $userIds)->update(['points' => 0]);
$task->update(['quota_remaining' => 100]);
echo "   Done.\n\n🏁 Stress test complete.\n";