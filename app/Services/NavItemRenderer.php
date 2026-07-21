<?php

namespace App\Services;

use App\Models\NavItem;
use App\Models\NavMenu;
use App\Models\CmsPage;
use App\Models\Brand;
use App\Models\Category;
use App\Plugins\Support\PluginManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/**
 * NavItemRenderer
 *
 * Stateless service that converts a NavItem record + runtime context
 * into rendered HTML for the public navigation.
 *
 * Context array keys:
 *   'user'      => ?App\Models\User
 *   'cartCount' => int
 */
class NavItemRenderer
{
    public function __construct(
        protected PluginManager $pluginManager
    ) {}

    // ─── Public entry point ───────────────────────────────────────────────────

    /**
     * Render a nav item to its link HTML (not the <li> wrapper, just the <a> / text).
     * Returns '' if the item should be hidden (inactive CMS page, etc.).
     */
    public function resolveLink(NavItem $item, array $context = []): array
    {
        // Returns ['href' => string, 'label' => string, 'skip' => bool]
        $user = $context['user'] ?? null;

        return match ($item->item_type) {
            'home'         => ['href' => url('/'),                     'label' => $item->label, 'skip' => false],
            'shop'         => ['href' => route('shop.index'),          'label' => $item->label, 'skip' => false],
            'cart'         => ['href' => route('shop.cart'),           'label' => $item->label, 'skip' => false],
            'account'      => ['href' => route('dashboard'),           'label' => $item->label, 'skip' => false],
            'link'         => $this->resolveCustomLink($item),
            'cms_page'     => $this->resolveCmsPage($item),
            'parent',
            'no_link',
            'mega_menu',
            'html_submenu',
            'categories',
            'brands',
            'separator',
            'plugin'       => ['href' => '#', 'label' => $item->label, 'skip' => false],
            default        => ['href' => '#', 'label' => $item->label, 'skip' => false],
        };
    }

    /**
     * Render any dynamic sub-menu HTML for a top-level item (categories, brands, mega_menu, etc.).
     * Returns empty string if no sub-menu needed.
     */
    public function renderSubMenu(NavItem $item, array $context = []): string
    {
        return match ($item->item_type) {
            'categories'   => $this->renderCategories($item, $context),
            'brands'       => $this->renderBrands($item, $context),
            'mega_menu'    => $this->renderMegaMenu($item),
            'html_submenu' => $this->renderHtmlSubMenu($item),
            'plugin'       => $this->renderPlugin($item, $context),
            default        => '',
        };
    }

    // ─── Built-in type resolvers ──────────────────────────────────────────────

    protected function resolveCustomLink(NavItem $item): array
    {
        $url = $item->url ?? '#';
        // If it looks like a relative path (no scheme), prepend root
        if ($url !== '#' && !str_contains($url, '//')) {
            $url = url('/' . ltrim($url, '/'));
        }
        return ['href' => $url, 'label' => $item->label, 'skip' => false];
    }

    protected function resolveCmsPage(NavItem $item): array
    {
        if (!$item->cms_page_id) {
            return ['href' => '#', 'label' => $item->label, 'skip' => false];
        }

        try {
            $page = CmsPage::select('page_seo_link', 'page_active')
                ->find($item->cms_page_id);

            if (!$page || !$page->page_active) {
                return ['href' => '#', 'label' => $item->label, 'skip' => true];
            }

            return [
                'href'  => route('page.show', $page->page_seo_link),
                'label' => $item->label,
                'skip'  => false,
            ];
        } catch (\Throwable $e) {
            Log::warning("[NavItemRenderer] CMS page lookup failed: " . $e->getMessage());
            return ['href' => '#', 'label' => $item->label, 'skip' => false];
        }
    }

    // ─── Sub-menu renderers ───────────────────────────────────────────────────

    protected function renderCategories(NavItem $item, array $context): string
    {
        try {
            // Load categories that are active and included in menu
            $categories = Category::where('active', 1)
                ->where('include_in_menu', 1)
                ->orderBy('menu_order')
                ->get();

            if ($categories->isEmpty()) return '';

            $html = '<ul class="nav-dropdown">';
            foreach ($categories as $cat) {
                $url = route('shop.category', $cat->seo_link ?? \Str::slug($cat->name));
                $html .= '<li><a href="' . e($url) . '">' . e($cat->name) . '</a>';

                // Sub-categories (second level)
                if ($cat->relationLoaded('subcategories') || method_exists($cat, 'subcategories')) {
                    try {
                        $subs = $cat->subcategories()
                            ->where('active', 1)
                            ->where('include_in_menu', 1)
                            ->orderBy('menu_order')
                            ->get();
                        if ($subs->isNotEmpty()) {
                            $html .= '<ul class="nav-dropdown">';
                            foreach ($subs as $sub) {
                                $subUrl = route('shop.category', $sub->seo_link ?? \Str::slug($sub->name));
                                $html .= '<li><a href="' . e($subUrl) . '">' . e($sub->name) . '</a></li>';
                            }
                            $html .= '</ul>';
                        }
                    } catch (\Throwable) {}
                }

                $html .= '</li>';
            }
            $html .= '</ul>';
            return $html;
        } catch (\Throwable $e) {
            Log::warning("[NavItemRenderer] Categories render failed: " . $e->getMessage());
            return '';
        }
    }

    protected function renderBrands(NavItem $item, array $context): string
    {
        try {
            $brands = Brand::where('active', 1)
                ->where('include_in_menu', 1)
                ->orderBy('menu_order')
                ->get();

            if ($brands->isEmpty()) return '';

            $html = '<ul class="nav-dropdown">';
            foreach ($brands as $brand) {
                $url = route('shop.brand', $brand->seo_link ?? \Str::slug($brand->name));
                $html .= '<li><a href="' . e($url) . '">' . e($brand->name) . '</a></li>';
            }
            $html .= '</ul>';
            return $html;
        } catch (\Throwable $e) {
            Log::warning("[NavItemRenderer] Brands render failed: " . $e->getMessage());
            return '';
        }
    }

    protected function renderMegaMenu(NavItem $item): string
    {
        if (!$item->html_content) return '';
        // Mega menu wraps raw HTML in a full-width container
        return '<div class="nav-mega-menu">' . $item->html_content . '</div>';
    }

    protected function renderHtmlSubMenu(NavItem $item): string
    {
        if (!$item->html_content) return '';
        return '<ul class="nav-dropdown"><li class="nav-html-submenu">' . $item->html_content . '</li></ul>';
    }

    protected function renderPlugin(NavItem $item, array $context): string
    {
        if (!$item->plugin_slug) return '';

        $plugin = $this->pluginManager->getTopNavPlugin($item->plugin_slug);
        if (!$plugin) {
            return '<!-- [nav-plugin-missing: ' . e($item->plugin_slug) . '] -->';
        }

        try {
            return $plugin->renderItem($item, $context);
        } catch (\Throwable $e) {
            Log::error("[NavItemRenderer] Plugin '{$item->plugin_slug}' error: " . $e->getMessage());
            return '';
        }
    }
}
