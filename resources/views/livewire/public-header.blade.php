<div class="w-full {{ $isSticky ? 'sticky top-0 z-[999] shadow-md' : 'relative z-40' }}">
    @if($useFallback)
        <livewire:public-navigation />
    @else
        <header class="header_container w-full relative bg-[var(--header-background-color)]">
            
@php
    $navPlacement = \App\Models\CmsSetting::get('nav_placement');
    if (empty($navPlacement)) {
        $navInside = !empty(\App\Models\CmsSetting::get('css_var_nav_inside_main_header')) || !empty(\App\Models\CmsSetting::get('nav_inside_main_header'));
        $navPlacement = $navInside ? 'main_header' : 'standalone';
    }

    $featuresPlacement = \App\Models\CmsSetting::get('features_placement', 'main_header');

    $sortCol = match(strtolower($deviceView ?? 'desktop')) {
        'tablet', 'medium' => 'sort_tablet',
        'mobile', 'small'  => 'sort_mobile',
        default            => 'sort_desktop',
    };

    $activeHeaderRows = $headerBlocks->where('type', 1)->filter(function($b) use ($deviceView) {
        return $b->isActiveForDevice($deviceView);
    })->sortBy(function($b) use ($sortCol) {
        return $b->{$sortCol};
    });
@endphp

            @foreach($activeHeaderRows as $rowBlock)

                {{-- Top Sharing / Alerts Container --}}
                @if($rowBlock->target_element === 'top_sharing_container')
                    <div class="top_sharing_container">
                        <div id="top_sharing_section">
                            <ul class="top_sharing_contents flex w-full items-center justify-between gap-4">
                                @if(isset($parsedBlocks['sharing_col1']) && $parsedBlocks['sharing_col1']['block']->isActiveForDevice($deviceView))
                                    <li class="sharing_col1 flex-1 min-w-0 transition-all duration-300">{!! $parsedBlocks['sharing_col1']['content'] !!}</li>
                                @endif
                                @if(isset($parsedBlocks['sharing_col2']) && $parsedBlocks['sharing_col2']['block']->isActiveForDevice($deviceView))
                                    <li class="sharing_col2 flex-1 min-w-0 transition-all duration-300">{!! $parsedBlocks['sharing_col2']['content'] !!}</li>
                                @endif
                                @if(isset($parsedBlocks['sharing_col3']) && $parsedBlocks['sharing_col3']['block']->isActiveForDevice($deviceView))
                                    <li class="sharing_col3 flex-1 min-w-0 transition-all duration-300">{!! $parsedBlocks['sharing_col3']['content'] !!}</li>
                                @endif
                            </ul>
                        </div>
                    </div>

                {{-- Main Header Container --}}
                @elseif($rowBlock->target_element === 'site_header_container')
                    <div class="site_header_container">
                        <div id="site_header" class="w-full">
                            <div class="site_header_contents flex w-full items-center justify-between gap-2 sm:gap-4 py-3 md:py-4">
                                
                                {{-- Logo Area --}}
                                @if(isset($parsedBlocks['header_logo']) && $parsedBlocks['header_logo']['block']->isActiveForDevice($deviceView))
                                    <div class="header_logo shrink-0 w-auto max-w-max flex items-center justify-center my-auto py-1 min-w-0 mr-auto lg:mr-0">
                                        {!! $parsedBlocks['header_logo']['content'] !!}
                                    </div>
                                @endif

                                {{-- Middle Section: Header Columns + Embedded Navigation --}}
                                <div class="flex-1 min-w-0 flex flex-col justify-center gap-1 shrink">
                                    @if($navPlacement === 'main_header')
                                        <nav class="top_nav_container w-full min-w-0 shrink hidden lg:flex">
                                            <div id="top_nav_area" class="w-full min-w-0">
                                                @if($navMenu && $navItems && count($navItems) > 0)
                                                    <x-nav-dynamic :menu="$navMenu" :items="$navItems" :cartCount="$cartCount" :user="auth()->user()" />
                                                @else
                                                    <div id="top_nav_contents" class="py-1 px-2 flex items-center justify-start bg-transparent">
                                                        <ul class="flex items-center gap-6 text-sm font-semibold text-slate-800 dark:text-slate-200">
                                                            <li><a href="{{ url('/') }}" class="hover:text-indigo-600 transition-colors">@label('nav.home', 'Home')</a></li>
                                                            <li><a href="{{ route('shop.index') }}" class="hover:text-indigo-600 transition-colors">@label('nav.shop', 'Shop')</a></li>
                                                            <li><a href="{{ route('kb.index') }}" class="hover:text-indigo-600 transition-colors">@label('nav.knowledge_base', 'Knowledge Base')</a></li>
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        </nav>
                                    @endif

                                    <div class="flex items-center gap-4 w-full min-w-0 shrink">
                                        @if(isset($parsedBlocks['header_col1']) && $parsedBlocks['header_col1']['block']->isActiveForDevice($deviceView))
                                            <div class="header_col1 flex-1 min-w-0 shrink hidden md:block transition-all duration-300">
                                                {!! $parsedBlocks['header_col1']['content'] !!}
                                                @if($navPlacement === 'header_col1')
                                                    <nav class="top_nav_container w-full hidden lg:flex mt-1">
                                                        <div id="top_nav_area_col1" class="w-full">
                                                            @if($navMenu && $navItems && count($navItems) > 0)
                                                                <x-nav-dynamic :menu="$navMenu" :items="$navItems" :cartCount="$cartCount" :user="auth()->user()" />
                                                            @else
                                                                <div class="py-1 px-2 flex items-center gap-4 text-xs font-semibold">
                                                                    <a href="{{ url('/') }}" class="hover:text-indigo-600 font-semibold">@label('nav.home', 'Home')</a>
                                                                    <a href="{{ route('shop.index') }}" class="hover:text-indigo-600 font-semibold">@label('nav.shop', 'Shop')</a>
                                                                    <a href="{{ route('kb.index') }}" class="hover:text-indigo-600 font-semibold">@label('nav.knowledge_base', 'Knowledge Base')</a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </nav>
                                                @endif
                                                @if($featuresPlacement === 'header_col1')
                                                    <div id="header_features_icons_col1" class="flex items-center gap-3 mt-1">
                                                        @livewire('language-switcher')
                                                        <button type="button" wire:click.prevent="$dispatch('open-cart')" @click="$dispatch('open-cart')" class="relative p-2 text-slate-700 dark:text-slate-200 hover:text-indigo-600 transition-colors focus:outline-none" aria-label="Shopping Cart">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                                            @if($cartCount > 0)
                                                                <span class="absolute -top-1 -right-1 bg-indigo-600 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center shadow-sm">{{ $cartCount }}</span>
                                                            @endif
                                                        </button>
                                                        <a href="{{ auth()->check() ? route('dashboard') : route('login') }}"
                                                           class="p-2 text-slate-700 dark:text-slate-200 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
                                                           title="{{ auth()->check() ? auth()->user()->name : siteLabel('nav.sign_in', 'Sign In') }}"
                                                           aria-label="{{ auth()->check() ? auth()->user()->name : siteLabel('nav.sign_in', 'Sign In') }}">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        @if(isset($parsedBlocks['header_col2']) && $parsedBlocks['header_col2']['block']->isActiveForDevice($deviceView))
                                            <div class="header_col2 flex-1 min-w-0 shrink hidden lg:block transition-all duration-300">
                                                {!! $parsedBlocks['header_col2']['content'] !!}
                                                @if($navPlacement === 'header_col2')
                                                    <nav class="top_nav_container w-full hidden lg:flex mt-1">
                                                        <div id="top_nav_area_col2" class="w-full">
                                                            @if($navMenu && $navItems && count($navItems) > 0)
                                                                <x-nav-dynamic :menu="$navMenu" :items="$navItems" :cartCount="$cartCount" :user="auth()->user()" />
                                                            @else
                                                                <div class="py-1 px-2 flex items-center gap-4 text-xs font-semibold">
                                                                    <a href="{{ url('/') }}" class="hover:text-indigo-600 font-semibold">@label('nav.home', 'Home')</a>
                                                                    <a href="{{ route('shop.index') }}" class="hover:text-indigo-600 font-semibold">@label('nav.shop', 'Shop')</a>
                                                                    <a href="{{ route('kb.index') }}" class="hover:text-indigo-600 font-semibold">@label('nav.knowledge_base', 'Knowledge Base')</a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </nav>
                                                @endif
                                                @if($featuresPlacement === 'header_col2')
                                                    <div id="header_features_icons_col2" class="flex items-center gap-3 mt-1">
                                                        @livewire('language-switcher')
                                                        <button type="button" wire:click.prevent="$dispatch('open-cart')" @click="$dispatch('open-cart')" class="relative p-2 text-slate-700 dark:text-slate-200 hover:text-indigo-600 transition-colors focus:outline-none" aria-label="Shopping Cart">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                                            @if($cartCount > 0)
                                                                <span class="absolute -top-1 -right-1 bg-indigo-600 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center shadow-sm">{{ $cartCount }}</span>
                                                            @endif
                                                        </button>
                                                        <a href="{{ auth()->check() ? route('dashboard') : route('login') }}"
                                                           class="p-2 text-slate-700 dark:text-slate-200 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
                                                           title="{{ auth()->check() ? auth()->user()->name : siteLabel('nav.sign_in', 'Sign In') }}"
                                                           aria-label="{{ auth()->check() ? auth()->user()->name : siteLabel('nav.sign_in', 'Sign In') }}">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                        </a>
                                                    </div>
<header id="site_header" class="w-full relative z-40 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 transition-colors duration-300">
    {{-- Header Container Rows --}}
    @if(isset($headerBlocks) && $headerBlocks->count() > 0)
        @foreach($headerBlocks as $block)
            @if($block->type == 1)
                @if($block->target_element === 'top_nav_container')
                    @if($navPlacement === 'standalone')
                        <div id="top_nav_row" class="top_nav_row w-full transition-all duration-300">
                            <div class="site_header_container container mx-auto px-4 py-2 flex items-center justify-between">
                                <div id="top_nav_area" class="w-full">
                                    @if($navMenu && $navItems && count($navItems) > 0)
                                        <x-nav-dynamic :menu="$navMenu" :items="$navItems" :cartCount="$cartCount" :user="auth()->user()" />
                                    @else
                                        <div class="py-2 px-3 flex items-center gap-6 text-sm font-semibold text-slate-700 dark:text-slate-200">
                                            <a href="{{ url('/') }}" class="hover:text-indigo-600 font-semibold">@label('nav.home', 'Home')</a>
                                            <a href="{{ route('shop.index') }}" class="hover:text-indigo-600 font-semibold">@label('nav.shop', 'Shop')</a>
                                            <a href="{{ route('kb.index') }}" class="hover:text-indigo-600 font-semibold">@label('nav.knowledge_base', 'Knowledge Base')</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @elseif($block->target_element === 'header_top_bar')
                    <div id="header_top_bar" class="header_top_bar w-full text-xs transition-all duration-300">
                        <div class="site_header_container container mx-auto px-4 py-2 flex flex-wrap items-center justify-between gap-4">
                            @if(isset($parsedBlocks['header_col1']) && $parsedBlocks['header_col1']['block']->isActiveForDevice($deviceView))
                                <div class="header_col1 flex-1 min-w-0 shrink transition-all duration-300">
                                    {!! $parsedBlocks['header_col1']['content'] !!}
                                    @if($navPlacement === 'header_col1')
                                        <nav class="top_nav_container w-full hidden lg:flex mt-1">
                                            <div id="top_nav_area_col1" class="w-full">
                                                @if($navMenu && $navItems && count($navItems) > 0)
                                                    <x-nav-dynamic :menu="$navMenu" :items="$navItems" :cartCount="$cartCount" :user="auth()->user()" />
                                                @else
                                                    <div class="py-1 px-2 flex items-center gap-4 text-xs font-semibold">
                                                        <a href="{{ url('/') }}" class="hover:text-indigo-600 font-semibold">@label('nav.home', 'Home')</a>
                                                        <a href="{{ route('shop.index') }}" class="hover:text-indigo-600 font-semibold">@label('nav.shop', 'Shop')</a>
                                                        <a href="{{ route('kb.index') }}" class="hover:text-indigo-600 font-semibold">@label('nav.knowledge_base', 'Knowledge Base')</a>
                                                    </div>
                                                @endif
                                            </div>
                                        </nav>
                                    @endif
                                    @if($activeSearchPlacement === 'header_col1' && isset($parsedBlocks['header_search']) && $parsedBlocks['header_search']['block']->isActiveForDevice($deviceView))
                                        <div class="header_search_container w-full mt-2">
                                            {!! $parsedBlocks['header_search']['content'] !!}
                                        </div>
                                    @endif
                                    @if($featuresPlacement === 'header_col1')
                                        <div id="header_features_icons_col1" class="flex items-center gap-3 mt-1">
                                            @livewire('language-switcher')
                                            <button type="button" wire:click.prevent="$dispatch('open-cart')" @click="$dispatch('open-cart')" class="relative p-2 text-slate-700 dark:text-slate-200 hover:text-indigo-600 transition-colors focus:outline-none" aria-label="Shopping Cart">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                                @if($cartCount > 0)
                                                    <span class="absolute -top-1 -right-1 bg-indigo-600 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center shadow-sm">{{ $cartCount }}</span>
                                                @endif
                                            </button>
                                            <a href="{{ auth()->check() ? route('dashboard') : route('login') }}"
                                               class="p-2 text-slate-700 dark:text-slate-200 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
                                               title="{{ auth()->check() ? auth()->user()->name : siteLabel('nav.sign_in', 'Sign In') }}"
                                               aria-label="{{ auth()->check() ? auth()->user()->name : siteLabel('nav.sign_in', 'Sign In') }}">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if(isset($parsedBlocks['header_col2']) && $parsedBlocks['header_col2']['block']->isActiveForDevice($deviceView))
                                <div class="header_col2 flex-1 min-w-0 shrink hidden lg:block transition-all duration-300">
                                    {!! $parsedBlocks['header_col2']['content'] !!}
                                    @if($navPlacement === 'header_col2')
                                        <nav class="top_nav_container w-full hidden lg:flex mt-1">
                                            <div id="top_nav_area_col2" class="w-full">
                                                @if($navMenu && $navItems && count($navItems) > 0)
                                                    <x-nav-dynamic :menu="$navMenu" :items="$navItems" :cartCount="$cartCount" :user="auth()->user()" />
                                                @else
                                                    <div class="py-1 px-2 flex items-center gap-4 text-xs font-semibold">
                                                        <a href="{{ url('/') }}" class="hover:text-indigo-600 font-semibold">@label('nav.home', 'Home')</a>
                                                        <a href="{{ route('shop.index') }}" class="hover:text-indigo-600 font-semibold">@label('nav.shop', 'Shop')</a>
                                                        <a href="{{ route('kb.index') }}" class="hover:text-indigo-600 font-semibold">@label('nav.knowledge_base', 'Knowledge Base')</a>
                                                    </div>
                                                @endif
                                            </div>
                                        </nav>
                                    @endif
                                    @if($activeSearchPlacement === 'header_col2' && isset($parsedBlocks['header_search']) && $parsedBlocks['header_search']['block']->isActiveForDevice($deviceView))
                                        <div class="header_search_container w-full mt-2">
                                            {!! $parsedBlocks['header_search']['content'] !!}
                                        </div>
                                    @endif
                                    @if($featuresPlacement === 'header_col2')
                                        <div id="header_features_icons_col2" class="flex items-center gap-3 mt-1">
                                            @livewire('language-switcher')
                                            <button type="button" wire:click.prevent="$dispatch('open-cart')" @click="$dispatch('open-cart')" class="relative p-2 text-slate-700 dark:text-slate-200 hover:text-indigo-600 transition-colors focus:outline-none" aria-label="Shopping Cart">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                                @if($cartCount > 0)
                                                    <span class="absolute -top-1 -right-1 bg-indigo-600 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center shadow-sm">{{ $cartCount }}</span>
                                                @endif
                                            </button>
                                            <a href="{{ auth()->check() ? route('dashboard') : route('login') }}"
                                               class="p-2 text-slate-700 dark:text-slate-200 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
                                               title="{{ auth()->check() ? auth()->user()->name : siteLabel('nav.sign_in', 'Sign In') }}"
                                               aria-label="{{ auth()->check() ? auth()->user()->name : siteLabel('nav.sign_in', 'Sign In') }}">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                {{-- ROW CONTENT TYPE: Main Site Header Bar --}}
                @elseif($block->target_element === 'site_header_container')
                    <div id="site_header_container" class="site_header_container w-full transition-all duration-300">
                        <div class="site_header_contents container mx-auto px-4 py-3 flex items-center justify-between gap-4 relative">
                            {{-- Logo --}}
                            @if(isset($parsedBlocks['header_logo']) && $parsedBlocks['header_logo']['block']->isActiveForDevice($deviceView))
                                <div class="header_logo shrink-0 flex items-center justify-center my-auto py-1 mr-auto lg:mr-0">
                                    {!! $parsedBlocks['header_logo']['content'] !!}
                                </div>
                            @endif

                            {{-- Embedded Navigation Bar (Center Flex) --}}
                            @if($navPlacement === 'main_header')
                                <nav class="top_nav_container flex-1 min-w-0 hidden lg:flex items-center justify-center">
                                    <div id="top_nav_area_main" class="w-full">
                                        @if($navMenu && $navItems && count($navItems) > 0)
                                            <x-nav-dynamic :menu="$navMenu" :items="$navItems" :cartCount="$cartCount" :user="auth()->user()" />
                                        @else
                                            <div class="py-2 px-3 flex items-center gap-6 text-sm font-semibold text-slate-700 dark:text-slate-200">
                                                <a href="{{ url('/') }}" class="hover:text-indigo-600 font-semibold">@label('nav.home', 'Home')</a>
                                                <a href="{{ route('shop.index') }}" class="hover:text-indigo-600 font-semibold">@label('nav.shop', 'Shop')</a>
                                                <a href="{{ route('kb.index') }}" class="hover:text-indigo-600 font-semibold">@label('nav.knowledge_base', 'Knowledge Base')</a>
                                            </div>
                                        @endif
                                    </div>
                                </nav>
                            @endif

                            {{-- Header Live Search Bar Slot (Main Header Bar) --}}
                            @if($activeSearchPlacement === 'main_header' && isset($parsedBlocks['header_search']) && $parsedBlocks['header_search']['block']->isActiveForDevice($deviceView))
                                <div class="header_search_container flex-1 min-w-[140px] max-w-md mx-2 shrink z-1">
                                    {!! $parsedBlocks['header_search']['content'] !!}
                                </div>
                            @endif

                            {{-- Features Bar / Icons --}}
                            <div id="header_features_bar" class="flex items-center gap-2 sm:gap-3 shrink-0 self-center ml-auto lg:ml-0">
                                @if($featuresPlacement === 'main_header' && (!isset($parsedBlocks['header_features']) || $parsedBlocks['header_features']['block']->isActiveForDevice($deviceView)))
                                    <div id="header_features_icons" class="flex items-center gap-2 sm:gap-3">
                                        @livewire('language-switcher')
                                        <button type="button" wire:click.prevent="$dispatch('open-cart')" @click="$dispatch('open-cart')" class="relative p-2 text-slate-700 dark:text-slate-200 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors focus:outline-none" aria-label="Shopping Cart">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                            @if($cartCount > 0)
                                                <span class="absolute -top-1 -right-1 bg-indigo-600 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center shadow-sm">
                                                    {{ $cartCount }}
                                                </span>
                                            @endif
                                        </button>

                                        <a href="{{ auth()->check() ? route('dashboard') : route('login') }}"
                                           class="p-2 text-slate-700 dark:text-slate-200 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
                                           title="{{ auth()->check() ? auth()->user()->name : siteLabel('nav.sign_in', 'Sign In') }}"
                                           aria-label="{{ auth()->check() ? auth()->user()->name : siteLabel('nav.sign_in', 'Sign In') }}">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        </a>
                                    </div>
                                @endif

                                {{-- Mobile Hamburger Button Toggle --}}
                                <button id="header_mobile_toggle" type="button" wire:click="toggleMobileMenu" class="lg:hidden p-2 rounded-lg text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition ml-auto" aria-label="Toggle Menu">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                {{-- Other generic builder rows --}}
                @else
                    <div id="header_row_{{ $block->id }}" class="w-full transition-all duration-300">
                        <div class="site_header_container container mx-auto px-4 py-2">
                            {!! $parsedBlocks[$block->target_element ?? $block->id]['content'] ?? '' !!}
                        </div>
                    </div>
                @endif
            @endif

        @endforeach

        {{-- Standalone Navigation Row Fallback --}}
        @if($navPlacement === 'standalone' && !$headerBlocks->firstWhere('target_element', 'top_nav_container'))
            <div id="top_nav_row_fallback" class="top_nav_row w-full transition-all duration-300">
                <div class="site_header_container container mx-auto px-4 py-2 flex items-center justify-between">
                    <div id="top_nav_area_fallback" class="w-full">
                        @if($navMenu && $navItems && count($navItems) > 0)
                            <x-nav-dynamic :menu="$navMenu" :items="$navItems" :cartCount="$cartCount" :user="auth()->user()" />
                        @else
                            <div class="py-2 px-3 flex items-center gap-6 text-sm font-semibold text-slate-700 dark:text-slate-200">
                                <a href="{{ url('/') }}" class="hover:text-indigo-600 font-semibold">@label('nav.home', 'Home')</a>
                                <a href="{{ route('shop.index') }}" class="hover:text-indigo-600 font-semibold">@label('nav.shop', 'Shop')</a>
                                <a href="{{ route('kb.index') }}" class="hover:text-indigo-600 font-semibold">@label('nav.knowledge_base', 'Knowledge Base')</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

    {{-- Fallback default header --}}
    @else
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <x-site-logo />
            @if($navMenu && $navItems)
                <x-nav-dynamic :menu="$navMenu" :items="$navItems" :cartCount="$cartCount" :user="auth()->user()" />
            @endif
        </div>
    @endif

    {{-- Mobile Navigation Drawer --}}
    @if($mobileMenuOpen)
        <div class="lg:hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm" wire:click="toggleMobileMenu"></div>
        <div class="lg:hidden fixed inset-y-0 right-0 z-50 w-full max-w-xs bg-white dark:bg-slate-800 shadow-2xl p-6 overflow-y-auto space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-4">
                <span class="font-bold text-slate-900 dark:text-white text-base">@label('nav.menu', 'Menu')</span>
                <button wire:click="toggleMobileMenu" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Top Search in Mobile Menu --}}
            @if($mobileSearchPosition === 'top' && isset($parsedBlocks['header_search']) && $parsedBlocks['header_search']['block']->isActiveForDevice('mobile'))
                <div class="mb-4">
                    {!! \App\Services\HeaderFooterParserService::renderSearchBar() !!}
                </div>
            @endif

            {{-- Navigation Links --}}
            <div class="space-y-2">
                @if($navItems && count($navItems) > 0)
                    @foreach($navItems as $item)
                        @if(!$item->isVisibleFor(auth()->user())) @continue @endif
                        @if($item->hide_on_mobile) @continue @endif
                        @php
                            $renderer = app(\App\Services\NavItemRenderer::class);
                            $resolved = $renderer->resolveLink($item, ['user' => auth()->user(), 'cartCount' => $cartCount]);
                        @endphp
                        @if($resolved['skip']) @continue @endif

                        @if($item->item_type === 'categories')
                            <div class="py-1"><livewire:category-menu-widget /></div>
                        @elseif($item->item_type === 'brands')
                            <div class="py-1"><livewire:public-brands-menu /></div>
                        @elseif($item->item_type === 'separator')
                            <hr class="my-2 border-slate-200 dark:border-slate-700">
                        @else
                            <div>
                                <a href="{{ $resolved['href'] }}" target="{{ $item->target }}" class="block py-2 text-sm font-semibold text-slate-800 dark:text-slate-200 hover:text-indigo-600">
                                    {{ $item->label }}
                                </a>
                                @if($item->children && count($item->children) > 0)
                                    <div class="pl-4 space-y-1">
                                        @foreach($item->children as $child)
                                            @if(!$child->isVisibleFor(auth()->user())) @continue @endif
                                            @if($child->hide_on_mobile) @continue @endif
                                            @php $childResolved = $renderer->resolveLink($child, ['user' => auth()->user(), 'cartCount' => $cartCount]); @endphp
                                            @if(!$childResolved['skip'])
                                                <a href="{{ $childResolved['href'] }}" target="{{ $child->target }}" class="block py-1 text-xs text-slate-500 dark:text-slate-400 hover:text-indigo-600">
                                                    {{ $child->label }}
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                @else
                    <a href="{{ url('/') }}" class="block py-2 text-sm font-semibold text-slate-800 dark:text-slate-200">@label('nav.home', 'Home')</a>
                    <a href="{{ route('shop.index') }}" class="block py-2 text-sm font-semibold text-slate-800 dark:text-slate-200">@label('nav.shop', 'Shop')</a>
                    <div class="py-1"><livewire:category-menu-widget /></div>
                    <div class="py-1"><livewire:public-brands-menu /></div>
                    <a href="{{ route('kb.index') }}" class="block py-2 text-sm font-semibold text-slate-800 dark:text-slate-200">@label('nav.knowledge_base', 'Knowledge Base')</a>
                @endif
            </div>

            {{-- Bottom Search in Mobile Menu --}}
            @if($mobileSearchPosition === 'bottom' && isset($parsedBlocks['header_search']) && $parsedBlocks['header_search']['block']->isActiveForDevice('mobile'))
                <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-700">
                    {!! \App\Services\HeaderFooterParserService::renderSearchBar() !!}
                </div>
            @endif
        </div>
    @endif
</header>
