<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\CmsSlide;

$slides = CmsSlide::all();
echo "Found " . $slides->count() . " slides:\n";
foreach ($slides as $s) {
    echo "Slide #{$s->ImageID}:\n";
    echo "  - slide_heading_css: " . var_export($s->slide_heading_css ?? null, true) . "\n";
    echo "  - slide_content_css: " . var_export($s->slide_content_css ?? null, true) . "\n";
}
