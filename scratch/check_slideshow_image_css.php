<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$rows = DB::table('cms_slideshow_images')->get();
echo "Found " . $rows->count() . " rows in cms_slideshow_images:\n";
foreach ($rows as $r) {
    echo "Slide #{$r->ImageID}:\n";
    echo "  - slide_heading_css: " . var_export($r->slide_heading_css ?? null, true) . "\n";
    echo "  - slide_content_css: " . var_export($r->slide_content_css ?? null, true) . "\n";
}
