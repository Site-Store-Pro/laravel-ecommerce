<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Http\Kernel::class)->bootstrap();

// Clear the custom_css for live-search-2026 since it was wrongly pre-populated
// with default CSS content. The user's override field should start empty.
$plugin = \App\Models\Plugin::where('shortcode', 'live-search-2026')->first();

if (!$plugin) {
    echo "Plugin not found!\n";
    exit(1);
}

$affected = \DB::table('plugin_settings')
    ->where('plugin_id', $plugin->id)
    ->where('field_name', 'custom_css')
    ->update(['field_value' => '']);

echo "Updated $affected row(s). custom_css is now empty.\n";

// Verify
$row = \DB::table('plugin_settings')->where('plugin_id', $plugin->id)->where('field_name', 'custom_css')->first();
echo "New value: '" . $row->field_value . "'\n";
