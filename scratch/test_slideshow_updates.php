<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\CmsSlide;

$slide = new CmsSlide([
    'Title' => 'Test Slide',
    'cdn_image' => 'https://example.com/direct-url-image.jpg',
    'slide_alignment' => 'top-left',
]);

echo "Slide Alignment: " . $slide->slide_alignment . "\n";
echo "Desktop Image URL: " . $slide->desktopImageUrl() . "\n";
echo "Thumbnail URL (fallback to direct URL): " . $slide->thumbnailUrl() . "\n";
