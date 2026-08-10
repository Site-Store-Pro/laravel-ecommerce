<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\CmsSetting;
use App\Services\HeaderFooterCssManager;

echo "Current CmsSetting sticky_body_offset: " . (CmsSetting::get('sticky_body_offset', '0px')) . "\n";
echo "HeaderFooterCssManager default sticky_body_offset: " . (HeaderFooterCssManager::getDefaultVariables()['sticky_body_offset']) . "\n";
