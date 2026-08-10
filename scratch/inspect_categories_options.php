<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Plugin;

$plugin = Plugin::where('shortcode', 'categories-2026')->first() 
       ?? Plugin::where('filename', 'categories_2026')->first();

if ($plugin) {
    echo "Found Categories Plugin (ID: {$plugin->id}):\n";
    foreach ($plugin->options as $opt) {
        echo "  - " . $opt->field_name . " (" . $opt->field_type . ") default: " . var_export($opt->field_default_value, true) . " label: " . $opt->field_label . "\n";
    }
} else {
    echo "Categories plugin not found!\n";
}
