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
    background: var(--nav-bg, rgba(255,255,255,0.85));
    border-bottom: 1px solid var(--nav-border, rgba(226,232,240,0.8));
    backdrop-filter: var(--nav-backdrop, blur(12px));
    -webkit-backdrop-filter: var(--nav-backdrop, blur(12px));
    {{ $menu->sticky ? 'position: sticky; top: 0; z-index: 50;' : '' }}
}
#top-nav-{{ $menu->slug }} .dyn-nav-link {
    color: var(--nav-text, #475569);
    font-size: 0.875rem;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.15s;
    padding: 0.25rem 0;
    position: relative;
}
#top-nav-{{ $menu->slug }} .dyn-nav-link:hover,
#top-nav-{{ $menu->slug }} .dyn-nav-link:focus {
    color: var(--nav-text-hover, #4f46e5);
    outline: none;
}
#top-nav-{{ $menu->slug }} .nav-cart-badge {
    background: var(--nav-badge-bg, #4f46e5);
    color: var(--nav-badge-text, #fff);
}
/* Dropdown */
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
    border-radius: 0.75rem;
    box-shadow: var(--nav-dropdown-shadow, 0 10px 40px rgba(0,0,0,.10));
    padding: 0.5rem;
    list-style: none;
    margin: 0;
}
#top-nav-{{ $menu->slug }} .nav-mega-menu {
    left: 0;
    right: 0;
    min-width: 100%;
    border-radius: 0 0 0.75rem 0.75rem;
    padding: 1.5rem;
}
#top-nav-{{ $menu->slug }} .nav-item-wrap:hover > .nav-dropdown,
#top-nav-{{ $menu->slug }} .nav-item-wrap:focus-within > .nav-dropdown,
#top-nav-{{ $menu->slug }} .nav-item-wrap:hover > .nav-mega-menu,
#top-nav-{{ $menu->slug }} .nav-item-wrap:focus-within > .nav-mega-menu {
    display: block;
}
#top-nav-{{ $menu->slug }} .nav-dropdown li a,
#top-nav-{{ $menu->slug }} .nav-dropdown li button {
    display: block;
    width: 100%;
    padding: 0.5rem 0.75rem;
    color: var(--nav-dropdown-text, #1e293b);
    border-radius: 0.5rem;
    text-decoration: none;
    font-size: 0.8125rem;
    font-weight: 500;
    transition: background 0.15s, color 0.15s;
    text-align: left;
    background: none;
    border: none;
    cursor: pointer;
}
#top-nav-{{ $menu->slug }} .nav-dropdown li a:hover,
#top-nav-{{ $menu->slug }} .nav-dropdown li button:hover {
    background: var(--nav-dropdown-hover-bg, #f8fafc);
    color: var(--nav-text-hover, #4f46e5);
}
</style>

<header id="top-nav-{{ $menu->slug }}"
        x-data="{ mobileOpen: false }"
        role="navigation"
        aria-label="Main navigation">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between gap-4">

        {{-- Logo --}}
        @if($menu->show_logo)
        <div class="shrink-0" style="filter: var(--nav-logo-filter, none)">
            <x-site-logo />
        </div>
        @endif

        {{-- Desktop items --}}
        <nav class="hidden md:flex items-center gap-1 flex-1" aria-label="Desktop navigation">
            @foreach($items as $item)
                @if(!$item->isVisibleFor($context['user'])) @continue @endif
                @if($item->hide_on_desktop) @continue @endif
                <x-nav-item :item="$item" :renderer="$renderer" :context="$context" :cartCount="$cartCount" />
            @endforeach
        </nav>

        {{-- Mobile hamburger --}}
        <button @click="mobileOpen = !mobileOpen"
                class="md:hidden p-2 rounded-lg transition-colors"
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
         class="md:hidden border-t px-4 py-4 space-y-1"
         style="background: var(--nav-mobile-bg, #fff); border-color: var(--nav-border, #e2e8f0)"
         @click.away="mobileOpen = false">
        @foreach($items as $item)
            @if(!$item->isVisibleFor($context['user'])) @continue @endif
            @if($item->hide_on_mobile) @continue @endif
            @php $resolved = $renderer->resolveLink($item, $context); @endphp
            @if($resolved['skip']) @continue @endif

            @if($item->item_type === 'separator')
                <hr class="my-2" style="border-color: var(--nav-border)">
            @else
                <a href="{{ $resolved['href'] }}"
                   class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors"
                   style="color: var(--nav-mobile-text, #1e293b)"
                   {{ $item->open_in_new_tab ? 'target="_blank" rel="noopener"' : '' }}
                   {{ $item->aria_label ? 'aria-label="'.e($item->aria_label).'"' : '' }}>
                    {!! $item->label !!}
                    @if($item->item_type === 'cart' && ($cartCount ?? 0) > 0)
                        <span class="nav-cart-badge px-1.5 py-0.5 text-xs font-bold rounded-full">{{ $cartCount }}</span>
                    @endif
                </a>
                {{-- Mobile children (flat, indented) --}}
                @if($item->children->isNotEmpty())
                    <div class="ml-4 space-y-0.5">
                        @foreach($item->children as $child)
                            @if(!$child->isVisibleFor($context['user'])) @continue @endif
                            @php $cr = $renderer->resolveLink($child, $context); @endphp
                            @if(!$cr['skip'])
                            <a href="{{ $cr['href'] }}"
                               class="block px-3 py-2 rounded-lg text-sm transition-colors"
                               style="color: var(--nav-mobile-text, #1e293b); opacity: 0.8"
                               {{ $child->open_in_new_tab ? 'target="_blank" rel="noopener"' : '' }}>
                                {!! $child->label !!}
                            </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            @endif
        @endforeach
    </div>
</header>
