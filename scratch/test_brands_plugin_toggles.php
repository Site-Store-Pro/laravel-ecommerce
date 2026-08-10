<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Plugin;
use App\Plugins\Display\BrandsPlugin;

$plugin = Plugin::where('shortcode', 'brands-2026')->first() 
       ?? Plugin::where('filename', 'brands_2026')->first();

$brandsPlugin = new BrandsPlugin();

// Test 1: Enabled Toggles
$htmlEnabled = $brandsPlugin->render([
    'show_navigation' => 'true',
    'show_pagination' => 'true',
], $plugin);

// Test 2: Disabled Toggles
$htmlDisabled = $brandsPlugin->render([
    'show_navigation' => 'false',
    'show_pagination' => 'false',
], $plugin);

echo "=== TEST 1: Navigation & Pagination ENABLED ===\n";
echo "Has prev arrow element: " . (str_contains($htmlEnabled, '<div class="brands-swiper-prev"') ? 'YES (PASS)' : 'NO (FAIL)') . "\n";
echo "Has pagination element: " . (str_contains($htmlEnabled, '<div class="swiper-pagination"') ? 'YES (PASS)' : 'NO (FAIL)') . "\n";
echo "Has navigation config in JS: " . (str_contains($htmlEnabled, 'navigation:') ? 'YES (PASS)' : 'NO (FAIL)') . "\n";

echo "\n=== TEST 2: Navigation & Pagination DISABLED ===\n";
echo "Has prev arrow element: " . (str_contains($htmlDisabled, '<div class="brands-swiper-prev"') ? 'YES (FAIL)' : 'NO (PASS)') . "\n";
echo "Has pagination element: " . (str_contains($htmlDisabled, '<div class="swiper-pagination"') ? 'YES (FAIL)' : 'NO (PASS)') . "\n";
echo "Has navigation config in JS: " . (str_contains($htmlDisabled, 'navigation:') ? 'YES (FAIL)' : 'NO (PASS)') . "\n";
