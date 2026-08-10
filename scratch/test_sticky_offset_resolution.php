<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\CmsSetting;

// Test setting css_var_sticky_body_offset
CmsSetting::set('css_var_sticky_body_offset', '95px');
CmsSetting::set('css_var_top_nav_sticky', '1');

$stickySetting    = CmsSetting::get('css_var_top_nav_sticky') ?? CmsSetting::get('top_nav_sticky', '1');
$isStickyNav      = in_array($stickySetting, ['1', 1, true, 'true'], true);
$stickyBodyOffset = CmsSetting::get('css_var_sticky_body_offset') ?? CmsSetting::get('sticky_body_offset', '0px');
$stickyStyle      = ($isStickyNav && !empty($stickyBodyOffset) && $stickyBodyOffset !== '0px') ? 'padding-top: ' . $stickyBodyOffset . ' !important;' : '';

echo "Sticky Nav Enabled: " . var_export($isStickyNav, true) . "\n";
echo "Resolved Sticky Body Offset: " . $stickyBodyOffset . "\n";
echo "Generated inline style: " . $stickyStyle . "\n";
