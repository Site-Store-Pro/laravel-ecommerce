<?php

namespace App\Plugins\Display;

use App\Models\CmsSetting;
use App\Models\Plugin;
use App\Plugins\Contracts\DisplayPlugin;
use Illuminate\Support\Facades\Log;

class SiteNewsFlashPlugin implements DisplayPlugin
{
    public function slug(): string
    {
        return 'newsflash-2026';
    }

    public function name(): string
    {
        return 'Site News Flash Ticker 2026';
    }

    public function render(array $params, Plugin $plugin): string
    {
        try {
            $settings = $plugin->getSettings();

            $message     = $params['message']     ?? $settings['news_message']     ?? CmsSetting::get('news_flash_text', 'Welcome to our store! Enjoy free shipping on orders over $50.');
            $bgColor     = $params['bg_color']    ?? $settings['bg_color']         ?? 'transparent';
            $textColor   = $params['text_color']  ?? $settings['text_color']       ?? '';
            $link        = $params['link']        ?? $settings['news_link']        ?? '';
            $linkText    = $params['link_text']   ?? $settings['news_link_text']   ?? 'Learn More';
            $dismissible = strtolower($params['dismissible'] ?? $settings['dismissible'] ?? 'off') === 'on';

            if (empty(trim($message))) {
                return '';
            }

            $styleAttr = '';
            $styles = [];
            if (!empty($bgColor) && $bgColor !== 'transparent') {
                $styles[] = "background-color: " . e($bgColor);
            }
            if (!empty($textColor) && $textColor !== 'inherit') {
                $styles[] = "color: " . e($textColor);
            }
            if (!empty($styles)) {
                $styleAttr = ' style="' . implode('; ', $styles) . ';"';
            }

            $containerClass = ($bgColor !== 'transparent' && !empty($bgColor)) 
                ? 'site-news-flash-ticker px-4 py-2 rounded-xl shadow-sm text-xs font-semibold flex items-center justify-between gap-4 my-1 transition-all'
                : 'site-news-flash-ticker py-1 text-xs font-semibold flex items-center justify-between gap-4 my-1 transition-all text-slate-800 dark:text-slate-200';

            $html = '<div x-data="{ dismissed: false }" x-show="!dismissed" class="' . $containerClass . '"' . $styleAttr . '>';
            $html .= '<div class="flex items-center gap-2 overflow-hidden">';
            $html .= '<span class="truncate">' . e($message) . '</span>';
            
            if (!empty($link)) {
                $html .= '<a href="' . e($link) . '" class="underline font-bold hover:opacity-80 transition shrink-0">' . e($linkText) . ' &rarr;</a>';
            }

            $html .= '</div>';

            if ($dismissible) {
                $html .= '<button @click="dismissed = true" class="p-1 rounded-md hover:opacity-75 transition shrink-0 opacity-80" title="Dismiss announcement" aria-label="Dismiss">✕</button>';
            }

            $html .= '</div>';

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
            Log::error('[SiteNewsFlashPlugin] Render error: ' . $e->getMessage());
            return '<!-- [plugin-error: newsflash-2026] ' . e($e->getMessage()) . ' -->';
        }
    }
}
