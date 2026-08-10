<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/../resources/views');
$iterator = new RecursiveIteratorIterator($dir);

foreach ($iterator as $file) {
    if ($file->isFile()) {
        $content = file_get_contents($file->getPathname());
        if (str_contains($content, 'show-cart-modal') || str_contains($content, 'showCartModal')) {
            echo "VIEW MATCH: " . $file->getPathname() . "\n";
            $lines = explode("\n", $content);
            foreach ($lines as $idx => $line) {
                if (str_contains($line, 'show-cart-modal') || str_contains($line, 'showCartModal')) {
                    echo "  L" . ($idx + 1) . ": " . trim($line) . "\n";
                }
            }
        }
    }
}
