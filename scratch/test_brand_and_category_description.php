<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Brand;
use App\Models\Category;
use App\Livewire\ShopCatalog;

$brand = Brand::first();
$category = Category::first();

echo "=== BRAND DESCRIPTION CHECK ===\n";
if ($brand) {
    echo "Brand: {$brand->name}\n";
    $catalog = new ShopCatalog();
    $catalog->brand = $brand->slug;
    $view = $catalog->render();
    $data = $view->getData();
    echo "Page Description: " . var_export($data['pageDescription'], true) . "\n";
}

echo "\n=== CATEGORY DESCRIPTION CHECK ===\n";
if ($category) {
    echo "Category: {$category->name}\n";
    $catalog2 = new ShopCatalog();
    $catalog2->category = $category->slug;
    $view2 = $catalog2->render();
    $data2 = $view2->getData();
    echo "Page Description: " . var_export($data2['pageDescription'], true) . "\n";
}
