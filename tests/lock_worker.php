<?php
/**
 * ═══════════════════════════════════════════════════════════════════════
 * LOCK WORKER — Runs Connection B in a separate PHP process
 * ═══════════════════════════════════════════════════════════════════════
 * 
 * Called by lock_demo.php with:
 *   php lock_worker.php <task_id> <user_id> <base64_db_config>
 * 
 * This process will:
 *   1. Start a transaction
 *   2. Try SELECT ... FOR UPDATE — WILL BLOCK until Connection A commits
 *   3. Once lock acquired, check quota
 *   4. If quota > 0: insert submission, decrement quota (should NOT happen if A worked)
 *   5. If quota = 0: rollback (correct path)
 */

if ($argc < 4) {
    echo "Usage: php lock_worker.php <task_id> <user_id> <base64_db_config>\n";
    exit(1);
}

$taskId   = (int) $argv[1];
$userId   = (int) $argv[2];
$dbConfig = json_decode(base64_decode($argv[3]), true);

if (!$dbConfig) {
    echo "ERROR: Invalid DB config\n";
    exit(1);
}

$dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";

$pdoB = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$startTime = microtime(true);

echo "   [Worker B] PID=" . getmypid() . " — Started at " . date('H:i:s') . "\n";

// Start transaction
$pdoB->beginTransaction();
echo "   [Worker B] BEGIN TRANSACTION ✓\n";

// Try SELECT ... FOR UPDATE — THIS WILL BLOCK
echo "   [Worker B] Attempting SELECT...FOR UPDATE (will block)...\n";
$t0 = microtime(true);

try {
    $stmt = $pdoB->prepare("SELECT quota_remaining FROM tasks WHERE id = ? FOR UPDATE");
    $stmt->execute([$taskId]);
    $row = $stmt->fetch();
    
    $waitTime = round((microtime(true) - $t0) * 1000, 2);
    echo "   [Worker B] Lock acquired after {$waitTime}ms (quota={$row['quota_remaining']})\n";
    
    if ($waitTime > 500) {
        echo "   [Worker B] ✅ Blocked for {$waitTime}ms → Connection A held the lock!\n";
    } else {
        echo "   [Worker B] ⚠️ Lock acquired too fast ({$waitTime}ms) — Connection A may not have held lock\n";
    }
    
    // Check quota
    if ($row['quota_remaining'] > 0) {
        // This should NOT happen if Connection A committed first
        echo "   [Worker B] ❌ Quota > 0 — Would create DUPLICATE submission!\n";
        
        $pdoB->prepare(
            "INSERT INTO submissions (task_id, user_id, status, proof_text, created_at, updated_at) 
             VALUES (?, ?, 'approved', 'locktest-proof', NOW(), NOW())"
        )->execute([$taskId, $userId]);
        
        $pdoB->prepare("UPDATE tasks SET quota_remaining = quota_remaining - 1 WHERE id = ?")
            ->execute([$taskId]);
        
        $pdoB->prepare("UPDATE users SET points = points + 50 WHERE id = ?")
            ->execute([$userId]);
        
        echo "   [Worker B] ❌ Submission created (should NOT have happened!)\n";
        $pdoB->commit();
        echo "   [Worker B] COMMIT\n";
    } else {
        // Correct: quota is exhausted
        echo "   [Worker B] ✅ Quota exhausted → Correctly blocked!\n";
        $pdoB->rollBack();
        echo "   [Worker B] ROLLBACK\n";
    }
    
} catch (\PDOException $e) {
    $elapsed = round((microtime(true) - $t0) * 1000, 2);
    
    if (str_contains($e->getMessage(), 'Lock wait timeout')) {
        echo "   [Worker B] ⚠️ Lock wait timeout after {$elapsed}ms\n";
        echo "   [Worker B] ROLLBACK (lock timeout)\n";
        $pdoB->rollBack();
    } elseif (str_contains($e->getMessage(), 'Deadlock')) {
        echo "   [Worker B] ⚠️ Deadlock detected after {$elapsed}ms\n";
        echo "   [Worker B] ROLLBACK (deadlock)\n";
        $pdoB->rollBack();
    } else {
        echo "   [Worker B] ❌ Error: {$e->getMessage()}\n";
        $pdoB->rollBack();
    }
}

$totalTime = round((microtime(true) - $startTime) * 1000, 2);
echo "   [Worker B] Total time: {$totalTime}ms — Done\n";
exit(0);