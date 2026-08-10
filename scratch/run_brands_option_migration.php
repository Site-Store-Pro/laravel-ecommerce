<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Plugin;
use App\Models\PluginOption;

$plugin = Plugin::where('shortcode', 'brands-2026')->first()
       ?? Plugin::where('filename', 'brands_2026')->first();

if ($plugin) {
    PluginOption::updateOrCreate(
        ['plugin_id' => $plugin->id, 'field_name' => 'show_navigation'],
        [
            'field_label'         => 'Show Navigation Arrows',
            'field_type'          => 'checkbox',
            'field_default_value' => '1',
            'sort_order'          => 7,
        ]
    );

    PluginOption::updateOrCreate(
        ['plugin_id' => $plugin->id, 'field_name' => 'show_pagination'],
        [
            'field_label'         => 'Show Pagination Dots',
            'field_type'          => 'checkbox',
            'field_default_value' => '1',
            'sort_order'          => 8,
        ]
    );

    echo "Successfully upserted show_navigation and show_pagination for Brands plugin.\n";
} else {
    echo "Brands plugin not found.\n";
}
