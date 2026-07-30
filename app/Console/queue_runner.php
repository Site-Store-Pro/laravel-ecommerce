<?php
/**
 * Background Queue Runner
 * =======================
 * Spawned by AdminQueueMonitor::startWorker() to process Laravel jobs in the
 * background without blocking the HTTP request.
 *
 * Located at app/Console/queue_runner.php (tracked in Git).
 *
 * Lifecycle
 * ---------
 *  1. Writes own PID → storage/app/queue_worker.pid    (signals "running" to the admin UI)
 *  2. Runs artisan queue:work and streams output → storage/app/queue_worker.log (real-time)
 *  3. Deletes storage/app/queue_worker.pid             (signals "done" to the admin UI)
 *
 * CLI Arguments
 * -------------
 *  $argv[1]  Max jobs to process before stopping  (default: 500)
 *  $argv[2]  Queue name                           (default: 'default')
 */

$projectRoot   = dirname(dirname(__DIR__));                 // project root directory
$storageAppDir = $projectRoot . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'app';
$artisan       = $projectRoot . DIRECTORY_SEPARATOR . 'artisan';
$pidFile       = $storageAppDir . DIRECTORY_SEPARATOR . 'queue_worker.pid';
$logFile       = $storageAppDir . DIRECTORY_SEPARATOR . 'queue_worker.log';

$maxJobs = isset($argv[1]) ? max(1, (int) $argv[1]) : 500;
$queue   = isset($argv[2]) ? preg_replace('/[^a-zA-Z0-9_\-]/', '', $argv[2]) : 'default';

// ── Ensure storage/app directory exists ──────────────────────────────────────
if (!is_dir($storageAppDir)) {
    @mkdir($storageAppDir, 0755, true);
}

// ── Sanity check ─────────────────────────────────────────────────────────────
if (!file_exists($artisan)) {
    $msg = date('Y-m-d H:i:s') . " [ERROR] Cannot locate artisan at: {$artisan}\n";
    file_put_contents($logFile, $msg, FILE_APPEND | LOCK_EX);
    exit(1);
}

// ── Register PID so AdminQueueMonitor can track / stop us ────────────────────
file_put_contents($pidFile, getmypid(), LOCK_EX);

// ── Open log in append mode ───────────────────────────────────────────────────
$log = fopen($logFile, 'a');

// ── Resolve PHP CLI Binary ───────────────────────────────────────────────────
$phpBin = (function() {
    $bin = PHP_BINARY;
    $basename = strtolower(basename($bin));
    if ($bin && !str_contains($basename, 'fpm') && !str_contains($basename, 'cgi')) {
        return $bin;
    }
    foreach (['/usr/bin/php', '/usr/bin/php8.5', '/usr/bin/php8.4', '/usr/bin/php8.3', '/usr/local/bin/php'] as $c) {
        if (file_exists($c) && is_executable($c)) {
            return $c;
        }
    }
    return 'php';
})();

// ── Build the artisan command ─────────────────────────────────────────────────
$cmd = escapeshellarg($phpBin) . ' ' . escapeshellarg($artisan)
    . ' queue:work'
    . ' --queue='     . escapeshellarg($queue)
    . ' --stop-when-empty'
    . ' --max-jobs='  . $maxJobs
    . ' --tries=3'
    . ' --timeout=300'
    . ' 2>&1';

// ── Stream output to log in real time ────────────────────────────────────────
$handle = popen($cmd, 'r');

if ($handle) {
    while (!feof($handle)) {
        $line = fgets($handle);
        if ($line !== false) {
            fwrite($log, $line);
            fflush($log);
        }
    }
    pclose($handle);
} else {
    fwrite($log, date('Y-m-d H:i:s') . " [ERROR] Failed to spawn artisan queue:work.\n");
}

// ── Write finish marker and close ────────────────────────────────────────────
fwrite($log, "\n=== Queue Worker Finished at " . date('Y-m-d H:i:s') . " ===\n");
fflush($log);
fclose($log);

// Remove PID file — this is the "done" signal read by AdminQueueMonitor
@unlink($pidFile);
