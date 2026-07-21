{{-- Cross-Selling Manager Partial --}}
<div id="section-cross-selling" class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">

    {{-- Section Header --}}
    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-rose-50 rounded-xl">
                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-900">Cross-Selling</h3>
                <p class="text-xs text-slate-400 mt-0.5">
                    Add up to <strong>10</strong> related products. Control whether each appears on the
                    <span class="font-semibold text-slate-600">Product Page</span> and/or the
                    <span class="font-semibold text-slate-600">Post Add-to-Cart</span> page.
                </p>
            </div>
        </div>
        <span class="text-sm font-extrabold {{ $product->crossSells->count() >= 10 ? 'text-rose-500' : 'text-indigo-600' }}">
            {{ $product->crossSells->count() }} / 10
        </span>
    </div>

    @error('crossSell')
        <p class="text-xs text-rose-500 font-semibold bg-rose-50 border border-rose-100 rounded-2xl px-4 py-2">
            {{ $message }}
        </p>
    @enderror

    {{-- Live Product Search --}}
    @if($product->crossSells->count() < 10)
        <div class="relative">
            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1.5">
                Search &amp; Add Product
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input
                    type="text"
                    wire:model.live.debounce.400ms="crossSellSearch"
                    placeholder="Type a product name or ID to search…"
                    class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 focus:bg-white text-sm transition"
                    autocomplete="off"
                >
                <div wire:loading wire:target="updatedCrossSellSearch"
                     class="absolute inset-y-0 right-3 flex items-center">
                    <svg class="animate-spin w-4 h-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg"
                         fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                </div>
            </div>

            {{-- Search Results Dropdown --}}
            @if($crossSellSearchActive && count($crossSellResults) > 0)
                <div class="absolute z-30 left-0 right-0 mt-1 bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden">
                    <ul class="divide-y divide-slate-100 max-h-72 overflow-y-auto">
                        @foreach($crossSellResults as $result)
                            <li>
                                <button
                                    type="button"
                                    wire:click="addCrossSell({{ $result['id'] }})"
                                    class="w-full flex items-center gap-3 px-4 py-3 hover:bg-indigo-50 transition text-left group"
                                >
                                    @if($result['thumbnail'])
                                        <img src="{{ $result['thumbnail'] }}"
                                             alt="{{ $result['title'] }}"
                                             class="w-10 h-10 rounded-xl object-cover flex-shrink-0 border border-slate-100">
                                    @else
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-slate-800 truncate group-hover:text-indigo-700">
                                            {{ $result['title'] }}
                                        </p>
                                        <p class="text-[10px] text-slate-400">ID #{{ $result['id'] }}</p>
                                    </div>
                                    <span class="flex-shrink-0 inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-600 text-white text-[10px] font-bold rounded-xl opacity-0 group-hover:opacity-100 transition">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Add
                                    </span>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                    <div class="px-4 py-2 border-t border-slate-100 bg-slate-50">
                        <p class="text-[10px] text-slate-400">
                            Showing {{ count($crossSellResults) }} result(s) — max 25 per search.
                            Refine your query to narrow results.
                        </p>
                    </div>
                </div>
            @elseif($crossSellSearchActive && count($crossSellResults) === 0)
                <div class="absolute z-30 left-0 right-0 mt-1 bg-white border border-slate-200 rounded-2xl shadow-xl px-4 py-4 text-center">
                    <p class="text-sm text-slate-500 font-semibold">No matching products found.</p>
                    <p class="text-xs text-slate-400 mt-0.5">Try a different name or product ID.</p>
                </div>
            @endif
        </div>
    @else
        <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl text-center">
            <p class="text-xs font-bold text-amber-700">Maximum of 10 cross-selling items reached. Remove one to add another.</p>
        </div>
    @endif

    {{-- Current Cross-Sell Items List --}}
    @if($product->crossSells->isEmpty())
        <div class="p-8 bg-slate-50 border border-dashed border-slate-200 rounded-2xl text-center">
            <svg class="mx-auto h-10 w-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            <p class="text-sm font-bold text-slate-500">No cross-selling products configured.</p>
            <p class="text-xs text-slate-400 mt-1">Use the search above to add related products.</p>
        </div>
    @else
        <div class="overflow-x-auto rounded-2xl border border-slate-200 shadow-sm">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 w-12">Sort</th>
                        <th class="px-4 py-3">Product</th>
                        <th class="px-4 py-3 text-center">Show on<br>Product Page</th>
                        <th class="px-4 py-3 text-center">Show on<br>Post-Cart Page</th>
                        <th class="px-4 py-3 text-right">Remove</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($product->crossSells as $crossSell)
                        @php $cp = $crossSell->crossSellProduct; @endphp
                        <tr class="hover:bg-slate-50/60 transition">

                            {{-- Sort Order --}}
                            <td class="px-4 py-3">
                                <input
                                    type="number"
                                    step="0.5"
                                    value="{{ $crossSell->sort_order }}"
                                    wire:change="updateCrossSellOrder({{ $crossSell->id }}, $event.target.value)"
                                    class="w-16 px-2 py-1 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs text-center font-semibold"
                                    title="Sort order"
                                >
                            </td>

                            {{-- Product Info --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($cp && $cp->primaryThumbnailUrl())
                                        <img src="{{ $cp->primaryThumbnailUrl() }}"
                                             alt="{{ $cp->title ?? '' }}"
                                             class="w-10 h-10 rounded-xl object-cover border border-slate-100 flex-shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-slate-800">
                                            {{ $cp ? $cp->title : '(Product Deleted)' }}
                                        </p>
                                        <p class="text-[10px] text-slate-400">ID #{{ $crossSell->cross_sell_product_id }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Display on Item View --}}
                            <td class="px-4 py-3 text-center">
                                <button
                                    type="button"
                                    wire:click="toggleCrossSellItemView({{ $crossSell->id }})"
                                    title="{{ $crossSell->display_on_item_view ? 'Shown on product page — click to hide' : 'Hidden from product page — click to show' }}"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-xl transition {{ $crossSell->display_on_item_view ? 'bg-emerald-100 text-emerald-600 hover:bg-emerald-200' : 'bg-slate-100 text-slate-400 hover:bg-slate-200' }}"
                                >
                                    @if($crossSell->display_on_item_view)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    @endif
                                </button>
                            </td>

                            {{-- Display on Post-Cart --}}
                            <td class="px-4 py-3 text-center">
                                <button
                                    type="button"
                                    wire:click="toggleCrossSellPostCart({{ $crossSell->id }})"
                                    title="{{ $crossSell->display_on_post_cart ? 'Shown post-cart — click to hide' : 'Hidden from post-cart — click to show' }}"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-xl transition {{ $crossSell->display_on_post_cart ? 'bg-indigo-100 text-indigo-600 hover:bg-indigo-200' : 'bg-slate-100 text-slate-400 hover:bg-slate-200' }}"
                                >
                                    @if($crossSell->display_on_post_cart)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    @endif
                                </button>
                            </td>

                            {{-- Remove --}}
                            <td class="px-4 py-3 text-right">
                                <button
                                    type="button"
                                    wire:click="removeCrossSell({{ $crossSell->id }})"
                                    wire:confirm="Remove this cross-sell product?"
                                    class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-[10px] font-bold rounded-xl transition"
                                >
                                    Remove
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="text-[10px] text-slate-400 text-right">
            ✓ = Enabled &nbsp;|&nbsp; ✕ = Disabled &nbsp;|&nbsp; Click any toggle to change display placement.
        </p>
    @endif
</div>
