<?php

foreach (glob(__DIR__ . '/../resources/views/plugins/display/*.blade.php') as $file) {
    echo "VIEW: " . basename($file) . "\n";
    $content = file_get_contents($file);
    $lines = explode("\n", $content);
    foreach ($lines as $idx => $line) {
        if (str_contains($line, 'class=') && (str_contains($line, 'btn') || str_contains($line, 'button') || str_contains($line, 'fi-btn'))) {
            echo "  L" . ($idx + 1) . ": " . trim($line) . "\n";
        }
    }
}
