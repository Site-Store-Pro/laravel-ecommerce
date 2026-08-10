<?php

function searchDir($dir, $pattern) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isFile() && !str_contains($file->getPathname(), 'vendor') && !str_contains($file->getPathname(), 'storage') && !str_contains($file->getPathname(), 'node_modules') && !str_contains($file->getPathname(), '.git')) {
            $content = file_get_contents($file->getPathname());
            if (preg_match($pattern, $content)) {
                $lines = explode("\n", $content);
                foreach ($lines as $idx => $line) {
                    if (preg_match($pattern, $line)) {
                        echo $file->getPathname() . " line " . ($idx + 1) . ": " . trim($line) . "\n";
                    }
                }
            }
        }
    }
}

searchDir(__DIR__ . '/../resources', '/padding.*25px/i');
searchDir(__DIR__ . '/../app', '/padding.*25px/i');
searchDir(__DIR__ . '/../public', '/padding.*25px/i');
searchDir(__DIR__ . '/../resources', '/main/i');
