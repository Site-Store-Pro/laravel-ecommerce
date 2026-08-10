<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Plugin;

$plugin = Plugin::where('shortcode', 'categories-2026')->first() 
       ?? Plugin::where('filename', 'categories_2026')->first();

if ($plugin) {
    echo "Categories Plugin Options Detail:\n";
    foreach ($plugin->options as $opt) {
        echo "ID: {$opt->id} | Name: {$opt->field_name} | Type: {$opt->field_type} | Editor: " . var_export($opt->field_editor ?? null, true) . "\n";
        echo "Label: {$opt->field_label}\n";
        echo "Default Value:\n" . $opt->field_default_value . "\n---\n";
    }
}
