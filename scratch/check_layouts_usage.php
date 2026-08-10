<?php

$files = [
    __DIR__ . '/../resources/views/pages/cms.blade.php',
    __DIR__ . '/../resources/views/pages/home.blade.php',
    __DIR__ . '/../resources/views/layouts/public.blade.php',
    __DIR__ . '/../resources/views/layouts/app.blade.php',
];

foreach ($files as $f) {
    if (file_exists($f)) {
        echo "FILE: " . basename($f) . "\n";
        $content = file_get_contents($f);
        if (str_contains($content, 'show-cart-modal')) {
            echo "  HAS show-cart-modal modal: YES\n";
        } else {
            echo "  HAS show-cart-modal modal: NO\n";
        }
    }
}
