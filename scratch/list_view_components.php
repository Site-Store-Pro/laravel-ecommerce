<?php

$files = glob(__DIR__ . '/../resources/views/components/*.blade.php');
foreach ($files as $f) {
    echo "COMPONENT: " . basename($f) . "\n";
}
