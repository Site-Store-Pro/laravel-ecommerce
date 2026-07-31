@php
    try {
        $frontendDark = \App\Models\CmsSetting::isEnabled('frontend_dark_mode');
    } catch (\Exception $e) {
        $frontendDark = false;
    }
    try {
        $currentLang = app(\App\Services\LanguageService::class)->current();
        $isRtl = (bool) $currentLang->rtl;
    } catch (\Exception $e) {
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

                    <main class="p-0 m-0 w-full">
                        {{ $slot }}
                    </main>
                </div>

                <livewire:public-footer />
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
                    <h3 class="text-xl font-bold text-slate-900">@label('global.added_to_cart_heading', 'Added to Cart!')</h3>
                    <p class="text-sm text-slate-500 mt-1">@label('global.added_to_cart_message', 'You have successfully added this item to your shopping cart.')</p>
                </div>

                {{-- Item details --}}
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-left">
                    <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">@label('global.cart_modal_item_details', 'Item Details')</span>
                    <span class="font-bold text-slate-800 text-sm block mt-1" x-text="itemName"></span>
                    <span class="text-xs text-slate-500 block mt-0.5">@label('global.cart_modal_quantity', 'Quantity:') <span x-text="qty"></span></span>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col gap-2 pt-2">
                    <a :href="checkoutUrl"
                       class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md transition duration-150 block text-center">
                        @label('global.cart_modal_go_to_checkout', 'Go to Checkout')
                    </a>
                    <button type="button" @click="close()"
                            class="w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition duration-150">
                        @label('global.cart_modal_continue_shopping', 'Continue Shopping')
                    </button>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════════
             GLOBAL CART ERROR MODAL
             Placed here alongside the cart confirmation modal so it sits above
             ALL page stacking contexts. Triggered by any Livewire component
             calling $this->dispatch('show-cart-error', message: '...').
             Used by FeaturedItemsWidget, CrossSellListWidget, etc.
             ═══════════════════════════════════════════════════════════════════ --}}
        <div
            x-data="{
                show: false,
                message: '',
                open(detail) {
                    this.message = detail.message || 'An unexpected error occurred.';
                    this.show = true;
                    document.body.style.overflow = 'hidden';
                },
                close() {
                    this.show = false;
                    document.body.style.overflow = '';
                }
            }"
            x-on:show-cart-error.window="open($event.detail)"
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
                class="relative bg-white border border-rose-100 rounded-3xl p-8 shadow-2xl max-w-md w-full text-center space-y-5"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
            >
                {{-- Corner close button --}}
                <button type="button" @click="close()"
                        class="absolute top-4 right-4 p-1.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                {{-- Icon --}}
                <div class="inline-flex items-center justify-center p-3 rounded-full bg-rose-50 text-rose-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>

                {{-- Heading + message --}}
                <div class="space-y-2">
                    <h3 class="text-xl font-bold text-slate-900">@label('global.error_modal_heading', 'Unable to Add to Cart')</h3>
                    <p class="text-sm text-rose-600 font-medium leading-relaxed" x-text="message"></p>
                </div>

                {{-- Dismiss button --}}
                <button type="button" @click="close()"
                        class="w-full py-3 bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold rounded-xl shadow-md transition duration-150">
                    @label('global.error_modal_dismiss', 'Dismiss')
                </button>
            </div>
        </div>

        <x-toast-alert />
        @stack('scripts')
        <!-- Custom JS / Third-party scripts (DB-driven) -->
        <x-site-custom-js-loader />
        @livewireScripts
    </body>
</html>
