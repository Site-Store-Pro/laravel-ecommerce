<?php

$full = __DIR__ . '/../resources/views/livewire/layout/navigation.blade.php';
$lines = explode("\n", file_get_contents($full));
foreach ($lines as $idx => $l) {
    if (str_contains($l, 'h-full')) {
        echo "L" . ($idx + 1) . ": " . trim($l) . "\n";
    }
}
