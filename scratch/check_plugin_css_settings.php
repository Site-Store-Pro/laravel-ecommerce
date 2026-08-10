<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Http\Kernel::class)->bootstrap();

$plugins = ['slideshow-2026', 'live-search-2026'];
foreach ($plugins as $slug) {
    $plugin = \App\Models\Plugin::where('shortcode', $slug)->first();
    if (!$plugin) { echo "[$slug] NOT FOUND\n"; continue; }
    echo "[$slug] id={$plugin->id}\n";
    $settings = $plugin->settings()->get();
    foreach ($settings as $s) {
        $val = substr($s->field_value ?? '', 0, 120);
        echo "  {$s->field_name} = " . (strlen($s->field_value ?? '') > 0 ? "[".strlen($s->field_value)." chars]: $val" : "(empty)") . "\n";
    }
    echo "  getSettings() custom_css = '" . substr($plugin->getSettings()['custom_css'] ?? '(key missing)', 0, 80) . "'\n\n";
}
