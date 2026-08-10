<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/../resources/views');
$iterator = new RecursiveIteratorIterator($dir);

foreach ($iterator as $file) {
    if ($file->isFile()) {
        $content = file_get_contents($file->getPathname());
        if (str_contains($content, 'btn-primary') || str_contains($content, 'btn_primary') || str_contains($content, 'primary_button') || str_contains($content, 'primary-btn')) {
            echo "VIEW MATCH: " . $file->getPathname() . "\n";
            $lines = explode("\n", $content);
            foreach ($lines as $idx => $l) {
                if (str_contains($l, 'btn-primary') || str_contains($l, 'btn_primary') || str_contains($l, 'primary_button')) {
                    echo "  L" . ($idx + 1) . ": " . trim($l) . "\n";
                }
            }
        }
    }
}
