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
        if (str_contains($content, 'main') && str_contains($content, 'padding')) {
            $lines = explode("\n", $content);
            foreach ($lines as $idx => $line) {
                if (stripos($line, 'main') !== false && stripos($line, 'padding') !== false) {
                    echo $file->getPathname() . " line " . ($idx + 1) . ": " . trim($line) . "\n";
                }
            }
        }
    }
}
