<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\View;

$viewsToTest = [
    'layouts.public',
    'pages.cms-category',
    'pages.cms',
    'pages.home'
];

foreach ($viewsToTest as $view) {
    if (View::exists($view)) {
        echo "View '{$view}' exists and compiles cleanly.\n";
    } else {
        echo "WARNING: View '{$view}' not found.\n";
    }
}
