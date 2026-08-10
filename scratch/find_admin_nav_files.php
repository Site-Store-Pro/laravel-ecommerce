<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/../resources/views');
$iterator = new RecursiveIteratorIterator($dir);

foreach ($iterator as $file) {
    if ($file->isFile()) {
        $path = strtolower($file->getPathname());
        if (str_contains($path, 'nav') || str_contains($path, 'header')) {
            echo $file->getPathname() . "\n";
        }
    }
}
