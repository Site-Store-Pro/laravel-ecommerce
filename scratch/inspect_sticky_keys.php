<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\CmsSetting;
use Illuminate\Support\Facades\DB;

echo "=== CMS SETTINGS SEARCH FOR STICKY ===\n";
$settings = CmsSetting::all();
foreach ($settings as $s) {
    if (str_contains(strtolower($s->key ?? $s->setting_key ?? $s->name ?? ''), 'sticky')) {
        echo "  " . var_export($s->toArray(), true) . "\n";
    }
}

echo "\n=== CmsSetting::get('sticky_body_offset') ===\n";
echo var_export(CmsSetting::get('sticky_body_offset'), true) . "\n";

echo "\n=== CmsSetting::get('css_var_sticky_body_offset') ===\n";
echo var_export(CmsSetting::get('css_var_sticky_body_offset'), true) . "\n";
