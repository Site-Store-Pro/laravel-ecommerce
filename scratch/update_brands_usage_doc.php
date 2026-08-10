<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Plugin;

$plugin = Plugin::where('shortcode', 'brands-2026')->first() 
       ?? Plugin::where('filename', 'brands_2026')->first();

if ($plugin) {
    $newUsage = '<p>Add <strong>[plugin:brands-2026]</strong> to any page. Options: <code>display=slider|grid|list</code>, <code>max=12</code>, <code>cols=4</code>, <code>header="Featured Brands"</code>, <code>autoplay=on|off</code>, <code>show_label=1|0</code> (show/hide text brand name under logo), <code>show_navigation=1|0</code> (show/hide slider prev/next arrows), <code>show_pagination=1|0</code> (show/hide slider pagination dots).</p>';
    
    $plugin->usage_instructions = $newUsage;
    $plugin->save();

    echo "Successfully updated usage_instructions for Brands Display (2026).\n";
} else {
    echo "Plugin not found!\n";
}
