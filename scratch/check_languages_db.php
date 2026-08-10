<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Language;

$langs = Language::all();
echo "Languages in DB:\n";
foreach ($langs as $l) {
    echo "  - ID: {$l->id} | Code: {$l->code} | Name: {$l->name} | Active: " . ($l->is_active ? 'YES' : 'NO') . " | Default: " . ($l->is_default ? 'YES' : 'NO') . "\n";
}
