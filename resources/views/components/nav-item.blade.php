@php
    /** @var \App\Models\NavItem $item */
    /** @var \App\Services\NavItemRenderer $renderer */
    /** @var array $context */
    /** @var int $cartCount */

    $resolved   = $renderer->resolveLink($item, $context);
    $subMenuHtml = $renderer->renderSubMenu($item, $context);
    $hasChildren = $item->children->isNotEmpty() || !empty($subMenuHtml);

    $liClass = 'nav-item-wrap relative flex items-center';
    if ($item->css_class) $liClass .= ' ' . e($item->css_class);
@endphp

@if($resolved['skip'])
    {{-- Hidden / inactive page — render nothing --}}
@elseif($item->item_type === 'separator')
    <span class="w-px h-5 mx-1" style="background: var(--nav-border)"></span>
@elseif($item->item_type === 'categories')
    <li class="{{ $liClass }}">
        <livewire:category-menu-widget :label="$resolved['label']" />
    </li>
@elseif($item->item_type === 'brands')
    <li class="{{ $liClass }}">
        <livewire:public-brands-menu :label="$resolved['label']" />
    </li>
@elseif($item->item_type === 'cart')
    {{-- Cart: dispatches open-cart event --}}
    <li class="{{ $liClass }}">
        <button wire:click.prevent="$dispatch('open-cart')"
                class="dyn-nav-link flex items-center gap-1.5 px-3 py-2 focus:outline-none"
                {{ $item->aria_label ? 'aria-label="'.e($item->aria_label).'"' : '' }}>
            {!! $resolved['label'] !!}
            @if(($cartCount ?? 0) > 0)
                <span class="nav-cart-badge inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold rounded-full leading-none">
                    {{ $cartCount }}
                </span>
            @endif
        </button>
    </li>
@elseif($item->item_type === 'login_logout')
    <li class="{{ $liClass }}">
        @if(auth()->check())
            <div class="flex items-center gap-3">
                <a href="{{ route('profile') }}" class="text-sm font-semibold text-slate-700 dark:text-slate-300 hover:text-indigo-600 transition-colors">
                    {{ auth()->user()->name }}
                </a>
                <span class="h-4 w-px bg-slate-200 dark:bg-slate-700"></span>
                <button wire:click.prevent="logout"
                        class="text-sm font-semibold text-red-650 hover:text-red-800 transition-colors focus:outline-none cursor-pointer bg-transparent border-0"
                        {{ $item->aria_label ? 'aria-label="'.e($item->aria_label).'"' : '' }}>
                    @label('nav.log_out', 'Logout')
                </button>
            </div>
        @else
            <a href="{{ $resolved['href'] }}"
               class="dyn-nav-link px-3 py-2"
               {{ $item->open_in_new_tab ? 'target="_blank" rel="noopener noreferrer"' : '' }}
               {{ $item->aria_label ? 'aria-label="'.e($item->aria_label).'"' : '' }}>
                {!! $resolved['label'] !!}
            </a>
        @endif
    </li>
@elseif($item->item_type === 'parent' || $item->item_type === 'no_link')
    {{-- Non-clickable parent dropdown container --}}
    <li class="{{ $liClass }}">
        <button type="button"
                class="dyn-nav-link px-3 py-2 inline-flex items-center gap-1 cursor-pointer bg-transparent border-0"
                {{ $item->aria_label ? 'aria-label="'.e($item->aria_label).'"' : '' }}>
            {!! $resolved['label'] !!}
            @if($hasChildren)
                <svg class="w-3 h-3 opacity-60 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
            @endif
        </button>
        @if($hasChildren)
            @if(!empty($subMenuHtml))
                {!! $subMenuHtml !!}
            @else
                @include('components.nav-children', ['children' => $item->children, 'renderer' => $renderer, 'context' => $context])
            @endif
        @endif
    </li>
@else
    {{-- Standard link item --}}
    <li class="{{ $liClass }}">
        <a href="{{ $resolved['href'] }}"
           class="dyn-nav-link px-3 py-2 {{ $hasChildren ? 'inline-flex items-center gap-0.5' : '' }}"
           {{ $item->open_in_new_tab ? 'target="_blank" rel="noopener noreferrer"' : '' }}
           {{ $item->aria_label ? 'aria-label="'.e($item->aria_label).'"' : '' }}>
            {!! $resolved['label'] !!}
            @if($hasChildren)
                <svg class="w-3 h-3 opacity-60 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
            @endif
        </a>
        @if($hasChildren)
            @if(!empty($subMenuHtml))
                {!! $subMenuHtml !!}
            @else
                @include('components.nav-children', ['children' => $item->children, 'renderer' => $renderer, 'context' => $context])
            @endif
        @endif
    </li>
@endif
