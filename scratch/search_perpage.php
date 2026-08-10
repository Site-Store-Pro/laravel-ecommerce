<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/../app');
$iterator = new RecursiveIteratorIterator($dir);

foreach ($iterator as $file) {
    if ($file->isFile()) {
        $content = file_get_contents($file->getPathname());
        if (str_contains($content, 'perPage')) {
            echo "APP: " . $file->getPathname() . "\n";
        }
    }
}

$dir2 = new RecursiveDirectoryIterator(__DIR__ . '/../resources');
$iterator2 = new RecursiveIteratorIterator($dir2);

foreach ($iterator2 as $file) {
    if ($file->isFile()) {
        $content = file_get_contents($file->getPathname());
        if (str_contains($content, 'perPage')) {
            echo "VIEW: " . $file->getPathname() . "\n";
        }
    }
}
