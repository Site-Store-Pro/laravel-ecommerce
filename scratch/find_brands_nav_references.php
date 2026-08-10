<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/../resources/views');
$iterator = new RecursiveIteratorIterator($dir);

foreach ($iterator as $file) {
    if ($file->isFile() && str_contains($file->getPathname(), 'nav')) {
        $content = file_get_contents($file->getPathname());
        if (str_contains($content, 'description') || str_contains($content, 'brand')) {
            echo "VIEW: " . $file->getPathname() . "\n";
        }
    }
}
