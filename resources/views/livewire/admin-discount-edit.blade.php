<div class="py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('admin.discounts.index') }}" wire:navigate class="p-2 rounded-xl border border-slate-200 bg-white text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ $discountId ? 'Edit Discount' : 'Create New Discount' }}</h1>
                <p class="text-sm text-slate-500 mt-1">Configure promotional coupon codes, specific items rules, category discounts, BOGO, or store-wide order values breaks.</p>
            </div>
        </div>

        <form wire:submit.prevent="save" class="space-y-6">
            <!-- 1. Basic Discount Settings Card -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                <h3 class="text-base font-bold text-slate-800 pb-3 border-b border-slate-100 uppercase tracking-wider text-xs">Basic Settings</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Select Discount Type</label>
                        <select wire:model.live="discount_type_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                            @foreach($discountTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        @error('discount_type_id') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Enter Name for Discount</label>
                        <input type="text" wire:model="name" placeholder="e.g. 20% OFF All Rings Sale" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                        @error('name') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Discount Value Type ($ or % OFF)</label>
                        <select wire:model="value_type" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                            <option value="1">Specific Value Off ($)</option>
                            <option value="2">Percent Off (%)</option>
                        </select>
                        <span class="text-[10px] text-slate-400 block mt-1">(Applies to all types except BOGO which is always percent based)</span>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Enter Discount Amount (Value)</label>
                        <input type="number" step="0.01" wire:model="value" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                        @error('value') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- 2. Coupon / Gift Certificate Options -->
            @if($discount_type_id == 1)
                <div class="bg-indigo-50/40 border border-indigo-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                    <h3 class="text-base font-bold text-indigo-900 pb-3 border-b border-indigo-100 uppercase tracking-wider text-xs">Coupon & Gift Certificate Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="text-xs font-bold text-indigo-700 block mb-2 uppercase tracking-wider">Enter Code</label>
                            <input type="text" wire:model="code" placeholder="e.g. SAVE20" class="w-full px-4 py-2.5 bg-white border border-indigo-200 text-indigo-900 rounded-2xl focus:outline-none focus:border-indigo-500 text-sm uppercase">
                            @error('code') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-bold text-indigo-700 block mb-2 uppercase tracking-wider">Code Type</label>
                            <select wire:model="code_type" class="w-full px-4 py-2.5 bg-white border border-indigo-200 text-indigo-900 rounded-2xl focus:outline-none focus:border-indigo-500">
                                <option value="0">Coupon Code (Multiple Use)</option>
                                <option value="1">Gift Certificate (Single Use Only)</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-indigo-700 block mb-2 uppercase tracking-wider">Wholesale User Only</label>
                            <select wire:model="wholesale_only" class="w-full px-4 py-2.5 bg-white border border-indigo-200 text-indigo-900 rounded-2xl focus:outline-none focus:border-indigo-500">
                                <option value="0">No, All Customers</option>
                                <option value="1">Yes, Wholesale Only</option>
                            </select>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 3. Product Specific Settings (Only valid if Item-Specific or Coupon Code) -->
            @if($discount_type_id == 6 || $discount_type_id == 1)
                <div class="bg-rose-50/30 border border-rose-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                    <h3 class="text-base font-bold text-rose-950 pb-3 border-b border-rose-100 uppercase tracking-wider text-xs">Product-Specific Selections & Rules</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-bold text-rose-800 block mb-2 uppercase tracking-wider">Target Product (Live Search)</label>
                            <div class="relative">
                                <div class="flex">
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="productSearch" 
                                           placeholder="Search product by title (min 2 chars)..." 
                                           class="w-full px-4 py-2.5 bg-white border border-rose-200 text-rose-900 rounded-2xl focus:outline-none focus:border-rose-500 text-sm">
                                    @if($product_id)
                                        <button type="button" wire:click="clearProduct" class="absolute right-3 top-3 text-rose-400 hover:text-rose-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                                @if(count($searchedProducts) > 0)
                                    <div class="absolute z-50 w-full mt-1 bg-white border border-rose-100 rounded-2xl shadow-xl max-h-60 overflow-y-auto">
                                        @foreach($searchedProducts as $p)
                                            <button type="button" 
                                                    wire:click="selectProduct({{ $p->id }}, '{{ addslashes($p->title) }}')" 
                                                    class="w-full text-left px-4 py-2.5 text-sm text-rose-950 hover:bg-rose-50 transition font-medium border-b border-rose-50/50 last:border-b-0">
                                                {{ $p->title }}
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                                @if($product_id)
                                    <div class="text-xs text-rose-600 mt-1.5 font-semibold flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Selected Target Product: ID #{{ $product_id }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 pt-2">
                            <div>
                                <label class="text-xs font-bold text-rose-800 block mb-1 uppercase tracking-wider">QTY Min</label>
                                <input type="number" wire:model="item_qty_min" class="w-full px-4 py-2.5 bg-white border border-rose-200 text-rose-900 rounded-2xl focus:outline-none focus:border-rose-500 text-sm">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-rose-800 block mb-1 uppercase tracking-wider">QTY Max</label>
                                <input type="number" wire:model="item_qty_max" class="w-full px-4 py-2.5 bg-white border border-rose-200 text-rose-900 rounded-2xl focus:outline-none focus:border-rose-500 text-sm">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-rose-800 block mb-1 uppercase tracking-wider">Item SubTotal Min ($)</label>
                                <input type="number" step="0.01" wire:model="item_subtotal_min" class="w-full px-4 py-2.5 bg-white border border-rose-200 text-rose-900 rounded-2xl focus:outline-none focus:border-rose-500 text-sm">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-rose-800 block mb-1 uppercase tracking-wider">Item SubTotal Max ($)</label>
                                <input type="number" step="0.01" wire:model="item_subtotal_max" class="w-full px-4 py-2.5 bg-white border border-rose-200 text-rose-900 rounded-2xl focus:outline-none focus:border-rose-500 text-sm">
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 4. BOGO Discount Settings -->
            @if($discount_type_id == 7)
                <div class="bg-emerald-50/20 border border-emerald-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                    <h3 class="text-base font-bold text-emerald-900 pb-3 border-b border-emerald-100 uppercase tracking-wider text-xs">BOGO Discount Settings (Buy X and Get Y)</h3>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-xs font-bold text-emerald-800 block mb-2 uppercase tracking-wider">Trigger Quantity (Buy X count)</label>
                                <input type="number" wire:model="free_range1" class="w-full px-4 py-2.5 bg-white border border-emerald-200 text-emerald-900 rounded-2xl focus:outline-none focus:border-emerald-500 text-sm">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-emerald-800 block mb-2 uppercase tracking-wider font-semibold">Select Trigger Product X (Live Search)</label>
                                <div class="relative">
                                    <div class="flex">
                                        <input type="text" 
                                               wire:model.live.debounce.300ms="triggerProductSearch" 
                                               placeholder="Search trigger product..." 
                                               class="w-full px-4 py-2.5 bg-white border border-emerald-200 text-emerald-900 rounded-2xl focus:outline-none focus:border-emerald-500 text-sm">
                                        @if($buy_x_get_y)
                                            <button type="button" wire:click="clearTriggerProduct" class="absolute right-3 top-3 text-emerald-400 hover:text-emerald-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                    @if(count($searchedTriggerProducts) > 0)
                                        <div class="absolute z-50 w-full mt-1 bg-white border border-emerald-100 rounded-2xl shadow-xl max-h-60 overflow-y-auto">
                                            @foreach($searchedTriggerProducts as $p)
                                                <button type="button" 
                                                        wire:click="selectTriggerProduct({{ $p->id }}, '{{ addslashes($p->title) }}')" 
                                                        class="w-full text-left px-4 py-2.5 text-sm text-emerald-950 hover:bg-emerald-50 transition font-medium border-b border-emerald-50/50 last:border-b-0">
                                                    {{ $p->title }}
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if($buy_x_get_y)
                                        <div class="text-xs text-emerald-600 mt-1.5 font-semibold flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Selected Trigger: ID #{{ $buy_x_get_y }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="text-xs font-bold text-emerald-800 block mb-2 uppercase tracking-wider">Discounted Quantity (Get Y count)</label>
                                <input type="number" wire:model="free_range2" class="w-full px-4 py-2.5 bg-white border border-emerald-200 text-emerald-900 rounded-2xl focus:outline-none focus:border-emerald-500 text-sm">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-emerald-800 block mb-2 uppercase tracking-wider font-semibold">Select Discounted Product Y (Live Search)</label>
                                <div class="relative">
                                    <div class="flex">
                                        <input type="text" 
                                               wire:model.live.debounce.300ms="targetProductSearch" 
                                               placeholder="Search discounted product..." 
                                               class="w-full px-4 py-2.5 bg-white border border-emerald-200 text-emerald-900 rounded-2xl focus:outline-none focus:border-emerald-500 text-sm">
                                        @if($product_id_y)
                                            <button type="button" wire:click="clearTargetProduct" class="absolute right-3 top-3 text-emerald-400 hover:text-emerald-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                    @if(count($searchedTargetProducts) > 0)
                                        <div class="absolute z-50 w-full mt-1 bg-white border border-emerald-100 rounded-2xl shadow-xl max-h-60 overflow-y-auto">
                                            @foreach($searchedTargetProducts as $p)
                                                <button type="button" 
                                                        wire:click="selectTargetProduct({{ $p->id }}, '{{ addslashes($p->title) }}')" 
                                                        class="w-full text-left px-4 py-2.5 text-sm text-emerald-950 hover:bg-emerald-50 transition font-medium border-b border-emerald-50/50 last:border-b-0">
                                                    {{ $p->title }}
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if($product_id_y)
                                        <div class="text-xs text-emerald-600 mt-1.5 font-semibold flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Selected Target: ID #{{ $product_id_y }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-emerald-800 block mb-2 uppercase tracking-wider font-semibold">Percentage Off Y Item (%)</label>
                                <input type="number" step="0.1" wire:model="product_y_percent" placeholder="e.g. 50 for 50% Off, 100 for Free" class="w-full px-4 py-2.5 bg-white border border-emerald-200 text-emerald-900 rounded-2xl focus:outline-none focus:border-emerald-500 text-sm animate-pulse-once">
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-emerald-800 block mb-2 uppercase tracking-wider">Custom Cart BOGO Warning Note</label>
                            <textarea wire:model="bogo_cart_text" rows="3" placeholder="This item is part of a discounted package and the quantity cannot be edited..." class="w-full px-4 py-2.5 bg-white border border-emerald-200 text-emerald-900 rounded-2xl focus:outline-none focus:border-emerald-500 text-sm"></textarea>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 5. Brand / Category / SubCat / Collection rules -->
            @if($discount_type_id == 5)
                <div class="bg-cyan-50/20 border border-cyan-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                    <h3 class="text-base font-bold text-cyan-900 pb-3 border-b border-cyan-100 uppercase tracking-wider text-xs">Brand & Category Specific Rules</h3>
                    
                    <div class="space-y-6 divide-y divide-cyan-100/60">
                        <!-- Brand Select -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pb-6">
                            <div>
                                <label class="text-xs font-bold text-cyan-800 block mb-2 uppercase tracking-wider font-semibold">Target Brand (Live Search)</label>
                                <div class="relative">
                                    <div class="flex">
                                        <input type="text" 
                                               wire:model.live.debounce.300ms="brandSearch" 
                                               placeholder="Search brand (min 2 chars)..." 
                                               class="w-full px-4 py-2.5 bg-white border border-cyan-200 text-cyan-900 rounded-2xl focus:outline-none focus:border-cyan-500 text-sm">
                                        @if($brand_id)
                                            <button type="button" wire:click="clearBrand" class="absolute right-3 top-3 text-cyan-400 hover:text-cyan-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                    @if(count($searchedBrands) > 0)
                                        <div class="absolute z-50 w-full mt-1 bg-white border border-cyan-100 rounded-2xl shadow-xl max-h-60 overflow-y-auto">
                                            @foreach($searchedBrands as $b)
                                                <button type="button" 
                                                        wire:click="selectBrand({{ $b->id }}, '{{ addslashes($b->name) }}')" 
                                                        class="w-full text-left px-4 py-2.5 text-sm text-cyan-950 hover:bg-cyan-50 transition font-medium border-b border-cyan-50/50 last:border-b-0">
                                                    {{ $b->name }}
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if($brand_id)
                                        <div class="text-xs text-cyan-600 mt-1.5 font-semibold flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Selected Brand: ID #{{ $brand_id }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="lg:col-span-2 grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="text-xs text-cyan-700 font-semibold block mb-1">QTY Min</label>
                                    <input type="number" wire:model="brand_qty_min" class="w-full px-3 py-2 bg-white border border-cyan-200 text-cyan-900 rounded-xl text-xs">
                                </div>
                                <div>
                                    <label class="text-xs text-cyan-700 font-semibold block mb-1">QTY Max</label>
                                    <input type="number" wire:model="brand_qty_max" class="w-full px-3 py-2 bg-white border border-cyan-200 text-cyan-900 rounded-xl text-xs">
                                </div>
                                <div>
                                    <label class="text-xs text-cyan-700 font-semibold block mb-1">SubTotal Min</label>
                                    <input type="number" step="0.01" wire:model="brand_subtotal_min" class="w-full px-3 py-2 bg-white border border-cyan-200 text-cyan-900 rounded-xl text-xs">
                                </div>
                                <div>
                                    <label class="text-xs text-cyan-700 font-semibold block mb-1">SubTotal Max</label>
                                    <input type="number" step="0.01" wire:model="brand_subtotal_max" class="w-full px-3 py-2 bg-white border border-cyan-200 text-cyan-900 rounded-xl text-xs">
                                </div>
                            </div>
                        </div>

                        <!-- Category Select -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 py-6">
                            <div>
                                <label class="text-xs font-bold text-cyan-800 block mb-2 uppercase tracking-wider font-semibold">Target Category (Live Search)</label>
                                <div class="relative">
                                    <div class="flex">
                                        <input type="text" 
                                               wire:model.live.debounce.300ms="categorySearch" 
                                               placeholder="Search category (min 2 chars)..." 
                                               class="w-full px-4 py-2.5 bg-white border border-cyan-200 text-cyan-900 rounded-2xl focus:outline-none focus:border-cyan-500 text-sm">
                                        @if($category_id)
                                            <button type="button" wire:click="clearCategory" class="absolute right-3 top-3 text-cyan-400 hover:text-cyan-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                    @if(count($searchedCategories) > 0)
                                        <div class="absolute z-50 w-full mt-1 bg-white border border-cyan-100 rounded-2xl shadow-xl max-h-60 overflow-y-auto">
                                            @foreach($searchedCategories as $c)
                                                <button type="button" 
                                                        wire:click="selectCategory({{ $c->id }}, '{{ addslashes($c->name) }}')" 
                                                        class="w-full text-left px-4 py-2.5 text-sm text-cyan-950 hover:bg-cyan-50 transition font-medium border-b border-cyan-50/50 last:border-b-0">
                                                    {{ $c->name }}
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if($category_id)
                                        <div class="text-xs text-cyan-600 mt-1.5 font-semibold flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Selected Category: ID #{{ $category_id }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="lg:col-span-2 grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="text-xs text-cyan-700 font-semibold block mb-1">QTY Min</label>
                                    <input type="number" wire:model="cat_qty_min" class="w-full px-3 py-2 bg-white border border-cyan-200 text-cyan-900 rounded-xl text-xs">
                                </div>
                                <div>
                                    <label class="text-xs text-cyan-700 font-semibold block mb-1">QTY Max</label>
                                    <input type="number" wire:model="cat_qty_max" class="w-full px-3 py-2 bg-white border border-cyan-200 text-cyan-900 rounded-xl text-xs">
                                </div>
                                <div>
                                    <label class="text-xs text-cyan-700 font-semibold block mb-1">SubTotal Min</label>
                                    <input type="number" step="0.01" wire:model="cat_subtotal_min" class="w-full px-3 py-2 bg-white border border-cyan-200 text-cyan-900 rounded-xl text-xs">
                                </div>
                                <div>
                                    <label class="text-xs text-cyan-700 font-semibold block mb-1">SubTotal Max</label>
                                    <input type="number" step="0.01" wire:model="cat_subtotal_max" class="w-full px-3 py-2 bg-white border border-cyan-200 text-cyan-900 rounded-xl text-xs">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endif

            <!-- 6. General Order Filters & Shipping Options -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                <h3 class="text-base font-bold text-slate-800 pb-3 border-b border-slate-100 uppercase tracking-wider text-xs">General Order Filters (Applies to all types)</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Order Subtotal Minimum ($)</label>
                        <input type="number" step="0.01" wire:model="order_minimum" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Order Subtotal Maximum ($)</label>
                        <input type="number" step="0.01" wire:model="order_maximum" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Order Quantity Minimum</label>
                        <input type="number" wire:model="order_qty_min" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Order Quantity Maximum</label>
                        <input type="number" wire:model="order_qty_max" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Order Weight Minimum (lbs)</label>
                        <input type="number" step="0.1" wire:model="order_weight_min" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Order Weight Maximum (lbs)</label>
                        <input type="number" step="0.1" wire:model="order_weight_max" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                    </div>
                </div>

                <div class="flex items-center gap-6 pt-2">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" wire:model="free_shipping" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm font-semibold text-slate-700">Free Shipping Discount? (Applies free shipping to entire order)</span>
                    </label>
                </div>
            </div>

            <!-- 7. Activation Status, Dates & detail page options -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                <h3 class="text-base font-bold text-slate-800 pb-3 border-b border-slate-100 uppercase tracking-wider text-xs">Discount Status & Details</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Discount Start Date</label>
                        <input type="date" wire:model="start_date" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                        @error('start_date') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Discount Expiration Date</label>
                        <input type="date" wire:model="expiration_date" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                        @error('expiration_date') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="divide-y divide-slate-100">
                    @if(in_array($discount_type_id, [5, 6, 7]))
                        <div class="flex items-center justify-between py-4" wire:key="discount-promo-toggle-row">
                            <div>
                                <span class="text-sm font-bold text-slate-900 block">Show Discount Info On Item Detail Page</span>
                                <span class="text-xs text-slate-400">If checked, custom promotional text entered below will appear on the product details view.</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="show_get_x_free" value="1" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>

                        @if($show_get_x_free == 1)
                            <div class="py-4" wire:key="discount-promo-text-row">
                                <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Promo Info Details Text (HTML Allowed)</label>
                                <div wire:ignore 
                                     x-data="{
                                         discountText: @entangle('show_get_x_text'),
                                         initTiny() {
                                             tinymce.init({
                                                 selector: '#discount_promo_text_editor',
                                                 license_key: 'gpl',
                                                 promotion: false,
                                                 height: 300,
                                                 menubar: 'insert format tools table',
                                                 content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
                                                 content_css: ['https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css'],
                                                 plugins: 'advlist autolink lists link image charmap preview anchor searchreplace wordcount visualblocks code fullscreen insertdatetime media table help emoticons pagebreak directionality',
                                                 toolbar: 'fullscreen undo redo code | bold italic underline strikethrough | forecolor backcolor sizeselect blocks fontfamily fontsize lineheight | alignleft aligncenter alignright alignjustify | outdent indent | removeformat numlist bullist | pagebreak | charmap emoticons | fullscreen preview  | image media link anchor | ltr rtl',
                                                 branding: false,
                                                 contextmenu: 'link image imagetools',
                                                 setup: (editor) => {
                                                     editor.on('init', () => {
                                                         editor.setContent(this.discountText || '');
                                                     });
                                                     editor.on('change', () => {
                                                         this.discountText = editor.getContent();
                                                     });
                                                     editor.on('blur', () => {
                                                         this.discountText = editor.getContent();
                                                     });
                                                 }
                                             });
                                         }
                                     }"
                                     x-init="initTiny()"
                                     x-cleanup="tinymce.remove('#discount_promo_text_editor')"
                                     wire:key="discount-promo-editor-container">
                                    <textarea id="discount_promo_text_editor" class="w-full"></textarea>
                                </div>
                            </div>
                        @endif
                    @endif

                    <div class="flex items-center justify-between py-4">
                        <div>
                            <span class="text-sm font-bold text-slate-900 block">Discount Active Status</span>
                            <span class="text-xs text-slate-400">Enable or disable this promo rule globally.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="is_active" value="1" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Button Row -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.discounts.index') }}" wire:navigate class="inline-flex items-center justify-center px-6 py-3 rounded-2xl font-bold text-sm bg-slate-100 hover:bg-slate-200 text-slate-700 transition duration-150">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center justify-center px-6 py-3 rounded-2xl font-bold text-sm bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-150 transition duration-150">
                    Save Discount
                </button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('build/node_modules/tinymce/tinymce.min.js') }}"></script>
