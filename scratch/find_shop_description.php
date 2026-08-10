<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/../resources/views');
$iterator = new RecursiveIteratorIterator($dir);

foreach ($iterator as $file) {
    if ($file->isFile()) {
        $content = file_get_contents($file->getPathname());
        if (str_contains($content, 'curated catalog') || str_contains($content, 'wholesale pricing')) {
            echo "VIEW MATCH: " . $file->getPathname() . "\n";
        }
    }
}

$dir2 = new RecursiveDirectoryIterator(__DIR__ . '/../app');
$iterator2 = new RecursiveIteratorIterator($dir2);

foreach ($iterator2 as $file) {
    if ($file->isFile()) {
        $content = file_get_contents($file->getPathname());
        if (str_contains($content, 'curated catalog') || str_contains($content, 'wholesale pricing')) {
            echo "APP MATCH: " . $file->getPathname() . "\n";
        }
    }
}
