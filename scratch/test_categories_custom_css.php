<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Plugin;
use App\Plugins\Display\CategoriesPlugin;

$plugin = Plugin::where('shortcode', 'categories-2026')->first() 
       ?? Plugin::where('filename', 'categories_2026')->first();

$catPlugin = new CategoriesPlugin();

// Test 1: Rendering with DB Custom CSS
$htmlDb = $catPlugin->render([], $plugin);

// Test 2: Rendering with Shortcode Custom CSS Override
$htmlShortcode = $catPlugin->render(['custom_css' => '.categories-plugin-card { border-color: red !important; }'], $plugin);

echo "=== TEST 1: DB CUSTOM CSS ===\n";
echo "Has <style> block: " . (str_contains($htmlDb, '<style>') ? 'YES (PASS)' : 'NO (FAIL)') . "\n";
echo "Has wrapper class: " . (str_contains($htmlDb, 'categories-plugin-wrapper') ? 'YES (PASS)' : 'NO (FAIL)') . "\n";
echo "Has card class: " . (str_contains($htmlDb, 'categories-plugin-card') ? 'YES (PASS)' : 'NO (FAIL)') . "\n";

echo "\n=== TEST 2: SHORTCODE OVERRIDE CSS ===\n";
echo "Contains custom override CSS: " . (str_contains($htmlShortcode, 'border-color:red!important') || str_contains($htmlShortcode, 'border-color: red !important') ? 'YES (PASS)' : 'NO (FAIL)') . "\n";
