<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== CMS SETTINGS ===";
if (Schema::hasTable('cms_settings')) {
    $rows = DB::table('cms_settings')->get();
    foreach ($rows as $r) {
        $k = $r->key ?? $r->setting_key ?? $r->name ?? '';
        $v = $r->value ?? $r->setting_value ?? '';
        if (str_contains(strtolower($k), 'sticky') || str_contains(strtolower($v), 'sticky') || str_contains(strtolower($k), 'css') || str_contains(strtolower($v), 'padding')) {
            echo "Key: {$k} | Value: {$v}\n";
        }
    }
}

echo "\n=== NAV MENUS ===\n";
if (Schema::hasTable('nav_menus')) {
    $rows = DB::table('nav_menus')->get();
    foreach ($rows as $r) {
        echo "ID: {$r->id} | Name: {$r->name} | Sticky: {$r->sticky} | Offset: {$r->sticky_body_offset} | Custom CSS: {$r->custom_css}\n";
    }
}
