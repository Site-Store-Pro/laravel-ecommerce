<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Success Banner --}}
        <div class="mb-10 flex flex-col sm:flex-row items-center gap-6 bg-emerald-50 border border-emerald-200 rounded-3xl p-8 shadow-sm">
            <div class="flex-shrink-0 w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div class="flex-1 text-center sm:text-left">
                <h1 class="text-2xl font-extrabold text-emerald-900">
                    {{ $addedProduct ? $addedProduct->title : 'Item' }} @label('post_cart.added_message', 'was added to your cart!')
                </h1>
                @if($variant)
                    <p class="text-sm text-emerald-700 mt-1 font-medium">@label('post_cart.sku', 'SKU:') {{ $variant->sku }}</p>
                @endif
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-3 flex-shrink-0">
                <a href="{{ route('shop.index') }}" wire:navigate
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-emerald-200 text-emerald-800 text-sm font-bold rounded-2xl hover:bg-emerald-50 transition duration-150 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    @label('post_cart.continue_shopping', 'Continue Shopping')
                </a>
                <a href="{{ route('shop.cart') }}" wire:navigate
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-2xl hover:bg-slate-50 transition duration-150 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    @label('post_cart.view_cart', 'View Cart')
                </a>
                <a href="{{ route('shop.checkout') }}" wire:navigate
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl transition duration-150 shadow-md">
                    @label('post_cart.checkout', 'Checkout')
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Cross-Sell Products Section --}}
        @if($addedProduct)
            @livewire('cross-sell-list-widget', [
                'productId'  => $addedProduct->id,
                'display'    => 'grid',
                'max'        => 8,
                'sort'       => 'sort_order',
                'header'     => 'You May Also Like',
                'cols'       => 4,
                'instanceId' => 'postcart_' . $addedProduct->id,
            ])
        @endif

    </div>
</div>
