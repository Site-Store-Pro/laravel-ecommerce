@php
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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#f8fafc">
    <title>{{ config('app.name', 'SupportDesk') }}</title>

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
        {{-- file-icon-vectors: active pack driven by CMS setting --}}
        <link rel="stylesheet" href="{{ $fileIconCssMap[$fileIconPack] ?? $fileIconCssMap['vivid'] }}">
        {{-- flag-icons: CSS-based flag rendering for language switcher --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icons@7.2.3/css/flag-icons.min.css">
        @stack('styles')
        @livewireStyles
    <x-site-theme-styles />
    <link rel="stylesheet" href="{{ asset('css/prose.css') }}">
</head>
<body class="antialiased font-sans bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 overflow-x-hidden selection:bg-indigo-500 selection:text-white">

    <!-- Background Glows -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-20%] left-[-10%] w-[60vw] h-[60vw] rounded-full bg-gradient-to-tr from-indigo-200/30 to-violet-200/20 dark:from-indigo-900/20 dark:to-violet-900/10 blur-3xl opacity-60"></div>
        <div class="absolute top-[40%] right-[-15%] w-[50vw] h-[50vw] rounded-full bg-gradient-to-br from-indigo-100/30 to-purple-100/20 dark:from-indigo-900/10 dark:to-purple-900/10 blur-3xl opacity-50"></div>
    </div>

    <div class="min-h-[100dvh] flex flex-col justify-between relative">
        <!-- Public Navigation Header -->
        <livewire:public-navigation />

        <!-- Auth Card Content -->
        <main class="flex-1 flex flex-col items-center justify-center px-4 py-10">
            <!-- Card -->
            <div class="w-full max-w-md">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer -->
        @include('layouts.footer', ['theme' => $frontendDark ? 'dark' : 'light'])
    </div>

    <x-toast-alert />
    <!-- Custom JS / Third-party scripts (DB-driven) -->
    <x-site-custom-js-loader />
    @livewireScripts
</body>
</html>
