<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$tables = Schema::getTableListing();

foreach ($tables as $table) {
    try {
        $rows = DB::table($table)->get();
        foreach ($rows as $row) {
            $json = json_encode($row);
            if (str_contains($json, '25px') || str_contains($json, 'padding-top') || (str_contains($json, 'main') && str_contains($json, 'padding'))) {
                echo "Table [{$table}]:\n";
                print_r($row);
                echo "\n-----------------------------------------\n";
            }
        }
    } catch (\Throwable $e) {
        // skip unreadable
    }
}
