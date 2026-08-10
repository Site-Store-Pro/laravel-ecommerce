<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Plugin;
use App\Models\CmsSlideshow;
use App\Models\CmsSlide;
use App\Plugins\Display\SlideshowPlugin;

$slideshow = CmsSlideshow::firstOrCreate(
    ['slideshow_name' => 'Test Slideshow'],
    ['slideshow_active' => 1, 'sort_order' => 1]
);

CmsSlide::firstOrCreate(
    ['slideshow_id' => $slideshow->slideshow_id, 'Title' => 'Test Slide'],
    ['Active' => 1, 'ImageSort' => 1, 'LargeImage' => 'test.jpg']
);

$plugin = Plugin::where('shortcode', 'slideshow-2026')->first()
       ?? Plugin::where('filename', 'slideshow_2026')->first();

$slideshowPlugin = new SlideshowPlugin();

// Test rendering with custom_css parameter override
$html = $slideshowPlugin->render(['custom_css' => '.slideshow-plugin-heading { font-size: 4rem !important; }'], $plugin);

echo "=== SLIDESHOW CUSTOM CSS TEST ===\n";
echo "Has <style> block: " . (str_contains($html, '<style>') ? 'YES (PASS)' : 'NO (FAIL)') . "\n";
echo "Contains custom override CSS: " . (str_contains($html, 'font-size:4rem!important') || str_contains($html, 'font-size: 4rem !important') ? 'YES (PASS)' : 'NO (FAIL)') . "\n";
