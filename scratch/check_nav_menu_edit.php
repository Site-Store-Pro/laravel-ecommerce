<?php

$full = __DIR__ . '/../resources/views/livewire/admin-nav-menu-edit.blade.php';
if (file_exists($full)) {
    $lines = explode("\n", file_get_contents($full));
    foreach ($lines as $idx => $l) {
        if (str_contains($l, 'sticky')) {
            echo "L" . ($idx + 1) . ": " . trim($l) . "\n";
        }
    }
}
