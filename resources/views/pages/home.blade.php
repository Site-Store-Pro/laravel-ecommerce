@php
    $page = Schema::hasTable('cms_pages')
        ? (\App\Models\CmsPage::withCurrentTranslations()->where('slug', 'home')->first()
            ?: \App\Models\CmsPage::withCurrentTranslations()->find(1))
        : null;
    $metaTitle = $page?->meta_title ?: (config('app.name', 'Support Ticketing') . ' | Premier E-Commerce & Customer Care');
    $metaDescription = $page?->meta_description ?: 'Browse our curated catalog of physical and digital products, enjoy fast fulfillment, and experience premier 24/7 support.';
    try {
        $frontendDark = \App\Models\CmsSetting::isEnabled('frontend_dark_mode');
    } catch (\Exception $e) {
        $frontendDark = false;
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth {{ $frontendDark ? 'dark' : '' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="theme-color" content="#f8fafc">
        <title>{{ $metaTitle }}</title>
        @if($metaDescription)
            <meta name="description" content="{{ $metaDescription }}">
        @endif

        @if($page && $page->custom_css)
            @if(str_contains($page->custom_css, '<style') || str_contains($page->custom_css, '<link'))
                {!! \App\Services\CssMinifierService::minify($page->custom_css) !!}
            @else
                <style>
                    {!! \App\Services\CssMinifierService::minify($page->custom_css) !!}
                </style>
            @endif
        @endif

        <!-- Google Fonts (DB-driven) -->
        <x-site-google-fonts-loader />

        <!-- Google Analytics (DB-driven) -->
        <x-site-google-analytics-loader />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @php
            $fileIconPack = App\Models\CmsSetting::get('file_icon_pack', 'vivid');
            $fileIconCssMap = [
                'vivid'   => 'https://cdn.jsdelivr.net/npm/file-icon-vectors@1.0.0/dist/file-icon-vivid.min.css',
                'classic' => 'https://cdn.jsdelivr.net/npm/file-icon-vectors@1.0.0/dist/file-icon-classic.min.css',
                'square'  => 'https://cdn.jsdelivr.net/npm/file-icon-vectors@1.0.0/dist/file-icon-square-o.min.css',
            ];
        @endphp
        <link rel="stylesheet" href="{{ $fileIconCssMap[$fileIconPack] ?? $fileIconCssMap['vivid'] }}">
        {{-- Swiper 11 Bundle (Global Slider Engine for Display Plugins) --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        {{-- flag-icons: CSS-based flag rendering for language switcher --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icons@7.2.3/css/flag-icons.min.css">
        {{-- AOS (Animate On Scroll) 2.3.4 --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
        @stack('styles')
        @livewireStyles
        <x-site-theme-styles />
        <x-header-footer-styles />
        <link rel="stylesheet" href="{{ asset('css/prose.css') }}">
    </head>
    <body class="antialiased font-sans bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 selection:bg-indigo-500 selection:text-white p-0 m-0 overflow-x-clip max-w-full">
        <!-- Background Glows (hidden in dark mode — light-tone glows are unsuitable on dark bg) -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10 dark:hidden">
            <div class="absolute top-[-20%] left-[-10%] w-[60vw] h-[60vw] rounded-full bg-gradient-to-tr from-indigo-200/30 to-violet-200/20 blur-3xl opacity-60"></div>
            <div class="absolute top-[40%] right-[-15%] w-[50vw] h-[50vw] rounded-full bg-gradient-to-br from-indigo-100/30 to-purple-100/20 blur-3xl opacity-50"></div>
        </div>

        @php
            $stickySetting    = \App\Models\CmsSetting::get('css_var_top_nav_sticky') ?? \App\Models\CmsSetting::get('top_nav_sticky', '1');
            $isStickyNav      = in_array($stickySetting, ['1', 1, true, 'true'], true);
            $stickyBodyOffset = \App\Models\CmsSetting::get('css_var_sticky_body_offset') ?? \App\Models\CmsSetting::get('sticky_body_offset', '0px');
            $stickyStyle      = ($isStickyNav && !empty($stickyBodyOffset) && $stickyBodyOffset !== '0px') ? 'padding-top: ' . $stickyBodyOffset . ';' : '';
        @endphp
        <div class="min-h-screen flex flex-col p-0 m-0 w-full relative">
            <livewire:public-header />

            <div class="flex-1 flex flex-col p-0 m-0 w-full main-sticky-offset site-main-content" style="{{ $stickyStyle }}">

                <!-- Slideshow Plugin full width block -->
                @if($page && !empty($page->include_slideshow))
                    <div class="w-full p-0 m-0 leading-none">
                        {!! \App\Services\ContentParserService::parse($page->include_slideshow) !!}
                    </div>
                @endif

                <main class="flex-1 p-0 m-0 w-full">
                    @if($page)
                        {!! $page->parsed_content !!}
                    @endif
                </main>
            </div>{{-- end flex-1 content --}}

            <livewire:public-footer />
        </div>{{-- end min-h-screen outer wrapper --}}

        @if($page && $page->custom_js)
            @if(str_contains($page->custom_js, '<script'))
                {!! $page->custom_js !!}
            @else
                <script>
                    {!! $page->custom_js !!}
                </script>
            @endif
        @endif
        @if($page && auth()->check() && auth()->user()->isAdmin())
            {{-- Floating Admin Edit Button --}}
            <div class="fixed bottom-6 right-6 z-50">
                <a href="{{ route('admin.cms-pages.edit', $page->id) }}"
                   target="cms_edit_{{ $page->id }}"
                   onclick="let w = window.open('', this.target); if(w) { try { if(w.location.href === 'about:blank' || w.location.href === '') { w.location.href = this.href; } } catch(e) { w.location.href = this.href; } w.focus(); } return false;"
                   class="inline-flex items-center gap-2 px-5 py-3 bg-slate-900 hover:bg-slate-800 text-white font-extrabold rounded-full shadow-2xl hover:shadow-indigo-500/20 hover:scale-105 transition-all group duration-200 border border-slate-800">
                    <svg class="w-4 h-4 text-indigo-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>@label('cms.edit_page', 'Edit Page')</span>
                </a>
            </div>
        @endif

        <livewire:slide-cart />
        <x-cart-confirmation-modal />
        <x-toast-alert />
        <!-- Custom JS / Third-party scripts (DB-driven) -->
        <x-site-custom-js-loader />
        {{-- AOS: Animate On Scroll — mobile-aware init --}}
        <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
        <script>
            // Strip data-aos from elements that have NOT opted in to mobile animation
            // (data-aos-mobile="true" marks an element as mobile-enabled)
            if (window.innerWidth < 768) {
                document.querySelectorAll('[data-aos]').forEach(function(el) {
                    if (el.getAttribute('data-aos-mobile') !== 'true') {
                        el.removeAttribute('data-aos');
                    }
                });
            }
            AOS.init({
                once:     true,
                offset:   80,
                duration: 600,
                easing:   'ease-out-cubic'
            });
        </script>
        @livewireScripts
    </body>
</html>
