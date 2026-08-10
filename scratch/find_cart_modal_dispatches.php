<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/../app/Livewire');
$iterator = new RecursiveIteratorIterator($dir);

foreach ($iterator as $file) {
    if ($file->isFile()) {
        $content = file_get_contents($file->getPathname());
        if (str_contains($content, 'show-cart-modal')) {
            echo "LIVEWIRE MATCH: " . $file->getPathname() . "\n";
            $lines = explode("\n", $content);
            foreach ($lines as $idx => $line) {
                if (str_contains($line, 'show-cart-modal')) {
                    echo "  L" . ($idx + 1) . ": " . trim($line) . "\n";
                }
            }
        }
    }
}
