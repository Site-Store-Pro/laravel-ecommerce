{{-- ═══════════════════════════════════════════════════════════════════
     GLOBAL CART CONFIRMATION & ERROR MODALS
     Placed here as a direct component — above ALL page stacking
     contexts, including sticky nav bars.
     Triggered by any Livewire component calling:
       - $this->dispatch('show-cart-modal', itemName: '...', qty: 1)
       - $this->dispatch('show-cart-error', message: '...')
     ═══════════════════════════════════════════════════════════════════ --}}

{{-- 1. Cart Success Modal --}}
<div
    x-data="{
        show: false,
        itemName: '',
        qty: 1,
        checkoutUrl: '{{ route('shop.checkout') }}',
        open(detail) {
            let d = detail;
            if (Array.isArray(d)) d = d[0];
            if (d && typeof d === 'object' && d.detail) d = d.detail;
            if (Array.isArray(d)) d = d[0];
            if (d && typeof d === 'object' && d[0]) d = d[0];
            d = d || {};
            this.itemName = d.itemName || d.item_name || '';
            this.qty = d.qty || d.quantity || 1;
            this.show = true;
            document.body.style.overflow = 'hidden';
        },
        close() {
            this.show = false;
            document.body.style.overflow = '';
        }
    }"
    x-on:show-cart-modal.window="open($event.detail)"
    x-show="show"
    x-cloak
    style="display:none"
    class="fixed inset-0 z-[99999] flex items-center justify-center p-4"
    @keydown.escape.window="close()"
>
    {{-- Backdrop --}}
    <div
        class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
        @click="close()"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    ></div>

    {{-- Modal card --}}
    <div
        class="relative bg-white border border-slate-100 rounded-3xl p-8 shadow-2xl max-w-md w-full text-center space-y-6"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
    >
        {{-- Icon --}}
        <div class="inline-flex items-center justify-center p-3 rounded-full bg-indigo-50 text-indigo-600">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
        </div>

        {{-- Heading --}}
        <div>
            <h3 class="text-xl font-bold text-slate-900">@label('global.added_to_cart_heading', 'Added to Cart!')</h3>
            <p class="text-sm text-slate-500 mt-1">@label('global.added_to_cart_message', 'You have successfully added this item to your shopping cart.')</p>
        </div>

        {{-- Item details --}}
        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-left">
            <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">@label('global.cart_modal_item_details', 'Item Details')</span>
            <span class="font-bold text-slate-800 text-sm block mt-1" x-text="itemName"></span>
            <span class="text-xs text-slate-500 block mt-0.5">@label('global.cart_modal_quantity', 'Quantity:') <span x-text="qty"></span></span>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col gap-2 pt-2">
            <a :href="checkoutUrl"
               class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md transition duration-150 block text-center">
                @label('global.cart_modal_go_to_checkout', 'Go to Checkout')
            </a>
            <button type="button" @click="close()"
                    class="w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition duration-150">
                @label('global.cart_modal_continue_shopping', 'Continue Shopping')
            </button>
        </div>
    </div>
</div>

{{-- 2. Cart Error Modal --}}
<div
    x-data="{
        show: false,
        message: '',
        open(detail) {
            let d = detail;
            if (Array.isArray(d)) d = d[0];
            if (d && typeof d === 'object' && d.detail) d = d.detail;
            if (Array.isArray(d)) d = d[0];
            if (d && typeof d === 'object' && d[0]) d = d[0];
            d = d || {};
            this.message = d.message || 'An unexpected error occurred.';
            this.show = true;
            document.body.style.overflow = 'hidden';
        },
        close() {
            this.show = false;
            document.body.style.overflow = '';
        }
    }"
    x-on:show-cart-error.window="open($event.detail)"
    x-show="show"
    x-cloak
    style="display:none"
    class="fixed inset-0 z-[99999] flex items-center justify-center p-4"
    @keydown.escape.window="close()"
>
    {{-- Backdrop --}}
    <div
        class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
        @click="close()"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    ></div>

    {{-- Modal card --}}
    <div
        class="relative bg-white border border-rose-100 rounded-3xl p-8 shadow-2xl max-w-md w-full text-center space-y-5"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
    >
        {{-- Corner close button --}}
        <button type="button" @click="close()"
                class="absolute top-4 right-4 p-1.5 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition focus:outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        {{-- Error Icon --}}
        <div class="inline-flex items-center justify-center p-3 rounded-full bg-rose-50 text-rose-600">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        {{-- Heading --}}
        <div>
            <h3 class="text-xl font-bold text-slate-900">@label('global.cart_error_heading', 'Notice')</h3>
            <p class="text-sm text-slate-600 mt-2 font-medium leading-relaxed" x-text="message"></p>
        </div>

        {{-- Actions --}}
        <div class="pt-2">
            <button type="button" @click="close()"
                    class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold rounded-xl transition duration-150 shadow-md">
                @label('global.cart_error_close', 'Got it')
            </button>
        </div>
    </div>
</div>
