<div>
    {{-- Flash messages --}}
    @if(session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 rounded-2xl border border-red-100 flex items-center gap-3 text-red-800 text-sm font-semibold">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Product display — routed to the correct sub-partial --}}
    @if($display === 'list')
        @include('plugins.display.featured-items-widget-list', compact('products', 'header'))
    @elseif($display === 'slider')
        @include('plugins.display.featured-items-widget-slider', compact('products', 'header', 'nav', 'autoplay', 'slides', 'speed', 'instanceId'))
    @else
        @include('plugins.display.featured-items-widget-grid', compact('products', 'header', 'cols'))
    @endif

    {{--
        NOTE: The "Added to Cart" modal is NOT rendered here.
        FeaturedItemsWidget::buyNow() dispatches a 'show-cart-modal' browser event
        which is caught by the global modal in layouts/public.blade.php.
        This keeps modal logic in one place (DRY) and avoids stacking-context issues.
    --}}
</div>
