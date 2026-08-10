<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$items = \App\Models\NavItem::all(['id', 'parent_id', 'label', 'item_type']);
foreach ($items as $item) {
    echo "ID: {$item->id} | Parent: " . var_export($item->parent_id, true) . " | Label: {$item->label} | Type: {$item->item_type}\n";
}
