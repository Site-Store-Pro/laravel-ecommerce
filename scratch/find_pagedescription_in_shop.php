<?php

$full = file_get_contents(__DIR__ . '/../app/Livewire/ShopCatalog.php');
$lines = explode("\n", $full);
foreach ($lines as $idx => $line) {
    if (str_contains($line, 'pageDescription') || str_contains($line, 'page_description') || str_contains($line, 'curated catalog') || str_contains($line, 'getPageDescription')) {
        echo "L" . ($idx + 1) . ": " . trim($line) . "\n";
    }
}
