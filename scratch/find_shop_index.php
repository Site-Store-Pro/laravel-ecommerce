<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/../resources/views');
$iterator = new RecursiveIteratorIterator($dir);

foreach ($iterator as $file) {
    if ($file->isFile()) {
        $name = strtolower($file->getFilename());
        if (str_contains($name, 'shop') || str_contains($name, 'catalog') || str_contains($name, 'product')) {
            echo $file->getPathname() . "\n";
        }
    }
}
