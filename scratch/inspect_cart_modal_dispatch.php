<?php

$files = [
    __DIR__ . '/../app/Livewire/ShopCatalog.php',
    __DIR__ . '/../app/Livewire/ProductDetails.php',
    __DIR__ . '/../app/Livewire/FeaturedItemsWidget.php',
    __DIR__ . '/../app/Livewire/CrossSellListWidget.php',
];

foreach ($files as $f) {
    if (file_exists($f)) {
        echo "FILE: " . basename($f) . "\n";
        $content = file_get_contents($f);
        $lines = explode("\n", $content);
        foreach ($lines as $idx => $line) {
            if (str_contains($line, 'show-cart-modal') || str_contains($line, 'cart-updated')) {
                echo "  L" . ($idx + 1) . ": " . trim($line) . "\n";
            }
        }
    }
}
