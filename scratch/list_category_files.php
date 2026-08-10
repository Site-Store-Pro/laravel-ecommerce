<?php

echo "=== APP/PLUGINS/DISPLAY ===\n";
foreach (glob(__DIR__ . '/../app/Plugins/Display/*.php') as $f) {
    echo "  " . basename($f) . "\n";
}

echo "\n=== SEARCHING FOR UNSPLASH IN PROJECT ===\n";
$dir = new RecursiveDirectoryIterator(__DIR__ . '/../app');
$iterator = new RecursiveIteratorIterator($dir);
foreach ($iterator as $file) {
    if ($file->isFile()) {
        $content = file_get_contents($file->getPathname());
        if (str_contains($content, 'unsplash')) {
            echo "  FOUND UNSPLASH IN: " . $file->getPathname() . "\n";
        }
    }
}

$dir2 = new RecursiveDirectoryIterator(__DIR__ . '/../resources');
$iterator2 = new RecursiveIteratorIterator($dir2);
foreach ($iterator2 as $file) {
    if ($file->isFile()) {
        $content = file_get_contents($file->getPathname());
        if (str_contains($content, 'unsplash')) {
            echo "  FOUND UNSPLASH IN: " . $file->getPathname() . "\n";
        }
    }
}
