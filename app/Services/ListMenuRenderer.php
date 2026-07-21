<?php

namespace App\Services;

use App\Models\CmsListMenu;
use App\Models\CmsPage;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Plugins\Support\ShortcodeProcessor;
use Illuminate\Support\Str;

class ListMenuRenderer
{
    /**
     * Render a List Menu to HTML by its ID.
     */
    public static function render(int $id): string
    {
        $menu = CmsListMenu::with(['items' => function ($query) {
            $query->orderBy('sort_val');
        }])->find($id);

        if (!$menu) {
            return "<!-- List Menu #{$id} not found -->";
        }

        $html = '';
        if (!empty($menu->custom_css)) {
            $html .= "<style>\n" . $menu->custom_css . "\n</style>\n";
        }

        $html .= '<ul id="cms-menu-' . $menu->id . '" class="cms-list-menu">';
        foreach ($menu->items as $item) {
            $parsedContent = self::parseItemContent($item->list_item);
            $html .= '<li class="cms-list-menu-item">' . $parsedContent . '</li>';
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * Parse shortcodes inside list item content.
     */
    public static function parseItemContent(?string $content): string
    {
        if (empty($content)) {
            return '';
        }

        // Regular expression to match [type:id] or [type:id label="Text"]
        // Matches:
        // Group 1: type (page|product|category|brand)
        // Group 2: id (integer)
        // Group 3: optional label (inside quotes)
        $pattern = '/\[(page|product|category|brand):(\d+)(?:\s+label="([^"]*)")?\]/i';

        $content = preg_replace_callback($pattern, function (array $matches) {
            $type = strtolower($matches[1]);
            $id = (int) $matches[2];
            $label = $matches[3] ?? null;

            switch ($type) {
                case 'page':
                    $page = CmsPage::find($id);
                    if ($page) {
                        return '<a href="' . e(route('page.show', $page->slug)) . '">' . e($label ?: $page->title) . '</a>';
                    }
                    return '<span class="text-slate-400 font-semibold">' . e($label ?: "Page #{$id}") . '</span>';

                case 'product':
                    $product = Product::find($id);
                    if ($product) {
                        return '<a href="' . e(route('shop.product', $product->seo_slug)) . '">' . e($label ?: $product->title) . '</a>';
                    }
                    return '<span class="text-slate-400 font-semibold">' . e($label ?: "Product #{$id}") . '</span>';

                case 'category':
                    $category = Category::find($id);
                    if ($category) {
                        return '<a href="' . e(route('shop.category', $category->slug)) . '">' . e($label ?: $category->name) . '</a>';
                    }
                    return '<span class="text-slate-400 font-semibold">' . e($label ?: "Category #{$id}") . '</span>';

                case 'brand':
                    $brand = Brand::find($id);
                    if ($brand) {
                        return '<a href="' . e(route('shop.brand', $brand->slug)) . '">' . e($label ?: $brand->name) . '</a>';
                    }
                    return '<span class="text-slate-400 font-semibold">' . e($label ?: "Brand #{$id}") . '</span>';
            }

            return $matches[0];
        }, $content);

        // Process any embedded [plugin:slug ...] shortcodes via the standard shortcode processor
        $content = app(ShortcodeProcessor::class)->process($content);

        return $content;
    }
}
