<?php

namespace App\Plugins\Display;

use App\Models\Brand;
use App\Models\Plugin;
use App\Plugins\Contracts\DisplayPlugin;
use Illuminate\Support\Facades\Log;

class BrandsPlugin implements DisplayPlugin
{
    public function slug(): string
    {
        return 'brands-2026';
    }

    public function name(): string
    {
        return 'Brands Display 2026';
    }

    public function render(array $params, Plugin $plugin): string
    {
        try {
            $settings = $plugin->getSettings();

            $display    = strtolower($params['display']    ?? $settings['display_type']  ?? 'slider');
            $max        = max(1, (int) ($params['max']      ?? $settings['max_brands']    ?? 12));
            $cols       = max(2, min(6, (int) ($params['cols'] ?? $settings['columns']   ?? 4)));
            $header     = $params['header']                 ?? $settings['header_title']  ?? 'Featured Brands';
            $autoplay   = strtolower($params['autoplay']    ?? $settings['autoplay']      ?? 'on');
            $showLabel  = filter_var($params['show_label']  ?? $settings['show_label']    ?? true, FILTER_VALIDATE_BOOLEAN);

            $brands = Brand::visibleInMenu()->orderBy('name')->limit($max)->get();

            if ($brands->isEmpty()) {
                return '<!-- [plugin:brands-2026] No active brands found -->';
            }

            $instanceId = 'brands_' . substr(md5($display . $max . microtime()), 0, 8);

            $html = '';
            if (!empty($header)) {
                $html .= '<div class="mb-4"><h3 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">' . e($header) . '</h3></div>';
            }

            if ($display === 'slider') {
                $html .= '<div x-data="{ current: 0, total: ' . $brands->count() . ', autoplay: ' . ($autoplay === 'on' ? 'true' : 'false') . ' }" ';
                $html .= 'x-init="if (autoplay) { setInterval(() => { current = (current + 1) % Math.ceil(total / ' . $cols . '); }, 4000); }" ';
                $html .= 'class="relative overflow-hidden w-full py-4">';
                
                $html .= '<div class="flex transition-transform duration-500 ease-out gap-4" :style="\'transform: translateX(-\' + (current * 100) + \'%);\'">'; 

                foreach ($brands as $brand) {
                    $brandUrl = route('shop.brand', $brand->slug);
                    $imgUrl   = $brand->brand_icon_direct_url
                        ?: $brand->brand_icon
                        ?: ($brand->image_url ?? '')
                        ?: 'https://via.placeholder.com/150x80?text=' . urlencode($brand->name);

                    $html .= '<div class="shrink-0 p-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm hover:shadow-md transition flex items-center justify-center text-center" style="width: calc((100% - ' . (($cols - 1) * 1) . 'rem) / ' . $cols . ');">';
                    $html .= '<a href="' . e($brandUrl) . '" class="group flex flex-col items-center gap-2 w-full">';
                    $html .= '<img src="' . e($imgUrl) . '" alt="' . e($brand->name) . '" class="brand-logo-img h-12 max-w-full object-contain filter grayscale group-hover:grayscale-0 transition-all duration-300">';
                    if ($showLabel) {
                        $html .= '<span class="text-xs font-bold text-slate-700 dark:text-slate-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">' . e($brand->name) . '</span>';
                    }
                    $html .= '</a></div>';
                }

                $html .= '</div></div>';
            } elseif ($display === 'list') {
                $html .= '<div class="flex flex-wrap items-center gap-3 py-2">';
                foreach ($brands as $brand) {
                    $brandUrl = route('shop.brand', $brand->slug);
                    $imgUrl   = $brand->brand_icon_direct_url ?: $brand->brand_icon ?: ($brand->image_url ?? '');
                    $html .= '<a href="' . e($brandUrl) . '" class="inline-flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 hover:border-indigo-500 hover:text-indigo-600 transition shadow-sm">';
                    if ($imgUrl) {
                        $html .= '<img src="' . e($imgUrl) . '" alt="' . e($brand->name) . '" class="brand-logo-img w-4 h-4 object-contain">';
                    }
                    if ($showLabel) {
                        $html .= '<span>' . e($brand->name) . '</span>';
                    }
                    $html .= '</a>';
                }
                $html .= '</div>';
            } else {
                // Grid layout
                $gridColsClass = match ($cols) {
                    2 => 'grid-cols-2',
                    3 => 'grid-cols-3',
                    5 => 'grid-cols-2 sm:grid-cols-5',
                    6 => 'grid-cols-2 sm:grid-cols-6',
                    default => 'grid-cols-2 sm:grid-cols-4',
                };

                $html .= '<div class="grid ' . $gridColsClass . ' gap-4 py-2">';
                foreach ($brands as $brand) {
                    $brandUrl = route('shop.brand', $brand->slug);
                    $imgUrl   = $brand->brand_icon_direct_url
                        ?: $brand->brand_icon
                        ?: ($brand->image_url ?? '')
                        ?: 'https://via.placeholder.com/150x80?text=' . urlencode($brand->name);

                    $html .= '<a href="' . e($brandUrl) . '" class="group p-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm hover:shadow-md hover:border-indigo-500 transition flex flex-col items-center justify-center text-center gap-2">';
                    $html .= '<img src="' . e($imgUrl) . '" alt="' . e($brand->name) . '" class="brand-logo-img h-12 max-w-full object-contain filter grayscale group-hover:grayscale-0 transition-all duration-300">';
                    if ($showLabel) {
                        $html .= '<span class="text-xs font-bold text-slate-700 dark:text-slate-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">' . e($brand->name) . '</span>';
                    }
                    $html .= '</a>';
                }
                $html .= '</div>';
            }


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

            return $cssHtml . $html;

        } catch (\Throwable $e) {
            Log::error('[BrandsPlugin] Render error: ' . $e->getMessage());
            return '<!-- [plugin-error: brands-2026] ' . e($e->getMessage()) . ' -->';
        }
    }
}
