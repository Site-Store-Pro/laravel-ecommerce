@php
/** Partial: renders a plain <ul> of child nav items (used by nav-item.blade.php for dropdown children) */
/** @var \Illuminate\Support\Collection $children */
/** @var \App\Services\NavItemRenderer $renderer */
/** @var array $context */
@endphp
<ul class="nav-dropdown max-h-[22rem] overflow-y-auto overscroll-contain scrollbar-thin" role="menu">
    @foreach($children as $child)
        @if(!$child->isVisibleFor($context['user'] ?? null)) @continue @endif
        @if($child->hide_on_desktop) @continue @endif
        @php $cr = $renderer->resolveLink($child, $context); @endphp
        @if($cr['skip']) @continue @endif
        <li role="menuitem">
            @if($child->item_type === 'separator')
                <hr class="my-1" style="border-color: var(--nav-dropdown-border, #e2e8f0)">
            @elseif($child->item_type === 'login_logout')
                @if(auth()->check())
                    <div class="flex items-center justify-between px-3 py-1.5 w-full">
                        <a href="{{ route('profile') }}" class="text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-indigo-600 transition-colors">
                            {{ auth()->user()->name }}
                        </a>
                        <button wire:click.prevent="logout"
                                class="text-xs font-semibold text-red-650 hover:text-red-800 transition-colors focus:outline-none cursor-pointer bg-transparent border-0"
                                {{ $child->aria_label ? 'aria-label="'.e($child->aria_label).'"' : '' }}>
                            Logout
                        </button>
                    </div>
                @else
                    <a href="{{ $cr['href'] }}"
                       {{ $child->open_in_new_tab ? 'target="_blank" rel="noopener noreferrer"' : '' }}
                       {{ $child->aria_label ? 'aria-label="'.e($child->aria_label).'"' : '' }}>
                        {!! $cr['label'] !!}
                    </a>
                @endif
            @else
                <a href="{{ $cr['href'] }}"
                   {{ $child->open_in_new_tab ? 'target="_blank" rel="noopener noreferrer"' : '' }}
                   {{ $child->aria_label ? 'aria-label="'.e($child->aria_label).'"' : '' }}>
                    {!! $child->label !!}
                </a>
            @endif
        </li>
    @endforeach
</ul>
