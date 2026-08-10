<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/../resources/views');
$iterator = new RecursiveIteratorIterator($dir);

foreach ($iterator as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $content = file_get_contents($file->getPathname());
        if (str_contains($content, 'top_nav_sticky') || str_contains($content, 'sticky_body_offset') || str_contains($content, 'offset') || str_contains($content, 'Sticky')) {
            $lines = explode("\n", $content);
            foreach ($lines as $idx => $line) {
                if (stripos($line, 'sticky') !== false || stripos($line, 'offset') !== false) {
                    echo $file->getFilename() . " line " . ($idx + 1) . ": " . trim($line) . "\n";
                }
            }
        }
    }
}
