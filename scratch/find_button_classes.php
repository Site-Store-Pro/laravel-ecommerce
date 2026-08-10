<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\CmsSetting;

echo "Primary Button CSS setting: " . var_export(CmsSetting::get('primary_button_css'), true) . "\n";
echo "Primary Button class setting: " . var_export(CmsSetting::get('primary_button_class'), true) . "\n";
echo "All CmsSetting keys containing 'button' or 'btn':\n";
foreach (CmsSetting::all() as $s) {
    if (str_contains(strtolower($s->key), 'button') || str_contains(strtolower($s->key), 'btn')) {
        echo "  - {$s->key} = {$s->value}\n";
    }
}
