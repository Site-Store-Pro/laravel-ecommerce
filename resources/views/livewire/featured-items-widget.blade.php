<div>
    {{--
        NOTE: Cart errors (out of stock, max qty, standalone conflicts) are surfaced via
        the global 'show-cart-error' browser event — caught by the modal in layouts/public.blade.php.
        No inline error banner is needed here.
    --}}
    {{-- Product display — routed to the correct sub-partial --}}
    @if($display === 'list')
        @include('plugins.display.featured-items-widget-list', compact('products', 'header', 'showBadge'))
    @elseif($display === 'slider')
        @include('plugins.display.featured-items-widget-slider', compact('products', 'header', 'nav', 'autoplay', 'slides', 'speed', 'instanceId', 'showBadge'))
    @else
        @include('plugins.display.featured-items-widget-grid', compact('products', 'header', 'cols', 'showBadge'))
    @endif

    {{--
        NOTE: The "Added to Cart" modal is NOT rendered here.
        FeaturedItemsWidget::buyNow() dispatches a 'show-cart-modal' browser event
        which is caught by the global modal in layouts/public.blade.php.
        This keeps modal logic in one place (DRY) and avoids stacking-context issues.
    --}}
</div>
