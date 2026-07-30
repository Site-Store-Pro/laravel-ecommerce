<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class AdminQueueMonitor extends Component
{
    // ── State ──────────────────────────────────────────────────────────────────
    public bool   $isRunning    = false;
    public int    $maxJobs      = 500;
    public string $queueName    = 'default';

    // ── Flash ──────────────────────────────────────────────────────────────────
    public string $flashMessage = '';
    public string $flashType    = 'success';

    // ── Internal paths (set in boot) ───────────────────────────────────────────
    private string $logFile;
    private string $pidFile;
    private string $runnerScript;

    public function boot(): void
    {
        $this->logFile      = storage_path('app/queue_worker.log');
        $this->pidFile      = storage_path('app/queue_worker.pid');
        $this->runnerScript = file_exists(app_path('Console/queue_runner.php'))
            ? app_path('Console/queue_runner.php')
            : storage_path('app/queue_runner.php');
    }

    public function mount(): void
    {
        $this->isRunning = $this->checkIfRunning();
    }

    // ── Computed ───────────────────────────────────────────────────────────────

    /** Number of jobs waiting in the queue. */
    #[Computed]
    public function pendingJobs(): int
    {
        try {
            return DB::table('jobs')->count();
        } catch (\Throwable) {
            return 0;
        }
    }

    /** Number of jobs that have permanently failed. */
    #[Computed]
    public function failedJobs(): int
    {
        try {
            return DB::table('failed_jobs')->count();
        } catch (\Throwable) {
            return 0;
        }
    }

    /** Last 150 lines of the worker log file. */
    #[Computed]
    public function logLines(): array
    {
        if (!file_exists($this->logFile)) {
            return [];
        }

        $lines = @file($this->logFile, FILE_IGNORE_NEW_LINES);
        if (!$lines) {
            return [];
        }

        return array_values(array_slice($lines, -150));
    }

    /** Count of "Processed" lines in the current log. */
    #[Computed]
    public function processedCount(): int
    {
        return count(array_filter(
            $this->logLines,
            fn (string $l) => str_contains($l, 'Processed:') || str_contains($l, 'DONE')
        ));
    }

    /** Count of "Failed" lines in the current log. */
    #[Computed]
    public function failedCount(): int
    {
        return count(array_filter(
            $this->logLines,
            fn (string $l) => str_contains($l, 'Failed:') || str_contains($l, 'FAILED')
        ));
    }

    // ── Actions ────────────────────────────────────────────────────────────────

    /** Called by wire:poll while the worker is running. */
    public function refreshStatus(): void
    {
        $this->isRunning = $this->checkIfRunning();
    }

    /** Spawn the background queue worker. */
    public function startWorker(): void
    {
        if ($this->checkIfRunning()) {
            $this->flash('A worker is already running.', 'error');
            return;
        }

        if (!file_exists($this->runnerScript)) {
            $this->flash('queue_runner.php not found in storage/app — cannot start worker.', 'error');
            return;
        }

        // Append a start marker to the log (don't overwrite — preserve history)
        $this->appendToLog(
            "\n=== Queue Worker Started at " . now()->toDateTimeString()
            . "  |  max-jobs: {$this->maxJobs}  |  queue: {$this->queueName} ===\n"
        );

        $phpCli  = $this->getPhpCliBinary();
        $runner  = $this->runnerScript;
        $maxJobs = (int) $this->maxJobs;
        $queue   = $this->queueName;

        if (PHP_OS_FAMILY === 'Windows') {
            // Windows: start /B "" "PHP_BINARY" "runnerScript" maxJobs queue
            $cmd = sprintf(
                'start /B "" %s %s %d %s',
                escapeshellarg($phpCli),
                escapeshellarg($runner),
                $maxJobs,
                escapeshellarg($queue)
            );
            pclose(popen($cmd, 'r'));
        } else {
            // Linux / Amazon Linux 2023: nohup + & using PHP CLI binary
            $cmd = sprintf(
                'nohup %s %s %d %s > /dev/null 2>&1 &',
                escapeshellarg($phpCli),
                escapeshellarg($runner),
                $maxJobs,
                escapeshellarg($queue)
            );
            exec($cmd);
        }

        // Give the runner a moment to write its PID file before we check
        usleep(400_000);

        $this->isRunning = $this->checkIfRunning();
        $this->flash('Queue worker started — processing jobs in the background.', 'success');
    }

    /** Resolve the CLI PHP binary path (prevents using php-fpm or php-cgi under web servers). */
    private function getPhpCliBinary(): string
    {
        $bin = PHP_BINARY;
        $basename = strtolower(basename($bin));
        if ($bin && !str_contains($basename, 'fpm') && !str_contains($basename, 'cgi')) {
            return $bin;
        }

        $candidates = [
            '/usr/bin/php',
            '/usr/bin/php8.5',
            '/usr/bin/php8.4',
            '/usr/bin/php8.3',
            '/usr/bin/php8.2',
            '/usr/local/bin/php',
        ];

        foreach ($candidates as $candidate) {
            if (file_exists($candidate) && is_executable($candidate)) {
                return $candidate;
            }
        }

        return 'php';
    }

    /** Send SIGTERM to the running worker (graceful stop). */
    public function stopWorker(): void
    {
        if (!file_exists($this->pidFile)) {
            $this->isRunning = false;
            $this->flash('No active worker found.', 'error');
            return;
        }

        $pid = (int) trim(file_get_contents($this->pidFile));

        if ($pid > 0) {
            if (PHP_OS_FAMILY === 'Windows') {
                // taskkill terminates the process tree
                exec("taskkill /F /T /PID {$pid} 2>NUL");
            } elseif (function_exists('posix_kill')) {
                posix_kill($pid, SIGTERM);
            } else {
                // Fallback for servers without the POSIX extension
                exec("kill -TERM {$pid} 2>/dev/null");
            }
        }

        @unlink($this->pidFile);

        $this->appendToLog(
            "\n=== Worker Stopped by Admin at " . now()->toDateTimeString() . " ===\n"
        );

        $this->isRunning = false;
        $this->flash('Queue worker stopped.', 'success');
    }

    /** Wipe the log file. */
    public function clearLog(): void
    {
        file_put_contents($this->logFile, '');
        $this->flash('Log cleared.', 'success');
    }

    public function flash(string $message, string $type = 'success'): void
    {
        $this->flashMessage = $message;
        $this->flashType    = $type;
    }

    public function clearFlash(): void
    {
        $this->flashMessage = '';
    }

    // ── Private helpers ────────────────────────────────────────────────────────

    /**
     * Determine whether the background worker process is alive.
     *
     * Strategy:
     *  1. No PID file → not running.
     *  2. PID file exists → read the PID and check if the OS reports that
     *     process as alive.
     *  3. If the process is dead but the PID file was never cleaned up
     *     (abnormal exit), remove the stale file and return false.
     */
    private function checkIfRunning(): bool
    {
        if (!file_exists($this->pidFile)) {
            return false;
        }

        $pid = (int) trim(file_get_contents($this->pidFile));

        if ($pid <= 0) {
            @unlink($this->pidFile);
            return false;
        }

        if (PHP_OS_FAMILY === 'Windows') {
            // tasklist /FI returns at least 2 lines when the PID exists
            exec("tasklist /FI \"PID eq {$pid}\" 2>NUL", $output);
            $alive = count($output) > 1;
        } elseif (function_exists('posix_kill')) {
            // posix_kill with signal 0 tests existence without sending a signal
            $alive = posix_kill($pid, 0);
        } else {
            // Last-resort: check the Linux /proc filesystem
            $alive = file_exists("/proc/{$pid}");
        }

        if (!$alive) {
            @unlink($this->pidFile);
        }

        return $alive;
    }

    private function appendToLog(string $text): void
    {
        file_put_contents($this->logFile, $text, FILE_APPEND | LOCK_EX);
    }

    public function render()
    {
        return view('livewire.admin-queue-monitor')
            ->layout('layouts.app');
    }
}
