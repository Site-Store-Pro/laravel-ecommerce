<!DOCTYPE html>
<html lang="en" class="h-full bg-white dark:bg-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header and Footer Live Preview Frame</title>

    <!-- Tailwind CSS Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    {{-- flag-icons: CSS-based flag rendering for language switcher --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icons@7.2.3/css/flag-icons.min.css">

    <!-- Dynamic Header & Footer CSS Theme Variables -->
    <x-header-footer-styles />

    <style>
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        /* Prevent link default pointer cursor while preserving preview visuals */
        a {
            cursor: default !important;
        }
    </style>
</head>
<body class="bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 font-sans antialiased min-h-screen">

    @if($tab === 'header' || $tab === 'full_preview' || $tab === 'css_manager')
        <livewire:public-header :device-view="$device" />
    @endif

    @if($tab === 'full_preview')
        <div class="p-6 sm:p-12 text-center bg-slate-50 dark:bg-slate-800/40 my-6 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 mx-4">
            <h3 class="text-base font-bold text-slate-800 dark:text-white">Storefront Page Content Simulator</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-md mx-auto">
                Simulating catalog product grid and page body inside a <strong class="text-indigo-600 dark:text-indigo-400">{{ ucfirst($device) }}</strong> viewport width bounds.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
                <div class="h-20 bg-slate-200 dark:bg-slate-700/60 rounded-xl flex items-center justify-center text-xs font-semibold text-slate-500">Product Card #1</div>
                <div class="h-20 bg-slate-200 dark:bg-slate-700/60 rounded-xl flex items-center justify-center text-xs font-semibold text-slate-500">Product Card #2</div>
                <div class="h-20 bg-slate-200 dark:bg-slate-700/60 rounded-xl flex items-center justify-center text-xs font-semibold text-slate-500">Product Card #3</div>
            </div>
        </div>
    @endif

    @if($tab === 'footer' || $tab === 'full_preview' || $tab === 'css_manager')
        <livewire:public-footer :device-view="$device" />
    @endif

    @livewireScripts

    {{-- DISABLE EXTERNAL NAVIGATION IN PREVIEW MODE --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.body.addEventListener('click', function (e) {
                const targetLink = e.target.closest('a[href]');
                if (targetLink && !targetLink.hasAttribute('wire:click')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
