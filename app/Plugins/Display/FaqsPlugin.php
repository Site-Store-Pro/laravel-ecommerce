<?php

namespace App\Plugins\Display;

use App\Models\CmsFaq;
use App\Models\Plugin;
use App\Plugins\Contracts\DisplayPlugin;
use Illuminate\Support\Facades\Log;

class FaqsPlugin implements DisplayPlugin
{
    public function slug(): string
    {
        return 'faqs-2026';
    }

    public function name(): string
    {
        return 'FAQ Accordion Display';
    }

    public function render(array $params, Plugin $plugin): string
    {
        try {
            $settings = $plugin->getSettings();

            // ── Parameters (shortcode attrs override plugin settings) ──────────
            $header      = $params['header']     ?? $settings['header_title'] ?? 'Frequently Asked Questions';
            $showHeader  = strtolower($params['show_header']    ?? ($settings['show_header']    ?? '1')) !== '0';
            $openFirst   = strtolower($params['open_first']     ?? ($settings['open_first']     ?? '0')) !== '0';
            $allowMulti  = strtolower($params['allow_multiple'] ?? ($settings['allow_multiple'] ?? '0')) !== '0';
            $max         = (int) ($params['max']   ?? $settings['max_items']  ?? 0);
            $customCss   = $params['custom_css']   ?? $settings['custom_css'] ?? '';

            // ── Fetch active FAQs ─────────────────────────────────────────────
            $query = CmsFaq::active()->ordered();
            if ($max > 0) {
                $query->limit($max);
            }
            $faqs = $query->get();

            if ($faqs->isEmpty()) {
                return '<!-- [plugin:faqs-2026] No active FAQs found -->';
            }

            // ── Unique instance ID (supports multiple accordions per page) ─────
            $instanceId = 'faq_' . substr(md5(uniqid('faq', true)), 0, 8);

            // ── Custom CSS block ──────────────────────────────────────────────
            $cssBlock = '';
            if (!empty($customCss)) {
                $cssBlock = '<style>' . \App\Services\CssMinifierService::minify($customCss) . '</style>';
            }

            // ── Build HTML ────────────────────────────────────────────────────
            $html = $cssBlock;
            $html .= '<div id="' . $instanceId . '" class="faq-accordion w-full mx-auto space-y-2 py-2">';

            // Optional section header
            if ($showHeader && !empty($header)) {
                $html .= '<div class="mb-6">'
                       . '<h3 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">'
                       . e($header)
                       . '</h3>'
                       . '</div>';
            }

            // Alpine.js controller on the outer wrapper
            // allowMulti: each item manages its own open state independently
            // !allowMulti: only one open at a time — siblings are closed via a shared counter trick
            if ($allowMulti) {
                // Each item is independently controlled — no parent state needed
                foreach ($faqs as $index => $faq) {
                    $openByDefault = ($openFirst && $index === 0) ? 'true' : 'false';
                    $itemId = $instanceId . '_item_' . $faq->id;

                    $html .= '<div x-data="{ open: ' . $openByDefault . ' }" wire:ignore class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm transition-all duration-200 hover:shadow-md" id="' . $itemId . '">';

                    // Question / trigger button
                    $html .= '<button @click="open = !open"'
                           . ' :aria-expanded="open"'
                           . ' aria-controls="' . $itemId . '_body"'
                           . ' class="w-full flex items-center justify-between gap-4 px-5 py-4 text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 group transition">'
                           . '<span class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-snug group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">'
                           . e($faq->question)
                           . '</span>'
                           // Plus/minus icon
                           . '<span class="shrink-0 w-7 h-7 rounded-full flex items-center justify-center border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-400 dark:text-slate-400 group-hover:border-indigo-300 group-hover:text-indigo-500 dark:group-hover:text-indigo-400 transition">'
                           . '<svg class="w-4 h-4 transition-transform duration-300" :class="open ? \'rotate-45\' : \'\'" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
                           . '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>'
                           . '</svg>'
                           . '</span>'
                           . '</button>';

                    // Answer body
                    $html .= '<div id="' . $itemId . '_body"'
                           . ' x-show="open"'
                           . ' x-collapse'
                           . ' role="region">'
                           . '<div class="px-5 pb-5 pt-1 text-sm text-slate-600 dark:text-slate-300 leading-relaxed border-t border-slate-100 dark:border-slate-700/60">'
                           . nl2br(e($faq->answer))
                           . '</div>'
                           . '</div>';

                    $html .= '</div>'; // item
                }
            } else {
                // Single-open accordion: parent tracks which item is open
                $openDefault = $openFirst ? '0' : 'null';
                $html .= '<div x-data="{ activeItem: ' . $openDefault . ' }" wire:ignore>';

                foreach ($faqs as $index => $faq) {
                    $itemId = $instanceId . '_item_' . $faq->id;
                    $idx    = $index; // integer literal

                    $html .= '<div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm transition-all duration-200 hover:shadow-md mb-2 last:mb-0" id="' . $itemId . '">';

                    $html .= '<button @click="activeItem === ' . $idx . ' ? activeItem = null : activeItem = ' . $idx . '"'
                           . ' :aria-expanded="activeItem === ' . $idx . '"'
                           . ' aria-controls="' . $itemId . '_body"'
                           . ' class="w-full flex items-center justify-between gap-4 px-5 py-4 text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 group transition">'
                           . '<span class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-snug group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">'
                           . e($faq->question)
                           . '</span>'
                           . '<span class="shrink-0 w-7 h-7 rounded-full flex items-center justify-center border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-400 dark:text-slate-400 group-hover:border-indigo-300 group-hover:text-indigo-500 dark:group-hover:text-indigo-400 transition">'
                           . '<svg class="w-4 h-4 transition-transform duration-300" :class="activeItem === ' . $idx . ' ? \'rotate-45\' : \'\'" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
                           . '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>'
                           . '</svg>'
                           . '</span>'
                           . '</button>';

                    $html .= '<div id="' . $itemId . '_body"'
                           . ' x-show="activeItem === ' . $idx . '"'
                           . ' x-collapse'
                           . ' role="region">'
                           . '<div class="px-5 pb-5 pt-1 text-sm text-slate-600 dark:text-slate-300 leading-relaxed border-t border-slate-100 dark:border-slate-700/60">'
                           . nl2br(e($faq->answer))
                           . '</div>'
                           . '</div>';

                    $html .= '</div>'; // item
                }

                $html .= '</div>'; // x-data wrapper
            }

            $html .= '</div>'; // .faq-accordion

            // x-collapse requires the Alpine Collapse plugin — inject if not already loaded
            $html .= '<script>(function(){'
                   . 'if(typeof Alpine!=="undefined"&&Alpine.plugin&&!window.__alpineCollapseLoaded){'
                   . 'const s=document.createElement("script");'
                   . 's.src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js";'
                   . 's.defer=true;'
                   . 's.onload=function(){window.__alpineCollapseLoaded=true;};'
                   . 'document.head.appendChild(s);'
                   . '}'
                   . '})();</script>';

            return $html;

        } catch (\Throwable $e) {
            Log::error('[FaqsPlugin] Render error: ' . $e->getMessage());
            return '<!-- [plugin-error: faqs-2026] ' . e($e->getMessage()) . ' -->';
        }
    }
}
