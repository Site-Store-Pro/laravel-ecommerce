<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = DB::select('SHOW TABLES');
$dbName = DB::getDatabaseName();
$keyName = "Tables_in_" . $dbName;

foreach ($tables as $t) {
    $tableName = $t->$keyName;
    $cols = DB::select("SHOW COLUMNS FROM `{$tableName}`");
    foreach ($cols as $c) {
        $colName = $c->Field;
        try {
            $count = DB::table($tableName)->where($colName, 'LIKE', '%text-shadow%')->count();
            if ($count > 0) {
                echo "DB MATCH in `{$tableName}`.`{$colName}`: {$count} row(s)\n";
                $rows = DB::table($tableName)->where($colName, 'LIKE', '%text-shadow%')->get();
                foreach ($rows as $r) {
                    echo "  Row ID " . ($r->id ?? $r->slideshow_id ?? $r->SlideID ?? '?') . ": " . substr(json_encode($r), 0, 200) . "\n";
                }
            }
        } catch (\Throwable $e) {}
    }
}
