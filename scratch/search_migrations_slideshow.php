<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/../database/migrations');
$iterator = new RecursiveIteratorIterator($dir);

foreach ($iterator as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.php')) {
        $content = file_get_contents($file->getPathname());
        if (str_contains($content, 'slideshow') || str_contains($content, 'text-shadow')) {
            echo "MIGRATION MATCH: " . $file->getFilename() . "\n";
        }
    }
}
