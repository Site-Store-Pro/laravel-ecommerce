<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\View;

if (View::exists('livewire.public-header') && View::exists('components.nav-dynamic')) {
    echo "public-header and nav-dynamic exist and compile cleanly.\n";
}
