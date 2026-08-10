<?php

namespace App\Plugins\Display;

use App\Models\CmsTestimonial;
use App\Models\Plugin;
use App\Plugins\Contracts\DisplayPlugin;
use Illuminate\Support\Facades\Log;

class TestimonialsPlugin implements DisplayPlugin
{
    public function slug(): string
    {
        return 'testimonials-2026';
    }

    public function name(): string
    {
        return 'Testimonials Display 2026';
    }

    public function render(array $params, Plugin $plugin): string
    {
        try {
            $settings = $plugin->getSettings();

            $display    = strtolower($params['display']    ?? $settings['display_type'] ?? 'slider');
            $max        = max(1, (int) ($params['max']     ?? $settings['max_items']    ?? 6));
            $cols       = max(1, min(4, (int) ($params['cols'] ?? $settings['columns']  ?? 3)));
            $header     = array_key_exists('header', $params)
                            ? $params['header']
                            : ($settings['header_title'] ?: 'What Our Customers Say');
            $quoteIcon  = $params['quote_icon']             ?? $settings['quote_icon']   ?? 'quote-left'; // 'quote-left', 'double-quote', 'none'
            $showRating = strtolower($params['rating']     ?? $settings['show_rating']  ?? 'on') === 'on';

            $testimonials = CmsTestimonial::active()->withCurrentTranslations()->limit($max)->get();

            if ($testimonials->isEmpty()) {
                return '<!-- [plugin:testimonials-2026] No active testimonials found -->';
            }

            $html = '';
            if (!empty($header)) {
                $html .= '<div class="mb-6 text-center"><h3 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">' . e($header) . '</h3></div>';
            }

            $quoteSvg = '<svg class="w-8 h-8 text-indigo-500/30 shrink-0 mb-3" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>';

            if ($display === 'slider') {
                $instanceId     = 'tmn_' . substr(md5(uniqid('t', true)), 0, 8);
                $slidesPerViewSm = min($cols, 2);   // ≤639px = 1, 640-1023px = min(cols,2), ≥1024px = cols
                $loopEnabled    = $testimonials->count() > $cols ? 'true' : 'false';

                $html .= '<div class="testimonials-plugin-section relative py-4" id="' . $instanceId . '_outer">';

                // Scoped overrides — indigo accent, equal-height slides, room for pagination dots
                $html .= '<style>'
                       . '#' . $instanceId . '_outer .swiper-button-prev,'
                       . '#' . $instanceId . '_outer .swiper-button-next { color: #6366f1; }'
                       . '#' . $instanceId . '_outer .swiper-pagination-bullet-active { background: #6366f1; }'
                       . '#' . $instanceId . '_outer .swiper-slide { height: auto; }'
                       . '#' . $instanceId . '_outer .swiper { padding-bottom: 2.75rem; }'
                       . '#' . $instanceId . '_outer .tmn-card { height: 100%; display: flex; flex-direction: column; justify-content: space-between; }'
                       . '</style>';

                // Swiper container
                $html .= '<div class="swiper" id="' . $instanceId . '">';
                $html .= '<div class="swiper-wrapper">';

                foreach ($testimonials as $t) {
                    $html .= '<div class="swiper-slide">';
                    $html .= '<div class="tmn-card p-6 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl shadow-sm transition-all duration-300 hover:shadow-md">';

                    $html .= '<div>';
                    if ($quoteIcon !== 'none') {
                        $html .= $quoteSvg;
                    }
                    if ($showRating) {
                        $html .= '<div class="flex items-center text-amber-400 mb-3">';
                        for ($i = 1; $i <= 5; $i++) {
                            $filled = $i <= $t->rating ? 'fill-current' : 'text-slate-200 dark:text-slate-600';
                            $html .= '<svg class="w-4 h-4 ' . $filled . '" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';
                        }
                        $html .= '</div>';
                    }
                    $html .= '<p class="text-slate-700 dark:text-slate-200 text-sm leading-relaxed mb-6 font-medium italic">&ldquo;' . e($t->content) . '&rdquo;</p>';
                    $html .= '</div>';

                    $html .= '<div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-700/60">';
                    $html .= '<img src="' . e($t->getAvatarUrl()) . '" alt="' . e($t->author_name) . '" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-600 shrink-0">';
                    $html .= '<div>';
                    $html .= '<div class="font-bold text-slate-900 dark:text-white text-xs">' . e($t->author_name) . '</div>';
                    if ($t->author_title || $t->company_name) {
                        $subtitle = implode(' • ', array_filter([$t->author_title, $t->company_name]));
                        $html .= '<div class="text-[11px] text-slate-400 font-medium">' . e($subtitle) . '</div>';
                    }
                    $html .= '</div></div>';
                    $html .= '</div>'; // .tmn-card
                    $html .= '</div>'; // .swiper-slide
                }

                $html .= '</div>'; // .swiper-wrapper

                // Swiper UI controls
                $html .= '<div class="swiper-pagination" id="' . $instanceId . '_pag"></div>';
                $html .= '<div class="swiper-button-prev" id="' . $instanceId . '_prev"></div>';
                $html .= '<div class="swiper-button-next" id="' . $instanceId . '_next"></div>';

                $html .= '</div>'; // .swiper
                $html .= '</div>'; // outer wrapper

                $html .= '<script>(function(){'
                       .   'function init(){'
                       .     'if(typeof Swiper==="undefined"){setTimeout(init,80);return;}'
                       .     'new Swiper("#' . $instanceId . '",{'
                       .       'slidesPerView:1,'
                       .       'spaceBetween:24,'
                       .       'loop:' . $loopEnabled . ','
                       .       'autoplay:{delay:6000,disableOnInteraction:false,pauseOnMouseEnter:true},'
                       .       'pagination:{el:"#' . $instanceId . '_pag",clickable:true},'
                       .       'navigation:{prevEl:"#' . $instanceId . '_prev",nextEl:"#' . $instanceId . '_next"},'
                       .       'breakpoints:{'
                       .         '640:{slidesPerView:' . $slidesPerViewSm . ',spaceBetween:20},'
                       .         '1024:{slidesPerView:' . $cols . ',spaceBetween:24}'
                       .       '}'
                       .     '});'
                       .   '}'
                       .   'if(document.readyState==="loading"){document.addEventListener("DOMContentLoaded",init);}else{init();}'
                       . '})();</script>';
            } else {
                // ── List layout — horizontal rows matching the /shop product list style ──
                $html .= '<div class="testimonials-plugin-list space-y-4 py-2">';

                foreach ($testimonials as $t) {
                    // Row wrapper
                    $html .= '<div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700/60 p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center gap-5 hover:shadow-lg transition-shadow duration-300">';

                    // Left — avatar
                    $html .= '<div class="shrink-0">';
                    $html .= '<img src="' . e($t->getAvatarUrl()) . '" alt="' . e($t->author_name) . '" class="w-16 h-16 rounded-2xl object-cover border border-slate-200 dark:border-slate-600">';
                    $html .= '</div>';

                    // Middle — quote icon + content + author meta
                    $html .= '<div class="flex-1 min-w-0">';
                    if ($quoteIcon !== 'none') {
                        $html .= '<svg class="w-5 h-5 text-indigo-400/50 mb-1" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>';
                    }
                    $html .= '<p class="text-slate-700 dark:text-slate-200 text-sm leading-relaxed font-medium italic line-clamp-3">&ldquo;' . e($t->content) . '&rdquo;</p>';
                    $html .= '<div class="mt-2 flex items-center gap-2 flex-wrap">';
                    $html .= '<span class="text-xs font-bold text-slate-900 dark:text-white">' . e($t->author_name) . '</span>';
                    if ($t->author_title || $t->company_name) {
                        $subtitle = implode(' · ', array_filter([$t->author_title, $t->company_name]));
                        $html .= '<span class="text-[11px] text-slate-400 font-medium">' . e($subtitle) . '</span>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    // Right — star rating
                    if ($showRating) {
                        $html .= '<div class="flex items-center gap-0.5 shrink-0 self-start sm:self-center">';
                        for ($i = 1; $i <= 5; $i++) {
                            $cls = $i <= $t->rating ? 'text-amber-400 fill-current' : 'text-slate-200 dark:text-slate-600';
                            $html .= '<svg class="w-4 h-4 ' . $cls . '" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';
                        }
                        $html .= '</div>';
                    }

                    $html .= '</div>'; // row
                }

                $html .= '</div>'; // .testimonials-plugin-list
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
            Log::error('[TestimonialsPlugin] Render error: ' . $e->getMessage());
            return '<!-- [plugin-error: testimonials-2026] ' . e($e->getMessage()) . ' -->';
        }
    }
}
