<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/..');
$iterator = new RecursiveIteratorIterator($dir);

foreach ($iterator as $file) {
    if ($file->isFile() && 
        !str_contains($file->getPathname(), 'vendor') && 
        !str_contains($file->getPathname(), 'storage') && 
        !str_contains($file->getPathname(), 'node_modules') && 
        !str_contains($file->getPathname(), '.git') && 
        !str_contains($file->getPathname(), 'scratch')
    ) {
        $content = file_get_contents($file->getPathname());
        if (str_contains(strtolower($file->getPathname()), 'brand') && (str_contains($content, 'swiper') || str_contains($content, 'slider') || str_contains($content, 'plugin'))) {
            echo $file->getPathname() . "\n";
        }
    }
}
