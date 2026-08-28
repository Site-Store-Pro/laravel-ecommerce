@php
    $isCustomer = auth()->check() && in_array(auth()->user()->role_id?->value, [1, 2]);
    if ($isCustomer) {
        $sessionTheme = session('frontend_theme') ?: session('theme_mode');
        $cookieTheme = request()->cookie('frontend_theme')
            ?: ($_COOKIE['frontend_theme'] ?? (request()->cookie('theme_mode') ?: ($_COOKIE['theme_mode'] ?? null)));

        if (!empty(auth()->user()->theme_preference)) {
            $isDark = auth()->user()->theme_preference === 'dark';
        } elseif (!empty($sessionTheme)) {
            $isDark = $sessionTheme === 'dark';
        } elseif (!empty($cookieTheme)) {
            $isDark = $cookieTheme === 'dark';
        } else {
            try {
                $isDark = \App\Models\CmsSetting::isEnabled('frontend_dark_mode');
            } catch (\Throwable $e) {
                $isDark = false;
            }
        }
    } else {
        try {
            $isDark = \App\Models\CmsSetting::isEnabled('admin_dark_mode');
        } catch (\Throwable $e) {
            $isDark = false;
        }
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $isDark ? 'dark' : '' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Instant client-side theme gating for customer dashboard --}}
        @if($isCustomer)
        <script>
            (function() {
                var cookieMatch = document.cookie.match(/(?:^|;\s*)(?:frontend_theme|theme_mode)=([^;]+)/);
                var storedCookie = cookieMatch ? decodeURIComponent(cookieMatch[1]) : null;
                var storedLocal = null;
                try { storedLocal = localStorage.getItem('frontend_theme'); } catch (e) {}
                var theme = storedCookie || storedLocal;
                var isDark = theme ? (theme === 'dark') : {{ $isDark ? 'true' : 'false' }};
                if (isDark) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            })();
        </script>
        @endif

        <title>{{ \App\Models\CmsSetting::getSiteName() }}</title>

        <!-- Favicon (DB-driven) -->
        <x-site-favicon-loader />

        <!-- Google Fonts (DB-driven) -->
        <x-site-google-fonts-loader />

        <!-- Google Analytics (DB-driven) -->
        <x-site-google-analytics-loader />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @php
            $fileIconPack = App\Models\CmsSetting::get('file_icon_pack', 'vivid');
            $fileIconCssMap = [
                'vivid'   => 'https://cdn.jsdelivr.net/npm/file-icon-vectors@1.0.0/dist/file-icon-vivid.min.css',
                'classic' => 'https://cdn.jsdelivr.net/npm/file-icon-vectors@1.0.0/dist/file-icon-classic.min.css',
                'square'  => 'https://cdn.jsdelivr.net/npm/file-icon-vectors@1.0.0/dist/file-icon-square-o.min.css',
            ];
            // Load all three packs so the settings page live-preview works;
            // each pack's class names differ so only the correct icons render.
        @endphp
        {{-- file-icon-vectors: all packs (vivid/classic/square) --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/file-icon-vectors@1.0.0/dist/file-icon-classic.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/file-icon-vectors@1.0.0/dist/file-icon-square-o.min.css">
        <link rel="stylesheet" href="{{ $fileIconCssMap[$fileIconPack] ?? $fileIconCssMap['vivid'] }}" id="file-icon-active-pack">
        @stack('styles')
        <x-site-theme-styles />
        <link rel="stylesheet" href="{{ asset('css/prose.css') }}">
        {{-- flag-icons: renders country code flags (us, mx, fr…) as SVG images on all browsers/OS --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icons@7.2.3/css/flag-icons.min.css">
        {{-- SortableJS: drag-and-drop ordering --}}
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900 dark:bg-slate-900 dark:text-slate-100 selection:bg-indigo-500 selection:text-white">
        <div class="min-h-screen bg-[#f8fafc] dark:bg-slate-900 relative overflow-hidden">
            <!-- Decorative Background Glows -->
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-200/20 dark:bg-indigo-900/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-violet-200/20 dark:bg-violet-900/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10">
                @if(auth()->check() && in_array(auth()->user()->role_id?->value, [1, 2]))
                    <livewire:public-navigation />
                @else
                    <livewire:layout.navigation />
                @endif

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-slate-800 shadow dark:shadow-slate-700/50">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
            </div>
        </div>
    @stack('scripts')
    <!-- Custom JS / Third-party scripts (DB-driven) -->
    <x-site-custom-js-loader />
    <x-cart-confirmation-modal />
    <x-toast-alert />
    @livewireScripts
    </body>
</html>
