<?php

$files = [
    'app/Models/CmsSlideshow.php',
    'app/Models/CmsSlide.php',
    'app/Livewire/AdminSlideshows.php',
    'app/Livewire/AdminSlideshowEdit.php',
    'app/Plugins/Display/SlideshowPlugin.php',
    'resources/views/livewire/admin-slideshows.blade.php',
    'resources/views/livewire/admin-slideshow-edit.blade.php',
];

foreach ($files as $f) {
    $full = __DIR__ . '/../' . $f;
    if (file_exists($full)) {
        echo "EXISTS: {$f}\n";
    }
}

echo "\n--- SEARCHING APP/LIVEWIRE ---\n";
foreach (glob(__DIR__ . '/../app/Livewire/*Slide*.php') as $f) {
    echo "  " . basename($f) . "\n";
}

echo "\n--- SEARCHING APP/MODELS ---\n";
foreach (glob(__DIR__ . '/../app/Models/*Slide*.php') as $f) {
    echo "  " . basename($f) . "\n";
}

echo "\n--- SEARCHING RESOURCES/VIEWS/LIVEWIRE ---\n";
foreach (glob(__DIR__ . '/../resources/views/livewire/*slide*.php') as $f) {
    echo "  " . basename($f) . "\n";
}
