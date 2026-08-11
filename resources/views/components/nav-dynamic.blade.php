@php
    use App\Services\NavItemRenderer;

    /** @var \App\Models\NavMenu $menu */
    /** @var \Illuminate\Support\Collection $items  (pre-built tree, only top-level items with ->children loaded) */
    /** @var int $cartCount */
    /** @var \App\Models\User|null $user */

    $renderer = app(NavItemRenderer::class);
    $context  = ['user' => $user ?? null, 'cartCount' => $cartCount ?? 0];

    // Build CSS variable block for the chosen color scheme
    $schemeVars = $menu->colorSchemeVars();
    $cssVarBlock = '';
    if (!empty($schemeVars)) {
        $cssVarBlock = '#top-nav-' . e($menu->slug) . ' {';
        foreach ($schemeVars as $prop => $val) {
            $cssVarBlock .= $prop . ':' . $val . ';';
        }
        $cssVarBlock .= '}';
    }
    $customCss = $menu->custom_css ?? '';

    $alignmentClass = match($menu->alignment ?? 'left') {
        'center' => 'justify-center',
        'right'  => 'justify-end',
        'even'   => 'justify-between',
        default  => 'justify-start',
    };
@endphp

{{-- Scoped styles --}}
@if($cssVarBlock || $customCss)
<style>
    /* Nav scheme: {{ $menu->color_scheme }} */
    {!! $cssVarBlock !!}
    /* Nav custom CSS */
    {!! $customCss !!}
</style>
@endif

{{-- Base CSS for the dynamic nav (using CSS variables) --}}
<style>
#top-nav-{{ $menu->slug }} {
    background: var(--nav-bg, transparent);
    border-bottom: var(--nav-border, none);
    backdrop-filter: var(--nav-backdrop, none);
    -webkit-backdrop-filter: var(--nav-backdrop, none);
    {{ $menu->sticky ? 'position: sticky; top: 0; z-index: 50;' : '' }}
}
.dyn-nav-link,
#top-nav-{{ $menu->slug }} .dyn-nav-link {
    color: var(--nav-text, #334155);
    font-size: 0.875rem !important;
    font-weight: 600 !important;
    line-height: 1.25rem;
    text-decoration: none;
    transition: color 0.15s;
    padding: 0.25rem 0;
    position: relative;
}
.dyn-nav-link:hover,
.dyn-nav-link:focus,
#top-nav-{{ $menu->slug }} .dyn-nav-link:hover,
#top-nav-{{ $menu->slug }} .dyn-nav-link:focus {
    color: var(--nav-text-hover, #4f46e5);
    outline: none;
}
button.dyn-nav-link,
#top-nav-{{ $menu->slug }} button {
    color: var(--nav-text, #334155) !important;
    font-size: 0.875rem !important;
    font-weight: 600 !important;
    line-height: 1.25rem !important;
}
#top-nav-{{ $menu->slug }} button:hover,
#top-nav-{{ $menu->slug }} button:focus {
    color: var(--nav-text-hover, #4f46e5) !important;
}
#top-nav-{{ $menu->slug }} button svg {
    color: currentColor !important;
}
#top-nav-{{ $menu->slug }} .nav-cart-badge {
    background: var(--nav-badge-bg, #4f46e5);
    color: var(--nav-badge-text, #fff);
}
/* Dropdown */
.nav-dropdown,
.nav-mega-menu,
#top-nav-{{ $menu->slug }} .nav-dropdown,
#top-nav-{{ $menu->slug }} .nav-mega-menu {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 200;
    min-width: 12rem;
    background: var(--nav-dropdown-bg, #ffffff);
    border: 1px solid var(--nav-dropdown-border, #e2e8f0);
    border-radius: 1rem;
    box-shadow: var(--nav-dropdown-shadow, none);
    padding: 0.75rem 0.5rem;
    margin-top: 0.25rem;
    list-style: none;
}
.nav-mega-menu,
#top-nav-{{ $menu->slug }} .nav-mega-menu {
    left: 0;
    right: 0;
    min-width: 100%;
    border-radius: 0 0 0.75rem 0.75rem;
    padding: 1.5rem;
}
.nav-item-wrap:hover > .nav-dropdown,
.nav-item-wrap:focus-within > .nav-dropdown,
.nav-item-wrap:hover > .nav-mega-menu,
.nav-item-wrap:focus-within > .nav-mega-menu,
#top-nav-{{ $menu->slug }} .nav-item-wrap:hover > .nav-dropdown,
#top-nav-{{ $menu->slug }} .nav-item-wrap:focus-within > .nav-dropdown,
#top-nav-{{ $menu->slug }} .nav-item-wrap:hover > .nav-mega-menu,
#top-nav-{{ $menu->slug }} .nav-item-wrap:focus-within > .nav-mega-menu {
    display: block !important;
}
.nav-dropdown li a,
.nav-dropdown li button,
#top-nav-{{ $menu->slug }} .nav-dropdown li a,
#top-nav-{{ $menu->slug }} .nav-dropdown li button {
    display: block;
    width: 100%;
    padding: 0.5rem 0.875rem;
    color: var(--nav-dropdown-text, #1e293b);
    border-radius: 0.625rem;
    text-decoration: none;
    font-size: 0.8125rem;
    font-weight: 500;
    transition: background 0.15s, color 0.15s;
    text-align: left;
    background: none;
    border: none;
    cursor: pointer;
}
.nav-dropdown li a:hover,
.nav-dropdown li button:hover,
#top-nav-{{ $menu->slug }} .nav-dropdown li a:hover,
#top-nav-{{ $menu->slug }} .nav-dropdown li button:hover {
    background: var(--nav-dropdown-hover-bg, #f8fafc);
    color: var(--nav-text-hover, #4f46e5);
}

/* ── Dark mode overrides ─────────────────────────────────────────────────── */
/* Strategy: redefine ALL --nav-* CSS variables at the container level so     */
/* every descendant reading var(--nav-text) etc. gets dark-mode values.       */
/* This is necessary because HeaderFooterCssManager hard-sets --nav-text to   */
/* the admin's chosen color (e.g. #000000), making fallbacks inside           */
/* var(--nav-text, #cbd5e1) unreachable — the variable IS set, just wrong.   */
/* Redefining variables on the parent ensures the new values cascade down.    */

/* Standalone nav wrapper — redefine all nav variables */
html.dark #top-nav-{{ $menu->slug }} {
    --nav-bg:                  #1e293b;
    --nav-text:                #cbd5e1;
    --nav-text-hover:          #818cf8;
    --nav-border:              rgba(255, 255, 255, 0.06);
    --nav-dropdown-bg:         #1e293b;
    --nav-dropdown-border:     #334155;
    --nav-dropdown-text:       #cbd5e1;
    --nav-dropdown-hover-bg:   #334155;
    --nav-dropdown-shadow:     0 10px 40px rgba(0, 0, 0, 0.4);
    --nav-mobile-bg:           #1e293b;
    --nav-mobile-text:         #cbd5e1;
    background:                #1e293b;
    border-bottom-color:       rgba(255, 255, 255, 0.06);
}

/* Override --header-background-color so the Tailwind bg-[var()] class on
   .header_container also resolves to dark. This is needed because the
   Tailwind JIT class reads the CSS variable from :root, set by
   HeaderFooterCssManager to the admin's chosen color. */
html.dark {
    --header-background-color: #1e293b;

    /* ── Shop filter pills ────────────────────────────────────────────────── */
    /* Strategy: redefine the CSS variables here rather than fighting the      */
    /* compiled CSS !important rules. html.dark has higher specificity than    */
    /* :root, so these values cascade down and the compiled CSS var() calls    */
    /* resolve to the muted dark-mode tones automatically.                     */
    /* Admin colour overrides using direct colour values bypass variables       */
    /* entirely and are unaffected. Admin variable customisations via :root     */
    /* are superseded here in dark mode only.                                  */

    /* Category filter pills */
    --shop-category-pill-bg:           rgba(30, 41, 59, 0.75);
    --shop-category-pill-text:         #94a3b8;
    --shop-category-pill-border:       rgba(71, 85, 105, 0.55);
    --shop-category-pill-hover-bg:     rgba(44, 74, 124, 0.50);
    --shop-category-pill-hover-text:   #cbd5e1;
    --shop-category-pill-hover-border: rgba(44, 74, 124, 0.70);

    /* Brand filter pills */
    --shop-brand-pill-bg:              rgba(30, 41, 59, 0.75);
    --shop-brand-pill-text:            #94a3b8;
    --shop-brand-pill-border:          rgba(71, 85, 105, 0.55);
    --shop-brand-pill-hover-bg:        rgba(44, 74, 124, 0.50);
    --shop-brand-pill-hover-text:      #cbd5e1;
    --shop-brand-pill-hover-border:    rgba(44, 74, 124, 0.70);

    /* Subcategory filter pills */
    --shop-subcat-pill-bg:             rgba(30, 41, 59, 0.75);
    --shop-subcat-pill-text:           #94a3b8;
    --shop-subcat-pill-border:         rgba(71, 85, 105, 0.55);
    --shop-subcat-pill-hover-bg:       rgba(44, 74, 124, 0.50);
    --shop-subcat-pill-hover-text:     #cbd5e1;
    --shop-subcat-pill-hover-border:   rgba(44, 74, 124, 0.70);

    /* Grid / List view-mode toggle icons */
    --shop-view-inactive-bg:           #1e293b;
    --shop-view-inactive-text:         #64748b;
    --shop-view-active-bg:             #2c4a7c;
    --shop-view-active-text:           #cbd5e1;
}

/* Header builder container (outer wrapper) + site_header_container (inner div
   where HeaderFooterCssManager applies background-color: var(--header-background-color))
   Both need overriding because the compiled CSS paints .site_header_container
   separately from the outer .header_container wrapper. */
html.dark .header_container,
html.dark .site_header_container,
html.dark #site_header_container {
    --nav-bg:                    #1e293b;
    --nav-text:                  #cbd5e1;
    --nav-text-hover:            #818cf8;
    --nav-border:                rgba(255, 255, 255, 0.06);
    --nav-dropdown-bg:           #1e293b;
    --nav-dropdown-border:       #334155;
    --nav-dropdown-text:         #cbd5e1;
    --nav-dropdown-hover-bg:     #334155;
    --nav-dropdown-shadow:       0 10px 40px rgba(0, 0, 0, 0.4);
    --nav-mobile-bg:             #1e293b;
    --nav-mobile-text:           #cbd5e1;
    --header-background-color:   #1e293b;  /* redefine at element level too */
    background-color:            #1e293b !important;
    background-image:            none !important;
}

/* Explicit color rules — belt + braces for specificity */
html.dark .header_container .dyn-nav-link,
html.dark #top-nav-{{ $menu->slug }} .dyn-nav-link {
    color: #cbd5e1 !important;
}
html.dark .header_container .dyn-nav-link:hover,
html.dark .header_container .dyn-nav-link:focus,
html.dark #top-nav-{{ $menu->slug }} .dyn-nav-link:hover,
html.dark #top-nav-{{ $menu->slug }} .dyn-nav-link:focus {
    color: #818cf8 !important;
}
html.dark .header_container button.dyn-nav-link,
html.dark #top-nav-{{ $menu->slug }} button {
    color: #cbd5e1 !important;
}
html.dark .header_container button.dyn-nav-link:hover,
html.dark #top-nav-{{ $menu->slug }} button:hover,
html.dark #top-nav-{{ $menu->slug }} button:focus {
    color: #818cf8 !important;
}

/* Dropdown panels */
html.dark .header_container .nav-dropdown,
html.dark .header_container .nav-mega-menu,
html.dark #top-nav-{{ $menu->slug }} .nav-dropdown,
html.dark #top-nav-{{ $menu->slug }} .nav-mega-menu {
    background:    #1e293b;
    border-color:  #334155;
    box-shadow:    0 10px 40px rgba(0, 0, 0, 0.4);
}
html.dark .header_container .nav-dropdown li a,
html.dark .header_container .nav-dropdown li button,
html.dark #top-nav-{{ $menu->slug }} .nav-dropdown li a,
html.dark #top-nav-{{ $menu->slug }} .nav-dropdown li button {
    color: #cbd5e1 !important;
}
html.dark .header_container .nav-dropdown li a:hover,
html.dark .header_container .nav-dropdown li button:hover,
html.dark #top-nav-{{ $menu->slug }} .nav-dropdown li a:hover,
html.dark #top-nav-{{ $menu->slug }} .nav-dropdown li button:hover {
    background: #334155;
    color:      #818cf8 !important;
}

/* Top sharing / promo bar */
html.dark .top_sharing_container {
    background-color: #1e293b;
    color:            #cbd5e1;
}
html.dark .top_sharing_container a {
    color: #cbd5e1;
}
html.dark .top_sharing_container a:hover {
    color: #818cf8;
}

/* Fallback plain-link nav rows (no dynamic menu configured) */
html.dark .top_nav_row a,
html.dark .top_nav_area a,
html.dark #top_nav_row a,
html.dark #top_nav_area a,
html.dark #top_nav_area_main a,
html.dark #top_nav_area_col1 a,
html.dark #top_nav_area_col2 a {
    color: #cbd5e1;
}
html.dark .top_nav_row a:hover,
html.dark #top_nav_area_main a:hover,
html.dark #top_nav_area_col1 a:hover,
html.dark #top_nav_area_col2 a:hover {
    color: #818cf8;
}

/* ── Dark mode: muted slate-navy primary button override ─────────────────── */
/* Replaces the vibrant indigo-600 (#4f46e5) with a softer slate-navy tone   */
/* (#2c4a7c) that reads more naturally on dark backgrounds.                  */
/*                                                                             */
/* NOTE: No !important is used here intentionally — any admin CSS skinning    */
/* override that uses !important OR targets a more specific selector will     */
/* naturally take precedence, preserving button customisation capability.     */
html.dark button.bg-indigo-600,
html.dark a.bg-indigo-600,
html.dark input[type="submit"].bg-indigo-600,
html.dark input[type="button"].bg-indigo-600 {
    background-color: #2c4a7c;
}
html.dark button.bg-indigo-600:hover,
html.dark a.bg-indigo-600:hover,
html.dark input[type="submit"].bg-indigo-600:hover,
html.dark input[type="button"].bg-indigo-600:hover,
html.dark button.hover\:bg-indigo-700:hover,
html.dark a.hover\:bg-indigo-700:hover {
    background-color: #1e3664;
}
/* Focus ring — keep it visible but toned down */
/* Auto height and overflow containment for wrapping header navigation */
.header_container,
.site_header_container,
.site_header_contents,
.top_nav_container,
.top_nav_row {
    height: auto !important;
    min-height: max-content;
    overflow: visible;
}
</style>

@if($embedded ?? false)
    <ul class="flex flex-wrap items-center gap-x-6 gap-y-[25px] flex-1 {{ $alignmentClass }} list-none m-0 p-0 py-1">
        @foreach($items as $item)
            @if(!$item->isVisibleFor($context['user'])) @continue @endif
            @if($item->hide_on_desktop) @continue @endif
            <x-nav-item :item="$item" :renderer="$renderer" :context="$context" :cartCount="$cartCount" />
        @endforeach
    </ul>
@else
<header id="top-nav-{{ $menu->slug }}"
        x-data="{ mobileOpen: false }"
        role="navigation"
        aria-label="Main navigation">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-wrap items-center justify-between gap-4">

        {{-- Logo --}}
        @if($menu->show_logo)
        <div class="shrink-0 w-auto max-w-max flex items-center min-w-0" style="filter: var(--nav-logo-filter, none)">
            <x-site-logo />
        </div>
        @endif

        {{-- Desktop items --}}
        <nav class="hidden lg:flex flex-wrap items-center gap-6 flex-1 {{ $alignmentClass }}" aria-label="Desktop navigation">
            <ul class="flex flex-wrap items-center gap-x-6 gap-y-[25px] flex-1 {{ $alignmentClass }} list-none m-0 p-0 py-1">
                @foreach($items as $item)
                    @if(!$item->isVisibleFor($context['user'])) @continue @endif
                    @if($item->hide_on_desktop) @continue @endif
                    <x-nav-item :item="$item" :renderer="$renderer" :context="$context" :cartCount="$cartCount" />
                @endforeach
            </ul>
        </nav>

        {{-- Mobile hamburger --}}
        <button @click="mobileOpen = !mobileOpen"
                class="lg:hidden p-2 rounded-lg transition-colors"
                style="color: var(--nav-text)"
                aria-label="Toggle navigation"
                :aria-expanded="mobileOpen">
            <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
            <svg x-show="mobileOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Mobile slide-down --}}
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="lg:hidden border-t px-4 py-4 space-y-1"
         style="background: var(--nav-mobile-bg, #fff); border-color: var(--nav-border, #e2e8f0)"
         @click.away="mobileOpen = false">
        @foreach($items as $item)
            @if(!$item->isVisibleFor($context['user'])) @continue @endif
            @if($item->hide_on_mobile) @continue @endif
            @php $resolved = $renderer->resolveLink($item, $context); @endphp
            @if($resolved['skip']) @continue @endif

            @if($item->item_type === 'separator')
                <hr class="my-2" style="border-color: var(--nav-border)">
            @elseif($item->item_type === 'categories')
                <div class="px-3 py-1">
                    <livewire:category-menu-widget :label="$resolved['label']" />
                </div>
            @elseif($item->item_type === 'brands')
                <div class="px-3 py-1">
                    <livewire:public-brands-menu :label="$resolved['label']" />
                </div>
            @elseif($item->item_type === 'login_logout')
                @if(auth()->check())
                    <div class="flex items-center justify-between px-3 py-2 border-t border-b border-slate-100 dark:border-slate-800 my-1 w-full">
                        <a href="{{ route('profile') }}" class="text-sm font-semibold text-slate-700 dark:text-slate-300 hover:text-indigo-600 transition-colors">
                            {{ auth()->user()->name }}
                        </a>
                        <button wire:click.prevent="logout"
                                class="text-sm font-semibold text-red-650 hover:text-red-800 transition-colors focus:outline-none cursor-pointer bg-transparent border-0"
                                {{ $item->aria_label ? 'aria-label="'.e($item->aria_label).'"' : '' }}>
                            @label('nav.log_out', 'Logout')
                        </button>
                    </div>
                @else
                    <a href="{{ $resolved['href'] }}"
                       class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors"
                       style="color: var(--nav-mobile-text, #1e293b)"
                       {{ $item->open_in_new_tab ? 'target="_blank" rel="noopener"' : '' }}
                       {{ $item->aria_label ? 'aria-label="'.e($item->aria_label).'"' : '' }}>
                        {!! $resolved['label'] !!}
                    </a>
                @endif
            @else
                <a href="{{ $resolved['href'] }}"
                   class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors"
                   style="color: var(--nav-mobile-text, #1e293b)"
                   {{ $item->open_in_new_tab ? 'target="_blank" rel="noopener"' : '' }}
                   {{ $item->aria_label ? 'aria-label="'.e($item->aria_label).'"' : '' }}>
                    {!! $resolved['label'] !!}
                    @if($item->item_type === 'cart' && ($cartCount ?? 0) > 0)
                        <span class="nav-cart-badge px-1.5 py-0.5 text-xs font-bold rounded-full">{{ $cartCount }}</span>
                    @endif
                </a>
                {{-- Mobile children (flat, indented) --}}
                @if($item->children->isNotEmpty())
                    <div class="ml-4 space-y-0.5 max-h-[14rem] overflow-y-auto pr-1 scrollbar-thin scroll-smooth overscroll-contain">
                        @foreach($item->children as $child)
                            @if(!$child->isVisibleFor($context['user'])) @continue @endif
                            @if($child->hide_on_mobile) @continue @endif
                            @php $cr = $renderer->resolveLink($child, $context); @endphp
                            @if(!$cr['skip'])
                                @if($child->item_type === 'login_logout')
                                    @if(auth()->check())
                                        <div class="flex items-center justify-between px-3 py-1.5 ml-4 w-full">
                                            <a href="{{ route('profile') }}" class="text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-indigo-600 transition-colors">
                                                {{ auth()->user()->name }}
                                            </a>
                                            <button wire:click.prevent="logout"
                                                    class="text-xs font-semibold text-red-655 hover:text-red-800 transition-colors focus:outline-none cursor-pointer bg-transparent border-0"
                                                    {{ $child->aria_label ? 'aria-label="'.e($child->aria_label).'"' : '' }}>
                                                @label('nav.log_out', 'Logout')
                                            </button>
                                        </div>
                                    @else
                                        <a href="{{ $cr['href'] }}"
                                           class="block px-3 py-2 rounded-lg text-sm transition-colors"
                                           style="color: var(--nav-mobile-text, #1e293b); opacity: 0.8"
                                           {{ $child->open_in_new_tab ? 'target="_blank" rel="noopener"' : '' }}>
                                            {!! $cr['label'] !!}
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ $cr['href'] }}"
                                       class="block px-3 py-2 rounded-lg text-sm transition-colors"
                                       style="color: var(--nav-mobile-text, #1e293b); opacity: 0.8"
                                       {{ $child->open_in_new_tab ? 'target="_blank" rel="noopener"' : '' }}>
                                        {!! $cr['label'] !!}
                                    </a>
                                @endif
                            @endif
                        @endforeach
                    </div>
                @endif
            @endif
        @endforeach
    </div>
</header>
@endif
