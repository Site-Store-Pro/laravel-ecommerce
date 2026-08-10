<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Plugin;
use App\Plugins\Display\BrandsPlugin;

$pluginModel = new Plugin();
$brandsPlugin = new BrandsPlugin();

$renderedHtml = $brandsPlugin->render(['display' => 'slider'], $pluginModel);
echo "Rendered HTML snippet:\n";
echo substr($renderedHtml, 0, 500) . "\n";
