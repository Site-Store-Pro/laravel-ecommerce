<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\NavMenu;
use App\Models\NavItem;
use App\Services\NavItemRenderer;

$menu = NavMenu::find(1);
if ($menu) {
    $items = NavItem::where('menu_id', $menu->id)->whereNull('parent_id')->orderBy('position')->with('children')->get();
    echo "Found menu: " . $menu->name . " with " . $items->count() . " top-level items\n";
    $renderer = app(NavItemRenderer::class);
    
    foreach ($items as $item) {
        echo "- Item [{$item->id}] '{$item->label}' (type: {$item->item_type}), children count: " . $item->children->count() . "\n";
        foreach ($item->children as $child) {
            $cr = $renderer->resolveLink($child);
            echo "   ↳ Sub-item [{$child->id}] raw label: '{$child->label}', resolved label: '{$cr['label']}', href: '{$cr['href']}'\n";
        }
    }
}
