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
                            <div class="site_header_contents flex w-full items-stretch justify-between gap-4 py-2">
                                
                                {{-- Logo Area --}}
                                @if(isset($parsedBlocks['header_logo']) && $parsedBlocks['header_logo']['block']->isActiveForDevice($deviceView))
                                    <div class="header_logo shrink-0 flex items-center h-full self-stretch min-h-[60px]">
                                        {!! $parsedBlocks['header_logo']['content'] !!}
                                    </div>
                                @endif

                                {{-- Middle Section: Header Columns + Embedded Navigation --}}
                                <div class="flex-1 min-w-0 flex flex-col justify-center gap-1">
                                    @if($navPlacement === 'main_header')
                                        <nav class="top_nav_container w-full hidden lg:flex">
                                            <div id="top_nav_area" class="w-full">
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

                                    <div class="flex items-center gap-4 w-full">
                                        @if(isset($parsedBlocks['header_col1']) && $parsedBlocks['header_col1']['block']->isActiveForDevice($deviceView))
                                            <div class="header_col1 flex-1 min-w-0 hidden md:block transition-all duration-300">
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
                                                        @auth
                                                            <a href="{{ route('dashboard') }}" class="p-2 text-slate-700 dark:text-slate-200 hover:text-indigo-600 transition-colors"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></a>
                                                        @else
                                                            <a href="{{ route('login') }}" class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-colors">@label('nav.sign_in', 'Sign In')</a>
                                                        @endauth
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        @if(isset($parsedBlocks['header_col2']) && $parsedBlocks['header_col2']['block']->isActiveForDevice($deviceView))
                                            <div class="header_col2 flex-1 min-w-0 hidden lg:block transition-all duration-300">
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
                                                        @auth
                                                            <a href="{{ route('dashboard') }}" class="p-2 text-slate-700 dark:text-slate-200 hover:text-indigo-600 transition-colors"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></a>
                                                        @else
                                                            <a href="{{ route('login') }}" class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-colors">@label('nav.sign_in', 'Sign In')</a>
                                                        @endauth
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Features Bar / Icons --}}
                                <div id="header_features_bar" class="flex items-center gap-3 shrink-0 self-center">
                                    @if($featuresPlacement === 'main_header' && (!isset($parsedBlocks['header_features']) || $parsedBlocks['header_features']['block']->isActiveForDevice($deviceView)))
                                        <div id="header_features_icons" class="flex items-center gap-3">
                                            @livewire('language-switcher')
                                            <button type="button" wire:click.prevent="$dispatch('open-cart')" @click="$dispatch('open-cart')" class="relative p-2 text-slate-700 dark:text-slate-200 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors focus:outline-none" aria-label="Shopping Cart">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                                @if($cartCount > 0)
                                                    <span class="absolute -top-1 -right-1 bg-indigo-600 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center shadow-sm">
                                                        {{ $cartCount }}
                                                    </span>
                                                @endif
                                            </button>

                                            @auth
                                                <div class="relative group">
                                                    <a href="{{ route('dashboard') }}" class="p-2 text-slate-700 dark:text-slate-200 hover:text-indigo-600 transition-colors" title="{{ auth()->user()->name }}">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                    </a>
                                                </div>
                                            @else
                                                <a href="{{ route('login') }}" class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-colors">
                                                    @label('nav.sign_in', 'Sign In')
                                                </a>
                                            @endauth
                                        </div>
                                    @endif

                                    <button wire:click="toggleMobileMenu" aria-label="Toggle Mobile Menu" class="header_mobile_toggle lg:hidden p-2 text-slate-700 dark:text-slate-200 hover:text-indigo-600 focus:outline-none">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($mobileMenuOpen)
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                            @endif
                                        </svg>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                {{-- Standalone Top Navigation Container --}}
                @elseif($rowBlock->target_element === 'top_nav_container' && $navPlacement === 'standalone')
                    <nav class="top_nav_container w-full hidden lg:flex">
                        <div id="top_nav_area" class="w-full">
                            @if($navMenu && $navItems && count($navItems) > 0)
                                <x-nav-dynamic :menu="$navMenu" :items="$navItems" :cartCount="$cartCount" :user="auth()->user()" />
                            @else
                                <div id="top_nav_contents" class="py-2.5 px-4 flex items-center justify-between bg-transparent">
                                    <ul class="flex items-center gap-6 text-sm font-medium">
                                        <li><a href="{{ url('/') }}" class="hover:text-indigo-600 transition-colors">@label('nav.home', 'Home')</a></li>
                                        <li><a href="{{ route('shop.index') }}" class="hover:text-indigo-600 transition-colors">@label('nav.shop', 'Shop')</a></li>
                                        <li><a href="{{ route('kb.index') }}" class="hover:text-indigo-600 transition-colors">@label('nav.knowledge_base', 'Knowledge Base')</a></li>
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </nav>

                {{-- Custom Row #1 / Row #2 --}}
                @elseif(in_array($rowBlock->target_element, ['header_row1', 'header_row2']))
                    @if(isset($parsedBlocks[$rowBlock->target_element]) && !empty($parsedBlocks[$rowBlock->target_element]['content']))
                        <div class="{{ $rowBlock->target_element }}">
                            <div class="max-w-7xl w-full px-4 flex items-center justify-center">
                                {!! $parsedBlocks[$rowBlock->target_element]['content'] !!}
                            </div>
                        </div>
                    @endif
                @endif

            @endforeach

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

                    {{-- Search in Mobile Menu --}}
                    <div>
                        {!! \App\Services\HeaderFooterParserService::renderSearchBar() !!}
                    </div>

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
                </div>
            @endif

        </header>
    @endif
</div>
