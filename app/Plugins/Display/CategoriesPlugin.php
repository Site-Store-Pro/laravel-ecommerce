<?php

namespace App\Plugins\Display;

use App\Models\Category;
use App\Models\Plugin;
use App\Plugins\Contracts\DisplayPlugin;
use Illuminate\Support\Facades\Log;

class CategoriesPlugin implements DisplayPlugin
{
    public function slug(): string
    {
        return 'categories-2026';
    }

    public function name(): string
    {
        return 'Top Level Categories Display 2026';
    }

    public function render(array $params, Plugin $plugin): string
    {
        try {
            $settings = $plugin->getSettings();

            $display  = strtolower($params['display']  ?? $settings['display_type'] ?? 'grid');
            $max      = max(1, (int) ($params['max']   ?? $settings['max_categories'] ?? 12));
            $cols     = max(2, min(6, (int) ($params['cols'] ?? $settings['columns'] ?? 4)));
            $header   = $params['header']               ?? $settings['header_title'] ?? 'Top Categories';

            // Query TOP LEVEL categories ONLY
            $categories = Category::withCurrentTranslations()
                ->where(function ($q) {
                    $q->whereNull('parent_id')->orWhere('parent_id', 0);
                })
                ->where('is_visible_in_menu', true)
                ->orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc')
                ->get();

            // Filter out categories where BOTH display_label_in_plugins AND display_image_in_plugins are disabled (false)
            $categories = $categories->filter(function ($cat) {
                $showLabel = filter_var($cat->display_label_in_plugins ?? true, FILTER_VALIDATE_BOOLEAN);
                $showImg   = filter_var($cat->display_image_in_plugins ?? true, FILTER_VALIDATE_BOOLEAN);
                return $showLabel || $showImg;
            })->take($max);

            if ($categories->isEmpty()) {
                return '<!-- [plugin:categories-2026] No top level categories found -->';
            }

            $html = '<div class="categories-plugin-wrapper">';
            if (!empty($header)) {
                $html .= '<div class="mb-4"><h3 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">' . e($header) . '</h3></div>';
            }

            if ($display === 'list') {
                $html .= '<div class="flex flex-wrap items-center gap-3 py-2">';
                foreach ($categories as $cat) {
                    $showLabel = filter_var($cat->display_label_in_plugins ?? true, FILTER_VALIDATE_BOOLEAN);
                    $showImg   = filter_var($cat->display_image_in_plugins ?? true, FILTER_VALIDATE_BOOLEAN) && !empty($cat->category_image);
                    if (!$showLabel && !$showImg) continue;

                    $catUrl = route('shop.category', $cat->slug);

                    $html .= '<a href="' . e($catUrl) . '" class="categories-plugin-card inline-flex items-center gap-2.5 px-3.5 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 hover:border-indigo-500 hover:text-indigo-600 transition shadow-sm">';
                    if ($showImg) {
                        $html .= '<img src="' . e($cat->category_image) . '" alt="' . e($cat->name) . '" class="category-logo-img w-6 h-6 object-cover rounded-md shrink-0">';
                    }
                    if ($showLabel) {
                        $html .= '<span>' . e($cat->name) . '</span>';
                    }
                    $html .= '</a>';
                }
                $html .= '</div>';
            } elseif ($display === 'slider') {
                $html .= '<div x-data="{ current: 0, total: ' . $categories->count() . ' }" class="relative overflow-hidden w-full py-4">';
                $html .= '<div class="flex transition-transform duration-500 ease-out gap-4" :style="\'transform: translateX(-\' + (current * 100) + \'%);\'">';

                foreach ($categories as $cat) {
                    $showLabel = filter_var($cat->display_label_in_plugins ?? true, FILTER_VALIDATE_BOOLEAN);
                    $showImg   = filter_var($cat->display_image_in_plugins ?? true, FILTER_VALIDATE_BOOLEAN) && !empty($cat->category_image);
                    if (!$showLabel && !$showImg) continue;

                    $catUrl = route('shop.category', $cat->slug);

                    $html .= '<div class="shrink-0 p-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm hover:shadow-md transition flex flex-col items-center text-center gap-3" style="width: calc((100% - ' . (($cols - 1) * 1) . 'rem) / ' . $cols . ');">';
                    $html .= '<a href="' . e($catUrl) . '" class="categories-plugin-card group flex flex-col items-center gap-3 w-full">';
                    if ($showImg) {
                        $html .= '<div class="w-20 h-20 rounded-full overflow-hidden border-2 border-indigo-100 dark:border-indigo-900 group-hover:border-indigo-500 transition-colors shadow-sm shrink-0">';
                        $html .= '<img src="' . e($cat->category_image) . '" alt="' . e($cat->name) . '" class="category-logo-img w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">';
                        $html .= '</div>';
                    }
                    if ($showLabel) {
                        $html .= '<span class="text-xs font-bold text-slate-800 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">' . e($cat->name) . '</span>';
                    }
                    $html .= '</a></div>';
                }

                $html .= '</div></div>';
            } else {
                // Grid layout — desktop columns set inline; mobile breakpoints override via default_css !important rules.
                $html .= '<div class="categories-plugin-grid" style="grid-template-columns:repeat(' . $cols . ',minmax(0,1fr))">';
                foreach ($categories as $cat) {
                    $showLabel = filter_var($cat->display_label_in_plugins ?? true, FILTER_VALIDATE_BOOLEAN);
                    $showImg   = filter_var($cat->display_image_in_plugins ?? true, FILTER_VALIDATE_BOOLEAN) && !empty($cat->category_image);
                    if (!$showLabel && !$showImg) continue;

                    $catUrl = route('shop.category', $cat->slug);

                    $html .= '<a href="' . e($catUrl) . '" class="categories-plugin-card group p-5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm hover:shadow-md hover:border-indigo-500 transition flex flex-col items-center text-center gap-3">';
                    if ($showImg) {
                        $html .= '<div class="w-20 h-20 rounded-full overflow-hidden border-2 border-indigo-50 dark:border-indigo-950 group-hover:border-indigo-500 transition-colors shadow-sm shrink-0">';
                        $html .= '<img src="' . e($cat->category_image) . '" alt="' . e($cat->name) . '" class="category-logo-img w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">';
                        $html .= '</div>';
                    }
                    if ($showLabel) {
                        $html .= '<span class="text-xs font-bold text-slate-800 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-1">' . e($cat->name) . '</span>';
                    }
                    $html .= '</a>';
                }
                $html .= '</div>';
            }
            $html .= '</div>'; // .categories-plugin-wrapper

            $defaultCss = $plugin->getSetting('default_css', '');
            $customCss  = $params['custom_css'] ?? $settings['custom_css'] ?? $plugin->getSetting('custom_css', '');

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
            Log::error('[CategoriesPlugin] Render error: ' . $e->getMessage());
            return '<!-- [plugin-error: categories-2026] ' . e($e->getMessage()) . ' -->';
        }
    }
}
