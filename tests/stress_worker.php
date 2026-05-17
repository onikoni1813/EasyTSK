<?php
/**
 * ═══════════════════════════════════════════════════════════════════════
 * STRESS WORKER — Individual worker for 50-user concurrency test
 * ═══════════════════════════════════════════════════════════════════════
 * 
 * Called by concurrency_stress_v2.php with:
 *   php stress_worker.php <task_id> <user_id> <base64_db_config>
 * 
 * Mimics the EXACT locking pattern used in TaskController::submit():
 *   DB::transaction() + lockForUpdate() on task + lockForUpdate() on user
 * 
 * Output: JSON with {success: bool, message: string, wait_ms: int}
 */

if ($argc < 4) {
    echo json_encode(['success' => false, 'message' => 'Usage: php stress_worker.php <task_id> <user_id> <base64_db_config>']);
    exit(1);
}

$taskId   = (int) $argv[1];
$userId   = (int) $argv[2];
$dbConfig = json_decode(base64_decode($argv[3]), true);

if (!$dbConfig) {
    echo json_encode(['success' => false, 'message' => 'Invalid DB config']);
    exit(1);
}

$dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";

$pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$startTime = microtime(true);
$waitMs = 0;

try {
    $pdo->beginTransaction();

    // ── Lock task row (same as TaskController::submit) ──────────────────
    $t0 = microtime(true);
    $stmt = $pdo->prepare("SELECT quota_remaining FROM tasks WHERE id = ? FOR UPDATE");
    $stmt->execute([$taskId]);
    $taskRow = $stmt->fetch();
    $waitMs = round((microtime(true) - $t0) * 1000, 2);

    // ── Quota check ────────────────────────────────────────────────────
    if ($taskRow['quota_remaining'] <= 0) {
        $pdo->rollBack();
        echo json_encode([
            'success' => false,
            'message' => 'quota_exhausted',
            'wait_ms' => $waitMs,
        ]);
        exit(0);
    }

    // ── Duplicate check ────────────────────────────────────────────────
    $stmt = $pdo->prepare(
        "SELECT COUNT(*) as cnt FROM submissions 
         WHERE task_id = ? AND user_id = ? AND status IN ('pending', 'approved')"
    );
    $stmt->execute([$taskId, $userId]);
    $dupRow = $stmt->fetch();

    if ($dupRow['cnt'] > 0) {
        $pdo->rollBack();
        echo json_encode([
            'success' => false,
            'message' => 'already_submitted',
            'wait_ms' => $waitMs,
        ]);
        exit(0);
    }

    // ── Create submission ──────────────────────────────────────────────
    $pdo->prepare(
        "INSERT INTO submissions (task_id, user_id, status, proof_text, created_at, updated_at) 
         VALUES (?, ?, 'approved', 'stress-test-proof', NOW(), NOW())"
    )->execute([$taskId, $userId]);

    // ── Decrement quota ────────────────────────────────────────────────
    $pdo->prepare("UPDATE tasks SET quota_remaining = quota_remaining - 1 WHERE id = ?")
        ->execute([$taskId]);

    // ── Lock user row + update balance ─────────────────────────────────
    $stmt = $pdo->prepare("SELECT points FROM users WHERE id = ? FOR UPDATE");
    $stmt->execute([$userId]);
    
    $pdo->prepare("UPDATE users SET points = points + 50 WHERE id = ?")
        ->execute([$userId]);

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'approved',
        'wait_ms' => $waitMs,
    ]);
    exit(0);

} catch (\PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    $msg = $e->getMessage();
    if (str_contains($msg, 'Lock wait timeout')) {
        echo json_encode([
            'success' => false,
            'message' => 'lock_timeout',
            'wait_ms' => $waitMs,
        ]);
    } elseif (str_contains($msg, 'Deadlock')) {
        echo json_encode([
            'success' => false,
            'message' => 'deadlock',
            'wait_ms' => $waitMs,
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'error: ' . substr($msg, 0, 100),
            'wait_ms' => $waitMs,
        ]);
    }
    exit(0);
}