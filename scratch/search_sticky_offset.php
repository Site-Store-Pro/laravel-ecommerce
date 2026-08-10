<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/../resources');
$iterator = new RecursiveIteratorIterator($dir);

foreach ($iterator as $file) {
    if ($file->isFile()) {
        $content = file_get_contents($file->getPathname());
        if (str_contains($content, 'sticky_body_offset') || str_contains($content, 'sticky_offset') || str_contains($content, 'nav-dynamic')) {
            echo "VIEW: " . $file->getPathname() . "\n";
        }
    }
}

$dir2 = new RecursiveDirectoryIterator(__DIR__ . '/../app');
$iterator2 = new RecursiveIteratorIterator($dir2);

foreach ($iterator2 as $file) {
    if ($file->isFile()) {
        $content = file_get_contents($file->getPathname());
        if (str_contains($content, 'sticky_body_offset') || str_contains($content, 'header_sticky')) {
            echo "APP: " . $file->getPathname() . "\n";
        }
    }
}
