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

            $display        = strtolower($params['display']         ?? $settings['display_type']     ?? 'slider');
            $max            = max(1, (int) ($params['max']           ?? $settings['max_brands']       ?? 12));
            $cols           = max(2, min(6, (int) ($params['cols']    ?? $settings['columns']          ?? 4)));
            $header         = $params['header']                      ?? $settings['header_title']     ?? 'Featured Brands';
            $autoplay       = strtolower($params['autoplay']         ?? $settings['autoplay']         ?? 'on');
            $showLabel      = filter_var($params['show_label']       ?? $settings['show_label']       ?? true, FILTER_VALIDATE_BOOLEAN);
            $showNavigation = filter_var($params['show_navigation']  ?? $settings['show_navigation']  ?? true, FILTER_VALIDATE_BOOLEAN);
            $showPagination = filter_var($params['show_pagination']  ?? $settings['show_pagination']  ?? true, FILTER_VALIDATE_BOOLEAN);

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
                // ── Swiper.js Slider ─────────────────────────────────────────────────────
                // Matches the pattern used by featured-items-widget-slider and cross-sell-
                // widget-slider: wire:ignore prevents Livewire from destroying the Swiper
                // DOM; JS polls for the global Swiper constructor before initialising.
                $loopEnabled = $brands->count() > $cols ? 'true' : 'false';
                $autoplayJs  = $autoplay === 'on'
                    ? 'autoplay: { delay: 4000, disableOnInteraction: false, pauseOnMouseEnter: true },'
                    : '';

                $outerPaddingClass = $showNavigation ? 'px-8 sm:px-12' : 'px-0';

                $html .= <<<HTML
<div class="brands-plugin-slider-outer relative {$outerPaddingClass}" id="{$instanceId}_outer">
<style>
#{$instanceId}_outer .brands-swiper { width:100%; overflow:hidden; }
#{$instanceId}_outer .swiper-slide { height:auto; }
#{$instanceId}_outer .brand-slide-card {
    background:transparent; border:none; border-radius:1.25rem;
    box-shadow:none; padding:1rem;
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    text-align:center; gap:.5rem; height:100%;
    transition:transform .2s ease;
}
html.dark #{$instanceId}_outer .brand-slide-card { background:transparent; border:none; box-shadow:none; }
#{$instanceId}_outer .brand-slide-card:hover { box-shadow:none; border:none; }
html.dark #{$instanceId}_outer .brand-slide-card:hover { border:none; box-shadow:none; }
#{$instanceId}_outer .brands-swiper-prev,
#{$instanceId}_outer .brands-swiper-next {
    position:absolute; top:50%; transform:translateY(-50%); z-index:10;
    width:2.25rem; height:2.25rem; border-radius:50%;
    background:#fff; border:1px solid #e2e8f0;
    box-shadow:0 2px 8px rgba(0,0,0,.1);
    display:flex; align-items:center; justify-content:center;
    cursor:pointer; transition:background .2s, border-color .2s;
}
html.dark #{$instanceId}_outer .brands-swiper-prev,
html.dark #{$instanceId}_outer .brands-swiper-next { background:#1e293b; border-color:#475569; }
#{$instanceId}_outer .brands-swiper-prev:hover,
#{$instanceId}_outer .brands-swiper-next:hover { background:#4f46e5; border-color:#4f46e5; color:#fff; }
#{$instanceId}_outer .brands-swiper-prev { left:0.25rem; }
#{$instanceId}_outer .brands-swiper-next { right:0.25rem; }
#{$instanceId}_outer .swiper-pagination { margin-top:12px; position:relative; text-align:center; }
#{$instanceId}_outer .swiper-pagination-bullet { background:#cbd5e1; opacity:1; }
#{$instanceId}_outer .swiper-pagination-bullet-active { background:#4f46e5; }
@media (max-width: 499px) {
    #{$instanceId}_outer .brands-swiper { padding-left:1rem; padding-right:1rem; }
    #{$instanceId}_outer .swiper-slide { display:flex; justify-content:center; }
    #{$instanceId}_outer .brand-slide-card { width:100%; }
}
</style>
HTML;

                // Swiper wrapper — wire:ignore prevents Livewire re-rendering
                $html .= '<div wire:ignore>';
                $html .= '<div class="brands-swiper swiper" id="' . $instanceId . '">';
                $html .= '<div class="swiper-wrapper">';

                foreach ($brands as $brand) {
                    $brandUrl = route('shop.brand', $brand->slug);
                    $imgUrl   = $brand->brand_icon_direct_url
                        ?: ($brand->brand_icon ?: null);

                    $html .= '<div class="swiper-slide">';
                    $html .= '<a href="' . e($brandUrl) . '" class="brand-slide-card group block">';
                    if ($imgUrl) {
                        $html .= '<img src="' . e($imgUrl) . '" alt="' . e($brand->name) . '" '
                               . 'class="brand-logo-img h-10 max-w-full object-contain filter grayscale group-hover:grayscale-0 transition-all duration-300">';
                    }
                    if ($showLabel) {
                        $html .= '<span class="text-xs font-bold text-slate-700 group-hover:text-indigo-600 transition-colors" '
                               . 'style="color:inherit">' . e($brand->name) . '</span>';
                    }
                    $html .= '</a>';
                    $html .= '</div>'; // .swiper-slide
                }

                $html .= '</div>'; // .swiper-wrapper

                // Prev / Next arrows
                if ($showNavigation) {
                    $html .= '<div class="brands-swiper-prev" id="' . $instanceId . '_prev" aria-label="Previous">'
                           . '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
                           . '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>'
                           . '</svg></div>';
                    $html .= '<div class="brands-swiper-next" id="' . $instanceId . '_next" aria-label="Next">'
                           . '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
                           . '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>'
                           . '</svg></div>';
                }

                // Pagination dots
                if ($showPagination) {
                    $html .= '<div class="swiper-pagination" id="' . $instanceId . '_pag"></div>';
                }

                $html .= '</div>'; // .swiper (#instanceId)
                $html .= '</div>'; // wire:ignore

                $navigationConfig = $showNavigation ? "navigation: { prevEl: '#{$instanceId}_prev', nextEl: '#{$instanceId}_next' }," : "";
                $paginationConfig = $showPagination ? "pagination: { el: '#{$instanceId}_pag', clickable: true, dynamicBullets: true }," : "";

                // Inline JS — polls until global Swiper is ready
                $html .= <<<JS
<script>
(function () {
    function initBrandsSwiper_{$instanceId}() {
        if (typeof Swiper === 'undefined') {
            setTimeout(initBrandsSwiper_{$instanceId}, 100);
            return;
        }
        new Swiper('#{$instanceId}', {
            slidesPerView: 1,
            centeredSlides: true,
            spaceBetween: 16,
            loop: {$loopEnabled},
            observer: true,
            observeParents: true,
            breakpoints: {
                500:  { slidesPerView: 2, spaceBetween: 16, centeredSlides: false },
                640:  { slidesPerView: 3, spaceBetween: 16, centeredSlides: false },
                1024: { slidesPerView: {$cols}, spaceBetween: 16, centeredSlides: false },
            },
            {$autoplayJs}
            {$navigationConfig}
            {$paginationConfig}
        });
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initBrandsSwiper_{$instanceId});
    } else {
        initBrandsSwiper_{$instanceId}();
    }
    // Re-init after Livewire navigations (wire:navigate)
    document.addEventListener('livewire:navigated', initBrandsSwiper_{$instanceId});
})();
</script>
JS;

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
                // Grid layout — desktop columns set inline; mobile breakpoints override via default_css !important rules.
                $html .= '<div class="brands-plugin-grid" id="' . $instanceId . '_grid" style="grid-template-columns:repeat(' . $cols . ',minmax(0,1fr))">';
                foreach ($brands as $brand) {
                    $brandUrl = route('shop.brand', $brand->slug);
                    $imgUrl   = $brand->brand_icon_direct_url
                        ?: $brand->brand_icon
                        ?: ($brand->image_url ?? '')
                        ?: 'https://via.placeholder.com/150x80?text=' . urlencode($brand->name);

                    $html .= '<a href="' . e($brandUrl) . '" class="group p-5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm hover:shadow-md hover:border-indigo-500 transition flex flex-col items-center justify-center text-center gap-3 min-h-[110px] w-full">';
                    $html .= '<img src="' . e($imgUrl) . '" alt="' . e($brand->name) . '" class="brand-logo-img h-12 max-h-14 max-w-[85%] object-contain filter grayscale group-hover:grayscale-0 transition-all duration-300">';
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
