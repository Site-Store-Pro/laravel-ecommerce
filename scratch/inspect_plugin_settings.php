<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Plugin;

$all = Plugin::all();
$plugin = null;
foreach ($all as $p) {
    if (($p->slug ?? '') === 'live-search-2026') {
        $plugin = $p;
        break;
    }
}

if (!$plugin) {
    echo "Live search plugin not found in DB!\n";
    $all = Plugin::all();
    echo "Available plugins in DB:\n";
    foreach ($all as $p) {
        echo "  - " . ($p->plugin_slug ?? $p->slug ?? $p->name) . "\n";
    }
} else {
    echo "Found Live Search Plugin:\n";
    echo "ID: " . $plugin->id . "\n";
    echo "Settings array:\n";
    print_r($plugin->settings);
    echo "\ncustom_css setting via getSetting(): " . var_export($plugin->getSetting('custom_css'), true) . "\n";
    echo "custom_css property directly: " . var_export($plugin->custom_css ?? 'none', true) . "\n";
}
