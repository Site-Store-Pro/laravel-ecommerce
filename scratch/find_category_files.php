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
        $path = strtolower($file->getPathname());
        if (str_contains($path, 'categor') || str_contains($path, 'unsplash')) {
            echo $file->getPathname() . "\n";
        }
    }
}
