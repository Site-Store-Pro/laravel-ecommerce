<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/../app/Plugins');
$iterator = new RecursiveIteratorIterator($dir);

foreach ($iterator as $file) {
    if ($file->isFile()) {
        echo $file->getPathname() . "\n";
        $content = file_get_contents($file->getPathname());
        if (str_contains($content, 'swiper') || str_contains($content, 'slider') || str_contains($content, 'border') || str_contains($content, 'shadow')) {
            echo "  --> MATCHES slider/style: " . $file->getFilename() . "\n";
        }
    }
}
