<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== SEARCHING DATABASE CMS SETTINGS ===\n";
if (\Illuminate\Support\Facades\Schema::hasTable('cms_settings')) {
    $settings = DB::table('cms_settings')->get();
    foreach ($settings as $s) {
        $key = $s->key ?? $s->setting_key ?? $s->name ?? '';
        $val = $s->value ?? $s->setting_value ?? '';
        if (str_contains(strtolower($key), 'padding') || str_contains(strtolower($val), 'padding') || str_contains(strtolower($val), '25px') || str_contains(strtolower($key), 'header')) {
            echo "Key: {$key} = Value: {$val}\n";
        }
    }
}

echo "\n=== SEARCHING CODEBASE FOR PADDING-TOP ===\n";
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
        if (stripos($content, 'padding-top') !== false || stripos($content, 'padding') !== false) {
            $lines = explode("\n", $content);
            foreach ($lines as $idx => $line) {
                if (stripos($line, 'padding') !== false && (stripos($line, 'important') !== false || stripos($line, '25') !== false)) {
                    echo $file->getPathname() . " line " . ($idx + 1) . ": " . trim($line) . "\n";
                }
            }
        }
    }
}
