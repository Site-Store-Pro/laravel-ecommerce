<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Plugin;

$plugin = Plugin::where('shortcode', 'live-search-2026')->first() 
       ?? Plugin::where('filename', 'live_search_2026')->first();

if ($plugin) {
    echo "Plugin Found: " . $plugin->name . " (ID: {$plugin->id})\n\n";

    echo "=== PLUGIN OPTIONS (from plugin_options table) ===\n";
    foreach ($plugin->options as $opt) {
        echo "  - " . $opt->field_name . " (type: " . $opt->field_type . ") default: " . var_export($opt->field_default_value, true) . "\n";
    }

    echo "\n=== PLUGIN SETTINGS (from plugin_settings table) ===\n";
    foreach ($plugin->settings as $st) {
        echo "  - " . $st->field_name . " = " . var_export($st->field_value, true) . "\n";
    }

    echo "\n=== getSettings() Result ===\n";
    print_r($plugin->getSettings());
} else {
    echo "Plugin not found!\n";
}
