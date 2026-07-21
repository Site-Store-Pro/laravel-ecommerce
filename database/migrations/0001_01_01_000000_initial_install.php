<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $sql = file_get_contents(database_path('schema.sql'));
        if (DB::getDriverName() === 'sqlite') {
            // Normalize line endings
            $sql = str_replace("\r\n", "\n", $sql);
            
            // Remove MySQL specific lines
            $sql = preg_replace('/^SET .*$/m', '', $sql);
            $sql = preg_replace('/^LOCK TABLES .*$/m', '', $sql);
            $sql = preg_replace('/^UNLOCK TABLES;.*$/m', '', $sql);
            $sql = preg_replace('/^--.*$/m', '', $sql);
            
            // Convert types to SQLite compatible types
            $sql = preg_replace('/bigint\(\d+\)\s+unsigned/i', 'INTEGER', $sql);
            $sql = preg_replace('/bigint\(\d+\)/i', 'INTEGER', $sql);
            $sql = preg_replace('/int\(\d+\)\s+unsigned/i', 'INTEGER', $sql);
            $sql = preg_replace('/int\(\d+\)/i', 'INTEGER', $sql);
            $sql = preg_replace('/tinyint\(\d+\)\s+unsigned/i', 'INTEGER', $sql);
            $sql = preg_replace('/tinyint\(\d+\)/i', 'INTEGER', $sql);
            $sql = preg_replace('/double\(\d+,\s*\d+\)/i', 'REAL', $sql);
            $sql = preg_replace('/decimal\(\d+,\s*\d+\)/i', 'REAL', $sql);
            
            // Remove InnoDB engine choices
            $sql = preg_replace('/ENGINE\s*=\s*\w+(?:\s+AUTO_INCREMENT\s*=\s*\d+)?(?:\s+DEFAULT\s+CHARSET\s*=\s*\w+)?(?:\s+COLLATE\s*=\s*\w+)?;?/i', ';', $sql);
            
            // Convert AUTO_INCREMENT to autoincrement and current_timestamp() to CURRENT_TIMESTAMP
            $sql = preg_replace('/AUTO_INCREMENT/i', '', $sql);
            $sql = preg_replace('/current_timestamp\(\)/i', 'CURRENT_TIMESTAMP', $sql);
            
            // Strip out MySQL-specific constraints and keys
            $lines = explode("\n", $sql);
            $cleanedLines = [];
            foreach ($lines as $line) {
                if (preg_match('/^\s*CONSTRAINT\s+/i', $line)) {
                    continue;
                }
                if (preg_match('/^\s*KEY\s+/i', $line)) {
                    continue;
                }
                if (preg_match('/^\s*UNIQUE KEY\s+`([^`]+)`\s*\(([^)]+)\)/i', $line, $matches)) {
                    $cleanedLines[] = "  UNIQUE({$matches[2]}),";
                    continue;
                }
                $cleanedLines[] = $line;
            }
            $sql = implode("\n", $cleanedLines);
            $sql = preg_replace('/,\s*\n\s*\)/m', "\n)", $sql);
            
            // Execute separately
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            foreach ($statements as $stmt) {
                if (empty($stmt)) continue;
                DB::unprepared($stmt);
            }
        } else {
            DB::unprepared($sql);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('ticket_attachments');
        Schema::dropIfExists('ticket_replies');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('kb_articles');
        Schema::dropIfExists('kb_categories');
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');

        Schema::enableForeignKeyConstraints();
    }
};
