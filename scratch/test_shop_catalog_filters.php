<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Livewire\ShopCatalog;

$catalog = new ShopCatalog();
echo "Default hasActiveFilters: " . ($catalog->getHasActiveFiltersProperty() ? 'true' : 'false') . "\n";

$catalog->search = 'test';
echo "With search filter active, hasActiveFilters: " . ($catalog->getHasActiveFiltersProperty() ? 'true' : 'false') . "\n";
