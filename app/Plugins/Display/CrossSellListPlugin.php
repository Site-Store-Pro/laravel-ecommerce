<?php

namespace App\Plugins\Display;

use App\Models\Plugin;
use App\Plugins\Contracts\DisplayPlugin;
use Illuminate\Support\Facades\Log;

class CrossSellListPlugin implements DisplayPlugin
{
    public function slug(): string
    {
        return 'cross-sell-list';
    }

    public function name(): string
    {
        return 'Cross-Sell List Display';
    }

    public function render(array $params, Plugin $plugin): string
    {
        try {
            $settings = $plugin->getSettings();

            $productId = (int) ($params['product_id'] ?? $settings['product_id'] ?? 0);
            $display   = strtolower($params['display']  ?? $settings['display']    ?? 'grid');
            $max       = max(1, (int) ($params['max']   ?? $settings['max_items']  ?? 12));
            $sort      = strtolower($params['sort']     ?? $settings['sort_order'] ?? 'sort_order');
            $header    = $params['header']              ?? $settings['header_title'] ?? '';
            $cols      = (int) ($params['cols']         ?? $settings['grid_columns'] ?? 4);
            $nav       = strtolower($params['nav']      ?? $settings['show_nav']    ?? 'on');
            $autoplay  = strtolower($params['autoplay'] ?? $settings['autoplay']    ?? 'on');
            $slides    = max(1, (int) ($params['slides'] ?? $settings['slides_visible'] ?? 4));
            $speed     = max(500, (int) ($params['speed'] ?? $settings['autoplay_speed'] ?? 4000));

            $cols = max(2, min(6, $cols));

            $instanceId = 'cs_' . substr(md5('cross-sell' . $productId . $display . microtime()), 0, 8);

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
                "@livewire('cross-sell-list-widget', [
                    'productId'  => {$productId},
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
                ])"
            );
        } catch (\Throwable $e) {
            Log::error('[CrossSellListPlugin] Render error: ' . $e->getMessage());
            return '<!-- [plugin-error: cross-sell-list] ' . e($e->getMessage()) . ' -->';
        }
    }
}
