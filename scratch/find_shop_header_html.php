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
        if (str_contains($content, 'shop_header_custom_html')) {
            echo $file->getPathname() . "\n";
            $lines = explode("\n", $content);
            foreach ($lines as $idx => $line) {
                if (str_contains($line, 'shop_header_custom_html')) {
                    echo "  line " . ($idx + 1) . ": " . trim($line) . "\n";
                }
            }
        }
    }
}
