<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\Plugin;
use App\Plugins\Display\CategoriesPlugin;

// Fetch or create a dummy category for testing
$category = Category::whereNull('parent_id')->first();

if (!$category) {
    echo "No top-level category found in DB for verification.\n";
    exit(0);
}

echo "Testing Category: " . $category->name . "\n";
echo "Initial display_label_in_plugins: " . var_export($category->display_label_in_plugins, true) . "\n";
echo "Initial display_image_in_plugins: " . var_export($category->display_image_in_plugins, true) . "\n";

$plugin = new Plugin(['settings' => []]);
$categoriesPlugin = new CategoriesPlugin();

$html = $categoriesPlugin->render([], $plugin);
echo "\n--- Plugin Output Sample ---\n";
echo substr(strip_tags($html, '<img><span><a>'), 0, 500) . "\n";
echo "Has unsplash link: " . (str_contains($html, 'unsplash') ? 'YES (FAIL)' : 'NO (PASS)') . "\n";
