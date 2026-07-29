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
        @stack('styles')
        @livewireStyles
        <x-site-theme-styles />
        <x-header-footer-styles />
        <link rel="stylesheet" href="{{ asset('css/prose.css') }}">
    </head>
    <body class="antialiased font-sans bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 overflow-x-hidden selection:bg-indigo-500 selection:text-white p-0 m-0">
        <!-- Background Glows -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
            <div class="absolute top-[-20%] left-[-10%] w-[60vw] h-[60vw] rounded-full bg-gradient-to-tr from-indigo-200/30 to-violet-200/20 blur-3xl opacity-60"></div>
            <div class="absolute top-[40%] right-[-15%] w-[50vw] h-[50vw] rounded-full bg-gradient-to-br from-indigo-100/30 to-purple-100/20 blur-3xl opacity-50"></div>
        </div>

        <div class="min-h-[100dvh] flex flex-col justify-between relative p-0 m-0 w-full">
            <livewire:public-header />

            <main class="flex-1 p-0 m-0 w-full">
                @if($page)
                    {!! $page->parsed_content !!}
                @endif
            </main>

            <livewire:public-footer />
        </div>

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
        <x-toast-alert />
        <!-- Custom JS / Third-party scripts (DB-driven) -->
        <x-site-custom-js-loader />
        @livewireScripts
    </body>
</html>
