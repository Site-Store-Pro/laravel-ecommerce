<div class="{{ ($navMenu ? $navMenu->sticky : true) ? 'sticky top-0 z-50' : '' }}">
{{-- Dynamic top navigation (renders when a primary menu is configured in Navigation Builder) --}}
@if($navMenu && $navItems)
    <x-nav-dynamic :menu="$navMenu" :items="$navItems" :cartCount="$cartCount" :user="auth()->user()" />
@else
{{-- ─────────────────────────────────────────────────────────── --}}
{{-- FALLBACK: legacy hardcoded nav (active when no dynamic menu is set) --}}
{{-- ─────────────────────────────────────────────────────────── --}}
<header class="border-b border-slate-200/80 bg-white/80 backdrop-blur-md sticky top-0 z-50">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
        <!-- Logo -->
        <x-site-logo />

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center gap-6">
            @auth
                @if(in_array(auth()->user()->role_id?->value, [1, 2]))
                    <!-- Full shop nav + account tabs for customers/wholesale -->
                    <a href="{{ route('shop.index') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">
                        @label('nav.shop', 'Shop')
                    </a>
                    <livewire:category-menu-widget />
                    <livewire:public-brands-menu />
                    <button wire:click.prevent="$dispatch('open-cart')" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors flex items-center gap-1.5 focus:outline-none">
                        @label('nav.cart', 'Cart')
                        @if($cartCount > 0)
                            <span class="px-2 py-0.5 text-xs font-bold text-white bg-indigo-600 rounded-full">{{ number_format($cartCount, 0) }}</span>
                        @endif
                    </button>
                    <a href="{{ route('kb.index') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">
                        @label('nav.knowledge_base', 'Knowledge Base')
                    </a>
                    <div class="w-48 lg:w-60">
                        <livewire:kb-search-bar />
                    </div>
                    <!-- Account separator -->
                    <span class="h-5 w-px bg-slate-200"></span>
                    <a href="{{ route('dashboard', ['tab' => 'tickets']) }}" class="text-sm font-semibold {{ request()->get('tab', 'tickets') === 'tickets' ? 'text-indigo-600' : 'text-slate-600' }} hover:text-indigo-600 transition-colors">
                        @label('nav.tickets', 'Tickets')
                    </a>
                    <a href="{{ route('dashboard', ['tab' => 'orders']) }}" class="text-sm font-semibold {{ request()->get('tab') === 'orders' ? 'text-indigo-600' : 'text-slate-600' }} hover:text-indigo-600 transition-colors">
                        @label('nav.orders', 'Orders')
                    </a>
                    <a href="{{ route('dashboard', ['tab' => 'downloads']) }}" class="text-sm font-semibold {{ request()->get('tab') === 'downloads' ? 'text-indigo-600' : 'text-slate-600' }} hover:text-indigo-600 transition-colors">
                        @label('nav.downloads', 'Downloads')
                    </a>
                @else
                    <!-- Admin / Staff / Other public navigation -->
                    <a href="{{ route('shop.index') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">
                        @label('nav.shop', 'Shop')
                    </a>
                    <livewire:category-menu-widget />
                    <livewire:public-brands-menu />
                    <button wire:click.prevent="$dispatch('open-cart')" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors flex items-center gap-1.5 focus:outline-none">
                        @label('nav.cart', 'Cart')
                        @if($cartCount > 0)
                            <span class="px-2 py-0.5 text-xs font-bold text-white bg-indigo-600 rounded-full">{{ number_format($cartCount, 0) }}</span>
                        @endif
                    </button>
                    <a href="{{ route('kb.index') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">
                        @label('nav.knowledge_base', 'Knowledge Base')
                    </a>
                    <div class="w-48 lg:w-60">
                        <livewire:kb-search-bar />
                    </div>
                @endif
            @else
                <!-- Guest public navigation -->
                <a href="{{ route('shop.index') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">
                    @label('nav.shop', 'Shop')
                </a>
                <livewire:category-menu-widget />
                <livewire:public-brands-menu />
                <button wire:click.prevent="$dispatch('open-cart')" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors flex items-center gap-1.5 focus:outline-none">
                    @label('nav.cart', 'Cart')
                    @if($cartCount > 0)
                        <span class="px-2 py-0.5 text-xs font-bold text-white bg-indigo-600 rounded-full">{{ number_format($cartCount, 0) }}</span>
                    @endif
                </button>
                <a href="{{ route('kb.index') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">
                    @label('nav.knowledge_base', 'Knowledge Base')
                </a>
                <div class="w-48 lg:w-60">
                    <livewire:kb-search-bar />
                </div>
            @endauth

            @if (Route::has('login'))
                @auth
                    @if(in_array(auth()->user()->role_id?->value, [1, 2]))
                        <!-- Dropdown actions for customer roles -->
                        <div class="flex items-center gap-4 ml-4 pl-4 border-l border-slate-200">
                            <a href="{{ route('profile') }}" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition-colors">
                                {{ auth()->user()->name }}
                            </a>
                            <button wire:click="logout" class="text-sm font-semibold text-red-600 hover:text-red-800 transition-colors focus:outline-none">
                                @label('nav.log_out', 'Log Out')
                            </button>
                        </div>
                    @else
                        <a href="{{ route('dashboard') }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-indigo-100 hover:bg-indigo-500 transition-all hover:scale-105 active:scale-95">
                            @label('nav.dashboard', 'Dashboard')
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">
                        @label('nav.sign_in', 'Sign In')
                    </a>
                @endauth
            @endif

            {{-- Front-End Dark Mode Switcher --}}
            @if($showDarkModeSwitcher)
            <div x-data="{ isDark: document.documentElement.classList.contains('dark') }" class="ml-2">
                <button
                    type="button"
                    id="frontend-dark-mode-toggle"
                    @click="
                        isDark = !isDark;
                        document.documentElement.classList.toggle('dark', isDark);
                        var mode = isDark ? 'dark' : 'light';
                        try { localStorage.setItem('frontend_theme', mode); localStorage.setItem('theme_mode', mode); } catch(e){}
                        document.cookie = 'frontend_theme=' + mode + '; path=/; max-age=31536000; SameSite=Lax';
                        document.cookie = 'theme_mode=' + mode + '; path=/; max-age=31536000; SameSite=Lax';
                        document.cookie = 'visperity_theme=' + mode + '; path=/; max-age=31536000; SameSite=Lax';
                        document.cookie = 'theme=' + mode + '; path=/; max-age=31536000; SameSite=Lax';
                        $wire.toggleFrontendDarkMode(mode);
                    "
                    class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-slate-200 dark:border-slate-600 bg-white/80 dark:bg-slate-800/80 text-slate-500 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-indigo-600 dark:hover:text-indigo-400 focus:outline-none transition-all duration-200 shadow-sm backdrop-blur-sm cursor-pointer"
                    :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
                    aria-label="Toggle dark mode"
                >
                    {{-- Sun icon (visible in dark mode) --}}
                    <svg x-show="isDark" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                    {{-- Moon icon (visible in light mode) --}}
                    <svg x-show="!isDark" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>
            </div>
            @endif
        </nav>

        <!-- Hamburger & Mobile Utility (Mobile Only) -->
        <div class="flex items-center gap-3 md:hidden">
            <!-- Mobile Cart Shortcut -->
            @if(true)
                <button wire:click.prevent="$dispatch('open-cart')" class="text-slate-600 hover:text-indigo-600 transition-colors flex items-center gap-1 relative focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    @if($cartCount > 0)
                        <span class="absolute -top-1.5 -right-1.5 px-1.5 py-0.5 text-[10px] font-bold text-white bg-indigo-600 rounded-full leading-none">{{ number_format($cartCount, 0) }}</span>
                    @endif
                </button>
            @endif

            {{-- Mobile Front-End Dark Mode Switcher --}}
            @if($showDarkModeSwitcher)
            <div x-data="{ isDark: document.documentElement.classList.contains('dark') }">
                <button
                    type="button"
                    @click="
                        isDark = !isDark;
                        document.documentElement.classList.toggle('dark', isDark);
                        var mode = isDark ? 'dark' : 'light';
                        try { localStorage.setItem('frontend_theme', mode); localStorage.setItem('theme_mode', mode); } catch(e){}
                        document.cookie = 'frontend_theme=' + mode + '; path=/; max-age=31536000; SameSite=Lax';
                        document.cookie = 'theme_mode=' + mode + '; path=/; max-age=31536000; SameSite=Lax';
                        document.cookie = 'visperity_theme=' + mode + '; path=/; max-age=31536000; SameSite=Lax';
                        document.cookie = 'theme=' + mode + '; path=/; max-age=31536000; SameSite=Lax';
                        $wire.toggleFrontendDarkMode(mode);
                    "
                    class="inline-flex items-center justify-center w-8 h-8 rounded-xl text-slate-500 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors focus:outline-none cursor-pointer"
                    :aria-label="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
                >
                    {{-- Sun icon (visible in dark mode) --}}
                    <svg x-show="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                    {{-- Moon icon (visible in light mode) --}}
                    <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>
            </div>
            @endif

            <button wire:click="toggleMobileMenu" type="button" class="text-slate-500 hover:text-slate-900 focus:outline-none" aria-label="Toggle menu">
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

    <!-- Mobile Dropdown Navigation -->
    @if($mobileMenuOpen)
        <div class="md:hidden border-t border-slate-100 bg-white px-4 py-4 space-y-4 shadow-inner">
            <div class="space-y-3">
                @auth
                    @if(in_array(auth()->user()->role_id->value, [1, 2]))
                        <!-- Full shop nav mobile -->
                        <a href="{{ route('shop.index') }}" class="block px-3 py-2 rounded-xl text-base font-bold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition duration-150">
                            @label('nav.shop', 'Shop')
                        </a>
                        <div class="px-3">
                            <livewire:category-menu-widget />
                        </div>
                        <div class="px-3">
                            <livewire:public-brands-menu />
                        </div>
                        <a href="{{ route('kb.index') }}" class="block px-3 py-2 rounded-xl text-base font-bold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition duration-150">
                            @label('nav.knowledge_base', 'Knowledge Base')
                        </a>
                        <div class="border-t border-slate-100 pt-2 mt-1">
                            <div class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider px-3 pb-1">@label('nav.my_account', 'My Account')</div>
                            <a href="{{ route('dashboard', ['tab' => 'tickets']) }}" class="block px-3 py-2 rounded-xl text-base font-bold {{ request()->get('tab', 'tickets') === 'tickets' ? 'text-indigo-600' : 'text-slate-700' }} hover:bg-slate-50 hover:text-indigo-600 transition duration-150">
                                @label('nav.tickets', 'Tickets')
                            </a>
                            <a href="{{ route('dashboard', ['tab' => 'orders']) }}" class="block px-3 py-2 rounded-xl text-base font-bold {{ request()->get('tab') === 'orders' ? 'text-indigo-600' : 'text-slate-700' }} hover:bg-slate-50 hover:text-indigo-600 transition duration-150">
                                @label('nav.orders', 'Orders')
                            </a>
                            <a href="{{ route('dashboard', ['tab' => 'downloads']) }}" class="block px-3 py-2 rounded-xl text-base font-bold {{ request()->get('tab') === 'downloads' ? 'text-indigo-600' : 'text-slate-700' }} hover:bg-slate-50 hover:text-indigo-600 transition duration-150">
                                @label('nav.downloads', 'Downloads')
                            </a>
                        </div>
                    @else
                        <!-- Staff public mobile links -->
                        <a href="{{ route('shop.index') }}" class="block px-3 py-2 rounded-xl text-base font-bold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition duration-150">
                            @label('nav.shop', 'Shop')
                        </a>
                        <div class="px-3">
                            <livewire:category-menu-widget />
                        </div>
                        <div class="px-3">
                            <livewire:public-brands-menu />
                        </div>
                        <a href="{{ route('kb.index') }}" class="block px-3 py-2 rounded-xl text-base font-bold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition duration-150">
                            @label('nav.knowledge_base', 'Knowledge Base')
                        </a>
                    @endif
                @else
                    <!-- Guest public mobile links -->
                    <a href="{{ route('shop.index') }}" class="block px-3 py-2 rounded-xl text-base font-bold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition duration-150">
                        @label('nav.shop', 'Shop')
                    </a>
                    <div class="px-3">
                        <livewire:category-menu-widget />
                    </div>
                    <div class="px-3">
                        <livewire:public-brands-menu />
                    </div>
                    <a href="{{ route('kb.index') }}" class="block px-3 py-2 rounded-xl text-base font-bold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition duration-150">
                        @label('nav.knowledge_base', 'Knowledge Base')
                    </a>
                @endauth
            </div>

            <!-- Auth Buttons inside mobile menu -->
            <div class="border-t border-slate-100 pt-4 px-3 flex flex-col gap-2">
                @if (Route::has('login'))
                    @auth
                        @if(in_array(auth()->user()->role_id->value, [1, 2]))
                            <div class="flex items-center justify-between">
                                <a href="{{ route('profile') }}" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition-colors">
                                    {{ auth()->user()->name }}
                                </a>
                                <button wire:click="logout" class="text-sm font-semibold text-red-650 hover:text-red-800 transition-colors focus:outline-none">
                                    @label('nav.log_out', 'Log Out')
                                </button>
                            </div>
                        @else
                            <a href="{{ route('dashboard') }}" class="w-full text-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-100 hover:bg-indigo-500 transition duration-150">
                                @label('nav.dashboard', 'Dashboard')
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="w-full text-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition duration-150">
                            @label('nav.sign_in', 'Sign In')
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    @endif
</header>
@endif {{-- end @else fallback nav --}}
</div>
