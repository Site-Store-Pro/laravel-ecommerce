<?php

use App\Livewire\Actions\Logout;
use App\Models\CmsSetting;
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

    /**
     * Toggle admin dark mode setting and clear settings cache.
     */
    public function toggleAdminDarkMode(): void
    {
        $current = CmsSetting::isEnabled('admin_dark_mode');
        CmsSetting::set('admin_dark_mode', $current ? '0' : '1');
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="{{ (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->role_id === 3)) ? 'max-w-[1800px] w-full' : 'max-w-7xl' }} mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                @if(!auth()->check() || auth()->user()->role_id === \App\Enums\UserRole::User->value)
                <div class="shrink-0 flex items-center">
                    <x-site-logo />
                </div>
                @endif

                <!-- Navigation Links -->
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1.5 -my-px ms-2 admin:ms-6 h-full">
                    @auth
                        @if(auth()->user()->isAdmin() || auth()->user()->isOrderProcessor())
                            <a href="{{ route('admin.dashboard') }}" wire:navigate class="inline-flex items-center justify-center p-2 rounded-xl border-b-2 border-transparent text-slate-700 dark:text-sky-400 hover:text-slate-900 dark:hover:text-sky-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition duration-150 ease-in-out my-auto" title="Admin Dashboard" aria-label="Admin Dashboard">
                                <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600 dark:text-sky-300' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </a>
                        @endif

                        @if(auth()->user()->role_id === \App\Enums\UserRole::User)
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard') || (request()->routeIs('tickets.*') && !request()->routeIs('tickets.public'))" wire:navigate class="text-sm font-semibold transition-all">
                                <svg class="w-4 h-4 me-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                <span>{{ __('My Tickets') }}</span>
                            </x-nav-link>
                        @endif

                        @if (auth()->user()->isAdmin())
                            <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users*')" wire:navigate class="text-sm font-semibold transition-all">
                                <svg class="w-4 h-4 me-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 100 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <span>{{ __('Customers') }}</span>
                            </x-nav-link>
                        @endif

                        @if (auth()->user()->isAdmin() || auth()->user()->isOrderProcessor())
                            <x-nav-link :href="route('admin.ecommerce.orders')" :active="request()->routeIs('admin.ecommerce.orders*') || request()->routeIs('admin.ecommerce.order-details')" wire:navigate class="text-sm font-semibold transition-all">
                                <svg class="w-4 h-4 me-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                <span>{{ __('Orders') }}</span>
                            </x-nav-link>
                            <!-- Products Dropdown -->
                            @php
                                $productsActive = request()->routeIs('admin.ecommerce.products*') 
                                    || request()->routeIs('admin.ecommerce.product-create') 
                                    || request()->routeIs('admin.ecommerce.product-edit') 
                                    || request()->routeIs('admin.ecommerce.brands*') 
                                    || request()->routeIs('admin.ecommerce.categories*') 
                                    || request()->routeIs('admin.ecommerce.import') 
                                    || request()->routeIs('admin.ecommerce.inventory') 
                                    || request()->routeIs('admin.inventory-alerts.*');
                            @endphp
                            <div class="inline-flex items-center relative z-20 h-full">
                                <x-dropdown align="left" width="56">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-semibold leading-5 transition duration-150 ease-in-out cursor-pointer h-full focus:outline-none {{ $productsActive ? 'border-indigo-500 text-slate-900 dark:text-sky-300 dark:border-sky-400' : 'border-transparent text-slate-700 hover:text-slate-900 hover:border-slate-300 dark:text-sky-400 dark:hover:text-sky-300' }}">
                                            <svg class="w-4 h-4 me-1.5 shrink-0 text-slate-400 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                            <span>{{ __('Products') }}</span>
                                            <svg class="ms-1.5 h-4 w-4 text-slate-400 dark:text-sky-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <div class="py-1 space-y-0.5 min-w-[210px]">
                                            <x-dropdown-link :href="route('admin.ecommerce.products')" wire:navigate class="!text-[13px] !font-semibold whitespace-nowrap text-slate-700 dark:text-slate-200 hover:bg-indigo-50 dark:hover:bg-slate-700/60 py-2 px-3.5 transition-colors">
                                                {{ __('Products Manager') }}
                                            </x-dropdown-link>
                                            <x-dropdown-link :href="route('admin.ecommerce.product-create')" class="!text-[13px] !font-semibold whitespace-nowrap text-slate-700 dark:text-slate-200 hover:bg-indigo-50 dark:hover:bg-slate-700/60 py-2 px-3.5 transition-colors">
                                                {{ __('Add New Product') }}
                                            </x-dropdown-link>
                                            <x-dropdown-link :href="route('admin.ecommerce.brands')" wire:navigate class="!text-[13px] !font-semibold whitespace-nowrap text-slate-700 dark:text-slate-200 hover:bg-indigo-50 dark:hover:bg-slate-700/60 py-2 px-3.5 transition-colors">
                                                {{ __('Brands Manager') }}
                                            </x-dropdown-link>
                                            <x-dropdown-link :href="route('admin.ecommerce.categories')" wire:navigate class="!text-[13px] !font-semibold whitespace-nowrap text-slate-700 dark:text-slate-200 hover:bg-indigo-50 dark:hover:bg-slate-700/60 py-2 px-3.5 transition-colors">
                                                {{ __('Categories Manager') }}
                                            </x-dropdown-link>
                                            <x-dropdown-link :href="route('admin.ecommerce.import')" wire:navigate class="!text-[13px] !font-semibold whitespace-nowrap text-slate-700 dark:text-slate-200 hover:bg-indigo-50 dark:hover:bg-slate-700/60 py-2 px-3.5 transition-colors">
                                                {{ __('Import Products') }}
                                            </x-dropdown-link>
                                            <x-dropdown-link :href="route('admin.ecommerce.inventory')" wire:navigate class="!text-[13px] !font-semibold whitespace-nowrap text-slate-700 dark:text-slate-200 hover:bg-indigo-50 dark:hover:bg-slate-700/60 py-2 px-3.5 transition-colors">
                                                {{ __('Inventory Manager') }}
                                            </x-dropdown-link>
                                            <x-dropdown-link :href="route('admin.inventory-alerts.index')" wire:navigate class="!text-[13px] !font-semibold whitespace-nowrap text-slate-700 dark:text-slate-200 hover:bg-indigo-50 dark:hover:bg-slate-700/60 py-2 px-3.5 transition-colors">
                                                {{ __('Out of Stock Messages') }}
                                            </x-dropdown-link>
                                        </div>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                            <x-nav-link :href="route('admin.ecommerce.reviews')" :active="request()->routeIs('admin.ecommerce.reviews*')" wire:navigate class="text-sm font-semibold transition-all">
                                <svg class="w-4 h-4 me-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                <span>{{ __('Reviews') }}</span>
                            </x-nav-link>
                            <x-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')" wire:navigate class="text-sm font-semibold transition-all">
                                <svg class="w-4 h-4 me-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                <span>{{ __('Reports') }}</span>
                            </x-nav-link>
                        @endif

                        @if (auth()->user()->isOrderProcessor() && !auth()->user()->isAdmin())
                            <x-nav-link :href="route('admin.ecommerce.pending-orders')" :active="request()->routeIs('admin.ecommerce.pending-orders')" wire:navigate class="text-sm font-semibold transition-all text-amber-600">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 me-1 shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>{{ __('Pending Orders') }}</span>
                                </span>
                            </x-nav-link>
                        @endif

                        @if (auth()->user()->isAdmin())
                            <x-nav-link :href="route('admin.discounts.index')" :active="request()->routeIs('admin.discounts.*')" wire:navigate class="text-sm font-semibold transition-all">
                                <svg class="w-4 h-4 me-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                                <span>{{ __('Discounts') }}</span>
                            </x-nav-link>

                            <!-- Checkout Dropdown -->
                            @php
                                $checkoutActive = request()->routeIs('admin.ecommerce.shipping*') || request()->routeIs('admin.ecommerce.checkout.*');
                            @endphp
                            <div class="inline-flex items-center relative z-20 h-full">
                                <x-dropdown align="left" width="48">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-semibold leading-5 transition duration-150 ease-in-out cursor-pointer h-full focus:outline-none {{ $checkoutActive ? 'border-indigo-500 text-slate-900 dark:text-sky-300 dark:border-sky-400' : 'border-transparent text-slate-700 hover:text-slate-900 hover:border-slate-300 dark:text-sky-400 dark:hover:text-sky-300' }}">
                                            <svg class="w-4 h-4 me-1.5 shrink-0 text-slate-400 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                            <span>{{ __('Checkout') }}</span>
                                            <svg class="ms-1.5 h-4 w-4 text-slate-400 dark:text-sky-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
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
                                <svg class="w-4 h-4 me-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span>{{ __('Emails') }}</span>
                            </x-nav-link>

                            <!-- CMS Mega Menu -->
                            @php
                                $cmsActive = request()->routeIs('admin.cms-pages.*') || request()->routeIs('admin.cms-categories.*') || request()->routeIs('admin.cms-tags.*') || request()->routeIs('admin.cms-slideshows.*') || request()->routeIs('admin.cms-list-menus.*') || request()->routeIs('admin.cms-downloads.*') || request()->routeIs('admin.cms-embeds.*') || request()->routeIs('admin.cms-forms.*') || request()->routeIs('admin.kb.*') || request()->routeIs('admin.nav-builder.*') || request()->routeIs('admin.cms-header-footer.*') || request()->routeIs('admin.testimonials.*') || request()->routeIs('admin.faqs.*') || request()->routeIs('admin.modals.*') || request()->routeIs('admin.inventory-alerts.*') || request()->routeIs('admin.site-labels.*') || request()->routeIs('admin.languages.*');
                            @endphp
                            <div class="inline-flex items-center relative z-30 h-full"
                                 x-data="{ open: false }"
                                 @mouseenter="open = true"
                                 @mouseleave="open = false"
                                 @keydown.escape.window="open = false">

                                {{-- Trigger button --}}
                                <button @click="open = !open"
                                        class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-semibold leading-5 transition duration-150 ease-in-out cursor-pointer h-full focus:outline-none {{ $cmsActive ? 'border-indigo-500 text-slate-900 dark:text-sky-300 dark:border-sky-400' : 'border-transparent text-slate-700 hover:text-slate-900 hover:border-slate-300 dark:text-sky-400 dark:hover:text-sky-300' }}">
                                    <svg class="w-4 h-4 me-1.5 shrink-0 text-slate-400 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    <span>{{ __('CMS') }}</span>
                                    <svg class="ms-1.5 h-4 w-4 text-slate-400 dark:text-sky-400 transition-transform duration-200" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                {{-- Mega panel --}}
                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-100"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 translate-y-1"
                                     class="absolute left-0 top-full mt-0 w-[580px] rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 overflow-hidden"
                                     style="display:none;">

                                    {{-- Section: Content --}}
                                    <div class="px-4 pt-4 pb-1">
                                        <p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 px-1">Content</p>
                                        <div class="grid grid-cols-2 gap-0.5">

                                            {{-- Pages --}}
                                            <a href="{{ route('admin.cms-pages.index') }}" wire:navigate @click="open=false"
                                               class="group flex items-start gap-3 rounded-lg px-3 py-2.5 hover:bg-indigo-50 dark:hover:bg-slate-700/60 transition-colors {{ request()->routeIs('admin.cms-pages.*') ? 'bg-indigo-50 dark:bg-slate-700/60' : '' }}">
                                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 group-hover:bg-indigo-200 dark:group-hover:bg-indigo-800/50 transition-colors">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">Pages</p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400">CMS pages &amp; posts</p>
                                                </div>
                                            </a>

                                            {{-- Knowledge Base --}}
                                            <a href="{{ route('admin.kb.index') }}" wire:navigate @click="open=false"
                                               class="group flex items-start gap-3 rounded-lg px-3 py-2.5 hover:bg-sky-50 dark:hover:bg-slate-700/60 transition-colors {{ request()->routeIs('admin.kb.*') ? 'bg-sky-50 dark:bg-slate-700/60' : '' }}">
                                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-sky-100 dark:bg-sky-900/40 text-sky-600 dark:text-sky-400 group-hover:bg-sky-200 transition-colors">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">KB Docs</p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400">Knowledge base articles</p>
                                                </div>
                                            </a>

                                            {{-- Testimonials --}}
                                            <a href="{{ route('admin.testimonials.index') }}" wire:navigate @click="open=false"
                                               class="group flex items-start gap-3 rounded-lg px-3 py-2.5 hover:bg-amber-50 dark:hover:bg-slate-700/60 transition-colors {{ request()->routeIs('admin.testimonials.*') ? 'bg-amber-50 dark:bg-slate-700/60' : '' }}">
                                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 group-hover:bg-amber-200 transition-colors">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">Testimonials</p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400">Customer quotes &amp; reviews</p>
                                                </div>
                                            </a>

                                            {{-- FAQs --}}
                                            <a href="{{ route('admin.faqs.index') }}" wire:navigate @click="open=false"
                                               class="group flex items-start gap-3 rounded-lg px-3 py-2.5 hover:bg-emerald-50 dark:hover:bg-slate-700/60 transition-colors {{ request()->routeIs('admin.faqs.*') ? 'bg-emerald-50 dark:bg-slate-700/60' : '' }}">
                                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 group-hover:bg-emerald-200 transition-colors">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">FAQs</p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400">Accordion Q&amp;A sections</p>
                                                </div>
                                            </a>

                                            {{-- Forms --}}
                                            <a href="{{ route('admin.cms-forms.index') }}" wire:navigate @click="open=false"
                                               class="group flex items-start gap-3 rounded-lg px-3 py-2.5 hover:bg-violet-50 dark:hover:bg-slate-700/60 transition-colors {{ request()->routeIs('admin.cms-forms.*') ? 'bg-violet-50 dark:bg-slate-700/60' : '' }}">
                                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-900/40 text-violet-600 dark:text-violet-400 group-hover:bg-violet-200 transition-colors">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">Forms</p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400">Public contact forms</p>
                                                </div>
                                            </a>

                                            {{-- Modals --}}
                                            <a href="{{ route('admin.modals.index') }}" wire:navigate @click="open=false"
                                               class="group flex items-start gap-3 rounded-lg px-3 py-2.5 hover:bg-pink-50 dark:hover:bg-slate-700/60 transition-colors {{ request()->routeIs('admin.modals.*') ? 'bg-pink-50 dark:bg-slate-700/60' : '' }}">
                                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-pink-100 dark:bg-pink-900/40 text-pink-600 dark:text-pink-400 group-hover:bg-pink-200 transition-colors">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">Modals</p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400">Popup overlay messages</p>
                                                </div>
                                            </a>

                                            {{-- Downloads --}}
                                            <a href="{{ route('admin.cms-downloads.index') }}" wire:navigate @click="open=false"
                                               class="group flex items-start gap-3 rounded-lg px-3 py-2.5 hover:bg-teal-50 dark:hover:bg-slate-700/60 transition-colors {{ request()->routeIs('admin.cms-downloads.*') ? 'bg-teal-50 dark:bg-slate-700/60' : '' }}">
                                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-teal-100 dark:bg-teal-900/40 text-teal-600 dark:text-teal-400 group-hover:bg-teal-200 transition-colors">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">Downloads</p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400">File delivery &amp; assets</p>
                                                </div>
                                            </a>

                                            {{-- Code Embeds --}}
                                            <a href="{{ route('admin.cms-embeds.index') }}" wire:navigate @click="open=false"
                                               class="group flex items-start gap-3 rounded-lg px-3 py-2.5 hover:bg-slate-100 dark:hover:bg-slate-700/60 transition-colors {{ request()->routeIs('admin.cms-embeds.*') ? 'bg-slate-100 dark:bg-slate-700/60' : '' }}">
                                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 group-hover:bg-slate-300 transition-colors">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">Code Embeds</p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400">HTML / iframes / scripts</p>
                                                </div>
                                            </a>

                                            {{-- Slideshows --}}
                                            <a href="{{ route('admin.cms-slideshows.index') }}" wire:navigate @click="open=false"
                                               class="group flex items-start gap-3 rounded-lg px-3 py-2.5 hover:bg-orange-50 dark:hover:bg-slate-700/60 transition-colors {{ request()->routeIs('admin.cms-slideshows.*') ? 'bg-orange-50 dark:bg-slate-700/60' : '' }}">
                                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-orange-100 dark:bg-orange-900/40 text-orange-600 dark:text-orange-400 group-hover:bg-orange-200 transition-colors">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">Slideshows</p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400">Image carousels &amp; sliders</p>
                                                </div>
                                            </a>

                                            {{-- Inventory Alerts --}}
                                            <a href="{{ route('admin.inventory-alerts.index') }}" wire:navigate @click="open=false"
                                               class="group flex items-start gap-3 rounded-lg px-3 py-2.5 hover:bg-red-50 dark:hover:bg-slate-700/60 transition-colors {{ request()->routeIs('admin.inventory-alerts.*') ? 'bg-red-50 dark:bg-slate-700/60' : '' }}">
                                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400 group-hover:bg-red-200 transition-colors">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">Inventory Alerts</p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400">Low-stock alert messages</p>
                                                </div>
                                            </a>

                                        </div>
                                    </div>

                                    {{-- Divider --}}
                                    <div class="mx-4 my-2 border-t border-slate-100 dark:border-slate-700"></div>

                                    {{-- Section: Structure & Settings --}}
                                    <div class="px-4 pb-4">
                                        <p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 px-1">Structure &amp; Settings</p>
                                        <div class="grid grid-cols-2 gap-0.5">

                                            {{-- Categories --}}
                                            <a href="{{ route('admin.cms-categories.index') }}" wire:navigate @click="open=false"
                                               class="group flex items-start gap-3 rounded-lg px-3 py-2.5 hover:bg-indigo-50 dark:hover:bg-slate-700/60 transition-colors {{ request()->routeIs('admin.cms-categories.*') ? 'bg-indigo-50 dark:bg-slate-700/60' : '' }}">
                                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 group-hover:bg-indigo-200 transition-colors">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">Categories</p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400">Page category groups</p>
                                                </div>
                                            </a>

                                            {{-- Tags --}}
                                            <a href="{{ route('admin.cms-tags.index') }}" wire:navigate @click="open=false"
                                               class="group flex items-start gap-3 rounded-lg px-3 py-2.5 hover:bg-cyan-50 dark:hover:bg-slate-700/60 transition-colors {{ request()->routeIs('admin.cms-tags.*') ? 'bg-cyan-50 dark:bg-slate-700/60' : '' }}">
                                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-cyan-100 dark:bg-cyan-900/40 text-cyan-600 dark:text-cyan-400 group-hover:bg-cyan-200 transition-colors">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">Tags</p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400">Content labeling &amp; taxonomy</p>
                                                </div>
                                            </a>

                                            {{-- List Menus --}}
                                            <a href="{{ route('admin.cms-list-menus.index') }}" wire:navigate @click="open=false"
                                               class="group flex items-start gap-3 rounded-lg px-3 py-2.5 hover:bg-slate-100 dark:hover:bg-slate-700/60 transition-colors {{ request()->routeIs('admin.cms-list-menus.*') ? 'bg-slate-100 dark:bg-slate-700/60' : '' }}">
                                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 group-hover:bg-slate-300 transition-colors">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">List Menus</p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400">Reusable link lists</p>
                                                </div>
                                            </a>

                                            {{-- Navigation Builder --}}
                                            <a href="{{ route('admin.nav-builder.index') }}" wire:navigate @click="open=false"
                                               class="group flex items-start gap-3 rounded-lg px-3 py-2.5 hover:bg-violet-50 dark:hover:bg-slate-700/60 transition-colors {{ request()->routeIs('admin.nav-builder.*') ? 'bg-violet-50 dark:bg-slate-700/60' : '' }}">
                                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-900/40 text-violet-600 dark:text-violet-400 group-hover:bg-violet-200 transition-colors">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">Navigation Builder</p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400">Top nav menus &amp; dropdowns</p>
                                                </div>
                                            </a>

                                            {{-- Header & Footer Builder --}}
                                            <a href="{{ route('admin.cms-header-footer.index') }}" wire:navigate @click="open=false"
                                               class="group flex items-start gap-3 rounded-lg px-3 py-2.5 hover:bg-indigo-50 dark:hover:bg-slate-700/60 transition-colors {{ request()->routeIs('admin.cms-header-footer.*') ? 'bg-indigo-50 dark:bg-slate-700/60' : '' }}">
                                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 group-hover:bg-indigo-200 transition-colors">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">Header &amp; Footer Builder</p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400">Layout blocks &amp; CSS theme</p>
                                                </div>
                                            </a>

                                            {{-- Page Labels --}}
                                            <a href="{{ route('admin.site-labels.index') }}" wire:navigate @click="open=false"
                                               class="group flex items-start gap-3 rounded-lg px-3 py-2.5 hover:bg-yellow-50 dark:hover:bg-slate-700/60 transition-colors {{ request()->routeIs('admin.site-labels.*') ? 'bg-yellow-50 dark:bg-slate-700/60' : '' }}">
                                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-yellow-100 dark:bg-yellow-900/40 text-yellow-600 dark:text-yellow-400 group-hover:bg-yellow-200 transition-colors">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">Page Labels</p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400">Multilingual UI strings</p>
                                                </div>
                                            </a>

                                            {{-- Languages --}}
                                            <a href="{{ route('admin.languages.index') }}" wire:navigate @click="open=false"
                                               class="group flex items-start gap-3 rounded-lg px-3 py-2.5 hover:bg-emerald-50 dark:hover:bg-slate-700/60 transition-colors {{ request()->routeIs('admin.languages.*') ? 'bg-emerald-50 dark:bg-slate-700/60' : '' }}">
                                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 group-hover:bg-emerald-200 transition-colors">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">Languages</p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400">Site translations &amp; queue</p>
                                                </div>
                                            </a>

                                            {{-- Job Queue Monitor --}}
                                            <a href="{{ route('admin.languages.queue-monitor') }}" wire:navigate @click="open=false"
                                               class="group flex items-start gap-3 rounded-lg px-3 py-2.5 hover:bg-violet-50 dark:hover:bg-slate-700/60 transition-colors {{ request()->routeIs('admin.languages.queue-monitor') ? 'bg-violet-50 dark:bg-slate-700/60' : '' }}">
                                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-900/40 text-violet-600 dark:text-violet-400 group-hover:bg-violet-200 transition-colors">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">Queue Monitor</p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400">Translation batch job status</p>
                                                </div>
                                            </a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (auth()->user()->isTicketManager())
                            <x-nav-link :href="route('admin.assigned-tickets')" :active="request()->routeIs('admin.assigned-tickets')" wire:navigate class="text-sm font-semibold transition-all">
                                <svg class="w-4 h-4 me-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <span>{{ __('My Assigned') }}</span>
                            </x-nav-link>
                        @endif

                        @if (auth()->user()->isAdmin() || auth()->user()->isTicketManager())
                            <x-nav-link :href="route('admin.tickets')" :active="request()->routeIs('admin.tickets') || request()->routeIs('admin.tickets.*')" wire:navigate class="text-sm font-semibold transition-all">
                                <svg class="w-4 h-4 me-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                <span>{{ __('Tickets') }}</span>
                            </x-nav-link>
                        @endif

                        @if (auth()->user()->isAdmin())
                            <x-nav-link :href="route('admin.plugins.index')" :active="request()->routeIs('admin.plugins.*')" wire:navigate class="text-sm font-semibold transition-all">
                                <svg class="w-4 h-4 me-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/></svg>
                                <span>{{ __('Plugins') }}</span>
                            </x-nav-link>
                        @endif

                        @if (auth()->user()->isAdmin())
                            <x-nav-link :href="route('admin.settings')" :active="request()->routeIs('admin.settings')" class="text-sm font-semibold transition-all">
                                <svg class="w-4 h-4 me-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>{{ __('Settings') }}</span>
                            </x-nav-link>
                        @endif   
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden admin:flex admin:items-center admin:ms-6 gap-2">

                @php $showAdminSwitcher = \App\Models\CmsSetting::isEnabled('show_admin_dark_mode_switcher'); @endphp
                @if($showAdminSwitcher)
                @auth
                {{-- Admin Dark Mode Toggle Button --}}
                <div
                    x-data="{ isDark: document.documentElement.classList.contains('dark') }"
                    x-on:admin-dark-toggled.window="isDark = $event.detail.dark"
                >
                    <button
                        type="button"
                        id="admin-dark-mode-toggle"
                        @click="
                            isDark = !isDark;
                            document.documentElement.classList.toggle('dark', isDark);
                            $wire.toggleAdminDarkMode();
                        "
                        class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-indigo-600 dark:hover:text-indigo-400 focus:outline-none transition-all duration-200 shadow-sm"
                        :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
                    >
                        {{-- Sun icon (shown in dark mode) --}}
                        <svg x-show="isDark" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                        </svg>
                        {{-- Moon icon (shown in light mode) --}}
                        <svg x-show="!isDark" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>
                </div>
                @endauth
                @endif

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
            <div class="-me-2 flex items-center admin:hidden">
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
    <div :class="{'block': open, 'hidden': ! open}" class="hidden admin:hidden bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
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

                    <!-- Products Section -->
                    <div class="ps-4 border-l-2 border-slate-100 dark:border-slate-800 my-1.5 space-y-1">
                        <div class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-3 py-1">Products</div>
                        <x-responsive-nav-link :href="route('admin.ecommerce.products')" :active="request()->routeIs('admin.ecommerce.products') || request()->routeIs('admin.ecommerce.product-edit')" wire:navigate>
                            {{ __('Products Manager') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.ecommerce.product-create')" :active="request()->routeIs('admin.ecommerce.product-create')">
                            {{ __('Add New Product') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.ecommerce.brands')" :active="request()->routeIs('admin.ecommerce.brands*')" wire:navigate>
                            {{ __('Brands Manager') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.ecommerce.categories')" :active="request()->routeIs('admin.ecommerce.categories*')" wire:navigate>
                            {{ __('Categories Manager') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.ecommerce.import')" :active="request()->routeIs('admin.ecommerce.import*')" wire:navigate>
                            {{ __('Import Products') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.ecommerce.inventory')" :active="request()->routeIs('admin.ecommerce.inventory*')" wire:navigate>
                            {{ __('Inventory Manager') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.inventory-alerts.index')" :active="request()->routeIs('admin.inventory-alerts.*')" wire:navigate>
                            {{ __('Out of Stock Messages') }}
                        </x-responsive-nav-link>
                    </div>

                    <x-responsive-nav-link :href="route('admin.ecommerce.reviews')" :active="request()->routeIs('admin.ecommerce.reviews*')" wire:navigate>
                        {{ __('Reviews') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')" wire:navigate>
                        {{ __('Reports') }}
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
                    <div class="my-1.5">
                        <div class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-4 py-1.5">Content</div>
                        @foreach ([
                            ['route' => 'admin.cms-pages.index',       'match' => 'admin.cms-pages.*',        'label' => 'Pages',              'color' => 'text-indigo-500'],
                            ['route' => 'admin.kb.index',              'match' => 'admin.kb.*',               'label' => 'KB Docs',            'color' => 'text-sky-500'],
                            ['route' => 'admin.testimonials.index',    'match' => 'admin.testimonials.*',     'label' => 'Testimonials',       'color' => 'text-amber-500'],
                            ['route' => 'admin.faqs.index',            'match' => 'admin.faqs.*',             'label' => 'FAQs',               'color' => 'text-emerald-500'],
                            ['route' => 'admin.cms-forms.index',       'match' => 'admin.cms-forms.*',        'label' => 'Forms',              'color' => 'text-violet-500'],
                            ['route' => 'admin.modals.index',          'match' => 'admin.modals.*',           'label' => 'Modals',             'color' => 'text-pink-500'],
                            ['route' => 'admin.cms-downloads.index',   'match' => 'admin.cms-downloads.*',    'label' => 'Downloads',          'color' => 'text-teal-500'],
                            ['route' => 'admin.cms-embeds.index',      'match' => 'admin.cms-embeds.*',       'label' => 'Code Embeds',        'color' => 'text-slate-500'],
                            ['route' => 'admin.cms-slideshows.index',  'match' => 'admin.cms-slideshows.*',   'label' => 'Slideshows',         'color' => 'text-orange-500'],
                            ['route' => 'admin.inventory-alerts.index','match' => 'admin.inventory-alerts.*', 'label' => 'Inventory Alerts',   'color' => 'text-red-500'],
                        ] as $item)
                            <x-responsive-nav-link :href="route($item['route'])" :active="request()->routeIs($item['match'])" wire:navigate>
                                <span class="flex items-center gap-2">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $item['color'] }} inline-block"></span>
                                    {{ __($item['label']) }}
                                </span>
                            </x-responsive-nav-link>
                        @endforeach

                        <div class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-4 py-1.5 mt-2">Structure &amp; Settings</div>
                        @foreach ([
                            ['route' => 'admin.cms-categories.index',    'match' => 'admin.cms-categories.*',    'label' => 'Categories',             'color' => 'text-indigo-500'],
                            ['route' => 'admin.cms-tags.index',          'match' => 'admin.cms-tags.*',          'label' => 'Tags',                   'color' => 'text-cyan-500'],
                            ['route' => 'admin.cms-list-menus.index',    'match' => 'admin.cms-list-menus.*',    'label' => 'List Menus',             'color' => 'text-slate-500'],
                            ['route' => 'admin.nav-builder.index',       'match' => 'admin.nav-builder.*',       'label' => 'Navigation Builder',     'color' => 'text-violet-500'],
                            ['route' => 'admin.cms-header-footer.index', 'match' => 'admin.cms-header-footer.*', 'label' => 'Header & Footer Builder','color' => 'text-indigo-500'],
                            ['route' => 'admin.site-labels.index',       'match' => 'admin.site-labels.*',       'label' => 'Page Labels',            'color' => 'text-yellow-500'],
                            ['route' => 'admin.languages.index',         'match' => 'admin.languages.*',         'label' => 'Languages',              'color' => 'text-emerald-500'],
                            ['route' => 'admin.languages.queue-monitor', 'match' => 'admin.languages.queue-monitor', 'label' => 'Queue Monitor',      'color' => 'text-slate-400'],
                        ] as $item)
                            <x-responsive-nav-link :href="route($item['route'])" :active="request()->routeIs($item['match'])" wire:navigate>
                                <span class="flex items-center gap-2">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $item['color'] }} inline-block"></span>
                                    {{ __($item['label']) }}
                                </span>
                            </x-responsive-nav-link>
                        @endforeach
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
                    <x-responsive-nav-link :href="route('admin.settings')" :active="request()->routeIs('admin.settings')">
                        {{ __('Settings') }}
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

                    @php $showAdminSwitcherMobile = \App\Models\CmsSetting::isEnabled('show_admin_dark_mode_switcher'); @endphp
                    @if($showAdminSwitcherMobile)
                    {{-- Mobile Dark Mode Toggle --}}
                    <div
                        x-data="{ isDark: document.documentElement.classList.contains('dark') }"
                        class="px-4 py-2"
                    >
                        <button
                            type="button"
                            @click="
                                isDark = !isDark;
                                document.documentElement.classList.toggle('dark', isDark);
                                $wire.toggleAdminDarkMode();
                            "
                            class="flex items-center gap-3 w-full text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
                        >
                            <svg x-show="isDark" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                            </svg>
                            <svg x-show="!isDark" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                            <span x-text="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'"></span>
                        </button>
                    </div>
                    @endif

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
