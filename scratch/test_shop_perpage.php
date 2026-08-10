<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Livewire\ShopCatalog;

$component = new ShopCatalog();
echo "Default perPage property: " . $component->perPage . "\n";

$component->perPage = 999;
$component->sanitizePerPage();
echo "Sanitized invalid perPage (999): " . $component->perPage . "\n";

$component->perPage = 48;
$component->sanitizePerPage();
echo "Sanitized valid perPage (48): " . $component->perPage . "\n";
