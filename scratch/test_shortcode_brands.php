<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\ContentParserService;

$shortcodeWithOverrides = '[plugin:brands-2026 show_navigation=0 show_pagination=false]';
$parsedHtml = ContentParserService::parse($shortcodeWithOverrides);

echo "=== SHORTCODE PARSE RESULT ===\n";
echo "Has prev arrow element: " . (str_contains($parsedHtml, '<div class="brands-swiper-prev"') ? 'YES (FAIL)' : 'NO (PASS)') . "\n";
echo "Has pagination element: " . (str_contains($parsedHtml, '<div class="swiper-pagination"') ? 'YES (FAIL)' : 'NO (PASS)') . "\n";
echo "Has navigation config in JS: " . (str_contains($parsedHtml, 'navigation:') ? 'YES (FAIL)' : 'NO (PASS)') . "\n";
