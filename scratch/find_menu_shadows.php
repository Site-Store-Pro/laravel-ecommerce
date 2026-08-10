<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/../resources/views');
$iterator = new RecursiveIteratorIterator($dir);

foreach ($iterator as $file) {
    if ($file->isFile()) {
        $name = strtolower($file->getFilename());
        if (str_contains($name, 'category') || str_contains($name, 'brand') || str_contains($name, 'nav')) {
            $content = file_get_contents($file->getPathname());
            if (str_contains($content, 'shadow') || str_contains($content, 'glow') || str_contains($content, 'box-shadow')) {
                echo $file->getPathname() . "\n";
                $lines = explode("\n", $content);
                foreach ($lines as $idx => $line) {
                    if (str_contains($line, 'shadow') || str_contains($line, 'glow') || str_contains($line, 'box-shadow')) {
                        echo "  line " . ($idx + 1) . ": " . trim($line) . "\n";
                    }
                }
            }
        }
    }
}
