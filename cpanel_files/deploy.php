<?php

/**
 * ONE-TIME DEPLOYMENT SCRIPT
 * Access: https://easytsk.com/deploy.php?secret=EasyTask2026
 * Self-deletes after running.
 */

define('DEPLOY_SECRET', 'EasyTask2026');

if (!isset($_GET['secret']) || $_GET['secret'] !== DEPLOY_SECRET) {
    http_response_code(403);
    die('<h2 style="color:red;font-family:monospace">403 — Access Denied</h2>');
}

define('LARAVEL_ROOT', dirname(__DIR__) . '/easytsk_raw');
require LARAVEL_ROOT . '/vendor/autoload.php';
$app = require_once LARAVEL_ROOT . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;

$results = [];

function runArtisan(string $cmd, array $params = []): string {
    ob_start();
    $exit = Artisan::call($cmd, $params);
    $out  = Artisan::output();
    ob_end_clean();
    return trim($out) ?: ($exit === 0 ? 'Done ✅' : "Exit: $exit");
}

$steps = [
    'config:clear'  => fn() => runArtisan('config:clear'),
    'cache:clear'   => fn() => runArtisan('cache:clear'),
    'view:clear'    => fn() => runArtisan('view:clear'),
    'route:clear'   => fn() => runArtisan('route:clear'),
    'migrate'       => fn() => runArtisan('migrate', ['--force' => true]),
    'storage:link'  => function () {
        $target = LARAVEL_ROOT . '/storage/app/public';
        $link   = __DIR__ . '/storage';
        if (is_link($link) || file_exists($link)) return 'Already exists ✅';
        return symlink($target, $link) ? 'Symlink created ✅' : 'Failed ❌ — create manually';
    },
    'optimize'      => fn() => runArtisan('optimize'),
];

foreach ($steps as $name => $fn) {
    try { $results[$name] = $fn(); }
    catch (\Throwable $e) { $results[$name] = '❌ ' . $e->getMessage(); }
}

$selfDeleted = @unlink(__FILE__);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Deploy Results — EasyTSK</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { background: #0a0f1e; color: #e2e8f0; font-family: 'Courier New', monospace; padding: 40px 20px; }
.card { max-width: 700px; margin: 0 auto; background: #1a2235; border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; overflow: hidden; }
.header { background: linear-gradient(135deg, #00c853, #00a843); padding: 24px 32px; }
.header h1 { color: #000; font-size: 1.4rem; font-weight: 900; }
.header p  { color: rgba(0,0,0,0.6); font-size: 0.8rem; margin-top: 4px; }
.body { padding: 24px 32px; }
.step { display: flex; justify-content: space-between; padding: 14px 0; border-bottom: 1px solid rgba(255,255,255,0.05); gap: 12px; }
.step:last-child { border-bottom: none; }
.step-name { color: #94a3b8; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; flex-shrink: 0; }
.step-result { font-size: 0.78rem; color: #e2e8f0; text-align: right; word-break: break-word; }
.footer { background: rgba(0,0,0,0.3); padding: 16px 32px; text-align: center; font-size: 0.75rem; }
.ok   { color: #00c853; }
.fail { color: #f43f5e; font-weight: 700; }
</style>
</head>
<body>
<div class="card">
  <div class="header">
    <h1>🚀 EasyTSK — Deployment Complete</h1>
    <p><?= date('Y-m-d H:i:s') ?></p>
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
      <p class="ok">✅ deploy.php has been automatically deleted. You're safe.</p>
    <?php else: ?>
      <p class="fail">⚠️ Auto-delete failed! Delete deploy.php manually from cPanel!</p>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
