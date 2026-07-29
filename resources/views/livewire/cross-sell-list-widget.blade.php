<div>
    {{--
        NOTE: Cart errors (out of stock, max qty, standalone conflicts) are surfaced via
        the global 'show-cart-error' browser event — caught by the modal in layouts/public.blade.php.
        No inline error banner is needed here.
    --}}

    {{-- Product display — routed to the correct sub-partial --}}
    @if($display === 'list')
        @include('plugins.display.cross-sell-list-widget-list', compact('products', 'header'))
    @elseif($display === 'slider')
        @include('plugins.display.cross-sell-list-widget-slider', compact('products', 'header', 'nav', 'autoplay', 'slides', 'speed', 'instanceId'))
    @else
        @include('plugins.display.cross-sell-list-widget-grid', compact('products', 'header', 'cols'))
    @endif
</div>
