<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/../resources');
$iterator = new RecursiveIteratorIterator($dir);

echo "=== VIEW SEARCH ===\n";
foreach ($iterator as $file) {
    if ($file->isFile()) {
        $content = file_get_contents($file->getPathname());
        if (str_contains($content, 'sticky_body_offset') || str_contains($content, 'css_var_sticky_body_offset')) {
            echo "VIEW: " . $file->getPathname() . "\n";
        }
    }
}

$dir2 = new RecursiveDirectoryIterator(__DIR__ . '/../app');
$iterator2 = new RecursiveIteratorIterator($dir2);

echo "\n=== APP SEARCH ===\n";
foreach ($iterator2 as $file) {
    if ($file->isFile()) {
        $content = file_get_contents($file->getPathname());
        if (str_contains($content, 'sticky_body_offset') || str_contains($content, 'css_var_sticky_body_offset')) {
            echo "APP: " . $file->getPathname() . "\n";
        }
    }
}
