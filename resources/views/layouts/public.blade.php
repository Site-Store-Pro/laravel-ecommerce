@php
    try {
        $frontendDark = \App\Models\CmsSetting::isEnabled('frontend_dark_mode');
    } catch (\Exception $e) {
        $frontendDark = false;
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $frontendDark ? 'dark' : '' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php $__siteName = \App\Models\CmsSetting::getSiteName(); @endphp
        <title>@yield('title', $metaTitle ?? $pageTitle ?? $__siteName)</title>
        @if(isset($metaDescription))
            <meta name="description" content="{{ $metaDescription }}">
        @endif
        @stack('meta')

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
        {{-- file-icon-vectors: active pack driven by CMS setting --}}
        <link rel="stylesheet" href="{{ $fileIconCssMap[$fileIconPack] ?? $fileIconCssMap['vivid'] }}">
        @stack('styles')
        @livewireStyles
        <x-site-theme-styles />
        <link rel="stylesheet" href="{{ asset('css/prose.css') }}">
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900 dark:bg-slate-900 dark:text-slate-100 selection:bg-indigo-500 selection:text-white">
        <div class="min-h-screen bg-[#f8fafc] dark:bg-slate-900 flex flex-col">
            {{-- Decorative Background Glows (overflow-hidden here only, so it doesn't clip fixed modals) --}}
            <div class="pointer-events-none fixed inset-0 overflow-hidden -z-10" aria-hidden="true">
                <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-200/20 dark:bg-indigo-900/10 rounded-full blur-3xl"></div>
                <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-violet-200/20 dark:bg-violet-900/10 rounded-full blur-3xl"></div>
            </div>

            <div class="flex-1 flex flex-col justify-between">
                <div>
                    <livewire:public-navigation />

                    @if (isset($header))
                        <header class="bg-white dark:bg-slate-800 border-b border-slate-100 dark:border-slate-700 shadow-sm">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endif

                    <main>
                        {{ $slot }}
                    </main>
                </div>

                <footer class="border-t border-slate-200/60 dark:border-slate-700 bg-white dark:bg-slate-800 py-6 mt-16 text-center text-xs text-slate-400 dark:text-slate-500">
                    <p>&copy; {{ date('Y') }} {{ \App\Models\CmsSetting::getSiteName() }}. All rights reserved.</p>
                </footer>
            </div>
        </div>

        <livewire:slide-cart />

        {{-- ═══════════════════════════════════════════════════════════════════
             GLOBAL CART CONFIRMATION MODAL
             Placed here as a direct <body> child — above ALL page stacking
             contexts, including the sticky z-50 nav. Triggered by any
             Livewire component calling $this->dispatch('show-cart-modal', ...).
             ═══════════════════════════════════════════════════════════════════ --}}
        <div
            x-data="{
                show: false,
                itemName: '',
                qty: 1,
                checkoutUrl: '{{ route('shop.checkout') }}',
                open(detail) {
                    this.itemName = detail.itemName || '';
                    this.qty = detail.qty || 1;
                    this.show = true;
                    document.body.style.overflow = 'hidden';
                },
                close() {
                    this.show = false;
                    document.body.style.overflow = '';
                }
            }"
            x-on:show-cart-modal.window="open($event.detail)"
            x-show="show"
            x-cloak
            style="display:none"
            class="fixed inset-0 z-[99999] flex items-center justify-center p-4"
            @keydown.escape.window="close()"
        >
            {{-- Backdrop --}}
            <div
                class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
                @click="close()"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            ></div>

            {{-- Modal card --}}
            <div
                class="relative bg-white border border-slate-100 rounded-3xl p-8 shadow-2xl max-w-md w-full text-center space-y-6"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
            >
                {{-- Icon --}}
                <div class="inline-flex items-center justify-center p-3 rounded-full bg-indigo-50 text-indigo-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>

                {{-- Heading --}}
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Added to Cart!</h3>
                    <p class="text-sm text-slate-500 mt-1">You have successfully added this item to your shopping cart.</p>
                </div>

                {{-- Item details --}}
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-left">
                    <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Item Details</span>
                    <span class="font-bold text-slate-800 text-sm block mt-1" x-text="itemName"></span>
                    <span class="text-xs text-slate-500 block mt-0.5">Quantity: <span x-text="qty"></span></span>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col gap-2 pt-2">
                    <a :href="checkoutUrl"
                       class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md transition duration-150 block text-center">
                        Go to Checkout
                    </a>
                    <button type="button" @click="close()"
                            class="w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition duration-150">
                        Continue Shopping
                    </button>
                </div>
            </div>
        </div>

        <x-toast-alert />
        @stack('scripts')
        <!-- Custom JS / Third-party scripts (DB-driven) -->
        <x-site-custom-js-loader />
        @livewireScripts
    </body>
</html>
