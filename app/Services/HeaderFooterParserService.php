<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\CmsSetting;
use App\Plugins\Support\ShortcodeProcessor;
use Illuminate\Support\Str;

class HeaderFooterParserService
{
    /**
     * Parse raw header or footer block content expanding mustache tags {{...}},
     * list menus, dynamic logo, search bars, e-commerce links, and plugin shortcodes.
     */
    public static function parse(?string $content, ?string $targetElement = null): string
    {
        if ($targetElement === 'header_logo') {
            return self::renderDynamicLogo();
        }

        if (empty($content)) {
            return '';
        }

        // 1. Expand Year tags
        $content = str_replace(['{{year}}', '{{Year}}', '#year#'], date('Y'), $content);

        // 2. Expand Search Bar tags
        if (Str::contains($content, ['{{Search Bar: Live Keyword Search}}', '{{Search Bar}}', '{{search_bar}}'])) {
            $searchHtml = self::renderSearchBar();
            $content = str_replace(
                ['{{Search Bar: Live Keyword Search}}', '{{Search Bar}}', '{{search_bar}}'],
                $searchHtml,
                $content
            );
        }

        // 2b. Expand Navigation Bar tags
        if (Str::contains($content, ['{{Navigation Bar}}', '{{navigation_bar}}', '{{top_nav_container}}'])) {
            $navMenu  = \App\Models\NavMenu::getPrimary();
            $navItems = $navMenu ? \App\Models\NavItem::buildTree($navMenu->items()->withCurrentTranslations()->where('is_active', true)->get()) : collect();
            $navHtml  = \Illuminate\Support\Facades\View::make('components.nav-dynamic', [
                'menu'      => $navMenu,
                'items'     => $navItems,
                'cartCount' => 0,
                'user'      => auth()->user(),
            ])->render();
            $content = str_replace(['{{Navigation Bar}}', '{{navigation_bar}}', '{{top_nav_container}}'], $navHtml, $content);
        }

        // 2c. Expand Cart Features tags
        if (Str::contains($content, ['{{Cart Features}}', '{{cart_features}}', '{{header_features}}', '{{Cart & User Account Icons}}', '{{cart_user_account_icons}}', '{{Cart & User Account}}', '{{cart_account}}', '{{user_account_icons}}'])) {
            $showDarkModeSwitcher = CmsSetting::isEnabled('show_frontend_dark_mode_switcher');
            $switcherHtml = '';
            if ($showDarkModeSwitcher) {
                $switcherHtml = '
                <div x-data="{
                    isDark: document.documentElement.classList.contains(\'dark\'),
                    init() {
                        var cookieMatch = document.cookie.match(/(?:^|;\s*)frontend_theme=([^;]+)/);
                        var stored = cookieMatch ? decodeURIComponent(cookieMatch[1]) : (localStorage.getItem(\'frontend_theme\') || \'\');
                        if (stored === \'dark\') {
                            this.isDark = true;
                            document.documentElement.classList.add(\'dark\');
                        } else if (stored === \'light\') {
                            this.isDark = false;
                            document.documentElement.classList.remove(\'dark\');
                        } else {
                            this.isDark = document.documentElement.classList.contains(\'dark\');
                        }
                    },
                    toggleTheme() {
                        this.isDark = !this.isDark;
                        document.documentElement.classList.toggle(\'dark\', this.isDark);
                        var val = this.isDark ? \'dark\' : \'light\';
                        try { localStorage.setItem(\'frontend_theme\', val); } catch (e) {}
                        document.cookie = \'frontend_theme=\' + val + \'; path=/; max-age=31536000; SameSite=Lax\';
                        if (window.Livewire) {
                            Livewire.dispatch(\'toggle-frontend-dark-mode\', { theme: val });
                        }
                    }
                }" x-init="init()">
                    <button type="button" @click="toggleTheme()" class="p-2 text-slate-700 dark:text-slate-200 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors focus:outline-none flex items-center justify-center cursor-pointer" title="Toggle Dark Mode" aria-label="Toggle dark mode">
                        <svg x-show="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
                        <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    </button>
                </div>';
            }
            $featuresHtml = '
            <div id="header_features_icons" class="flex items-center gap-3">
                ' . $switcherHtml . '
                <button type="button" onclick="Livewire.dispatch(\'open-cart\')" class="relative p-2 text-slate-700 dark:text-slate-200 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors focus:outline-none" aria-label="Shopping Cart">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </button>
                <a href="' . e(auth()->check() ? route('dashboard') : route('login')) . '" class="p-2 text-slate-700 dark:text-slate-200 hover:text-indigo-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </a>
            </div>';
            $content = str_replace([
                '{{Cart Features}}', '{{cart_features}}', '{{header_features}}',
                '{{Cart & User Account Icons}}', '{{cart_user_account_icons}}',
                '{{Cart & User Account}}', '{{cart_account}}', '{{user_account_icons}}'
            ], $featuresHtml, $content);
        }

        // 4. Expand News Flash Display
        if (Str::contains($content, ['{{News Flash Display}}', '{{news_flash}}'])) {
            $content = str_replace(['{{News Flash Display}}', '{{news_flash}}'], '[plugin:newsflash-2026]', $content);
        }

        // 5. Expand Social Media Icons
        if (Str::contains($content, ['{{Social Media Icons (Small)}}', '{{social_icons}}'])) {
            $content = str_replace(['{{Social Media Icons (Small)}}', '{{social_icons}}'], '[plugin:social-icons-2026]', $content);
        }

        // 6. Expand {{menu||id, label}} or {{menu||id}}
        $content = preg_replace_callback('/\{\{menu\|\|(\d+)(?:,\s*([^}]+))?\}\}/i', function ($matches) {
            $menuId = (int) $matches[1];
            return ListMenuRenderer::render($menuId);
        }, $content);

        // 7. Expand {{category||id, label}}
        $content = preg_replace_callback('/\{\{category\|\|(\d+)(?:,\s*([^}]+))?\}\}/i', function ($matches) {
            $catId = (int) $matches[1];
            $customLabel = trim($matches[2] ?? '');
            $category = Category::find($catId);
            if ($category) {
                $label = $customLabel ?: $category->name;
                return '<a href="' . e(route('shop.category', $category->slug)) . '">' . e($label) . '</a>';
            }
            return '<span class="text-slate-400 font-medium">' . e($customLabel ?: "Category #{$catId}") . '</span>';
        }, $content);

        // 8. Expand {{subcategory||id, label}}
        $content = preg_replace_callback('/\{\{subcategory\|\|(\d+)(?:,\s*([^}]+))?\}\}/i', function ($matches) {
            $catId = (int) $matches[1];
            $customLabel = trim($matches[2] ?? '');
            $category = Category::find($catId);
            if ($category) {
                $label = $customLabel ?: $category->name;
                return '<a href="' . e(route('shop.category', $category->slug)) . '">' . e($label) . '</a>';
            }
            return '<span class="text-slate-400 font-medium">' . e($customLabel ?: "Subcategory #{$catId}") . '</span>';
        }, $content);

        // 9. Expand {{brand||id, label}}
        $content = preg_replace_callback('/\{\{brand\|\|(\d+)(?:,\s*([^}]+))?\}\}/i', function ($matches) {
            $brandId = (int) $matches[1];
            $customLabel = trim($matches[2] ?? '');
            $brand = Brand::find($brandId);
            if ($brand) {
                $label = $customLabel ?: $brand->name;
                return '<a href="' . e(route('shop.brand', $brand->slug)) . '">' . e($label) . '</a>';
            }
            return '<span class="text-slate-400 font-medium">' . e($customLabel ?: "Brand #{$brandId}") . '</span>';
        }, $content);

        // 10. Pass through Blade dynamic parsing & ShortcodeProcessor plugin shortcodes
        try {
            $content = ContentParserService::parse($content);
        } catch (\Throwable) {
            // Keep parsed content if Blade evaluation is omitted or error occurs
        }

        return $content;
    }

    /**
     * Render the dynamic site logo using CmsSetting::resolveLogoUrl() and CmsSetting::getSiteName().
     */
    public static function renderDynamicLogo(): string
    {
        $siteName = CmsSetting::getSiteName();
        $logoData = CmsSetting::resolveLogoUrl();

        $showLogo  = CmsSetting::isEnabled('header_show_logo', true);
        $showTitle = CmsSetting::isEnabled('header_show_site_title', true);

        if (!$showLogo && !$showTitle) {
            return '';
        }

        $logoType = $logoData['type'] ?? null;
        $logoVal  = $logoData['value'] ?? null;

        $logoMediaHtml = '';
        if ($showLogo) {
            if ($logoType === 'svg' && !empty($logoVal)) {
                $logoMediaHtml = '<span class="site-logo-icon flex items-center shrink-0">' . $logoVal . '</span>';
            } elseif ($logoType === 'url' && !empty($logoVal)) {
                $logoMediaHtml = '<img src="' . e($logoVal) . '" alt="' . e($siteName) . '" class="site-logo-img max-h-10 w-auto object-contain shrink-0">';
            } else {
                // Default fallback SVG icon (snazzy ribbon emblem without box) using menu label color
                $logoMediaHtml = '<span class="site-logo-icon flex items-center justify-center shrink-0 text-slate-800 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors" style="color: var(--nav-text, currentColor);"><svg class="w-6 h-6 shrink-0 transition-transform duration-300 group-hover:scale-105" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14a5 5 0 100-10 5 5 0 000 10zM8.5 13.5L6.5 21 12 18.5 17.5 21l-2-7.5M12 7.5v3m-1.5-1.5h3"/></svg></span>';
            }
        }

        $titleHtml = '';
        if ($showTitle) {
            $titleHtml = '<span class="site-logo-title text-lg font-extrabold tracking-tight text-slate-900 dark:text-white group-hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors truncate max-w-[130px] xs:max-w-[180px] sm:max-w-none">' . e($siteName) . '</span>';
        }

        return '<a href="' . e(url('/')) . '" class="site-logo-link group inline-flex items-center gap-1 hover:opacity-95 transition-opacity w-auto max-w-max min-w-0 shrink-0 my-auto py-0.5" title="' . e($siteName) . '">'
            . $logoMediaHtml
            . $titleHtml
            . '</a>';
    }

    /**
     * Render live keyword search bar.
     */
    public static function renderSearchBar(): string
    {
        return ContentParserService::parse('[plugin:live-search-2026]');
    }

    /**
     * Render social media icons.
     */
    public static function renderSocialIcons(): string
    {
        $facebook  = CmsSetting::get('social_facebook', '#');
        $twitter   = CmsSetting::get('social_twitter', '#');
        $instagram = CmsSetting::get('social_instagram', '#');

        return '
        <div class="social-icons-wrapper flex items-center gap-3 text-slate-400 dark:text-slate-500">
            <a href="' . e($facebook) . '" target="_blank" rel="noopener" class="hover:text-indigo-500 transition-colors" aria-label="Facebook">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
            </a>
            <a href="' . e($twitter) . '" target="_blank" rel="noopener" class="hover:text-indigo-400 transition-colors" aria-label="Twitter">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/></svg>
            </a>
            <a href="' . e($instagram) . '" target="_blank" rel="noopener" class="hover:text-pink-500 transition-colors" aria-label="Instagram">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
            </a>
        </div>';
    }
}
