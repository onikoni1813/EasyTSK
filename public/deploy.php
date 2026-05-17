<?php

/**
 * ╔══════════════════════════════════════════════════════════╗
 * ║          ONE-TIME DEPLOYMENT SCRIPT                      ║
 * ║  Run once after upload → it deletes itself automatically ║
 * ║  NEVER leave this file on the server!                    ║
 * ╚══════════════════════════════════════════════════════════╝
 *
 * ACCESS: https://yoursite.com/deploy.php?secret=CHANGE_THIS_KEY
 */

define('DEPLOY_SECRET', 'EasyTask2026');

// ── Security gate ────────────────────────────────────────────────────────────
if (!isset($_GET['secret']) || $_GET['secret'] !== DEPLOY_SECRET) {
    http_response_code(403);
    die('<h2 style="color:red;font-family:monospace">403 — Access Denied</h2>');
}

// ── Bootstrap Laravel (easytsk_raw/ is Laravel root, public_html/ is web root) ──
define('LARAVEL_ROOT', dirname(__DIR__) . '/easytsk_raw');
require LARAVEL_ROOT . '/vendor/autoload.php';
$app = require_once LARAVEL_ROOT . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;

// ── Helper ────────────────────────────────────────────────────────────────────
$results = [];

function runArtisan(string $command, array $params = []): string {
    ob_start();
    $exitCode = Artisan::call($command, $params);
    $output = Artisan::output();
    ob_end_clean();
    return trim($output) ?: ($exitCode === 0 ? 'Done ✅' : "Exit code: $exitCode");
}

// ── Run Commands ──────────────────────────────────────────────────────────────
$steps = [
    'config:clear'   => fn() => runArtisan('config:clear'),
    'cache:clear'    => fn() => runArtisan('cache:clear'),
    'view:clear'     => fn() => runArtisan('view:clear'),
    'route:clear'    => fn() => runArtisan('route:clear'),

    'migrate'        => fn() => runArtisan('migrate', ['--force' => true]),

    'storage:link'   => function () {
        $target = realpath(__DIR__ . '/../storage/app/public');
        $link   = __DIR__ . '/storage';
        if (is_link($link)) {
            return 'Already linked ✅';
        }
        if (!function_exists('symlink')) {
            // Fallback: copy files if symlink not allowed
            return 'symlink() not available — copy storage manually.';
        }
        return symlink($target, $link) ? 'Symlink created ✅' : 'Failed ❌ — try cPanel File Manager';
    },

    'optimize'       => fn() => runArtisan('optimize'),
];

foreach ($steps as $name => $fn) {
    try {
        $results[$name] = $fn();
    } catch (\Throwable $e) {
        $results[$name] = '❌ Error: ' . $e->getMessage();
    }
}

// ── Self-delete ───────────────────────────────────────────────────────────────
$selfDeleted = @unlink(__FILE__);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Deploy Results</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { background: #0a0f1e; color: #e2e8f0; font-family: 'Courier New', monospace; padding: 40px 20px; }
  .card { max-width: 700px; margin: 0 auto; background: #1a2235; border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; overflow: hidden; }
  .header { background: linear-gradient(135deg, #00c853, #00a843); padding: 24px 32px; }
  .header h1 { color: #000; font-size: 1.4rem; font-weight: 900; letter-spacing: -0.5px; }
  .header p { color: rgba(0,0,0,0.6); font-size: 0.8rem; margin-top: 4px; }
  .body { padding: 24px 32px; }
  .step { display: flex; justify-content: space-between; align-items: flex-start; padding: 14px 0; border-bottom: 1px solid rgba(255,255,255,0.05); gap: 12px; }
  .step:last-child { border-bottom: none; }
  .step-name { color: #94a3b8; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; flex-shrink: 0; }
  .step-result { font-size: 0.78rem; color: #e2e8f0; text-align: right; word-break: break-word; }
  .footer { background: rgba(0,0,0,0.3); padding: 16px 32px; }
  .self-delete { font-size: 0.75rem; text-align: center; }
  .self-delete.ok { color: #00c853; }
  .self-delete.fail { color: #f43f5e; font-weight: 700; }
</style>
</head>
<body>
<div class="card">
  <div class="header">
    <h1>🚀 Deployment Complete</h1>
    <p>One-time deploy script executed — <?= date('Y-m-d H:i:s') ?></p>
  </div>
  <div class="body">
    <?php foreach ($results as $name => $result): ?>
    <div class="step">
      <span class="step-name"><?= htmlspecialchars($name) ?></span>
      <span class="step-result"><?= nl2br(htmlspecialchars($result)) ?></span>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="footer">
    <?php if ($selfDeleted): ?>
      <p class="self-delete ok">✅ deploy.php has been automatically deleted from the server. You're safe.</p>
    <?php else: ?>
      <p class="self-delete fail">⚠️ Auto-delete failed! Manually delete deploy.php from your server NOW via cPanel File Manager!</p>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
</html>
