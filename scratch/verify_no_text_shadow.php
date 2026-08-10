<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Plugin;

$plugin = Plugin::where('shortcode', 'slideshow-2026')->first()
       ?? Plugin::where('filename', 'slideshow_2026')->first();

echo "Checking Slideshow Plugin (ID: {$plugin->id}) settings and options...\n";

foreach ($plugin->options as $opt) {
    if ($opt->field_name === 'default_css') {
        $hasShadow = str_contains($opt->field_default_value, 'text-shadow');
        echo "plugin_options default_css HAS TEXT-SHADOW: " . ($hasShadow ? 'YES (FAIL)' : 'NO (PASS)') . "\n";
    }
}

$settings = $plugin->getSettings();
$liveCss = $settings['live_css'] ?? $settings['default_css'] ?? '';
$hasShadowSettings = str_contains($liveCss, 'text-shadow');
echo "plugin_settings live_css HAS TEXT-SHADOW: " . ($hasShadowSettings ? 'YES (FAIL)' : 'NO (PASS)') . "\n";
