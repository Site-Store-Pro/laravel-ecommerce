<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Plugin;

$plugin = Plugin::where('shortcode', 'slideshow-2026')->first()
       ?? Plugin::where('filename', 'slideshow_2026')->first();

if ($plugin) {
    echo "Slideshow Plugin ID: {$plugin->id}\n";
    foreach ($plugin->options as $opt) {
        echo "Option: {$opt->field_name}\n";
        if (str_contains($opt->field_default_value, 'shadow') || str_contains($opt->field_default_value, 'text-shadow')) {
            echo "  Default Value HAS SHADOW:\n" . $opt->field_default_value . "\n";
        }
    }
}

// Search files for text-shadow in slideshow
$files = [
    __DIR__ . '/../resources/views/plugins/display/slideshow.blade.php',
    __DIR__ . '/../app/Plugins/Display/SlideshowPlugin.php',
    __DIR__ . '/../resources/css/app.css',
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (str_contains($content, 'text-shadow') || str_contains($content, 'shadow')) {
            echo "\nFILE MATCH: {$file}\n";
            $lines = explode("\n", $content);
            foreach ($lines as $idx => $line) {
                if (str_contains($line, 'shadow')) {
                    echo "  L" . ($idx + 1) . ": " . trim($line) . "\n";
                }
            }
        }
    }
}
