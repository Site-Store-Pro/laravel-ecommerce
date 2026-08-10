<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Plugin;

$plugin = Plugin::where('shortcode', 'brands-2026')->first() 
       ?? Plugin::where('filename', 'brands_2026')->first();

if ($plugin) {
    echo "Current Usage Instructions for {$plugin->name}:\n";
    echo $plugin->usage_instructions . "\n";
} else {
    echo "Plugin not found!\n";
}
