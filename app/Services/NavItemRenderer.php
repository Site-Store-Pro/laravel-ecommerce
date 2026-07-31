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
            'home'         => ['href' => url('/'),            'label' => $this->resolveLabel($item, 'nav.home', 'Home'), 'skip' => false],
            'shop'         => ['href' => route('shop.index'), 'label' => $this->resolveLabel($item, 'nav.shop', 'Shop'), 'skip' => false],
            'cart'         => ['href' => route('shop.cart'),  'label' => $this->resolveLabel($item, 'nav.cart', 'Cart'), 'skip' => false],
            'account'      => ['href' => route('dashboard'),  'label' => $this->resolveLabel($item, 'nav.my_account', 'My Account'), 'skip' => false],
            'categories'   => ['href' => '#',                 'label' => $this->resolveLabel($item, 'nav.categories_fallback', 'Categories'), 'skip' => false],
            'brands'       => ['href' => '#',                 'label' => $this->resolveLabel($item, 'nav.brands_fallback', 'Brands'), 'skip' => false],
            'link'         => $this->resolveCustomLink($item),
            'cms_page'     => $this->resolveCmsPage($item),
            'login_logout' => $this->resolveLoginLogout($item, $user),
            'parent',
            'no_link',
            'mega_menu',
            'html_submenu',
            'separator',
            'plugin'       => ['href' => '#',                 'label' => $this->resolveLabel($item), 'skip' => false],
            default        => ['href' => '#',                 'label' => $this->resolveLabel($item), 'skip' => false],
        };
    }

    /**
     * Resolve translated label for a NavItem.
     */
    public function resolveLabel(NavItem $item, ?string $siteLabelKey = null, string $defaultFallback = ''): string
    {
        $langService = app(\App\Services\LanguageService::class);
        $translatedLabel = (string) $item->label;

        if (!$langService->isDefault()) {
            if ($item->relationLoaded('translations')) {
                $trans = $item->translations->firstWhere('language_id', $langService->currentId());
                if ($trans && !empty($trans->label)) {
                    return $trans->label;
                }
            } else {
                $transLabel = $item->getTranslated('label');
                if ($transLabel !== '' && $transLabel !== $item->getRawOriginal('label')) {
                    return $transLabel;
                }
            }
        }

        // If the admin provided a custom label on the NavItem itself, prioritize it!
        if (trim($translatedLabel) !== '') {
            return trim($translatedLabel);
        }

        if ($siteLabelKey !== null) {
            return siteLabel($siteLabelKey, $defaultFallback);
        }

        return $defaultFallback;
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
        return ['href' => $url, 'label' => $this->resolveLabel($item), 'skip' => false];
    }

    protected function resolveCmsPage(NavItem $item): array
    {
        if (!$item->cms_page_id) {
            return ['href' => '#', 'label' => $this->resolveLabel($item), 'skip' => false];
        }

        try {
            $page = CmsPage::withCurrentTranslations()
                ->select('id', 'title', 'slug', 'is_active')
                ->find($item->cms_page_id);

            if (!$page || !$page->is_active) {
                return ['href' => '#', 'label' => $this->resolveLabel($item), 'skip' => true];
            }

            $label = $this->resolveLabel($item);
            $rawItemLabel = $item->getRawOriginal('label');
            $rawPageTitle = $page->getRawOriginal('title');
            if (empty($rawItemLabel) || $rawItemLabel === $rawPageTitle) {
                $label = $page->title;
            }

            return [
                'href'  => route('page.show', $page->slug),
                'label' => $label,
                'skip'  => false,
            ];
        } catch (\Throwable $e) {
            Log::warning("[NavItemRenderer] CMS page lookup failed: " . $e->getMessage());
            return ['href' => '#', 'label' => $this->resolveLabel($item), 'skip' => false];
        }
    }

    protected function resolveLoginLogout(NavItem $item, ?object $user): array
    {
        if ($user) {
            return [
                'href'  => '#',
                'label' => siteLabel('nav.log_out', 'Logout'),
                'skip'  => false,
            ];
        }

        return [
            'href'  => route('login'),
            'label' => $this->resolveLabel($item, 'nav.sign_in', 'Sign In'),
            'skip'  => false,
        ];
    }

    // ─── Sub-menu renderers ───────────────────────────────────────────────────

    protected function renderCategories(NavItem $item, array $context): string
    {
        try {
            $categories = Category::whereNull('parent_id')
                ->with('children')
                ->orderBy('sort_order', 'asc')
                ->get();

            if ($categories->isEmpty()) return '';

            $html = '<ul class="nav-dropdown nav-categories-dropdown">';
            foreach ($categories as $cat) {
                $url = route('shop.category', $cat->slug);
                $html .= '<li><a href="' . e($url) . '">' . e($cat->name) . '</a>';

                if ($cat->children && $cat->children->isNotEmpty()) {
                    $html .= '<ul class="nav-dropdown">';
                    foreach ($cat->children as $sub) {
                        $subUrl = route('shop.category', $sub->slug);
                        $html .= '<li><a href="' . e($subUrl) . '">' . e($sub->name) . '</a></li>';
                    }
                    $html .= '</ul>';
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
            $brands = Brand::orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc')
                ->get();

            if ($brands->isEmpty()) return '';

            $html = '<ul class="nav-dropdown nav-brands-dropdown min-w-[200px] py-1">';
            foreach ($brands as $brand) {
                $url = route('shop.brand', $brand->slug);
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
