<?php

$files = [
    'app/Services/HeaderFooterCssManager.php',
    'app/Livewire/AdminNavMenuEdit.php',
    'app/Models/NavMenu.php',
    'resources/views/components/nav-dynamic.blade.php',
    'resources/views/layouts/public.blade.php',
    'resources/views/pages/cms.blade.php',
    'resources/views/pages/cms-category.blade.php',
    'resources/views/pages/home.blade.php',
    'resources/views/livewire/admin-header-footer-builder.blade.php',
];

foreach ($files as $rel) {
    $full = __DIR__ . '/../' . $rel;
    if (file_exists($full)) {
        echo "=== $rel ===\n";
        $lines = explode("\n", file_get_contents($full));
        foreach ($lines as $idx => $l) {
            if (str_contains($l, 'sticky') || str_contains($l, 'offset')) {
                echo "  L" . ($idx + 1) . ": " . trim($l) . "\n";
            }
        }
    }
}
