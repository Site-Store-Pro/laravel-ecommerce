<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/../resources/views');
$iterator = new RecursiveIteratorIterator($dir);

foreach ($iterator as $file) {
    if ($file->isFile()) {
        $content = file_get_contents($file->getPathname());
        if (str_contains($content, 'brand') && str_contains($content, 'description')) {
            echo "VIEW HAS BRAND & DESCRIPTION: " . $file->getPathname() . "\n";
            $lines = explode("\n", $content);
            foreach ($lines as $idx => $line) {
                if (str_contains(strtolower($line), 'brand') && str_contains(strtolower($line), 'description')) {
                    echo "  L" . ($idx + 1) . ": " . trim($line) . "\n";
                }
            }
        }
    }
}

$dir2 = new RecursiveDirectoryIterator(__DIR__ . '/../app');
$iterator2 = new RecursiveIteratorIterator($dir2);

foreach ($iterator2 as $file) {
    if ($file->isFile()) {
        $content = file_get_contents($file->getPathname());
        if (str_contains($content, 'brand') && str_contains($content, 'description')) {
            echo "APP HAS BRAND & DESCRIPTION: " . $file->getPathname() . "\n";
            $lines = explode("\n", $content);
            foreach ($lines as $idx => $line) {
                if (str_contains(strtolower($line), 'brand') && str_contains(strtolower($line), 'description')) {
                    echo "  L" . ($idx + 1) . ": " . trim($line) . "\n";
                }
            }
        }
    }
}
