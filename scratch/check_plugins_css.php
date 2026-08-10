<?php

foreach (glob(__DIR__ . '/../app/Plugins/Display/*.php') as $file) {
    echo "=== " . basename($file) . " ===\n";
    $lines = explode("\n", file_get_contents($file));
    foreach ($lines as $idx => $l) {
        if (str_contains($l, 'default_css') || str_contains($l, 'custom_css') || str_contains($l, 'cssHtml') || str_contains($l, '<style>')) {
            echo "  L" . ($idx + 1) . ": " . trim($l) . "\n";
        }
    }
}
