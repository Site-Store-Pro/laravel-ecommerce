<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

         $this->redirect(route('login'), navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                @if(!auth()->check() || auth()->user()->role_id === \App\Enums\UserRole::User->value)
                <div class="shrink-0 flex items-center">
                    <x-site-logo />
                </div>
                @endif

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        @if(auth()->user()->isAdmin() || auth()->user()->isOrderProcessor())
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" wire:navigate class="text-sm font-semibold transition-all">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                        @endif

                        @if(auth()->user()->role_id === \App\Enums\UserRole::User)
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard') || (request()->routeIs('tickets.*') && !request()->routeIs('tickets.public'))" wire:navigate class="text-sm font-semibold transition-all">
                                {{ __('My Tickets') }}
                            </x-nav-link>
                        @endif

                        @if (auth()->user()->isAdmin())
                            <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users*')" wire:navigate class="text-sm font-semibold transition-all">
                                {{ __('Customers') }}
                            </x-nav-link>
                        @endif

                        @if (auth()->user()->isAdmin() || auth()->user()->isOrderProcessor())
                            <x-nav-link :href="route('admin.ecommerce.orders')" :active="request()->routeIs('admin.ecommerce.orders*') || request()->routeIs('admin.ecommerce.order-details')" wire:navigate class="text-sm font-semibold transition-all">
                                {{ __('Orders') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.ecommerce.products')" :active="request()->routeIs('admin.ecommerce.products*') || request()->routeIs('admin.ecommerce.product-edit')" wire:navigate class="text-sm font-semibold transition-all">
                                {{ __('Products') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.ecommerce.reviews')" :active="request()->routeIs('admin.ecommerce.reviews*')" wire:navigate class="text-sm font-semibold transition-all">
                                {{ __('Reviews') }}
                            </x-nav-link>
                        @endif

                        @if (auth()->user()->isOrderProcessor() && !auth()->user()->isAdmin())
                            <x-nav-link :href="route('admin.ecommerce.pending-orders')" :active="request()->routeIs('admin.ecommerce.pending-orders')" wire:navigate class="text-sm font-semibold transition-all text-amber-600">
                                <span class="flex items-center gap-1.5">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-600"></span>
                                    {{ __('Pending Orders') }}
                                </span>
                            </x-nav-link>
                        @endif

                        @if (auth()->user()->isAdmin())
                            <x-nav-link :href="route('admin.discounts.index')" :active="request()->routeIs('admin.discounts.*')" wire:navigate class="text-sm font-semibold transition-all">
                                {{ __('Discounts') }}
                            </x-nav-link>

                            <!-- Checkout Dropdown -->
                            <div class="inline-flex items-center relative z-20">
                                <x-dropdown align="left" width="48">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-semibold leading-5 transition duration-150 ease-in-out cursor-pointer h-full focus:outline-none {{ request()->routeIs('admin.ecommerce.shipping*') || request()->routeIs('admin.ecommerce.checkout.*') ? 'border-indigo-500 text-slate-900 dark:text-slate-100 focus:border-indigo-700' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 focus:text-slate-700 focus:border-slate-300' }}">
                                            <span>{{ __('Checkout') }}</span>
                                            <svg class="ms-1.5 h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('admin.ecommerce.shipping')" wire:navigate>
                                            {{ __('Shipping & Taxes') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.ecommerce.checkout.processors')" wire:navigate>
                                            {{ __('Processors | Payments') }}
                                        </x-dropdown-link>
                                    </x-slot>
                                </x-dropdown>
            </div>

                            <x-nav-link :href="route('admin.email-templates.index')" :active="request()->routeIs('admin.email-templates.*')" wire:navigate class="text-sm font-semibold transition-all">
                                {{ __('Emails') }}
                            </x-nav-link>

                            <!-- CMS Dropdown -->
                            <div class="inline-flex items-center relative z-20">
                                <x-dropdown align="left" width="48">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-semibold leading-5 transition duration-150 ease-in-out cursor-pointer h-full focus:outline-none {{ request()->routeIs('admin.cms-pages.*') || request()->routeIs('admin.cms-categories.*') || request()->routeIs('admin.cms-tags.*') || request()->routeIs('admin.cms-slideshows.*') || request()->routeIs('admin.cms-list-menus.*') || request()->routeIs('admin.cms-downloads.*') || request()->routeIs('admin.cms-embeds.*') || request()->routeIs('admin.cms-forms.*') || request()->routeIs('admin.kb.*') || request()->routeIs('admin.nav-builder.*') || request()->routeIs('admin.cms-header-footer.*') || request()->routeIs('admin.testimonials.*') || request()->routeIs('admin.site-labels.*') || request()->routeIs('admin.languages.*') ? 'border-indigo-500 text-slate-900 dark:text-slate-100 focus:border-indigo-700' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 focus:text-slate-700 focus:border-slate-300' }}">
                                            <span>{{ __('CMS') }}</span>
                                            <svg class="ms-1.5 h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('admin.cms-pages.index')" wire:navigate>
                                            {{ __('Pages') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.cms-categories.index')" wire:navigate>
                                            {{ __('Categories') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.cms-tags.index')" wire:navigate>
                                            {{ __('Tags') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.cms-slideshows.index')" wire:navigate>
                                            {{ __('Slideshows') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.cms-list-menus.index')" wire:navigate>
                                            {{ __('List Menus') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.cms-downloads.index')" wire:navigate>
                                            {{ __('Downloads') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.cms-embeds.index')" wire:navigate>
                                            {{ __('Code Embeds') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.cms-forms.index')" wire:navigate>
                                            {{ __('Forms') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.kb.index')" wire:navigate>
                                            {{ __('KB Docs') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.nav-builder.index')" wire:navigate>
                                            {{ __('Navigation Builder') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.cms-header-footer.index')" wire:navigate>
                                            {{ __('Header & Footer Builder') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.testimonials.index')" wire:navigate>
                                            {{ __('Testimonials') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.site-labels.index')" wire:navigate>
                                            {{ __('Page Labels') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.languages.index')" wire:navigate>
                                            {{ __('Languages') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.languages.queue-monitor')" wire:navigate>
                                            {{ __('Queue Monitor') }}
                                        </x-dropdown-link>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @endif

                        @if (auth()->user()->isTicketManager())
                            <x-nav-link :href="route('admin.assigned-tickets')" :active="request()->routeIs('admin.assigned-tickets')" wire:navigate class="text-sm font-semibold transition-all">
                                {{ __('My Assigned') }}
                            </x-nav-link>
                        @endif



                        @if (auth()->user()->isAdmin() || auth()->user()->isTicketManager())
                            <x-nav-link :href="route('admin.tickets')" :active="request()->routeIs('admin.tickets') || request()->routeIs('admin.tickets.*')" wire:navigate class="text-sm font-semibold transition-all">
                                {{ __('Tickets') }}
                            </x-nav-link>
                        @endif

                        @if (auth()->user()->isAdmin())
                            <x-nav-link :href="route('admin.plugins.index')" :active="request()->routeIs('admin.plugins.*')" wire:navigate class="text-sm font-semibold transition-all">
                                {{ __('Plugins') }}
                            </x-nav-link>
                        @endif

                        @if (auth()->user()->isAdmin())
                            <x-nav-link :href="route('admin.settings')" :active="request()->routeIs('admin.settings')" wire:navigate class="text-sm font-semibold transition-all">
                                {{ __('Global Settings') }}
                            </x-nav-link>
                        @endif   
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 px-3 py-2 border border-slate-200 text-sm leading-4 font-medium rounded-xl text-slate-600 bg-white hover:text-slate-900 hover:border-slate-300 focus:outline-none transition-all duration-150 shadow-sm">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile')" wire:navigate class="rounded-t-xl py-2 hover:bg-slate-50">
                                <span class="flex items-center gap-2 text-slate-700">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    {{ __('My Profile') }}
                                </span>
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <button wire:click="logout" class="w-full text-start border-t border-slate-100 hover:bg-red-50/50">
                                <x-dropdown-link class="rounded-b-xl py-2">
                                    <span class="flex items-center gap-2 text-red-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        {{ __('Log Out') }}
                                    </span>
                                </x-dropdown-link>
                            </button>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex gap-4">
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">Log in</a>
                        <a href="{{ route('register') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">Register</a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-b border-slate-200">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                @if(auth()->user()->isAdmin() || auth()->user()->isOrderProcessor())
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                @endif

                @if(auth()->user()->role_id === \App\Enums\UserRole::User)
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard') || (request()->routeIs('tickets.*') && !request()->routeIs('tickets.public'))" wire:navigate>
                        {{ __('My Tickets') }}
                    </x-responsive-nav-link>
                @endif

                @if (auth()->user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users*')" wire:navigate>
                        {{ __('Customers') }}
                    </x-responsive-nav-link>
                @endif

                @if (auth()->user()->isAdmin() || auth()->user()->isOrderProcessor())
                    <x-responsive-nav-link :href="route('admin.ecommerce.orders')" :active="request()->routeIs('admin.ecommerce.orders*') || request()->routeIs('admin.ecommerce.order-details')" wire:navigate>
                        {{ __('Orders') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.ecommerce.products')" :active="request()->routeIs('admin.ecommerce.products*') || request()->routeIs('admin.ecommerce.product-edit')" wire:navigate>
                        {{ __('Products') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.ecommerce.reviews')" :active="request()->routeIs('admin.ecommerce.reviews*')" wire:navigate>
                        {{ __('Reviews') }}
                    </x-responsive-nav-link>
                @endif

                 @if (auth()->user()->isOrderProcessor() && !auth()->user()->isAdmin())
                     <x-responsive-nav-link :href="route('admin.ecommerce.pending-orders')" :active="request()->routeIs('admin.ecommerce.pending-orders')" wire:navigate class="text-amber-600">
                         {{ __('Pending Orders') }}
                     </x-responsive-nav-link>
                 @endif

                @if (auth()->user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.discounts.index')" :active="request()->routeIs('admin.discounts.*')" wire:navigate>
                        {{ __('Discounts') }}
                    </x-responsive-nav-link>

                    <!-- Checkout Section -->
                    <div class="ps-4 border-l-2 border-slate-100 dark:border-slate-800 my-1.5 space-y-1">
                        <div class="text-3xs font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-3 py-1">Checkout</div>
                        <x-responsive-nav-link :href="route('admin.ecommerce.shipping')" :active="request()->routeIs('admin.ecommerce.shipping*')" wire:navigate>
                            {{ __('Shipping & Taxes') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.ecommerce.checkout.processors')" :active="request()->routeIs('admin.ecommerce.checkout.*')" wire:navigate>
                            {{ __('Processors | Payments') }}
                        </x-responsive-nav-link>
                    </div>

                    <x-responsive-nav-link :href="route('admin.email-templates.index')" :active="request()->routeIs('admin.email-templates.*')" wire:navigate>
                        {{ __('Emails') }}
                    </x-responsive-nav-link>

                    <!-- CMS Responsive Links -->
                    <div class="ps-4 border-l-2 border-slate-100 dark:border-slate-800 my-1.5 space-y-1">
                        <div class="text-3xs font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-3 py-1">CMS Sections</div>
                        <x-responsive-nav-link :href="route('admin.cms-pages.index')" :active="request()->routeIs('admin.cms-pages.*')" wire:navigate>
                            {{ __('Pages') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.cms-categories.index')" :active="request()->routeIs('admin.cms-categories.*')" wire:navigate>
                            {{ __('Categories') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.cms-tags.index')" :active="request()->routeIs('admin.cms-tags.*')" wire:navigate>
                            {{ __('Tags') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.cms-slideshows.index')" :active="request()->routeIs('admin.cms-slideshows.*')" wire:navigate>
                            {{ __('Slideshows') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.cms-list-menus.index')" :active="request()->routeIs('admin.cms-list-menus.*')" wire:navigate>
                            {{ __('List Menus') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.cms-downloads.index')" :active="request()->routeIs('admin.cms-downloads.*')" wire:navigate>
                            {{ __('Downloads') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.cms-embeds.index')" :active="request()->routeIs('admin.cms-embeds.*')" wire:navigate>
                            {{ __('Code Embeds') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.cms-forms.index')" :active="request()->routeIs('admin.cms-forms.*')" wire:navigate>
                            {{ __('Forms') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.kb.index')" :active="request()->routeIs('admin.kb.*')" wire:navigate>
                            {{ __('KB Docs') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.nav-builder.index')" :active="request()->routeIs('admin.nav-builder.*')" wire:navigate>
                            {{ __('Navigation Builder') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.cms-header-footer.index')" :active="request()->routeIs('admin.cms-header-footer.*')" wire:navigate>
                            {{ __('Header & Footer Builder') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.testimonials.index')" :active="request()->routeIs('admin.testimonials.*')" wire:navigate>
                            {{ __('Testimonials') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.site-labels.index')" :active="request()->routeIs('admin.site-labels.*')" wire:navigate>
                            {{ __('Page Labels') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.languages.index')" :active="request()->routeIs('admin.languages.index')" wire:navigate>
                            {{ __('Languages') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.languages.queue-monitor')" :active="request()->routeIs('admin.languages.queue-monitor')" wire:navigate>
                            {{ __('Queue Monitor') }}
                        </x-responsive-nav-link>
                    </div>
                @endif

                @if (auth()->user()->isTicketManager())
                    <x-responsive-nav-link :href="route('admin.assigned-tickets')" :active="request()->routeIs('admin.assigned-tickets')" wire:navigate>
                        {{ __('My Assigned') }}
                    </x-responsive-nav-link>
                @endif



                @if (auth()->user()->isAdmin() || auth()->user()->isTicketManager())
                    <x-responsive-nav-link :href="route('admin.tickets')" :active="request()->routeIs('admin.tickets') || request()->routeIs('admin.tickets.*')" wire:navigate>
                        {{ __('Tickets') }}
                    </x-responsive-nav-link>
                @endif

                @if (auth()->user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.plugins.index')" :active="request()->routeIs('admin.plugins.*')" wire:navigate>
                        {{ __('Plugins') }}
                    </x-responsive-nav-link>
                @endif

                @if (auth()->user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.settings')" :active="request()->routeIs('admin.settings')" wire:navigate>
                        {{ __('Global Settings') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        @auth
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-slate-200">
                <div class="px-4 flex items-center gap-3">
                    <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-sm font-bold text-indigo-600">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-semibold text-sm text-slate-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                        <div class="font-medium text-xs text-slate-500">{{ auth()->user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile')" wire:navigate>
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <button wire:click="logout" class="w-full text-start">
                        <x-responsive-nav-link class="text-red-600">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </button>
                </div>
            </div>
        @else
            <div class="pt-4 pb-4 border-t border-slate-200 px-4 space-y-2">
                <a href="{{ route('login') }}" class="block text-sm font-semibold text-slate-600 hover:text-slate-900">Log in</a>
                <a href="{{ route('register') }}" class="block text-sm font-semibold text-indigo-600 hover:text-indigo-800">Register</a>
            </div>
        @endauth
    </div>
</nav>
