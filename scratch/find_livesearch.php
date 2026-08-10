<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/../app/Plugins');
$iterator = new RecursiveIteratorIterator($dir);

foreach ($iterator as $file) {
    if ($file->isFile() && str_contains(strtolower($file->getPathname()), 'search')) {
        echo "APP PLUGIN: " . $file->getPathname() . "\n";
    }
}

$dir2 = new RecursiveDirectoryIterator(__DIR__ . '/../resources/views/plugins');
$iterator2 = new RecursiveIteratorIterator($dir2);

foreach ($iterator2 as $file) {
    if ($file->isFile() && str_contains(strtolower($file->getPathname()), 'search')) {
        echo "VIEW PLUGIN: " . $file->getPathname() . "\n";
    }
}
