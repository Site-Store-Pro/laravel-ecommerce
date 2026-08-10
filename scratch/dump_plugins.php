<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Plugin;

foreach (Plugin::all() as $p) {
    if (str_contains($p->name, 'Live Search')) {
        echo "Found: " . $p->name . "\n";
        print_r($p->toArray());
    }
}
