<?php

namespace App\Plugins\Display;

use App\Models\Plugin;
use App\Models\Product;
use App\Plugins\Contracts\DisplayPlugin;
use Illuminate\Support\Facades\Log;

class FeaturedItemsPlugin implements DisplayPlugin
{
    public function slug(): string
    {
        return 'featured-items';
    }

    public function name(): string
    {
        return 'Featured Items Display';
    }

    public function render(array $params, Plugin $plugin): string
    {
        try {
            // --- Resolve shortcode params (override plugin defaults) ---
            $settings = $plugin->getSettings();

            $display  = strtolower($params['display']  ?? $settings['display']  ?? 'grid');
            $max      = max(1, (int) ($params['max']   ?? $settings['max_items'] ?? 12));
            $sort     = strtolower($params['sort']      ?? $settings['sort_order'] ?? 'random');
            $header   = $params['header']               ?? $settings['header_title'] ?? '';
            $cols     = (int) ($params['cols']          ?? $settings['grid_columns']  ?? 4);
            $nav      = strtolower($params['nav']       ?? $settings['show_nav'] ?? 'on');
            $autoplay = strtolower($params['autoplay']  ?? $settings['autoplay'] ?? 'on');
            $slides   = max(1, (int) ($params['slides'] ?? $settings['slides_visible'] ?? 4));
            $speed    = max(500, (int) ($params['speed'] ?? $settings['autoplay_speed'] ?? 4000));
            $showBadge = strtolower($params['badge']    ?? $settings['show_badge'] ?? 'on') === 'on' ? 'on' : 'off';

            // Clamp cols to valid range
            $cols = max(2, min(6, $cols));

            // --- Query featured products ---
            $query = Product::with(['variants.inventory', 'variants.images'])
                ->withCurrentTranslations()
                ->where('featured_item', 1);

            // Only include products with at least one variant
            $query->whereHas('variants');

            // Sort
            match ($sort) {
                'name'   => $query->orderBy('title'),
                'price'  => $query->orderBy('title'),    // price ordering goes by join; fallback to title
                'newest' => $query->orderByDesc('created_at'),
                default  => $query->inRandomOrder(),
            };

            $products = $query->limit($max)->get();

            if ($products->isEmpty()) {
                return '<!-- [plugin:featured-items] No featured products found -->';
            }

            // Unique ID for this render (supports multiple on same page)
            $instanceId = 'fi_' . substr(md5(uniqid('fi', true)), 0, 8);

            $defaultCss = $plugin->getSetting('default_css', '');
            $customCss  = $params['custom_css'] ?? $settings['custom_css'] ?? '';

            $cssHtml = '';
            if (!empty($defaultCss) || !empty($customCss)) {
                $cssHtml = "<style>\n";
                if (!empty($defaultCss)) {
                    $cssHtml .= \App\Services\CssMinifierService::minify($defaultCss) . "\n";
                }
                if (!empty($customCss)) {
                    $cssHtml .= \App\Services\CssMinifierService::minify($customCss) . "\n";
                }
                $cssHtml .= "</style>";
            }

            return $cssHtml . \Illuminate\Support\Facades\Blade::render(
                "@livewire('featured-items-widget', [
                    'display'    => '{$display}',
                    'max'        => {$max},
                    'sort'       => '{$sort}',
                    'header'     => " . json_encode($header) . ",
                    'cols'       => {$cols},
                    'nav'        => '{$nav}',
                    'autoplay'   => '{$autoplay}',
                    'slides'     => {$slides},
                    'speed'      => {$speed},
                    'instanceId' => '{$instanceId}',
                    'showBadge'  => '{$showBadge}',
                ])"
            );
        } catch (\Throwable $e) {
            Log::error('[FeaturedItemsPlugin] Render error: ' . $e->getMessage());
            return '<!-- [plugin-error: featured-items] ' . e($e->getMessage()) . ' -->';
        }
    }
}
