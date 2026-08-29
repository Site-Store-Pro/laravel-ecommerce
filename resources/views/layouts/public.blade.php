@php
    $frontendDark = \App\Services\ThemePreferenceService::isFrontendDarkMode();
    try {
        $currentLang = app(\App\Services\LanguageService::class)->current();
        $isRtl = (bool) $currentLang->rtl;
    } catch (\Throwable $e) {
        $currentLang = null;
        $isRtl = false;
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="overflow-x-hidden max-w-full {{ $frontendDark ? 'dark' : '' }}"{{ $isRtl ? ' dir="rtl"' : '' }}>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Instant client-side theme gating to prevent FOUC / theme flash --}}
        <script>
            (function() {
                var cookieMatch = document.cookie.match(/(?:^|;\s*)(?:frontend_theme|theme_mode|visperity_theme|theme)=([^;]+)/);
                var storedCookie = cookieMatch ? decodeURIComponent(cookieMatch[1]) : null;
                var storedLocal = null;
                try { storedLocal = localStorage.getItem('frontend_theme') || localStorage.getItem('theme_mode'); } catch (e) {}
                var theme = storedLocal || storedCookie;
                var isDark = theme ? (theme === 'dark') : {{ $frontendDark ? 'true' : 'false' }};
                if (isDark) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            })();
        </script>

        @php $__siteName = \App\Models\CmsSetting::getSiteName(); @endphp
        <title>@yield('title', $metaTitle ?? $pageTitle ?? $__siteName)</title>
        @if(isset($metaDescription))
            <meta name="description" content="{{ $metaDescription }}">
        @endif
        @stack('meta')

        <!-- Favicon (DB-driven) -->
        <x-site-favicon-loader />

        <!-- Google Fonts (DB-driven) -->
        <x-site-google-fonts-loader />

        <!-- Google Analytics (DB-driven) -->
        <x-site-google-analytics-loader />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @if(config('services.recaptcha.site_key'))
        <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}" async defer></script>
        <script>window.recaptchaSiteKey = "{{ config('services.recaptcha.site_key') }}";</script>
        @endif
        @php
            $fileIconPack = App\Models\CmsSetting::get('file_icon_pack', 'vivid');
            $fileIconCssMap = [
                'vivid'   => 'https://cdn.jsdelivr.net/npm/file-icon-vectors@1.0.0/dist/file-icon-vivid.min.css',
                'classic' => 'https://cdn.jsdelivr.net/npm/file-icon-vectors@1.0.0/dist/file-icon-classic.min.css',
                'square'  => 'https://cdn.jsdelivr.net/npm/file-icon-vectors@1.0.0/dist/file-icon-square-o.min.css',
            ];
        @endphp
        {{-- Swiper 11 Bundle (Global Slider Engine for Display Plugins) --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

        @stack('styles')
        @livewireStyles
        <x-site-theme-styles />
        <x-header-footer-styles />
        <link rel="stylesheet" href="{{ asset('css/prose.css') }}">
        {{-- flag-icons: renders country code flags (us, mx, fr…) as SVG images on all browsers/OS --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icons@7.2.3/css/flag-icons.min.css">
    </head>
    @php
        $cmsPageModel   = $page ?? null;
        $cmsPageVidUrl  = ($cmsPageModel && method_exists($cmsPageModel, 'resolveBackgroundVideoUrl')) ? $cmsPageModel->resolveBackgroundVideoUrl() : null;
        $globalBgMode   = \App\Models\CmsSetting::get('page_bg_mode', 'default');
        $globalBgVidUrl = \App\Models\CmsSetting::resolvePageBgVideoUrl();
        $overlayColor   = \App\Models\CmsSetting::get('page_bg_overlay_color', '#000000');
        $overlayOpacity = \App\Models\CmsSetting::get('page_bg_overlay_opacity', '0');

        $activeVidUrl   = $cmsPageVidUrl ?: ($globalBgMode === 'video' ? $globalBgVidUrl : null);
        $pageBgMode     = $activeVidUrl ? 'video' : $globalBgMode;
    @endphp
    <body class="font-sans antialiased {{ $pageBgMode === 'default' ? 'bg-slate-50 dark:bg-slate-900' : 'bg-transparent' }} text-slate-900 dark:text-slate-100 selection:bg-indigo-500 selection:text-white p-0 m-0 overflow-x-clip max-w-full">

        @if($activeVidUrl)
            <div class="fixed inset-0 overflow-hidden -z-20 pointer-events-none">
                <video src="{{ $activeVidUrl }}" autoplay loop muted playsinline class="w-full h-full object-cover">
                    <source src="{{ $activeVidUrl }}" type="video/mp4">
                    <source src="{{ $activeVidUrl }}" type="video/webm">
                </video>
            </div>
        @endif

        @if(in_array($pageBgMode, ['image', 'video']) && floatval($overlayOpacity) > 0)
            <div class="fixed inset-0 -z-10 pointer-events-none" style="background-color: {{ $overlayColor }}; opacity: {{ $overlayOpacity }};"></div>
        @endif

        <div class="min-h-screen {{ $pageBgMode === 'default' ? 'bg-[#f8fafc] dark:bg-slate-900' : 'bg-transparent' }} flex flex-col p-0 m-0 w-full relative">
            @if($pageBgMode === 'default')
                {{-- Decorative Background Glows --}}
                <div class="pointer-events-none fixed inset-0 overflow-hidden -z-10" aria-hidden="true">
                    <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-200/20 dark:bg-indigo-900/10 rounded-full blur-3xl"></div>
                    <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-violet-200/20 dark:bg-violet-900/10 rounded-full blur-3xl"></div>
                </div>
            @endif

            <div class="flex-1 flex flex-col justify-between p-0 m-0 w-full">
                <div class="p-0 m-0 w-full">
                    <livewire:public-header />

                    @if (isset($header))
                        <header class="bg-white dark:bg-slate-800 border-b border-slate-100 dark:border-slate-700 shadow-sm">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endif

                    @php
                        $stickySetting    = \App\Models\CmsSetting::get('css_var_top_nav_sticky') ?? \App\Models\CmsSetting::get('top_nav_sticky', '1');
                        $isStickyNav      = in_array($stickySetting, ['1', 1, true, 'true'], true);
                        $stickyBodyOffset = \App\Models\CmsSetting::get('css_var_sticky_body_offset') ?? \App\Models\CmsSetting::get('sticky_body_offset', '0px');
                        $stickyStyle      = ($isStickyNav && !empty($stickyBodyOffset) && $stickyBodyOffset !== '0px') ? 'padding-top: ' . $stickyBodyOffset . ';' : '';
                    @endphp
                    <div class="flex-1 flex flex-col p-0 m-0 w-full main-sticky-offset site-main-content" style="{{ $stickyStyle }}">
                        <main class="p-0 m-0 w-full">
                            @yield('content')
                            {{ $slot ?? '' }}
                        </main>
                    </div>
                </div>

                <livewire:public-footer />
            </div>
        </div>

        <livewire:slide-cart />

        <x-cart-confirmation-modal />

        <x-toast-alert />
        @stack('scripts')
        <!-- Custom JS / Third-party scripts (DB-driven) -->
        <x-site-custom-js-loader />
        @livewireScripts
    </body>
</html>
