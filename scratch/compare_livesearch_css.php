<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Http\Kernel::class)->bootstrap();

$plugin = \App\Models\Plugin::where('shortcode', 'live-search-2026')->first();
$defaultCssRow = \DB::table('plugin_settings')->where('plugin_id', $plugin->id)->where('field_name', 'default_css')->first();
$customCssRow  = \DB::table('plugin_settings')->where('plugin_id', $plugin->id)->where('field_name', 'custom_css')->first();

$dc = trim($defaultCssRow->field_value ?? '');
$cc = trim($customCssRow->field_value ?? '');

echo "default_css length: " . strlen($dc) . "\n";
echo "custom_css length:  " . strlen($cc) . "\n";
echo "Are equal: " . ($dc === $cc ? 'YES' : 'NO') . "\n";

// Show first difference
for ($i = 0; $i < min(strlen($dc), strlen($cc)); $i++) {
    if ($dc[$i] !== $cc[$i]) {
        echo "First diff at char $i: dc='" . ord($dc[$i]) . "' cc='" . ord($cc[$i]) . "'\n";
        echo "dc context: " . substr($dc, max(0, $i-10), 30) . "\n";
        echo "cc context: " . substr($cc, max(0, $i-10), 30) . "\n";
        break;
    }
}
