@php
    $page = Schema::hasTable('cms_pages') ? \App\Models\CmsPage::find(1) : null;
    $metaTitle = $page?->meta_title ?: (config('app.name', 'Support Ticketing') . ' | Customer Support, Simplified');
    $metaDescription = $page?->meta_description ?: 'Submit support tickets, browse physical and digital merchandise, download your orders instantly, and resolve queries.';
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
                {!! $page->custom_css !!}
            @else
                <style>
                    {!! $page->custom_css !!}
                </style>
            @endif
        @endif

        <!-- Google Fonts (DB-driven) -->
        <x-site-google-fonts-loader />

        <!-- Google Analytics (DB-driven) -->
        <x-site-google-analytics-loader />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        <x-site-theme-styles />
    </head>
    <body class="antialiased font-sans bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 overflow-x-hidden selection:bg-indigo-500 selection:text-white">
        <!-- Background Glows -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[-20%] left-[-10%] w-[60vw] h-[60vw] rounded-full bg-gradient-to-tr from-indigo-200/30 to-violet-200/20 blur-3xl opacity-60"></div>
            <div class="absolute top-[40%] right-[-15%] w-[50vw] h-[50vw] rounded-full bg-gradient-to-br from-indigo-100/30 to-purple-100/20 blur-3xl opacity-50"></div>
        </div>

        <div class="min-h-[100dvh] flex flex-col justify-between relative">
            <livewire:public-navigation />

            <!-- Hero Section -->
            <main class="flex-1 flex flex-col justify-center py-20 lg:py-32">
                <!-- add cms_pages image include -->
                <livewire:cms-home-image />

                <!-- Add cms_pages content include -->
                <livewire:cms-home-content />
            </main>

            <!-- Footer -->
            @include('layouts.footer', ['theme' => $frontendDark ? 'dark' : 'light'])
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
        <livewire:slide-cart />
        <x-toast-alert />
        <!-- Custom JS / Third-party scripts (DB-driven) -->
        <x-site-custom-js-loader />
        @livewireScripts
    </body>
</html>
