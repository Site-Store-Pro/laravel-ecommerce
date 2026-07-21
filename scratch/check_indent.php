<?php
$f = file('C:/Sites/laravel-gemini/resources/views/livewire/admin-cms-page-edit.blade.php');
foreach ([184, 185, 241, 250, 307, 316] as $i) {
    $line = rtrim($f[$i], "\r\n");
    $spaces = strlen($line) - strlen(ltrim($line));
    echo "L" . ($i+1) . " (indent={$spaces}): " . substr($line, 0, 80) . "\n";
}
