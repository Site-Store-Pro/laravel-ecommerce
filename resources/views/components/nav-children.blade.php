@php
/** Partial: renders a plain <ul> of child nav items (used by nav-item.blade.php for dropdown children) */
/** @var \Illuminate\Support\Collection $children */
/** @var \App\Services\NavItemRenderer $renderer */
/** @var array $context */
@endphp
<ul class="nav-dropdown" role="menu">
    @foreach($children as $child)
        @if(!$child->isVisibleFor($context['user'] ?? null)) @continue @endif
        @php $cr = $renderer->resolveLink($child, $context); @endphp
        @if($cr['skip']) @continue @endif
        <li role="menuitem">
            @if($child->item_type === 'separator')
                <hr class="my-1" style="border-color: var(--nav-dropdown-border, #e2e8f0)">
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
