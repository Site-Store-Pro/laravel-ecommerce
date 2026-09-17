<?php

namespace App\Services;

use App\Models\CmsBuilderBlock;
use App\Models\CmsSetting;
use App\Models\Language;
use App\Models\NavItem;
use App\Models\NavMenu;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class HeaderFooterCacheService
{
    public const SETTING_CACHE_ENABLED  = 'header_footer_cache_enabled';
    public const SETTING_CACHE_BUILT_AT = 'header_footer_cache_built_at';
    public const SETTING_CACHE_LANG_COUNT = 'header_footer_cache_lang_count';
    public const SETTING_PREFIX_HEADER  = 'cached_header_html_';
    public const SETTING_PREFIX_FOOTER  = 'cached_footer_html_';
    public const CACHE_PREFIX_HEADER    = 'cms_header_cache_';
    public const CACHE_PREFIX_FOOTER    = 'cms_footer_cache_';

    /**
     * Check if the static header & footer cache is currently active.
     */
    public static function isCacheActive(): bool
    {
        $enabled = CmsSetting::get(self::SETTING_CACHE_ENABLED, '0');
        return in_array($enabled, ['1', 1, true, 'true'], true);
    }

    /**
     * Get cache status metadata for the Admin UI.
     */
    public static function getCacheMetadata(): array
    {
        $active = self::isCacheActive();
        $builtAt = CmsSetting::get(self::SETTING_CACHE_BUILT_AT);
        $langCount = (int) CmsSetting::get(self::SETTING_CACHE_LANG_COUNT, 0);

        return [
            'is_active'   => $active,
            'built_at'    => $builtAt,
            'lang_count'  => $langCount,
        ];
    }

    /**
     * Retrieve pre-rendered static Header HTML for a specific device and language.
     */
    public static function getHeaderHtml(string $device, int $langId): ?string
    {
        if (!self::isCacheActive()) {
            return null;
        }

        $device = strtolower($device);
        if (!in_array($device, ['desktop', 'tablet', 'mobile'], true)) {
            $device = 'desktop';
        }

        $cacheKey = self::CACHE_PREFIX_HEADER . "{$langId}_{$device}";

        return Cache::rememberForever($cacheKey, function () use ($langId, $device) {
            $settingKey = self::SETTING_PREFIX_HEADER . "{$langId}_{$device}";
            $html = CmsSetting::get($settingKey);
            return !empty($html) ? $html : null;
        });
    }

    /**
     * Retrieve pre-rendered static Footer HTML for a specific device and language.
     */
    public static function getFooterHtml(string $device, int $langId): ?string
    {
        if (!self::isCacheActive()) {
            return null;
        }

        $device = strtolower($device);
        if (!in_array($device, ['desktop', 'tablet', 'mobile'], true)) {
            $device = 'desktop';
        }

        $cacheKey = self::CACHE_PREFIX_FOOTER . "{$langId}_{$device}";

        return Cache::rememberForever($cacheKey, function () use ($langId, $device) {
            $settingKey = self::SETTING_PREFIX_FOOTER . "{$langId}_{$device}";
            $html = CmsSetting::get($settingKey);
            return !empty($html) ? $html : null;
        });
    }

    /**
     * Build and cache the static Header and Footer across all active languages and device viewports.
     *
     * @return array Summary of built items [languages => count, viewports => count, elapsed_ms => float]
     */
    public static function buildCache(): array
    {
        $startTime = microtime(true);
        $languages = Language::getAllActive();
        if ($languages->isEmpty()) {
            $defaultLang = Language::getDefault();
            $languages = collect([$defaultLang]);
        }

        $devices = ['desktop', 'tablet', 'mobile'];
        $langService = app(LanguageService::class);
        $originalLangId = $langService->currentId();
        $originalLocale = App::getLocale();

        $builtCount = 0;

        foreach ($languages as $lang) {
            $langId = (int) ($lang->id ?? 1);
            $langCode = strtolower((string) ($lang->code ?? 'en'));

            // Temporarily switch locale for translation evaluation
            App::setLocale($langCode);

            foreach ($devices as $device) {
                // 1. Render Header
                $headerHtml = self::renderHeaderForDeviceAndLanguage($device, $langId, $langCode);
                
                // Store in memory cache
                Cache::forever(self::CACHE_PREFIX_HEADER . "{$langId}_{$device}", $headerHtml);
                
                // Persist in DB setting
                CmsSetting::put(self::SETTING_PREFIX_HEADER . "{$langId}_{$device}", $headerHtml);

                // 2. Render Footer
                $footerHtml = self::renderFooterForDeviceAndLanguage($device, $langId, $langCode);
                
                // Store in memory cache
                Cache::forever(self::CACHE_PREFIX_FOOTER . "{$langId}_{$device}", $footerHtml);
                
                // Persist in DB setting
                CmsSetting::put(self::SETTING_PREFIX_FOOTER . "{$langId}_{$device}", $footerHtml);

                $builtCount += 2;
            }
        }

        // Restore original locale
        App::setLocale($originalLocale);

        // Update cache metadata
        CmsSetting::put(self::SETTING_CACHE_ENABLED, '1');
        CmsSetting::put(self::SETTING_CACHE_BUILT_AT, now()->toDateTimeString());
        CmsSetting::put(self::SETTING_CACHE_LANG_COUNT, (string) $languages->count());

        $elapsed = round((microtime(true) - $startTime) * 1000, 2);

        return [
            'languages_count' => $languages->count(),
            'devices_count'   => count($devices),
            'total_templates' => $builtCount,
            'elapsed_ms'      => $elapsed,
            'built_at'        => now()->toDateTimeString(),
        ];
    }

    /**
     * Clear all cached Header & Footer data from memory and persistent database storage.
     */
    public static function clearCache(): void
    {
        $languages = Language::getAllActive();
        $devices   = ['desktop', 'tablet', 'mobile'];

        foreach ($languages as $lang) {
            $langId = (int) ($lang->id ?? 1);
            foreach ($devices as $device) {
                Cache::forget(self::CACHE_PREFIX_HEADER . "{$langId}_{$device}");
                Cache::forget(self::CACHE_PREFIX_FOOTER . "{$langId}_{$device}");
                CmsSetting::forget(self::SETTING_PREFIX_HEADER . "{$langId}_{$device}");
                CmsSetting::forget(self::SETTING_PREFIX_FOOTER . "{$langId}_{$device}");
            }
        }

        CmsSetting::put(self::SETTING_CACHE_ENABLED, '0');
        CmsSetting::forget(self::SETTING_CACHE_BUILT_AT);
        CmsSetting::forget(self::SETTING_CACHE_LANG_COUNT);
    }

    /**
     * Internal renderer for Header HTML.
     */
    private static function renderHeaderForDeviceAndLanguage(string $device, int $langId, string $langCode): string
    {
        $hasBlocksTable = Schema::hasTable('cms_builder_blocks');
        $singleHeader   = (CmsSetting::get('single_header_config', '0') === '1');
        $evalDevice     = $singleHeader ? 'desktop' : $device;

        $headerBlocks = $hasBlocksTable ? CmsBuilderBlock::header()->withTranslationsForLanguage($langId)->where(function ($q) use ($singleHeader) {
            if ($singleHeader) {
                $q->where('is_active_desktop', true);
            } else {
                $q->where('is_active_desktop', true)
                  ->orWhere('is_active_tablet', true)
                  ->orWhere('is_active_mobile', true);
            }
        })->sortForDevice($evalDevice)->get() : collect();

        $useFallback = !$hasBlocksTable || $headerBlocks->isEmpty();

        $parsedBlocks = [];
        foreach ($headerBlocks as $block) {
            $parsedBlocks[$block->target_element ?? $block->id] = [
                'block'   => $block,
                'content' => HeaderFooterParserService::parse($block->getContentForDevice($evalDevice), $block->target_element),
            ];
        }

        $navMenu  = null;
        $navItems = null;
        try {
            if (Schema::hasTable('nav_menus')) {
                $menu = NavMenu::getPrimary();
                if ($menu) {
                    $navMenu  = $menu;
                    $flat     = $menu->items()->withTranslationsForLanguage($langId)->where('is_active', true)->get();
                    $navItems = NavItem::buildTree($flat);
                }
            }
        } catch (\Throwable) {}

        $stickySetting = CmsSetting::get('top_nav_sticky', '1');
        $isSticky      = in_array($stickySetting, ['1', 1, true, 'true'], true);
        $cssVars       = HeaderFooterCssManager::getActiveVariables();

        return View::make('livewire.public-header-inner', [
            'headerBlocks' => $headerBlocks,
            'parsedBlocks' => $parsedBlocks,
            'useFallback'  => $useFallback,
            'navMenu'      => $navMenu,
            'navItems'     => $navItems,
            'isSticky'     => $isSticky,
            'cssVars'      => $cssVars,
            'deviceView'   => $evalDevice,
            'singleHeader' => $singleHeader,
        ])->render();
    }

    /**
     * Internal renderer for Footer HTML.
     */
    private static function renderFooterForDeviceAndLanguage(string $device, int $langId, string $langCode): string
    {
        $hasBlocksTable = Schema::hasTable('cms_builder_blocks');
        $evalDevice     = $device;

        $footerBlocks = $hasBlocksTable ? CmsBuilderBlock::footer()->withTranslationsForLanguage($langId)->activeForDevice($evalDevice)->sortForDevice($evalDevice)->get() : collect();

        $parsedBlocks = [];
        foreach ($footerBlocks as $block) {
            $parsedBlocks[$block->target_element ?? $block->id] = [
                'block'   => $block,
                'content' => HeaderFooterParserService::parse($block->getContentForDevice($evalDevice), $block->target_element),
            ];
        }

        return View::make('livewire.public-footer-inner', [
            'footerBlocks' => $footerBlocks,
            'parsedBlocks' => $parsedBlocks,
            'device'       => $evalDevice,
            'deviceView'   => $evalDevice,
        ])->render();
    }
}
