<?php

echo "=== APP/LIVEWIRE CATEGORY FILES ===\n";
foreach (glob(__DIR__ . '/../app/Livewire/*Cat*.php') as $f) {
    echo "  " . basename($f) . "\n";
}

echo "\n=== RESOURCES/VIEWS/LIVEWIRE CATEGORY FILES ===\n";
foreach (glob(__DIR__ . '/../resources/views/livewire/*cat*.php') as $f) {
    echo "  " . basename($f) . "\n";
}
