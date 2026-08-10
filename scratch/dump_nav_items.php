<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\NavItem;

$items = NavItem::all();
foreach ($items as $i) {
    echo "ID: {$i->id} | MenuID: {$i->menu_id} | ParentID: {$i->parent_id} | Type: {$i->item_type} | Label: {$i->label} | PageID: {$i->cms_page_id} | URL: {$i->url}\n";
}
