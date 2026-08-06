@php
    $metaTitle = $page?->meta_title ?: ($page?->alternate_page_title ?: $page?->title);
    $metaDescription = $page?->meta_description;
    try {
        $frontendDark = \App\Models\CmsSetting::isEnabled('frontend_dark_mode');
    } catch (\Exception $e) {
        $frontendDark = false;
    }

    $alignment = $page?->page_title_alignment ?: 'middle-center';
    $alignMap = [
        'top-left'      => ['text' => 'justify-start items-start text-left', 'horizontal' => 'text-left items-start', 'author_justify' => 'justify-start'],
        'middle-left'   => ['text' => 'justify-center items-start text-left', 'horizontal' => 'text-left items-start', 'author_justify' => 'justify-start'],
        'bottom-left'   => ['text' => 'justify-end items-start text-left', 'horizontal' => 'text-left items-start', 'author_justify' => 'justify-start'],
        'top-center'    => ['text' => 'justify-start items-center text-center', 'horizontal' => 'text-center items-center', 'author_justify' => 'justify-center'],
        'middle-center' => ['text' => 'justify-center items-center text-center', 'horizontal' => 'text-center items-center', 'author_justify' => 'justify-center'],
        'bottom-center' => ['text' => 'justify-end items-center text-center', 'horizontal' => 'text-center items-center', 'author_justify' => 'justify-center'],
        'top-right'     => ['text' => 'justify-start items-end text-right', 'horizontal' => 'text-right items-end', 'author_justify' => 'justify-end'],
        'middle-right'  => ['text' => 'justify-center items-end text-right', 'horizontal' => 'text-right items-end', 'author_justify' => 'justify-end'],
        'bottom-right'  => ['text' => 'justify-end items-end text-right', 'horizontal' => 'text-right items-end', 'author_justify' => 'justify-end'],
    ];
    $classes = $alignMap[$alignment] ?? $alignMap['middle-center'];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth {{ $frontendDark ? 'dark' : '' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="theme-color" content="#f8fafc">
        <meta name="csrf-token" content="{{ csrf_token() }}">
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

        @if($page && $page->page_title_css)
            <style>
                {!! \App\Services\CssMinifierService::minify($page->page_title_css) !!}
            </style>
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
        {{-- file-icon-vectors: active pack driven by CMS setting --}}
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
    @php
        $cmsPageVidUrl  = $page?->resolveBackgroundVideoUrl();
        $globalBgVidUrl = \App\Models\CmsSetting::resolvePageBgVideoUrl();
        $globalBgMode   = \App\Models\CmsSetting::get('page_bg_mode', 'default');
        $overlayColor   = \App\Models\CmsSetting::get('page_bg_overlay_color', '#000000');
        $overlayOpacity = \App\Models\CmsSetting::get('page_bg_overlay_opacity', '0');

        $activeVidUrl = $cmsPageVidUrl ?: ($globalBgMode === 'video' ? $globalBgVidUrl : null);
        $activeMode   = $activeVidUrl ? 'video' : ($page?->background_image ? 'image' : $globalBgMode);
    @endphp
    <body class="antialiased font-sans {{ $activeMode === 'default' ? 'bg-slate-50 dark:bg-slate-900' : 'bg-transparent' }} text-slate-800 dark:text-slate-100 selection:bg-indigo-500 selection:text-white p-0 m-0 overflow-x-clip max-w-full">
        <!-- Background Video / Background Glows / Background Image support -->
        @if($activeVidUrl)
            <div class="fixed inset-0 overflow-hidden -z-20 pointer-events-none">
                <video src="{{ $activeVidUrl }}" autoplay loop muted playsinline class="w-full h-full object-cover">
                    <source src="{{ $activeVidUrl }}" type="video/mp4">
                    <source src="{{ $activeVidUrl }}" type="video/webm">
                </video>
            </div>
            @if(floatval($overlayOpacity) > 0)
                <div class="fixed inset-0 -z-10 pointer-events-none" style="background-color: {{ $overlayColor }}; opacity: {{ $overlayOpacity }};"></div>
            @endif
        @elseif($page && $page->background_image)
            <div class="fixed inset-0 overflow-hidden pointer-events-none bg-fixed bg-cover bg-center"
                 style="background-image: url('{{ $page->backgroundImageUrl() }}');"></div>
            @if(floatval($overlayOpacity) > 0)
                <div class="fixed inset-0 -z-10 pointer-events-none" style="background-color: {{ $overlayColor }}; opacity: {{ $overlayOpacity }};"></div>
            @endif
        @else
            <div class="fixed inset-0 overflow-hidden pointer-events-none dark:hidden">
                <div class="absolute top-[-20%] left-[-10%] w-[60vw] h-[60vw] rounded-full bg-gradient-to-tr from-indigo-200/30 to-violet-200/20 blur-3xl opacity-60"></div>
                <div class="absolute top-[40%] right-[-15%] w-[50vw] h-[50vw] rounded-full bg-gradient-to-br from-indigo-100/30 to-purple-100/20 blur-3xl opacity-50"></div>
            </div>
        @endif

        <div class="min-h-screen flex flex-col p-0 m-0 w-full relative">
            <livewire:public-header />

            <div class="flex-1 flex flex-col p-0 m-0 w-full">

            <!-- Slideshow Plugin full width block -->
            @if($page && !empty($page->include_slideshow))
                <div class="w-full p-0 m-0 leading-none">
                    {!! \App\Services\ContentParserService::parse($page->include_slideshow) !!}
                </div>
            @endif

            <!-- Header Image / Clean Title section -->
            @if($page && $page->header_image)
                <div class="w-full relative flex bg-cover bg-center text-white mb-12 shadow-inner" 
                     style="min-height: {{ $page->min_header_height ?: '320px' }}; background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.5)), url('{{ $page->headerImageUrl() }}');">
                    <div class="max-w-5xl w-full mx-auto px-6 relative z-10 flex flex-col flex-1 py-12 {{ $classes['text'] }}">
                        @if($page->show_title)
                            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4 drop-shadow-md">
                                {{ $page->alternate_page_title ?: $page->title }}
                            </h1>
                        @endif
                        @if($page->show_author || $page->show_date)
                            <div class="flex items-center gap-3 text-sm text-slate-300 font-medium {{ $classes['author_justify'] }}">
                                @if($page->show_author)
                                    <span class="bg-indigo-600/80 px-2.5 py-1 rounded-full text-xs text-white uppercase tracking-wider">@label('cms.author', 'Author')</span>
                                    <span>{{ $page->custom_author ?: ($page->author ? $page->author->name : 'System') }}</span>
                                @endif
                                @if($page->show_author && $page->show_date)
                                    <span>•</span>
                                @endif
                                @if($page->show_date)
                                    <span>{{ $page->created_at->format('M d, Y') }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @else
                @if($page && ($page->show_title || $page->show_author || $page->show_date))
                    <div class="max-w-5xl mx-auto px-6 pt-8 pb-0 w-full flex flex-col {{ $classes['horizontal'] }}">
                        <div class="border-b border-slate-200/80 pb-0 mb-6 w-full flex flex-col {{ $classes['horizontal'] }} {{ $classes['text'] }}">
                            @if($page->show_title)
                                <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 leading-snug pb-2 bg-gradient-to-r from-slate-900 to-indigo-950 bg-clip-text text-transparent dark:bg-none dark:text-slate-200 mb-4">
                                    {{ $page->alternate_page_title ?: $page->title }}
                                </h1>
                            @endif
                            @if($page->show_author || $page->show_date)
                                <div class="flex items-center justify-center md:justify-start gap-3 text-sm text-slate-500">
                                    @if($page->show_author)
                                        <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs font-semibold uppercase tracking-wider">@label('cms.published_by', 'Published By')</span>
                                        <span class="font-semibold text-slate-700">{{ $page->custom_author ?: ($page->author ? $page->author->name : 'System') }}</span>
                                    @endif
                                    @if($page->show_author && $page->show_date)
                                        <span>•</span>
                                    @endif
                                    @if($page->show_date)
                                        <span>{{ $page->created_at->format('M d, Y') }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endif

            <!-- Main Layout -->
            <main class="flex-1 p-0 m-0 w-full">
                @php
                    $hasSidebars = $page && (
                        (in_array($page->layout_type, [2, 4]) && !empty($page->left_col)) ||
                        (in_array($page->layout_type, [3, 4]) && !empty($page->right_col))
                    );
                @endphp

                @if($hasSidebars)
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full">
                        <div class="flex flex-col lg:flex-row gap-8 items-start">
                            @if($page && in_array($page->layout_type, [2, 4]) && !empty($page->left_col))
                                <!-- Left Sidebar -->
                                <div class="w-full lg:w-1/4 space-y-6">
                                    <div class="bg-white/95 backdrop-blur-md border border-slate-100 rounded-3xl p-6 shadow-xl shadow-slate-100/40">
                                        {!! $page->parsed_left_col !!}
                                    </div>
                                </div>
                            @endif

                            <!-- Main Column -->
                            <div class="w-full lg:flex-1 space-y-6">
                                <div class="cms-card-wrapper card bg-white/95 dark:bg-slate-800/95 backdrop-blur-md border border-slate-150 dark:border-slate-700/60 rounded-3xl p-6 sm:p-8 md:p-10 shadow-xl shadow-slate-100/40 dark:shadow-none">
                                    @if($page)
                                        {!! $page->parsed_content !!}

                                        @if($page->tags->count() > 0)
                                            <div class="mt-8 pt-6 border-t border-slate-100/60 flex flex-wrap gap-2 items-center">
                                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-2">@label('cms.tags', 'Tags:')</span>
                                                @foreach($page->tags as $tag)
                                                    <a href="{{ route('cms.tag', $tag->slug) }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-all cursor-pointer">
                                                        #{{ $tag->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if(!$page->hide_page_ranking)
                                            <div class="mt-6">
                                                <livewire:cms-page-rating :pageId="$page->id" />
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            @if($page && in_array($page->layout_type, [3, 4]) && !empty($page->right_col))
                                <!-- Right Sidebar -->
                                <div class="w-full lg:w-1/4 space-y-6">
                                    <div class="bg-white/95 dark:bg-slate-800/95 backdrop-blur-md border border-slate-100 rounded-3xl p-6 shadow-xl shadow-slate-100/40">
                                        {!! $page->parsed_right_col !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    {{-- Standard full-width page without sidebars --}}
                    @if($page)
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full">
                            <div class="cms-card-wrapper card bg-white/95 dark:bg-slate-800/95 backdrop-blur-md border border-slate-150 dark:border-slate-700/60 rounded-3xl p-6 sm:p-8 md:p-10 shadow-xl shadow-slate-100/40 dark:shadow-none">
                                {!! $page->parsed_content !!}

                                @if($page->tags->count() > 0)
                                    <div class="mt-8 pt-6 border-t border-slate-100/60 flex flex-wrap gap-2 items-center">
                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-2">@label('cms.tags', 'Tags:')</span>
                                        @foreach($page->tags as $tag)
                                            <a href="{{ route('cms.tag', $tag->slug) }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-all cursor-pointer">
                                                #{{ $tag->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                                @if(!$page->hide_page_ranking)
                                    <div class="mt-6">
                                        <livewire:cms-page-rating :pageId="$page->id" />
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
            </main>

            </div>{{-- end flex-1 content --}}

            <!-- Footer -->
            <livewire:public-footer />
        </div>{{-- end min-h-screen outer wrapper --}}

        @if(auth()->check() && auth()->user()->isAdmin())
            <!-- Floating Admin Edit Button -->
            <div class="fixed bottom-6 right-6 z-50">
                <a href="{{ route('admin.cms-pages.edit', $page->id) }}" 
                   target="cms_edit_{{ $page->id }}"
                   onclick="let w = window.open('', this.target); if(w) { try { if(w.location.href === 'about:blank' || w.location.href === '') { w.location.href = this.href; } } catch(e) { w.location.href = this.href; } w.focus(); } return false;"
                   class="inline-flex items-center gap-2 px-5 py-3 bg-slate-900 hover:bg-slate-800 dark:bg-indigo-600 dark:hover:bg-indigo-700 text-white font-extrabold rounded-full shadow-2xl hover:shadow-indigo-500/20 hover:scale-105 transition-all group duration-200 border border-slate-800 dark:border-indigo-500">
                    <svg class="w-4 h-4 text-indigo-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>@label('cms.edit_page', 'Edit Page')</span>
                </a>
            </div>
        @endif

        <livewire:slide-cart />

        {{-- ═══════════════════════════════════════════════════════════════════
             GLOBAL CART CONFIRMATION MODAL
             Triggered by any Livewire component calling $this->dispatch('show-cart-modal', ...).
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

        <!-- Custom JS / Third-party scripts (DB-driven) -->
        <x-site-custom-js-loader />
        @livewireScripts
    </body>
</html>
