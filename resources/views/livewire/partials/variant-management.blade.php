<div id="section-variants" class="scroll-mt-6">
                <!-- Variant Edit Modal/Overlay Form -->
                @if($isEditingVariant)
                    <div class="bg-indigo-50/50 border border-indigo-100 rounded-3xl p-8 shadow-sm space-y-6">
                        <div class="flex items-center justify-between pb-3 border-b border-indigo-100">
                            <h3 class="text-lg font-bold text-slate-900">Edit Variant: SKU {{ $sku }}</h3>
                            <button wire:click="cancelEditVariant" class="text-slate-400 hover:text-slate-600 font-bold text-sm">Cancel</button>
                        </div>
                        <form wire:submit.prevent="updateVariant" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">SKU</label>
                                    <input type="text" wire:model="sku" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                    @error('sku') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Public Price ($)</label>
                                    <input type="number" step="0.01" wire:model="public_price" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Wholesale Price ($)</label>
                                    <input type="number" step="0.01" wire:model="wholesale_price" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">On Sale</label>
                                    <select wire:model.live="on_sale" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                                @if($on_sale == 1)
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Sale Price ($)</label>
                                        <input type="number" step="0.01" wire:model="sale_price" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Sale Start Date</label>
                                        <input type="datetime-local" wire:model="sale_price_start_at" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Sale Stop Date</label>
                                        <input type="datetime-local" wire:model="sale_price_end_at" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                    </div>
                                @endif
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">UPC Code</label>
                                    <input type="text" wire:model="upc_code" placeholder="e.g. 012345678905" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Item Cost ($)</label>
                                    <input type="number" step="0.01" min="0" wire:model="item_cost" placeholder="0.00" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">MAP Price ($)</label>
                                    <input type="number" step="0.01" min="0" wire:model="item_map" placeholder="0.00" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Variant Fee ($)</label>
                                    <input type="number" step="0.01" wire:model="variant_fee" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Wholesale Variant Fee ($)</label>
                                    <input type="number" step="0.01" wire:model="wholesale_variant_fee" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Allow Personalization</label>
                                    <select wire:model.live="personalization_active" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                                @if($personalization_active)
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Personalization Fee ($)</label>
                                        <input type="number" step="0.01" wire:model="personalization_fee" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Personalization Label</label>
                                        <input type="text" wire:model="personalization_label" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500" placeholder="e.g. Add Gift Wrapping / Personalization">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Details / Message Label</label>
                                        <input type="text" wire:model="personalization_details_label" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500" placeholder="e.g. Personalization Details / Gift Message">
                                    </div>
                                    <div class="col-span-1 md:col-span-3">
                                        <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Placeholder Text</label>
                                        <input type="text" wire:model="personalization_placeholder" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500" placeholder="e.g. Enter names for engraving, personalization details, or a custom gift message here...">
                                    </div>
                                @endif
                            </div>

                            {{-- ── Variant Translations (Attributes + Personalization Labels) ──── --}}
                            @if(isset($activeLanguages) && $activeLanguages->count() > 0)
                            <div class="mt-4 border border-indigo-200 bg-indigo-50/40 rounded-2xl p-5 space-y-4">
                                <div class="flex items-center gap-2 pb-2 border-b border-indigo-100">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                                    </svg>
                                    <h4 class="text-xs font-bold text-indigo-700 uppercase tracking-wider">Variant Translations</h4>
                                    <span class="ml-auto text-[10px] text-slate-400 hidden sm:block">Translate attribute labels &amp; personalization prompts</span>
                                </div>

                                {{-- Language pills --}}
                                <div class="flex flex-wrap gap-2">
                                    @foreach($activeLanguages as $lang)
                                        @php
                                            $vTrans   = \App\Models\ProductVariantTranslation::where('product_variant_id', $selectedVariantId)
                                                            ->where('language_id', $lang->id)->first();
                                            $vHasData = $vTrans && (
                                                $vTrans->personalization_label ||
                                                $vTrans->personalization_details_label ||
                                                $vTrans->personalization_placeholder ||
                                                !empty($vTrans->attributes_translated)
                                            );
                                        @endphp
                                        <button type="button"
                                                wire:click="selectVariantTranslationLang('{{ $lang->code }}', {{ $lang->id }})"
                                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold border transition
                                                       {{ $variantTransLangCode === $lang->code
                                                              ? 'bg-indigo-600 text-white border-indigo-600'
                                                              : 'bg-white text-slate-600 border-slate-200 hover:border-indigo-300' }}">
                                            <span>{{ $lang->flag_emoji }}</span>
                                            {{ $lang->native_name }}
                                            @if($vHasData)
                                                <span class="text-[9px] px-1.5 py-0.5 rounded-full
                                                             {{ $variantTransLangCode === $lang->code ? 'bg-white/30 text-white' : 'bg-emerald-200 text-emerald-800' }}">✓</span>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>

                                @if($variantTransLangCode)
                                    @php
                                        // Decode the raw attributes JSON once so we can build the translation rows.
                                        $rawVariantAttrs = json_decode(
                                            \App\Models\ProductVariant::find($selectedVariantId)?->attributes ?? '{}',
                                            true
                                        ) ?: [];
                                    @endphp

                                    <div class="space-y-4 bg-white rounded-xl p-4 border border-indigo-100">

                                        {{-- ── Attribute Label Translations ── --}}
                                        @if(!empty($rawVariantAttrs))
                                            <div>
                                                <h5 class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                                                    <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                                    Attribute Labels
                                                </h5>
                                                <div class="space-y-2">
                                                    @foreach($rawVariantAttrs as $rawAttrKey => $rawAttrVal)
                                                        {{-- Attribute KEY row --}}
                                                        <div class="grid grid-cols-2 gap-3 items-center">
                                                            <div class="flex items-center gap-2">
                                                                <span class="px-2 py-1 bg-slate-100 rounded-lg text-xs font-mono text-slate-600 whitespace-nowrap">Key:</span>
                                                                <span class="text-xs font-semibold text-slate-700">{{ $rawAttrKey }}</span>
                                                            </div>
                                                            <input
                                                                type="text"
                                                                wire:model="trans_attributes.{{ $rawAttrKey }}"
                                                                placeholder="{{ $rawAttrKey }} in {{ $variantTransLangCode }}…"
                                                                class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400"
                                                            >
                                                        </div>
                                                        {{-- Attribute VALUE row --}}
                                                        <div class="grid grid-cols-2 gap-3 items-center pl-4">
                                                            <div class="flex items-center gap-2">
                                                                <span class="px-2 py-1 bg-indigo-50 rounded-lg text-xs font-mono text-indigo-600 whitespace-nowrap">Val:</span>
                                                                <span class="text-xs font-semibold text-slate-600">{{ $rawAttrVal }}</span>
                                                            </div>
                                                            <input
                                                                type="text"
                                                                wire:model="trans_attributes.{{ $rawAttrVal }}"
                                                                placeholder="{{ $rawAttrVal }} in {{ $variantTransLangCode }}…"
                                                                class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400"
                                                            >
                                                        </div>
                                                        @if(!$loop->last)
                                                            <hr class="border-slate-100">
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-xs text-slate-400 italic">
                                                No attributes defined on this variant. Add attributes (Color, Size, etc.) first, then save the variant before translating.
                                            </p>
                                        @endif

                                        {{-- ── Personalization Label Translations ── --}}
                                        @if($personalization_active)
                                            <div class="pt-3 border-t border-slate-100">
                                                <h5 class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                                                    <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                    Personalization Labels
                                                </h5>
                                                <div class="space-y-3">
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Personalization Label</label>
                                                        <input type="text" wire:model="trans_personalization_label"
                                                               placeholder="Translated label…"
                                                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Details / Message Label</label>
                                                        <input type="text" wire:model="trans_personalization_details_label"
                                                               placeholder="Translated details label…"
                                                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Placeholder Text</label>
                                                        <input type="text" wire:model="trans_personalization_placeholder"
                                                               placeholder="Translated placeholder text…"
                                                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="flex justify-end pt-1 border-t border-slate-100">
                                            <button type="button" wire:click="saveVariantTranslation" wire:loading.attr="disabled"
                                                    class="flex items-center gap-2 px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow transition">
                                                <span wire:loading wire:target="saveVariantTranslation"
                                                      class="animate-spin w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full inline-block"></span>
                                                Save Translation
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @endif

                            <!-- Quantity Discount breaks -->
                            <div class="bg-indigo-50/30 border border-indigo-100 rounded-3xl p-5 space-y-4">
                                <div class="flex items-center justify-between pb-2 border-b border-indigo-100">
                                    <h4 class="text-xs font-bold text-slate-600 uppercase tracking-wider">Quantity Discount Breaks</h4>
                                    <button type="button" wire:click="addQtyDiscount" class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 focus:outline-none">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                        Add Break Range
                                    </button>
                                </div>

                                @if(empty($qtyDiscounts))
                                    <p class="text-xs text-slate-400 italic">No quantity discount breaks configured. Click 'Add Break Range' to get started.</p>
                                @else
                                    <div class="space-y-3">
                                        @foreach($qtyDiscounts as $index => $break)
                                            <div class="grid grid-cols-1 md:grid-cols-5 gap-3 items-center">
                                                <div>
                                                    <label class="text-[10px] text-slate-400 font-semibold block uppercase">Min Qty</label>
                                                    <input type="number" wire:model="qtyDiscounts.{{ $index }}.qty_min" class="w-full px-3 py-1.5 bg-white border border-slate-200 text-xs text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 font-semibold">
                                                </div>
                                                <div>
                                                    <label class="text-[10px] text-slate-400 font-semibold block uppercase">Max Qty</label>
                                                    <input type="number" wire:model="qtyDiscounts.{{ $index }}.qty_max" class="w-full px-3 py-1.5 bg-white border border-slate-200 text-xs text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 font-semibold">
                                                </div>
                                                <div>
                                                    <label class="text-[10px] text-slate-400 font-semibold block uppercase">Value</label>
                                                    <input type="number" step="0.01" wire:model="qtyDiscounts.{{ $index }}.discount_value" class="w-full px-3 py-1.5 bg-white border border-slate-200 text-xs text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 font-semibold">
                                                </div>
                                                <div>
                                                    <label class="text-[10px] text-slate-400 font-semibold block uppercase">Type</label>
                                                    <select wire:model="qtyDiscounts.{{ $index }}.value_type" class="w-full px-3 py-1.5 bg-white border border-slate-200 text-xs text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500">
                                                        <option value="1">Value ($)</option>
                                                        <option value="2">Percent (%)</option>
                                                    </select>
                                                </div>
                                                <div class="pt-4 text-right">
                                                    <button type="button" wire:click="removeQtyDiscount({{ $index }})" class="text-rose-500 hover:text-rose-700 text-xs font-bold">Remove</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="bg-slate-50 border border-slate-200 rounded-3xl p-5 space-y-4">
                                <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                                    <h4 class="text-xs font-bold text-slate-600 uppercase tracking-wider">Attributes (Color, Size, etc.)</h4>
                                    <button type="button" wire:click="addAttribute" class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 focus:outline-none">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                        Add Attribute
                                    </button>
                                </div>

                                {{-- Inline builder inputs --}}
                                @if(empty($inlineAttributes))
                                    <p class="text-xs text-slate-400 italic">No attributes configured. Click 'Add Attribute' to get started.</p>
                                @else
                                    <div class="space-y-3">
                                        @foreach($inlineAttributes as $index => $attr)
                                            <div class="flex items-center gap-3" key="edit-attr-{{ $index }}">
                                                <div class="flex-1">
                                                    <input type="text" wire:model.blur="inlineAttributes.{{ $index }}.key" placeholder="Attribute Name (e.g. Color)" class="w-full px-3 py-2 bg-white border border-slate-200 text-xs text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 font-semibold">
                                                </div>
                                                <div class="flex-1">
                                                    <input type="text" wire:model.blur="inlineAttributes.{{ $index }}.value" placeholder="Value (e.g. Blue)" class="w-full px-3 py-2 bg-white border border-slate-200 text-xs text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500">
                                                </div>
                                                <button type="button" wire:click="removeAttribute({{ $index }})" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Direct JSON input for advanced users --}}
                                <div class="pt-3 border-t border-slate-200 font-sans">
                                    <label class="text-[10px] font-bold text-slate-400 block mb-1 uppercase tracking-wider">Advanced: Direct JSON Input</label>
                                    <input type="text" wire:model.blur="variantAttributes" placeholder='{"Color":"Blue"}' class="w-full px-3 py-2 bg-white border border-slate-200 text-xs text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 font-mono">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Weight</label>
                                    <input type="number" step="0.1" wire:model="weight" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Weight Type</label>
                                    <select wire:model="weight_type" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                        <option value="lbs">lbs</option>
                                        <option value="oz">oz</option>
                                        <option value="kg">kg</option>
                                        <option value="g">g</option>
                                    </select>
                                </div>
                                {{-- Requires Shipping Toggle --}}
                                <div class="flex flex-col justify-end pb-1">
                                    <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Requires Shipping</label>
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <div class="relative">
                                            <input type="checkbox" wire:model.number="shipping" class="sr-only peer" true-value="1" false-value="0">
                                            <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-indigo-400 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-500"></div>
                                        </div>
                                        <span class="text-sm font-bold {{ $shipping ? 'text-indigo-700' : 'text-slate-400' }} transition-colors">
                                            {{ $shipping ? 'Shipping Required' : 'No Shipping' }}
                                        </span>
                                    </label>
                                </div>
                                {{-- Charge Tax Toggle --}}
                                <div class="flex flex-col justify-end pb-1">
                                    <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Sales Tax / VAT</label>
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <div class="relative">
                                            <input type="checkbox" wire:model.number="charge_tax" class="sr-only peer" true-value="1" false-value="0">
                                            <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-emerald-400 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                        </div>
                                        <span class="text-sm font-bold {{ $charge_tax ? 'text-emerald-700' : 'text-slate-400' }} transition-colors">
                                            {{ $charge_tax ? 'Taxable' : 'Tax Exempt' }}
                                        </span>
                                    </label>
                                </div>
                            </div>

                            {{-- Shipping Dimensions --}}
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-3">
                                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Shipping Dimensions</h4>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                    <div>
                                        <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Length</label>
                                        <input type="number" step="0.01" min="0" wire:model="dimension_length" placeholder="0.00" class="w-full px-3 py-2 bg-white border border-slate-200 text-xs text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500">
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Width</label>
                                        <input type="number" step="0.01" min="0" wire:model="dimension_width" placeholder="0.00" class="w-full px-3 py-2 bg-white border border-slate-200 text-xs text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500">
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Height</label>
                                        <input type="number" step="0.01" min="0" wire:model="dimension_height" placeholder="0.00" class="w-full px-3 py-2 bg-white border border-slate-200 text-xs text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500">
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Unit</label>
                                        <select wire:model="dimension_unit" class="w-full px-3 py-2 bg-white border border-slate-200 text-xs text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500">
                                            <option value="in">Inches (in)</option>
                                            <option value="cm">Centimeters (cm)</option>
                                            <option value="mm">Millimeters (mm)</option>
                                            <option value="ft">Feet (ft)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Amazon-Specific Fields Section --}}
                            <div class="border border-amber-200 bg-amber-50/40 rounded-2xl p-5 space-y-4">
                                <div class="flex items-center justify-between pb-2 border-b border-amber-200/60">
                                    <h4 class="text-xs font-bold text-amber-800 uppercase tracking-wider flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z"/></svg>
                                        Amazon-Specific Settings
                                    </h4>
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" wire:model.live="amazon_product" value="1" class="rounded border-amber-300 text-amber-600 focus:ring-amber-500">
                                        <span class="text-xs font-bold text-amber-900">Enable Amazon Marketplace Listing</span>
                                    </label>
                                </div>
                                @if($amazon_product)
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div>
                                            <label class="text-xs font-bold text-slate-500 block mb-1">ASIN</label>
                                            <input type="text" wire:model="amazon_asin" placeholder="e.g. B08N5WRWNW" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-amber-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="text-xs font-bold text-slate-500 block mb-1">Amazon Price ($ Override)</label>
                                            <input type="number" step="0.01" min="0" wire:model="amazon_price" placeholder="Leave empty for retail" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-amber-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="text-xs font-bold text-slate-500 block mb-1">Condition</label>
                                            <select wire:model="amazon_condition" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-amber-500 text-sm">
                                                <option value="New">New</option>
                                                <option value="Used - Like New">Used - Like New</option>
                                                <option value="Used - Very Good">Used - Very Good</option>
                                                <option value="Used - Good">Used - Good</option>
                                                <option value="Used - Acceptable">Used - Acceptable</option>
                                                <option value="Refurbished">Refurbished</option>
                                            </select>
                                        </div>
                                        <div class="sm:col-span-3">
                                            <label class="text-xs font-bold text-slate-500 block mb-1">Amazon Item Type / Category Path</label>
                                            <input type="text" wire:model="amazon_item_type" placeholder="e.g. Clothing > Shirts > T-Shirts" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-amber-500 text-sm">
                                        </div>
                                        <div class="sm:col-span-3">
                                            <label class="text-xs font-bold text-slate-500 block mb-1">Amazon Bullet Points (up to 5 lines)</label>
                                            <textarea wire:model="amazon_bullet_points" rows="4" placeholder="Enter key feature highlights, one per line (up to 5 bullet points)" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-amber-500 text-sm"></textarea>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- eBay-Specific Fields Section --}}
                            <div class="border border-blue-200 bg-blue-50/40 rounded-2xl p-5 space-y-4">
                                <div class="flex items-center justify-between pb-2 border-b border-blue-200/60">
                                    <h4 class="text-xs font-bold text-blue-800 uppercase tracking-wider flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        eBay-Specific Settings
                                    </h4>
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" wire:model.live="ebay_product" value="1" class="rounded border-blue-300 text-blue-600 focus:ring-blue-500">
                                        <span class="text-xs font-bold text-blue-900">Enable eBay Marketplace Listing</span>
                                    </label>
                                </div>
                                @if($ebay_product)
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div>
                                            <label class="text-xs font-bold text-slate-500 block mb-1">eBay Category ID</label>
                                            <input type="text" wire:model="ebay_category_id" placeholder="e.g. 11450" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-blue-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="text-xs font-bold text-slate-500 block mb-1">eBay Price ($ Override)</label>
                                            <input type="number" step="0.01" min="0" wire:model="ebay_price" placeholder="Leave empty for retail" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-blue-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="text-xs font-bold text-slate-500 block mb-1">Listing Type</label>
                                            <select wire:model="ebay_listing_type" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-blue-500 text-sm">
                                                <option value="Fixed Price">Fixed Price</option>
                                                <option value="Auction">Auction</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-xs font-bold text-slate-500 block mb-1">Shipping Profile ID</label>
                                            <input type="text" wire:model="ebay_shipping_profile_id" placeholder="e.g. SHIP-FREE-STD" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-blue-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="text-xs font-bold text-slate-500 block mb-1">Return Policy ID</label>
                                            <input type="text" wire:model="ebay_return_policy_id" placeholder="e.g. RET-30DAY-FREE" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-blue-500 text-sm">
                                        </div>
                                        <div class="sm:col-span-3">
                                            <label class="text-xs font-bold text-slate-500 block mb-1">eBay Options / Traits (Color, Size, Material)</label>
                                            <input type="text" wire:model="ebay_options" placeholder="e.g. Color: Red | Size: L | Material: 100% Cotton" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-blue-500 text-sm">
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @include('livewire.partials.variant-processor-ids')

                            {{-- ═══════ INVENTORY & WAREHOUSE STOCK SECTION (Edit) ═══════ --}}
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-4">
                                <div class="flex items-center justify-between flex-wrap gap-2 pb-2 border-b border-slate-200">
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                            Inventory & Warehouse Stock Calculation
                                        </h4>
                                        <p class="text-[11px] text-slate-500 mt-0.5">Configure main in-stock levels and optional warehouse-specific inventory stock.</p>
                                    </div>

                                    <a href="{{ route('admin.ecommerce.shipping', ['tab' => 'warehouses']) }}" target="admin_warehouse_locations"
                                       class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:underline bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-200 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        Manage Warehouses & Fulfillment Locations &rarr;
                                    </a>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <label class="text-xs font-bold text-indigo-600 block mb-1 uppercase tracking-wider">Quantity Available (In Stock)</label>
                                        <input type="number" wire:model.live="quantity_available" class="w-full px-4 py-2.5 bg-white border border-indigo-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 font-bold">
                                        @error('quantity_available') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">Reserved Stock</label>
                                        <input type="number" wire:model.live="reserved_stock" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                        @error('reserved_stock') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">Main Warehouse Stock Level</label>
                                        <input type="number" wire:model.live="warehouse_stock_level" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                        @error('warehouse_stock_level') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">Primary Warehouse Facility</label>
                                        <select wire:model.live="location_id" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 font-semibold">
                                            @foreach($allWarehouseLocations as $loc)
                                                @php
                                                    $isUsedInChild = collect($variantWarehouseStock)->pluck('warehouse_location_id')->map(fn($id)=>(int)$id)->contains((int)$loc->id);
                                                @endphp
                                                @if(!$isUsedInChild || (int)$location_id === (int)$loc->id)
                                                    <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->code }})</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('location_id') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" wire:model.live="use_warehouse_stock" class="rounded text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-xs font-bold text-slate-700">Use Warehouse Stock in Calculations</span>
                                    </label>
                                </div>

                                {{-- Warehouse Locations Dependent Inventory Levels Child List --}}
                                @if($use_warehouse_stock)
                                    <div class="mt-4 p-4 bg-indigo-50/50 border border-indigo-100 rounded-2xl space-y-3">
                                        <div class="flex items-center justify-between flex-wrap gap-2">
                                            <div>
                                                <h5 class="text-xs font-bold text-indigo-900 uppercase tracking-wider">Warehouse Dependent Inventory Levels</h5>
                                                <p class="text-[11px] text-indigo-700/80">Assign inventory levels per warehouse location to contribute to total available stock.</p>
                                            </div>
                                            <button type="button" wire:click="addWarehouseStockLine" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition inline-flex items-center gap-1 cursor-pointer">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                Add Warehouse Location Stock
                                            </button>
                                        </div>

                                        @if(!empty($variantWarehouseStock))
                                            <div class="space-y-2">
                                                @foreach($variantWarehouseStock as $index => $wStock)
                                                    <div class="flex items-center gap-3 bg-white p-3 rounded-xl border border-indigo-100 shadow-sm">
                                                        <div class="flex-1">
                                                            <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Warehouse Location</label>
                                                            <select wire:model.live="variantWarehouseStock.{{ $index }}.warehouse_location_id" class="w-full px-3 py-2 bg-white border border-slate-200 text-xs text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 font-semibold">
                                                                @foreach($allWarehouseLocations as $loc)
                                                                    @php
                                                                        $isPrimary = (int)$location_id === (int)$loc->id;
                                                                        $isOtherChild = collect($variantWarehouseStock)
                                                                            ->forget($index)
                                                                            ->pluck('warehouse_location_id')
                                                                            ->map(fn($id)=>(int)$id)
                                                                            ->contains((int)$loc->id);
                                                                    @endphp
                                                                    @if(!$isPrimary && (!$isOtherChild || (int)($wStock['warehouse_location_id'] ?? 0) === (int)$loc->id))
                                                                        <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->code }}) - {{ $loc->city }}, {{ $loc->state_code }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="w-36">
                                                            <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Assigned Stock Qty</label>
                                                            <input type="number" min="0" wire:model.live="variantWarehouseStock.{{ $index }}.stock_level" class="w-full px-3 py-2 bg-white border border-slate-200 text-xs font-bold text-indigo-700 rounded-xl focus:outline-none focus:border-indigo-500">
                                                        </div>
                                                        <div class="pt-5">
                                                            <button type="button" wire:click="removeWarehouseStockLine({{ $index }})" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-xl transition cursor-pointer" title="Remove Location">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="p-3 text-center bg-white/70 rounded-xl border border-dashed border-indigo-200">
                                                <p class="text-xs text-slate-500">No additional warehouse location stock entries added. Click <strong>"Add Warehouse Location Stock"</strong> to assign inventory levels per warehouse location.</p>
                                            </div>
                                        @endif

                                        @php
                                            $childSum = (int) collect($variantWarehouseStock)->sum('stock_level');
                                            $totalAvailable = (int)$quantity_available + (int)$warehouse_stock_level + $childSum - (int)$reserved_stock;
                                        @endphp
                                        <div class="pt-2 flex items-center justify-between text-xs font-bold text-indigo-900 border-t border-indigo-200/80">
                                            <span>Total Available Calculated Stock:</span>
                                            <span class="px-3 py-1 bg-indigo-600 text-white rounded-xl shadow-sm text-xs">
                                                {{ $quantity_available }} (In Stock) + {{ $warehouse_stock_level }} (Main Warehouse) + {{ $childSum }} (Child Warehouses) - {{ $reserved_stock }} (Reserved) = {{ $totalAvailable }} Total Units
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- ═══════ EVENT DETAILS SUB-SECTION (Edit) ═══════ --}}
                            <div class="border border-violet-200 bg-violet-50/40 rounded-3xl p-5 space-y-4">
                                <div class="flex items-center gap-3 border-b border-violet-200/70 pb-3">
                                    <label class="inline-flex items-center gap-2.5 cursor-pointer group">
                                        <div class="relative">
                                            <input type="checkbox" wire:model.live="is_event" class="sr-only peer">
                                            <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-violet-400 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-600"></div>
                                        </div>
                                        <span class="text-sm font-bold {{ $is_event ? 'text-violet-700' : 'text-slate-400' }} transition-colors">Mark as Event / Calendar Item</span>
                                    </label>
                                    @if($is_event)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-violet-100 text-violet-700 border border-violet-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            Event Active
                                        </span>
                                    @endif
                                </div>

                                @if($is_event)
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">Event Label <span class="text-red-400">*</span></label>
                                            <input type="text" wire:model="event_label" placeholder="e.g. Digital Marketing Seminar — Sept 16, 9 AM" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-violet-500 text-sm">
                                            @error('event_label') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">Alternate / Tooltip Label</label>
                                            <input type="text" wire:model="alternate_label" placeholder="e.g. Digital Marketing Seminar | 9 AM - 10 AM |" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-violet-500 text-sm">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">Event Start Date &amp; Time <span class="text-red-400">*</span></label>
                                            <input type="datetime-local" wire:model="event_start_date" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-violet-500 text-sm">
                                            @error('event_start_date') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">Event End Date &amp; Time</label>
                                            <input type="datetime-local" wire:model="event_end_date" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-violet-500 text-sm">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">Calendar Colour</label>
                                            <div class="flex items-center gap-2">
                                                <input type="color" wire:model="label_background" class="w-10 h-10 rounded-xl border border-slate-200 cursor-pointer p-0.5 bg-white">
                                                <input type="text" wire:model="label_background" placeholder="#4f46e5" class="flex-1 px-3 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-violet-500 text-sm font-mono">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">Sort Order</label>
                                            <input type="number" step="0.1" wire:model="event_sort" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-violet-500 text-sm">
                                        </div>
                                        <div class="flex flex-col justify-end pb-1">
                                            <label class="text-xs font-bold text-slate-500 block mb-2 uppercase tracking-wider">Show Date on Front-End</label>
                                            <label class="inline-flex items-center gap-2.5 cursor-pointer">
                                                <input type="checkbox" wire:model="show_date" class="rounded text-violet-600 focus:ring-violet-500">
                                                <span class="text-xs font-bold text-slate-600">Display event date</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">Location / Venue / URL</label>
                                        <input type="text" wire:model="event_location" placeholder="e.g. Room 101, Convention Centre or https://zoom.us/j/..." class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-violet-500 text-sm">
                                    </div>

                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">Event Description</label>
                                        <textarea wire:model="event_description" rows="3" placeholder="Optional detailed description of this event session..." class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-violet-500 text-sm resize-none"></textarea>
                                    </div>
                                @else
                                    <p class="text-xs text-slate-400 italic">Enable the toggle above to configure event scheduling, calendar colour, dates, and location for this variant.</p>
                                @endif
                            </div>

                            <!-- Storage, Downloads & Image Uploads -->
                            <div class="border-t border-slate-200/60 pt-6 space-y-6">
                                <h4 class="text-base font-extrabold text-slate-900 tracking-tight">Storage, Downloads & Image Uploads</h4>

                                <div class="space-y-4">
                                    <!-- Horizontal Card 1: Image Gallery & Uploads -->
                                    <div class="p-6 bg-slate-50/50 border border-slate-200/60 rounded-3xl space-y-6">
                                        <div class="flex items-center justify-between border-b border-slate-200/60 pb-3">
                                            <div class="space-y-0.5">
                                                <h5 class="text-sm font-bold text-slate-800">1. Variant Images & Gallery</h5>
                                                <p class="text-[11px] text-slate-400">Configure and upload multiple sets of images (each requiring a thumbnail and main image, with an optional zoom image).</p>
                                            </div>
                                        </div>

                                        @if(empty($imageSets))
                                            <div class="text-center p-8 bg-white border border-slate-100 rounded-2xl shadow-sm">
                                                <svg class="mx-auto h-10 w-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <h4 class="mt-2 text-xs font-bold text-slate-700">No Image Sets Uploaded</h4>
                                                <p class="mt-1 text-[11px] text-slate-400">Add your first set of images using the form below.</p>
                                            </div>
                                        @else
                                            <!-- Responsive Table of Image Sets -->
                                            <div class="overflow-x-auto border border-slate-200 rounded-2xl bg-white shadow-sm">
                                                <table class="min-w-full divide-y divide-slate-150 text-left text-xs text-slate-700">
                                                    <thead class="bg-slate-50 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-200">
                                                        <tr>
                                                            <th scope="col" class="px-4 py-3">Thumbnail</th>
                                                            <th scope="col" class="px-4 py-3">Main Image</th>
                                                            <th scope="col" class="px-4 py-3">Zoom (Opt)</th>
                                                            <th scope="col" class="px-4 py-3">Alt Text</th>
                                                            <th scope="col" class="px-4 py-3">Zoom Desc</th>
                                                            <th scope="col" class="px-4 py-3 text-center">Search Image</th>
                                                            <th scope="col" class="px-4 py-3 text-center">Active</th>
                                                            <th scope="col" class="px-4 py-3">Storage</th>
                                                            <th scope="col" class="px-4 py-3 text-center">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-slate-100 bg-white">
                                                        @foreach($imageSets as $index => $set)
                                                            @php $isUrlMode = ($set['image_url_source'] ?? 0) == 1; @endphp
                                                            <tr class="hover:bg-slate-50/50 transition">
                                                                <!-- Thumbnail -->
                                                                <td class="px-4 py-3">
                                                                    <div class="flex items-center gap-3">
                                                                        @if($isUrlMode)
                                                                            <div class="flex flex-col gap-1">
                                                                                <img src="{{ $set['thumbnail_url'] ?? $set['thumbnail_path'] ?? '' }}" class="w-12 h-12 object-cover rounded-lg border border-amber-200 shadow-sm" alt="Thumbnail" onerror="this.style.display='none'">
                                                                                <div x-data="{ editing: false }">
                                                                                    <button type="button" x-show="!editing" @click="editing = true" class="px-2.5 py-1 text-[10px] font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 rounded-lg cursor-pointer transition text-center">Edit URL</button>
                                                                                    <div x-show="editing" class="mt-1">
                                                                                        <input type="text" wire:model="imageSets.{{ $index }}.thumbnail_path" class="w-40 px-2 py-1 text-[10px] bg-amber-50 border border-amber-200 rounded-lg focus:outline-none focus:border-amber-400 text-slate-700" placeholder="https://..." />
                                                                                        <button type="button" @click="editing = false" class="mt-1 text-[9px] text-slate-400 hover:text-slate-600">Done</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @else
                                                                            @if(isset($replaceThumbnail[$index]))
                                                                                <img src="{{ $replaceThumbnail[$index]->temporaryUrl() }}" class="w-12 h-12 object-cover rounded-lg border border-indigo-200 shadow-sm" alt="Thumbnail Preview">
                                                                            @elseif($set['thumbnail_url'])
                                                                                <img src="{{ $set['thumbnail_url'] }}" class="w-12 h-12 object-cover rounded-lg border border-slate-200 shadow-sm" alt="Thumbnail">
                                                                            @else
                                                                                <div class="w-12 h-12 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 text-[10px]">No image</div>
                                                                            @endif
                                                                            <div class="flex flex-col">
                                                                                <label class="px-2.5 py-1 text-[10px] font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg cursor-pointer transition text-center">
                                                                                    Replace
                                                                                    <input type="file" wire:model="replaceThumbnail.{{ $index }}" class="hidden">
                                                                                </label>
                                                                                <div wire:loading wire:target="replaceThumbnail.{{ $index }}" class="text-[9px] text-indigo-600 mt-1">Uploading...</div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </td>

                                                                <!-- Main Image -->
                                                                <td class="px-4 py-3">
                                                                    <div class="flex items-center gap-3">
                                                                        @if($isUrlMode)
                                                                            <div class="flex flex-col gap-1">
                                                                                <img src="{{ $set['main_url'] ?? $set['main_path'] ?? '' }}" class="w-12 h-12 object-cover rounded-lg border border-amber-200 shadow-sm" alt="Main" onerror="this.style.display='none'">
                                                                                <div x-data="{ editing: false }">
                                                                                    <button type="button" x-show="!editing" @click="editing = true" class="px-2.5 py-1 text-[10px] font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 rounded-lg cursor-pointer transition text-center">Edit URL</button>
                                                                                    <div x-show="editing" class="mt-1">
                                                                                        <input type="text" wire:model="imageSets.{{ $index }}.main_path" class="w-40 px-2 py-1 text-[10px] bg-amber-50 border border-amber-200 rounded-lg focus:outline-none focus:border-amber-400 text-slate-700" placeholder="https://..." />
                                                                                        <button type="button" @click="editing = false" class="mt-1 text-[9px] text-slate-400 hover:text-slate-600">Done</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @else
                                                                            @if(isset($replaceMain[$index]))
                                                                                <img src="{{ $replaceMain[$index]->temporaryUrl() }}" class="w-12 h-12 object-cover rounded-lg border border-indigo-200 shadow-sm" alt="Main Preview">
                                                                            @elseif($set['main_url'])
                                                                                <img src="{{ $set['main_url'] }}" class="w-12 h-12 object-cover rounded-lg border border-slate-200 shadow-sm" alt="Main Image">
                                                                            @else
                                                                                <div class="w-12 h-12 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 text-[10px]">No image</div>
                                                                            @endif
                                                                            <div class="flex flex-col">
                                                                                <label class="px-2.5 py-1 text-[10px] font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg cursor-pointer transition text-center">
                                                                                    Replace
                                                                                    <input type="file" wire:model="replaceMain.{{ $index }}" class="hidden">
                                                                                </label>
                                                                                <div wire:loading wire:target="replaceMain.{{ $index }}" class="text-[9px] text-indigo-600 mt-1">Uploading...</div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </td>

                                                                <!-- Zoom Image (Optional) -->
                                                                <td class="px-4 py-3">
                                                                    <div class="flex items-center gap-3">
                                                                        @if($isUrlMode)
                                                                            <div class="flex flex-col gap-1">
                                                                                @if($set['zoom_url'] ?? $set['zoom_path'] ?? null)
                                                                                    <img src="{{ $set['zoom_url'] ?? $set['zoom_path'] }}" class="w-12 h-12 object-cover rounded-lg border border-amber-200 shadow-sm" alt="Zoom" onerror="this.style.display='none'">
                                                                                @endif
                                                                                <div x-data="{ editing: false }">
                                                                                    <button type="button" x-show="!editing" @click="editing = true" class="px-2.5 py-1 text-[10px] font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 rounded-lg cursor-pointer transition text-center">{{ ($set['zoom_path'] ?? null) ? 'Edit URL' : 'Add URL' }}</button>
                                                                                    <div x-show="editing" class="mt-1">
                                                                                        <input type="text" wire:model="imageSets.{{ $index }}.zoom_path" class="w-40 px-2 py-1 text-[10px] bg-amber-50 border border-amber-200 rounded-lg focus:outline-none focus:border-amber-400 text-slate-700" placeholder="https://... (opt)" />
                                                                                        <button type="button" @click="editing = false" class="mt-1 text-[9px] text-slate-400 hover:text-slate-600">Done</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @else
                                                                            @if(isset($replaceZoom[$index]))
                                                                                <img src="{{ $replaceZoom[$index]->temporaryUrl() }}" class="w-12 h-12 object-cover rounded-lg border border-indigo-200 shadow-sm" alt="Zoom Preview">
                                                                            @elseif($set['zoom_url'])
                                                                                <img src="{{ $set['zoom_url'] }}" class="w-12 h-12 object-cover rounded-lg border border-slate-200 shadow-sm" alt="Zoom Image">
                                                                            @else
                                                                                <div class="w-12 h-12 rounded-lg bg-slate-50 border border-dashed border-slate-200 flex items-center justify-center text-slate-400 text-[9px] font-semibold">None</div>
                                                                            @endif
                                                                            <div class="flex flex-col">
                                                                                <label class="px-2.5 py-1 text-[10px] font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg cursor-pointer transition text-center">
                                                                                    {{ $set['zoom_path'] || isset($replaceZoom[$index]) ? 'Replace' : 'Add' }}
                                                                                    <input type="file" wire:model="replaceZoom.{{ $index }}" class="hidden">
                                                                                </label>
                                                                                <div wire:loading wire:target="replaceZoom.{{ $index }}" class="text-[9px] text-indigo-600 mt-1">Uploading...</div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </td>

                                                                <!-- Alt Text -->
                                                                <td class="px-4 py-3">
                                                                    <input type="text" wire:model="imageSets.{{ $index }}.image_alt" placeholder="Alt text..." class="w-28 px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg text-[10px] focus:outline-none focus:border-indigo-500 text-slate-700">
                                                                </td>
                                                                <!-- Zoom Description -->
                                                                <td class="px-4 py-3">
                                                                    <input type="text" wire:model="imageSets.{{ $index }}.zoom_label" placeholder="Zoom desc..." class="w-28 px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg text-[10px] focus:outline-none focus:border-indigo-500 text-slate-700">
                                                                </td>

                                                                <!-- Search Image Checkbox -->
                                                                <td class="px-4 py-3 text-center">
                                                                    <button type="button" wire:click="toggleSearchImage({{ $index }})" class="focus:outline-none">
                                                                        @if($set['search_image'] == 1)
                                                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-600 text-white shadow-sm hover:opacity-90">
                                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                                                </svg>
                                                                            </span>
                                                                        @else
                                                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-slate-100 border border-slate-200 hover:border-slate-300"></span>
                                                                        @endif
                                                                    </button>
                                                                </td>

                                                                <!-- Active Checkbox -->
                                                                <td class="px-4 py-3 text-center">
                                                                    <button type="button" wire:click="toggleActiveImage({{ $index }})" class="focus:outline-none">
                                                                        @if($set['active'] == 1)
                                                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-500 text-white shadow-sm hover:opacity-90">
                                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                                                </svg>
                                                                            </span>
                                                                        @else
                                                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-slate-100 border border-slate-200 hover:border-slate-300"></span>
                                                                        @endif
                                                                    </button>
                                                                </td>

                                                                <!-- Storage -->
                                                                <td class="px-4 py-3 font-mono text-[10px] text-slate-500">
                                                                    @if($isUrlMode)
                                                                        <span class="px-2 py-0.5 bg-amber-50 rounded text-amber-700 font-bold border border-amber-200">External URLs</span>
                                                                    @elseif($set['image_s3'] == 0)
                                                                        <span class="px-2 py-0.5 bg-slate-100 rounded text-slate-600 font-bold">Local</span>
                                                                    @elseif($set['image_s3'] == 1)
                                                                        <span class="px-2 py-0.5 bg-sky-50 rounded text-sky-700 font-bold border border-sky-100">Global S3</span>
                                                                    @else
                                                                        <span class="px-2 py-0.5 bg-indigo-50 rounded text-indigo-700 font-bold border border-indigo-100">Custom S3</span>
                                                                    @endif
                                                                </td>

                                                                <!-- Delete Action -->
                                                                <td class="px-4 py-3 text-center">
                                                                    <button type="button" wire:click="removeImageSet({{ $index }})" class="text-rose-600 hover:text-rose-800 transition p-1 hover:bg-rose-50 rounded-lg">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                        </svg>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif

                                        <!-- Add New Image Set Sub-Card -->
                                        <div class="p-5 bg-white border border-slate-200 rounded-2xl space-y-4 shadow-sm">
                                            <div class="pb-2 border-b border-slate-100 flex items-center justify-between">
                                                <h6 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Add New Image Set</h6>
                                                <!-- URL Mode Toggle -->
                                                <label class="flex items-center gap-2 cursor-pointer select-none">
                                                    <div class="relative">
                                                        <input type="checkbox" wire:model.live="new_image_url_source" class="sr-only peer">
                                                        <div class="w-8 h-4 bg-slate-200 rounded-full peer peer-checked:bg-amber-400 transition-colors duration-200"></div>
                                                        <div class="absolute top-0.5 left-0.5 w-3 h-3 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-4"></div>
                                                    </div>
                                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Enter Image URLs</span>
                                                </label>
                                            </div>

                                            {{-- URL Entry Panel --}}
                                            @if($new_image_url_source)
                                                <div class="p-3 bg-amber-50/60 border border-amber-200 rounded-xl">
                                                    <p class="text-[10px] text-amber-700 font-semibold">Enter direct external URLs for each image size. These will be stored and served as-is — no local or S3 disk resolution.</p>
                                                </div>
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                    <div class="p-3 bg-amber-50/40 border border-amber-200 rounded-xl space-y-2">
                                                        <label class="text-[10px] font-extrabold text-amber-700 block uppercase tracking-wider">Thumbnail URL (Req)</label>
                                                        <input type="url" wire:model.live="new_thumbnail_url" placeholder="https://example.com/thumb.jpg"
                                                               class="w-full px-3 py-2 bg-white border border-amber-200 text-slate-800 rounded-xl focus:outline-none focus:border-amber-400 text-xs">
                                                        @error('new_thumbnail_url') <span class="text-xs text-red-500 font-semibold block">{{ $message }}</span> @enderror
                                                        @if($new_thumbnail_url)
                                                            <img src="{{ $new_thumbnail_url }}" class="w-12 h-12 object-cover rounded-lg border border-amber-200 mt-1 shadow-sm" alt="Preview" onerror="this.style.display='none'">
                                                        @endif
                                                    </div>
                                                    <div class="p-3 bg-amber-50/40 border border-amber-200 rounded-xl space-y-2">
                                                        <label class="text-[10px] font-extrabold text-amber-700 block uppercase tracking-wider">Main Image URL (Req)</label>
                                                        <input type="url" wire:model.live="new_main_url" placeholder="https://example.com/main.jpg"
                                                               class="w-full px-3 py-2 bg-white border border-amber-200 text-slate-800 rounded-xl focus:outline-none focus:border-amber-400 text-xs">
                                                        @error('new_main_url') <span class="text-xs text-red-500 font-semibold block">{{ $message }}</span> @enderror
                                                        @if($new_main_url)
                                                            <img src="{{ $new_main_url }}" class="w-12 h-12 object-cover rounded-lg border border-amber-200 mt-1 shadow-sm" alt="Preview" onerror="this.style.display='none'">
                                                        @endif
                                                    </div>
                                                    <div class="p-3 bg-amber-50/40 border border-amber-200 rounded-xl space-y-2">
                                                        <label class="text-[10px] font-extrabold text-amber-700 block uppercase tracking-wider">Zoom URL (Opt)</label>
                                                        <input type="url" wire:model.live="new_zoom_url" placeholder="https://example.com/zoom.jpg"
                                                               class="w-full px-3 py-2 bg-white border border-amber-200 text-slate-800 rounded-xl focus:outline-none focus:border-amber-400 text-xs">
                                                        @error('new_zoom_url') <span class="text-xs text-red-500 font-semibold block">{{ $message }}</span> @enderror
                                                        @if($new_zoom_url)
                                                            <img src="{{ $new_zoom_url }}" class="w-12 h-12 object-cover rounded-lg border border-amber-200 mt-1 shadow-sm" alt="Preview" onerror="this.style.display='none'">
                                                        @endif
                                                    </div>
                                                </div>

                                            {{-- File Upload Panel (default) --}}
                                            @else
                                                {{-- Storage Destination Selector --}}
                                                <div>
                                                    <label class="text-[10px] font-extrabold text-slate-400 block mb-1 uppercase tracking-wider">Image Storage Destination</label>
                                                    <select wire:model.live="new_image_s3" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs">
                                                        <option value="0">Local Storage (public disk)</option>
                                                        <option value="1">Global S3 (.env config)</option>
                                                        <option value="2">Custom S3 Credentials</option>
                                                    </select>
                                                </div>

                                                @if((int)$new_image_s3 === 2)
                                                    <div class="p-4 bg-indigo-50/70 border border-indigo-200 rounded-xl space-y-3">
                                                        <span class="text-[10px] font-extrabold text-indigo-700 block uppercase tracking-wider">Custom S3 Credentials</span>
                                                        <div class="grid grid-cols-2 gap-3">
                                                            <div>
                                                                <label class="text-[9px] font-bold text-slate-500 block mb-1 uppercase tracking-wider">S3 Region</label>
                                                                <input type="text" wire:model="new_image_s3_region" placeholder="us-east-1" class="w-full px-3 py-1.5 bg-white border border-indigo-200 text-slate-800 rounded-lg focus:outline-none focus:border-indigo-500 text-xs">
                                                                @error('new_image_s3_region') <span class="text-xs text-red-500 font-semibold block mt-0.5">{{ $message }}</span> @enderror
                                                            </div>
                                                            <div>
                                                                <label class="text-[9px] font-bold text-slate-500 block mb-1 uppercase tracking-wider">S3 Bucket Name</label>
                                                                <input type="text" wire:model="new_image_s3_bucket_name" placeholder="my-bucket-name" class="w-full px-3 py-1.5 bg-white border border-indigo-200 text-slate-800 rounded-lg focus:outline-none focus:border-indigo-500 text-xs">
                                                                @error('new_image_s3_bucket_name') <span class="text-xs text-red-500 font-semibold block mt-0.5">{{ $message }}</span> @enderror
                                                            </div>
                                                            <div>
                                                                <label class="text-[9px] font-bold text-slate-500 block mb-1 uppercase tracking-wider">Access Key ID</label>
                                                                <input type="text" wire:model="new_image_s3_access_key_id" class="w-full px-3 py-1.5 bg-white border border-indigo-200 text-slate-800 rounded-lg focus:outline-none focus:border-indigo-500 text-xs">
                                                                @error('new_image_s3_access_key_id') <span class="text-xs text-red-500 font-semibold block mt-0.5">{{ $message }}</span> @enderror
                                                            </div>
                                                            <div>
                                                                <label class="text-[9px] font-bold text-slate-500 block mb-1 uppercase tracking-wider">Secret Access Key</label>
                                                                <input type="password" wire:model="new_image_s3_secret_access_key" class="w-full px-3 py-1.5 bg-white border border-indigo-200 text-slate-800 rounded-lg focus:outline-none focus:border-indigo-500 text-xs">
                                                                @error('new_image_s3_secret_access_key') <span class="text-xs text-red-500 font-semibold block mt-0.5">{{ $message }}</span> @enderror
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label class="text-[9px] font-bold text-slate-500 block mb-1 uppercase tracking-wider">CloudFront / S3 CDN URL Prefix <span class="font-normal text-slate-400">(optional)</span></label>
                                                            <input type="text" wire:model="new_cdn_url" placeholder="https://d1234abcd.cloudfront.net" class="w-full px-3 py-1.5 bg-white border border-indigo-200 text-slate-800 rounded-lg focus:outline-none focus:border-indigo-500 text-xs">
                                                            <span class="text-[9px] text-slate-400 block mt-0.5">Overrides the S3 endpoint with a CloudFront or custom CDN domain.</span>
                                                        </div>
                                                    </div>
                                                @elseif((int)$new_image_s3 === 1)
                                                    <div class="p-3 bg-sky-50/50 border border-sky-200 rounded-xl">
                                                        <label class="text-[9px] font-bold text-slate-500 block mb-1 uppercase tracking-wider">CloudFront / S3 CDN URL Prefix <span class="font-normal text-slate-400">(optional)</span></label>
                                                        <input type="text" wire:model="new_cdn_url" placeholder="https://d1234abcd.cloudfront.net" class="w-full px-3 py-1.5 bg-white border border-sky-200 text-slate-800 rounded-lg focus:outline-none focus:border-sky-400 text-xs">
                                                        <span class="text-[9px] text-slate-400 block mt-0.5">Overrides the global S3 endpoint with a CloudFront or CDN domain when serving images.</span>
                                                    </div>
                                                @endif

                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                    <div class="p-3 bg-slate-50/50 border border-slate-200 rounded-xl space-y-2">
                                                        <label class="text-[10px] font-extrabold text-slate-505 block uppercase tracking-wider">Thumbnail Image (Req)</label>
                                                        <input type="file" wire:model="new_thumbnail" class="w-full text-[9px] text-slate-505 file:py-1 file:px-2 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                                        @error('new_thumbnail') <span class="text-xs text-red-500 font-semibold block">{{ $message }}</span> @enderror
                                                        @if($new_thumbnail)
                                                            <img src="{{ $new_thumbnail->temporaryUrl() }}" class="w-12 h-12 object-cover rounded-lg border border-slate-200 mt-2 shadow-sm" alt="Thumbnail Preview">
                                                        @endif
                                                    </div>
                                                    <div class="p-3 bg-slate-50/50 border border-slate-200 rounded-xl space-y-2">
                                                        <label class="text-[10px] font-extrabold text-slate-505 block uppercase tracking-wider">Main Image (Req)</label>
                                                        <input type="file" wire:model="new_main" class="w-full text-[9px] text-slate-505 file:py-1 file:px-2 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                                        @error('new_main') <span class="text-xs text-red-500 font-semibold block">{{ $message }}</span> @enderror
                                                        @if($new_main)
                                                            <img src="{{ $new_main->temporaryUrl() }}" class="w-12 h-12 object-cover rounded-lg border border-slate-200 mt-2 shadow-sm" alt="Main Preview">
                                                        @endif
                                                    </div>
                                                    <div class="p-3 bg-slate-50/50 border border-slate-200 rounded-xl space-y-2">
                                                        <label class="text-[10px] font-extrabold text-slate-505 block uppercase tracking-wider">Zoom Image (Opt)</label>
                                                        <input type="file" wire:model="new_zoom" class="w-full text-[9px] text-slate-505 file:py-1 file:px-2 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                                        @error('new_zoom') <span class="text-xs text-red-500 font-semibold block">{{ $message }}</span> @enderror
                                                        @if($new_zoom)
                                                            <img src="{{ $new_zoom->temporaryUrl() }}" class="w-12 h-12 object-cover rounded-lg border border-slate-200 mt-2 shadow-sm" alt="Zoom Preview">
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Alt Text and Zoom Description inputs (independent of upload type) --}}
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                                <div>
                                                    <label class="text-[10px] font-extrabold text-slate-400 block mb-1 uppercase tracking-wider font-sans">Alt Text (All Upload Types)</label>
                                                    <input type="text" wire:model="new_image_alt" placeholder="Image description..."
                                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs">
                                                </div>
                                                <div>
                                                    <label class="text-[10px] font-extrabold text-slate-400 block mb-1 uppercase tracking-wider font-sans">Zoom Description (Modal Only)</label>
                                                    <input type="text" wire:model="new_zoom_label" placeholder="Zoom image description..."
                                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs">
                                                </div>
                                            </div>

                                            <div class="flex justify-end pt-1">
                                                <button type="button" wire:click.prevent="addImageSet" class="px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold rounded-xl text-xs transition duration-150 flex items-center gap-1.5 shadow-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                    Add Image Set
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Horizontal Card 2: Download Settings -->
                                    <div class="p-6 bg-slate-50/50 border border-slate-200/60 rounded-3xl space-y-4">
                                        <div class="flex items-center justify-between border-b border-slate-200/60 pb-3">
                                            <div class="space-y-0.5">
                                                <h5 class="text-sm font-bold text-slate-800">2. Digital Download Configuration</h5>
                                                <p class="text-[11px] text-slate-400">Configure file storage settings and upload the digital product file.</p>
                                            </div>
                                            <label class="flex items-center gap-2 cursor-pointer bg-white px-3 py-1.5 border border-slate-200 rounded-xl shadow-sm hover:bg-slate-50 transition-colors">
                                                <input type="checkbox" wire:model.live="download_item" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                                <span class="text-xs font-bold text-slate-700">Digital Product</span>
                                            </label>
                                        </div>

                                        @if($download_item)
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div class="space-y-3">
                                                    <div>
                                                        <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">File Storage Destination</label>
                                                        <select wire:model.live="download_s3" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                                            <option value="0">Local Storage (public disk)</option>
                                                            <option value="1">Global S3 Storage (.env config)</option>
                                                            <option value="2">Custom S3 Credentials</option>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">
                                                            Direct Download URL <span class="text-indigo-600 font-extrabold normal-case">(Overrides Uploaded File)</span>
                                                        </label>
                                                        <input type="url" wire:model="direct_download_url" placeholder="https://example.com/downloads/file.zip" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs font-mono">
                                                        @error('direct_download_url') <span class="text-xs text-red-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                                                        <p class="text-[10px] text-slate-400 font-medium mt-1">If entered, order download links will force-download directly from this URL and override local/S3 uploaded files.</p>
                                                    </div>

                                                    <div>
                                                        <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">
                                                            Digital Item Label / Badge Text
                                                        </label>
                                                        <input type="text" wire:model="download_label" placeholder="Digital Item (Instant Download)" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs font-medium">
                                                        @error('download_label') <span class="text-xs text-red-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                                                        <p class="text-[10px] text-slate-400 font-medium mt-1">Custom text displayed on item view page for digital download variants (defaults to &ldquo;Digital Item (Instant Download)&rdquo;).</p>
                                                    </div>

                                                    <div>
                                                        <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">Upload Product File</label>
                                                        <input type="file" wire:model="downloadFile" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                                        @error('downloadFile') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                                        <div wire:loading wire:target="downloadFile" class="text-xs text-indigo-600 mt-1">Uploading temporary file...</div>
                                                    </div>

                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">Expiration Date</label>
                                                            <input type="datetime-local" wire:model="download_expiration" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs">
                                                            @error('download_expiration') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                                        </div>
                                                        <div>
                                                            <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">Max Downloads</label>
                                                            <input type="number" wire:model="downloads_max_allowed" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs">
                                                            @error('downloads_max_allowed') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="flex flex-col justify-end">
                                                    @if($current_download_location)
                                                        <div class="p-4 bg-white border border-slate-200/60 rounded-2xl space-y-1.5 shadow-sm">
                                                            <span class="text-[10px] font-bold text-slate-400 uppercase block tracking-wider">Current File Location Path</span>
                                                            <div class="text-[11px] text-slate-600 break-all font-mono bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                                                {{ $current_download_location }}
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="h-full flex items-center justify-center border-2 border-dashed border-slate-200 rounded-2xl p-4 bg-slate-50/50">
                                                            <p class="text-xs text-slate-400 italic">No file uploaded yet.</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            @if($download_s3 == 2)
                                                <div class="p-5 bg-indigo-50/70 border border-indigo-200 rounded-2xl space-y-4 mt-4">
                                                    <span class="text-xs font-extrabold text-indigo-700 block uppercase tracking-wider">Custom Download S3 Credentials</span>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">S3 Region</label>
                                                            <input type="text" wire:model="download_s3_region" placeholder="us-east-1" class="w-full px-4 py-2 bg-white border border-indigo-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                                            @error('download_s3_region') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                                        </div>
                                                        <div>
                                                            <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">S3 Bucket Name</label>
                                                            <input type="text" wire:model="download_s3_bucket_name" placeholder="my-bucket-name" class="w-full px-4 py-2 bg-white border border-indigo-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                                            @error('download_s3_bucket_name') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">S3 Access Key ID</label>
                                                            <input type="text" wire:model="download_s3_access_key_id" class="w-full px-4 py-2 bg-white border border-indigo-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                                            @error('download_s3_access_key_id') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                                        </div>
                                                        <div>
                                                            <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">S3 Secret Access Key</label>
                                                            <input type="password" wire:model="download_s3_secret_access_key" class="w-full px-4 py-2 bg-white border border-indigo-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                                            @error('download_s3_secret_access_key') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="pt-3 border-t border-indigo-100 grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">CDN URL Override <span class="font-normal">(Optional)</span></label>
                                                            <input type="text" wire:model="cdn_url" placeholder="https://cdn.mywebsite.com" class="w-full px-4 py-2 bg-white border border-indigo-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                                            <span class="text-[10px] text-slate-400 block mt-1">Overrides default S3 endpoint URL. Resolves files through this CDN domain.</span>
                                                        </div>
                                                        <div>
                                                            <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">S3 Folder / Prefix <span class="font-normal">(Optional)</span></label>
                                                            <input type="text" wire:model="s3_folder" placeholder="e.g., custom-folder" class="w-full px-4 py-2 bg-white border border-indigo-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                                            <span class="text-[10px] text-slate-400 block mt-1">Specify custom prefix for uploads. Defaults to 'downloads' and 'images'.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($download_s3 == 1)
                                                <div class="p-4 bg-sky-50/50 border border-sky-200 rounded-2xl grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                                    <div>
                                                        <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">CDN URL Override <span class="font-normal">(Optional)</span></label>
                                                        <input type="text" wire:model="cdn_url" placeholder="https://cdn.mywebsite.com" class="w-full px-4 py-2 bg-white border border-sky-200 text-slate-800 rounded-xl focus:outline-none focus:border-sky-400 text-sm">
                                                        <span class="text-[10px] text-slate-400 block mt-1">Overrides default S3 endpoint URL. Resolves files through this CDN domain.</span>
                                                    </div>
                                                    <div>
                                                        <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">S3 Folder / Prefix <span class="font-normal">(Optional)</span></label>
                                                        <input type="text" wire:model="s3_folder" placeholder="e.g., custom-folder" class="w-full px-4 py-2 bg-white border border-sky-200 text-slate-800 rounded-xl focus:outline-none focus:border-sky-400 text-sm">
                                                        <span class="text-[10px] text-slate-400 block mt-1">Specify custom prefix for uploads. Defaults to 'downloads' and 'images'.</span>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                            </div>

                            <div class="flex gap-4 pt-2">
                                <button type="submit" wire:loading.attr="disabled" wire:target="updateVariant" class="px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-2xl shadow-md hover:opacity-90 flex items-center justify-center gap-2">
                                    <svg wire:loading wire:target="updateVariant" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Save Variant & Stock</span>
                                </button>
                                <button type="button" wire:click="cancelEditVariant" class="px-6 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-2xl">Cancel</button>
                            </div>
                        </form>
                    </div>
                 @endif

                <!-- Variant Add Form -->
                @if($isCreatingVariant)
                    <div class="bg-indigo-50/50 border border-indigo-100 rounded-3xl p-8 shadow-sm space-y-6">
                        <div class="flex items-center justify-between pb-3 border-b border-indigo-100">
                            <h3 class="text-lg font-bold text-slate-900">Add Variant</h3>
                            <button wire:click="cancelCreateVariant" class="text-slate-400 hover:text-slate-600 font-bold text-sm">Cancel</button>
                        </div>
                        <form wire:submit.prevent="saveVariant" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider">
                                            SKU
                                            <span class="text-rose-500 ml-0.5">*</span>
                                        </label>
                                        <button type="button" wire:click="generateSkuAndSet"
                                                class="inline-flex items-center gap-1 text-[10px] font-bold text-indigo-500 hover:text-indigo-700 transition-colors group"
                                                title="Generate a new SKU from the product title">
                                            <svg class="w-3 h-3 group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            Regenerate
                                        </button>
                                    </div>
                                    <input type="text" wire:model="sku"
                                           class="w-full px-4 py-2.5 bg-white border rounded-2xl focus:outline-none text-slate-800 text-sm transition-colors
                                                  {{ $errors->has('sku') ? 'border-rose-400 bg-rose-50 focus:border-rose-500' : 'border-slate-200 focus:border-indigo-500' }}"
                                           placeholder="e.g. MY-PRODUCT-AB1C"
                                           required>
                                    @error('sku')
                                        <span class="flex items-center gap-1 text-xs text-rose-600 font-semibold mt-1">
                                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                            {{ $message }}
                                        </span>
                                    @else
                                        <span class="text-[10px] text-slate-400 mt-0.5 block">Auto-generated from product title — edit freely.</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Public Price ($)</label>
                                    <input type="number" step="0.01" wire:model="public_price" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Wholesale Price ($)</label>
                                    <input type="number" step="0.01" wire:model="wholesale_price" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                </div>
                            </div>

                             <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">On Sale</label>
                                    <select wire:model.live="on_sale" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                                @if($on_sale == 1)
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Sale Price ($)</label>
                                        <input type="number" step="0.01" wire:model="sale_price" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                    </div>
                                @endif
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Variant Fee ($)</label>
                                    <input type="number" step="0.01" wire:model="variant_fee" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Wholesale Variant Fee ($)</label>
                                    <input type="number" step="0.01" wire:model="wholesale_variant_fee" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Allow Personalization</label>
                                    <select wire:model.live="personalization_active" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                                @if($personalization_active)
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Personalization Fee ($)</label>
                                        <input type="number" step="0.01" wire:model="personalization_fee" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Personalization Label</label>
                                        <input type="text" wire:model="personalization_label" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500" placeholder="e.g. Add Gift Wrapping / Personalization">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Details / Message Label</label>
                                        <input type="text" wire:model="personalization_details_label" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500" placeholder="e.g. Personalization Details / Gift Message">
                                    </div>
                                    <div class="col-span-1 md:col-span-3">
                                        <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Placeholder Text</label>
                                        <input type="text" wire:model="personalization_placeholder" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500" placeholder="e.g. Enter names for engraving, personalization details, or a custom gift message here...">
                                    </div>
                                @endif
                                @if(isset($activeLanguages) && $activeLanguages->count() > 0)
                                    <div class="col-span-1 md:col-span-3">
                                        <div class="flex items-center gap-2 mt-2 p-3 bg-indigo-50 border border-indigo-100 rounded-xl text-xs text-indigo-600">
                                            <svg class="w-4 h-4 flex-shrink-0 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                                            <span><strong>Translations</strong> for personalization labels can be added after saving this variant — click <em>Edit &amp; Inventory</em> on the saved variant to access the translation panel.</span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Quantity Discount breaks -->
                            <div class="bg-indigo-50/30 border border-indigo-100 rounded-3xl p-5 space-y-4">
                                <div class="flex items-center justify-between pb-2 border-b border-indigo-100">
                                    <h4 class="text-xs font-bold text-slate-600 uppercase tracking-wider">Quantity Discount Breaks</h4>
                                    <button type="button" wire:click="addQtyDiscount" class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 focus:outline-none">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                        Add Break Range
                                    </button>
                                </div>

                                @if(empty($qtyDiscounts))
                                    <p class="text-xs text-slate-400 italic">No quantity discount breaks configured. Click 'Add Break Range' to get started.</p>
                                @else
                                    <div class="space-y-3">
                                        @foreach($qtyDiscounts as $index => $break)
                                            <div class="grid grid-cols-1 md:grid-cols-5 gap-3 items-center">
                                                <div>
                                                    <label class="text-[10px] text-slate-400 font-semibold block uppercase">Min Qty</label>
                                                    <input type="number" wire:model="qtyDiscounts.{{ $index }}.qty_min" class="w-full px-3 py-1.5 bg-white border border-slate-200 text-xs text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 font-semibold">
                                                </div>
                                                <div>
                                                    <label class="text-[10px] text-slate-400 font-semibold block uppercase">Max Qty</label>
                                                    <input type="number" wire:model="qtyDiscounts.{{ $index }}.qty_max" class="w-full px-3 py-1.5 bg-white border border-slate-200 text-xs text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 font-semibold">
                                                </div>
                                                <div>
                                                    <label class="text-[10px] text-slate-400 font-semibold block uppercase">Value</label>
                                                    <input type="number" step="0.01" wire:model="qtyDiscounts.{{ $index }}.discount_value" class="w-full px-3 py-1.5 bg-white border border-slate-200 text-xs text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 font-semibold">
                                                </div>
                                                <div>
                                                    <label class="text-[10px] text-slate-400 font-semibold block uppercase">Type</label>
                                                    <select wire:model="qtyDiscounts.{{ $index }}.value_type" class="w-full px-3 py-1.5 bg-white border border-slate-200 text-xs text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500">
                                                        <option value="1">Value ($)</option>
                                                        <option value="2">Percent (%)</option>
                                                    </select>
                                                </div>
                                                <div class="pt-4 text-right">
                                                    <button type="button" wire:click="removeQtyDiscount({{ $index }})" class="text-rose-500 hover:text-rose-700 text-xs font-bold">Remove</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="bg-slate-50 border border-slate-200 rounded-3xl p-5 space-y-4">
                                <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                                    <h4 class="text-xs font-bold text-slate-600 uppercase tracking-wider">Attributes (Color, Size, etc.)</h4>
                                    <button type="button" wire:click="addAttribute" class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 focus:outline-none">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                        Add Attribute
                                    </button>
                                </div>

                                {{-- Inline builder inputs --}}
                                @if(empty($inlineAttributes))
                                    <p class="text-xs text-slate-400 italic">No attributes configured. Click 'Add Attribute' to get started.</p>
                                @else
                                    <div class="space-y-3">
                                        @foreach($inlineAttributes as $index => $attr)
                                            <div class="flex items-center gap-3" key="create-attr-{{ $index }}">
                                                <div class="flex-1">
                                                    <input type="text" wire:model.blur="inlineAttributes.{{ $index }}.key" placeholder="Attribute Name (e.g. Color)" class="w-full px-3 py-2 bg-white border border-slate-200 text-xs text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 font-semibold">
                                                </div>
                                                <div class="flex-1">
                                                    <input type="text" wire:model.blur="inlineAttributes.{{ $index }}.value" placeholder="Value (e.g. Blue)" class="w-full px-3 py-2 bg-white border border-slate-200 text-xs text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500">
                                                </div>
                                                <button type="button" wire:click="removeAttribute({{ $index }})" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Direct JSON input for advanced users --}}
                                <div class="pt-3 border-t border-slate-200 font-sans">
                                    <label class="text-[10px] font-bold text-slate-400 block mb-1 uppercase tracking-wider">Advanced: Direct JSON Input</label>
                                    <input type="text" wire:model.blur="variantAttributes" placeholder='{"Color":"Blue"}' class="w-full px-3 py-2 bg-white border border-slate-200 text-xs text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 font-mono">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Weight</label>
                                    <input type="number" step="0.1" wire:model="weight" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Weight Type</label>
                                    <select wire:model="weight_type" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                        <option value="lbs">lbs</option>
                                        <option value="oz">oz</option>
                                        <option value="kg">kg</option>
                                        <option value="g">g</option>
                                    </select>
                                </div>
                                {{-- Requires Shipping Toggle --}}
                                <div class="flex flex-col justify-end pb-1">
                                    <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Requires Shipping</label>
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <div class="relative">
                                            <input type="checkbox" wire:model.number="shipping" class="sr-only peer" true-value="1" false-value="0">
                                            <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-indigo-400 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-500"></div>
                                        </div>
                                        <span class="text-sm font-bold {{ $shipping ? 'text-indigo-700' : 'text-slate-400' }} transition-colors">
                                            {{ $shipping ? 'Shipping Required' : 'No Shipping' }}
                                        </span>
                                    </label>
                                </div>
                                {{-- Charge Tax Toggle --}}
                                <div class="flex flex-col justify-end pb-1">
                                    <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Sales Tax / VAT</label>
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <div class="relative">
                                            <input type="checkbox" wire:model.number="charge_tax" class="sr-only peer" true-value="1" false-value="0">
                                            <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-emerald-400 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                        </div>
                                        <span class="text-sm font-bold {{ $charge_tax ? 'text-emerald-700' : 'text-slate-400' }} transition-colors">
                                            {{ $charge_tax ? 'Taxable' : 'Tax Exempt' }}
                                        </span>
                                    </label>
                                </div>
                            </div>

                            @include('livewire.partials.variant-processor-ids')

                            {{-- ═══════ INVENTORY & WAREHOUSE STOCK SECTION (Create) ═══════ --}}
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-4">
                                <div class="flex items-center justify-between flex-wrap gap-2 pb-2 border-b border-slate-200">
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                            Inventory & Warehouse Stock Calculation
                                        </h4>
                                        <p class="text-[11px] text-slate-500 mt-0.5">Configure main in-stock levels and optional warehouse-specific inventory stock.</p>
                                    </div>

                                    <a href="{{ route('admin.ecommerce.shipping', ['tab' => 'warehouses']) }}" target="admin_warehouse_locations"
                                       class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:underline bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-200 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        Manage Warehouses & Fulfillment Locations &rarr;
                                    </a>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <label class="text-xs font-bold text-indigo-600 block mb-1 uppercase tracking-wider">Quantity Available (In Stock)</label>
                                        <input type="number" wire:model.live="quantity_available" class="w-full px-4 py-2.5 bg-white border border-indigo-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 font-bold">
                                        @error('quantity_available') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">Reserved Stock</label>
                                        <input type="number" wire:model.live="reserved_stock" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                        @error('reserved_stock') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">Main Warehouse Stock Level</label>
                                        <input type="number" wire:model.live="warehouse_stock_level" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                        @error('warehouse_stock_level') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">Primary Warehouse Facility</label>
                                        <select wire:model.live="location_id" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 font-semibold">
                                            @foreach($allWarehouseLocations as $loc)
                                                @php
                                                    $isUsedInChild = collect($variantWarehouseStock)->pluck('warehouse_location_id')->map(fn($id)=>(int)$id)->contains((int)$loc->id);
                                                @endphp
                                                @if(!$isUsedInChild || (int)$location_id === (int)$loc->id)
                                                    <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->code }})</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('location_id') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" wire:model.live="use_warehouse_stock" class="rounded text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-xs font-bold text-slate-700">Use Warehouse Stock in Calculations</span>
                                    </label>
                                </div>

                                {{-- Warehouse Locations Dependent Inventory Levels Child List --}}
                                @if($use_warehouse_stock)
                                    <div class="mt-4 p-4 bg-indigo-50/50 border border-indigo-100 rounded-2xl space-y-3">
                                        <div class="flex items-center justify-between flex-wrap gap-2">
                                            <div>
                                                <h5 class="text-xs font-bold text-indigo-900 uppercase tracking-wider">Warehouse Dependent Inventory Levels</h5>
                                                <p class="text-[11px] text-indigo-700/80">Assign inventory levels per warehouse location to contribute to total available stock.</p>
                                            </div>
                                            <button type="button" wire:click="addWarehouseStockLine" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition inline-flex items-center gap-1 cursor-pointer">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                Add Warehouse Location Stock
                                            </button>
                                        </div>

                                        @if(!empty($variantWarehouseStock))
                                            <div class="space-y-2">
                                                @foreach($variantWarehouseStock as $index => $wStock)
                                                    <div class="flex items-center gap-3 bg-white p-3 rounded-xl border border-indigo-100 shadow-sm">
                                                        <div class="flex-1">
                                                            <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Warehouse Location</label>
                                                            <select wire:model.live="variantWarehouseStock.{{ $index }}.warehouse_location_id" class="w-full px-3 py-2 bg-white border border-slate-200 text-xs text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 font-semibold">
                                                                @foreach($allWarehouseLocations as $loc)
                                                                    @php
                                                                        $isPrimary = (int)$location_id === (int)$loc->id;
                                                                        $isOtherChild = collect($variantWarehouseStock)
                                                                            ->forget($index)
                                                                            ->pluck('warehouse_location_id')
                                                                            ->map(fn($id)=>(int)$id)
                                                                            ->contains((int)$loc->id);
                                                                    @endphp
                                                                    @if(!$isPrimary && (!$isOtherChild || (int)($wStock['warehouse_location_id'] ?? 0) === (int)$loc->id))
                                                                        <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->code }}) - {{ $loc->city }}, {{ $loc->state_code }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="w-36">
                                                            <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Assigned Stock Qty</label>
                                                            <input type="number" min="0" wire:model.live="variantWarehouseStock.{{ $index }}.stock_level" class="w-full px-3 py-2 bg-white border border-slate-200 text-xs font-bold text-indigo-700 rounded-xl focus:outline-none focus:border-indigo-500">
                                                        </div>
                                                        <div class="pt-5">
                                                            <button type="button" wire:click="removeWarehouseStockLine({{ $index }})" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-xl transition cursor-pointer" title="Remove Location">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="p-3 text-center bg-white/70 rounded-xl border border-dashed border-indigo-200">
                                                <p class="text-xs text-slate-500">No additional warehouse location stock entries added. Click <strong>"Add Warehouse Location Stock"</strong> to assign inventory levels per warehouse location.</p>
                                            </div>
                                        @endif

                                        @php
                                            $childSum = (int) collect($variantWarehouseStock)->sum('stock_level');
                                            $totalAvailable = (int)$quantity_available + (int)$warehouse_stock_level + $childSum - (int)$reserved_stock;
                                        @endphp
                                        <div class="pt-2 flex items-center justify-between text-xs font-bold text-indigo-900 border-t border-indigo-200/80">
                                            <span>Total Available Calculated Stock:</span>
                                            <span class="px-3 py-1 bg-indigo-600 text-white rounded-xl shadow-sm text-xs">
                                                {{ $quantity_available }} (In Stock) + {{ $warehouse_stock_level }} (Main Warehouse) + {{ $childSum }} (Child Warehouses) - {{ $reserved_stock }} (Reserved) = {{ $totalAvailable }} Total Units
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- ═══════ EVENT DETAILS SUB-SECTION (Create) ═══════ --}}
                            <div class="border border-violet-200 bg-violet-50/40 rounded-3xl p-5 space-y-4">
                                <div class="flex items-center gap-3 border-b border-violet-200/70 pb-3">
                                    <label class="inline-flex items-center gap-2.5 cursor-pointer group">
                                        <div class="relative">
                                            <input type="checkbox" wire:model.live="is_event" class="sr-only peer">
                                            <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-violet-400 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-600"></div>
                                        </div>
                                        <span class="text-sm font-bold {{ $is_event ? 'text-violet-700' : 'text-slate-400' }} transition-colors">Mark as Event / Calendar Item</span>
                                    </label>
                                    @if($is_event)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-violet-100 text-violet-700 border border-violet-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            Event Active
                                        </span>
                                    @endif
                                </div>

                                @if($is_event)
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">Event Label <span class="text-red-400">*</span></label>
                                            <input type="text" wire:model="event_label" placeholder="e.g. Digital Marketing Seminar — Sept 16, 9 AM" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-violet-500 text-sm">
                                            @error('event_label') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">Alternate / Tooltip Label</label>
                                            <input type="text" wire:model="alternate_label" placeholder="e.g. Digital Marketing Seminar | 9 AM - 10 AM |" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-violet-500 text-sm">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">Event Start Date &amp; Time <span class="text-red-400">*</span></label>
                                            <input type="datetime-local" wire:model="event_start_date" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-violet-500 text-sm">
                                            @error('event_start_date') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">Event End Date &amp; Time</label>
                                            <input type="datetime-local" wire:model="event_end_date" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-violet-500 text-sm">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">Calendar Colour</label>
                                            <div class="flex items-center gap-2">
                                                <input type="color" wire:model="label_background" class="w-10 h-10 rounded-xl border border-slate-200 cursor-pointer p-0.5 bg-white">
                                                <input type="text" wire:model="label_background" placeholder="#4f46e5" class="flex-1 px-3 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-violet-500 text-sm font-mono">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">Sort Order</label>
                                            <input type="number" step="0.1" wire:model="event_sort" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-violet-500 text-sm">
                                        </div>
                                        <div class="flex flex-col justify-end pb-1">
                                            <label class="text-xs font-bold text-slate-500 block mb-2 uppercase tracking-wider">Show Date on Front-End</label>
                                            <label class="inline-flex items-center gap-2.5 cursor-pointer">
                                                <input type="checkbox" wire:model="show_date" class="rounded text-violet-600 focus:ring-violet-500">
                                                <span class="text-xs font-bold text-slate-600">Display event date</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">Location / Venue / URL</label>
                                        <input type="text" wire:model="event_location" placeholder="e.g. Room 101, Convention Centre or https://zoom.us/j/..." class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-violet-500 text-sm">
                                    </div>

                                    <div>
                                        <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">Event Description</label>
                                        <textarea wire:model="event_description" rows="3" placeholder="Optional detailed description of this event session..." class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-violet-500 text-sm resize-none"></textarea>
                                    </div>
                                @else
                                    <p class="text-xs text-slate-400 italic">Enable the toggle above to configure event scheduling, calendar colour, dates, and location for this variant.</p>
                                @endif
                            </div>

                            <!-- Storage, Downloads & Image Uploads -->
                            <div class="border-t border-slate-200/60 pt-6 space-y-6">
                                <h4 class="text-base font-extrabold text-slate-900 tracking-tight">Storage, Downloads & Image Uploads</h4>

                                <div class="space-y-4">
                                    <!-- Horizontal Card 1: Image Gallery & Uploads -->
                                    <div class="p-6 bg-slate-50/50 border border-slate-200/60 rounded-3xl space-y-6">
                                        <div class="flex items-center justify-between border-b border-slate-200/60 pb-3">
                                            <div class="space-y-0.5">
                                                <h5 class="text-sm font-bold text-slate-800">1. Variant Images & Gallery</h5>
                                                <p class="text-[11px] text-slate-400">Configure and upload multiple sets of images (each requiring a thumbnail and main image, with an optional zoom image).</p>
                                            </div>
                                        </div>

                                        {{-- Auto-save confirmation toast --}}
                                        @if(session('image_auto_saved') || $imageAutoSaved)
                                            <div x-data="{ show: true }"
                                                 x-show="show"
                                                 x-init="setTimeout(() => show = false, 3500)"
                                                 x-transition:leave="transition ease-in duration-300"
                                                 x-transition:leave-start="opacity-100 translate-y-0"
                                                 x-transition:leave-end="opacity-0 -translate-y-2"
                                                 class="flex items-center gap-2 px-4 py-2.5 bg-emerald-50 border border-emerald-200 rounded-2xl shadow-sm">
                                                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-xs font-semibold text-emerald-700">Image set auto-saved to the variant.</span>
                                            </div>
                                        @endif

                                        @if(empty($imageSets))
                                            <div class="text-center p-8 bg-white border border-slate-100 rounded-2xl shadow-sm">
                                                <svg class="mx-auto h-10 w-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <h4 class="mt-2 text-xs font-bold text-slate-700">No Image Sets Uploaded</h4>
                                                <p class="mt-1 text-[11px] text-slate-400">Add your first set of images using the form below.</p>
                                            </div>
                                        @else
                                            <!-- Responsive Table of Image Sets -->
                                            <div class="overflow-x-auto border border-slate-200 rounded-2xl bg-white shadow-sm">
                                                <table class="min-w-full divide-y divide-slate-150 text-left text-xs text-slate-700">
                                                    <thead class="bg-slate-50 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-200">
                                                        <tr>
                                                            <th scope="col" class="px-4 py-3">Thumbnail</th>
                                                            <th scope="col" class="px-4 py-3">Main Image</th>
                                                            <th scope="col" class="px-4 py-3">Zoom (Opt)</th>
                                                            <th scope="col" class="px-4 py-3">Alt Text</th>
                                                            <th scope="col" class="px-4 py-3">Zoom Desc</th>
                                                            <th scope="col" class="px-4 py-3 text-center">Search Image</th>
                                                            <th scope="col" class="px-4 py-3 text-center">Active</th>
                                                            <th scope="col" class="px-4 py-3">Storage</th>
                                                            <th scope="col" class="px-4 py-3 text-center">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-slate-100 bg-white">
                                                        @foreach($imageSets as $index => $set)
                                                            @php
                                                                $isUrlMode = ($set['image_url_source'] ?? 0) == 1;
                                                            @endphp
                                                            <tr class="hover:bg-slate-50/50 transition">
                                                                <!-- Thumbnail -->
                                                                <td class="px-4 py-3">
                                                                    @if($isUrlMode)
                                                                        <div class="flex flex-col gap-1">
                                                                            <img src="{{ $set['thumbnail_url'] ?? $set['thumbnail_path'] ?? '' }}" class="w-12 h-12 object-cover rounded-lg border border-amber-200 shadow-sm" alt="Thumbnail" onerror="this.style.display='none'">
                                                                            <div x-data="{ editing: false }">
                                                                                <button type="button" x-show="!editing" @click="editing = true" class="px-2.5 py-1 text-[10px] font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 rounded-lg cursor-pointer transition text-center">Edit URL</button>
                                                                                <div x-show="editing" class="mt-1">
                                                                                    <input type="text" wire:model="imageSets.{{ $index }}.thumbnail_path"
                                                                                           class="w-40 px-2 py-1 text-[10px] bg-amber-50 border border-amber-200 rounded-lg focus:outline-none focus:border-amber-400 text-slate-700"
                                                                                           placeholder="https://..." />
                                                                                    <button type="button" @click="editing = false" class="mt-1 text-[9px] text-slate-400 hover:text-slate-600">Done</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <div class="flex items-center gap-3">
                                                                            @if(isset($replaceThumbnail[$index]))
                                                                                <img src="{{ $replaceThumbnail[$index]->temporaryUrl() }}" class="w-12 h-12 object-cover rounded-lg border border-indigo-200 shadow-sm" alt="Thumbnail Preview">
                                                                            @elseif($set['thumbnail_url'])
                                                                                <img src="{{ $set['thumbnail_url'] }}" class="w-12 h-12 object-cover rounded-lg border border-slate-200 shadow-sm" alt="Thumbnail">
                                                                            @else
                                                                                <div class="w-12 h-12 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 text-[10px]">No image</div>
                                                                            @endif
                                                                            <div class="flex flex-col">
                                                                                <label class="px-2.5 py-1 text-[10px] font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg cursor-pointer transition text-center">
                                                                                    Replace
                                                                                    <input type="file" wire:model="replaceThumbnail.{{ $index }}" class="hidden">
                                                                                </label>
                                                                                <div wire:loading wire:target="replaceThumbnail.{{ $index }}" class="text-[9px] text-indigo-600 mt-1">Uploading...</div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </td>
                                                                <!-- Main Image -->
                                                                <td class="px-4 py-3">
                                                                    @if($isUrlMode)
                                                                        <div class="flex flex-col gap-1">
                                                                            <img src="{{ $set['main_url'] ?? $set['main_path'] ?? '' }}" class="w-12 h-12 object-cover rounded-lg border border-amber-200 shadow-sm" alt="Main" onerror="this.style.display='none'">
                                                                            <div x-data="{ editing: false }">
                                                                                <button type="button" x-show="!editing" @click="editing = true" class="px-2.5 py-1 text-[10px] font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 rounded-lg cursor-pointer transition text-center">Edit URL</button>
                                                                                <div x-show="editing" class="mt-1">
                                                                                    <input type="text" wire:model="imageSets.{{ $index }}.main_path"
                                                                                           class="w-40 px-2 py-1 text-[10px] bg-amber-50 border border-amber-200 rounded-lg focus:outline-none focus:border-amber-400 text-slate-700"
                                                                                           placeholder="https://..." />
                                                                                    <button type="button" @click="editing = false" class="mt-1 text-[9px] text-slate-400 hover:text-slate-600">Done</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <div class="flex items-center gap-3">
                                                                            @if(isset($replaceMain[$index]))
                                                                                <img src="{{ $replaceMain[$index]->temporaryUrl() }}" class="w-12 h-12 object-cover rounded-lg border border-indigo-200 shadow-sm" alt="Main Preview">
                                                                            @elseif($set['main_url'])
                                                                                <img src="{{ $set['main_url'] }}" class="w-12 h-12 object-cover rounded-lg border border-slate-200 shadow-sm" alt="Main Image">
                                                                            @else
                                                                                <div class="w-12 h-12 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 text-[10px]">No image</div>
                                                                            @endif
                                                                            <div class="flex flex-col">
                                                                                <label class="px-2.5 py-1 text-[10px] font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg cursor-pointer transition text-center">
                                                                                    Replace
                                                                                    <input type="file" wire:model="replaceMain.{{ $index }}" class="hidden">
                                                                                </label>
                                                                                <div wire:loading wire:target="replaceMain.{{ $index }}" class="text-[9px] text-indigo-600 mt-1">Uploading...</div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </td>

                                                                <!-- Zoom Image (Optional) -->
                                                                <td class="px-4 py-3">
                                                                    @if($isUrlMode)
                                                                        <div class="flex flex-col gap-1">
                                                                            @if($set['zoom_url'] ?? $set['zoom_path'] ?? null)
                                                                                <img src="{{ $set['zoom_url'] ?? $set['zoom_path'] }}" class="w-12 h-12 object-cover rounded-lg border border-amber-200 shadow-sm" alt="Zoom" onerror="this.style.display='none'">
                                                                            @endif
                                                                            <div x-data="{ editing: false }">
                                                                                <button type="button" x-show="!editing" @click="editing = true" class="px-2.5 py-1 text-[10px] font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 rounded-lg cursor-pointer transition text-center">{{ ($set['zoom_path'] ?? null) ? 'Edit URL' : 'Add URL' }}</button>
                                                                                <div x-show="editing" class="mt-1">
                                                                                    <input type="text" wire:model="imageSets.{{ $index }}.zoom_path"
                                                                                           class="w-40 px-2 py-1 text-[10px] bg-amber-50 border border-amber-200 rounded-lg focus:outline-none focus:border-amber-400 text-slate-700"
                                                                                           placeholder="https://... (opt)" />
                                                                                    <button type="button" @click="editing = false" class="mt-1 text-[9px] text-slate-400 hover:text-slate-600">Done</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <div class="flex items-center gap-3">
                                                                            @if(isset($replaceZoom[$index]))
                                                                                <img src="{{ $replaceZoom[$index]->temporaryUrl() }}" class="w-12 h-12 object-cover rounded-lg border border-indigo-200 shadow-sm" alt="Zoom Preview">
                                                                            @elseif($set['zoom_url'])
                                                                                <img src="{{ $set['zoom_url'] }}" class="w-12 h-12 object-cover rounded-lg border border-slate-200 shadow-sm" alt="Zoom Image">
                                                                            @else
                                                                                <div class="w-12 h-12 rounded-lg bg-slate-50 border border-dashed border-slate-200 flex items-center justify-center text-slate-400 text-[9px] font-semibold">None</div>
                                                                            @endif
                                                                            <div class="flex flex-col">
                                                                                <label class="px-2.5 py-1 text-[10px] font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg cursor-pointer transition text-center">
                                                                                    {{ $set['zoom_path'] || isset($replaceZoom[$index]) ? 'Replace' : 'Add' }}
                                                                                    <input type="file" wire:model="replaceZoom.{{ $index }}" class="hidden">
                                                                                </label>
                                                                                <div wire:loading wire:target="replaceZoom.{{ $index }}" class="text-[9px] text-indigo-600 mt-1">Uploading...</div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </td>

                                                                <!-- Alt Text -->
                                                                <td class="px-4 py-3">
                                                                    <input type="text" wire:model="imageSets.{{ $index }}.image_alt" placeholder="Alt text..." class="w-28 px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg text-[10px] focus:outline-none focus:border-indigo-500 text-slate-700">
                                                                </td>
                                                                <!-- Zoom Description -->
                                                                <td class="px-4 py-3">
                                                                    <input type="text" wire:model="imageSets.{{ $index }}.zoom_label" placeholder="Zoom desc..." class="w-28 px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg text-[10px] focus:outline-none focus:border-indigo-500 text-slate-700">
                                                                </td>

                                                                <!-- Search Image Checkbox -->
                                                                <td class="px-4 py-3 text-center">
                                                                    <button type="button" wire:click="toggleSearchImage({{ $index }})" class="focus:outline-none">
                                                                        @if($set['search_image'] == 1)
                                                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-600 text-white shadow-sm hover:opacity-90">
                                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                                                </svg>
                                                                            </span>
                                                                        @else
                                                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-slate-100 border border-slate-200 hover:border-slate-300"></span>
                                                                        @endif
                                                                    </button>
                                                                </td>

                                                                <!-- Active Checkbox -->
                                                                <td class="px-4 py-3 text-center">
                                                                    <button type="button" wire:click="toggleActiveImage({{ $index }})" class="focus:outline-none">
                                                                        @if($set['active'] == 1)
                                                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-500 text-white shadow-sm hover:opacity-90">
                                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                                                </svg>
                                                                            </span>
                                                                        @else
                                                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-slate-100 border border-slate-200 hover:border-slate-300"></span>
                                                                        @endif
                                                                    </button>
                                                                </td>

                                                                <!-- Storage -->
                                                                <td class="px-4 py-3 font-mono text-[10px] text-slate-500">
                                                                    @if($isUrlMode)
                                                                        <span class="px-2 py-0.5 bg-amber-50 rounded text-amber-700 font-bold border border-amber-200">External URLs</span>
                                                                    @elseif($set['image_s3'] == 0)
                                                                        <span class="px-2 py-0.5 bg-slate-100 rounded text-slate-600 font-bold">Local</span>
                                                                    @elseif($set['image_s3'] == 1)
                                                                        <span class="px-2 py-0.5 bg-sky-50 rounded text-sky-700 font-bold border border-sky-100">Global S3</span>
                                                                    @else
                                                                        <span class="px-2 py-0.5 bg-indigo-50 rounded text-indigo-700 font-bold border border-indigo-100">Custom S3</span>
                                                                    @endif
                                                                </td>
                                                                <!-- Delete Action -->
                                                                <td class="px-4 py-3 text-center">
                                                                    <button type="button" wire:click="removeImageSet({{ $index }})" class="text-rose-600 hover:text-rose-800 transition p-1 hover:bg-rose-50 rounded-lg">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                        </svg>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            <!-- Sub-row: Direct URL editors (when URL mode) or Custom S3 Credentials -->
                                                            @if($isUrlMode)
                                                            {{-- URL source mode: allow editing per-size URLs directly --}}
                                                            <tr class="bg-amber-50/20">
                                                                <td colspan="7" class="px-4 py-3 border-b border-amber-100">
                                                                    <div class="flex items-center gap-2 mb-2">
                                                                        <span class="text-[9px] font-extrabold text-amber-700 uppercase tracking-wider">Direct Image URLs — Override Per Size</span>
                                                                    </div>
                                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                                                        <div>
                                                                            <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Thumbnail URL</label>
                                                                            <input type="url" wire:model="imageSets.{{ $index }}.thumbnail_path" placeholder="https://example.com/thumb.jpg" class="w-full px-3 py-1.5 bg-white border border-amber-200 rounded-lg text-xs focus:outline-none focus:border-amber-400 text-slate-700">
                                                                        </div>
                                                                        <div>
                                                                            <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Main Image URL</label>
                                                                            <input type="url" wire:model="imageSets.{{ $index }}.main_path" placeholder="https://example.com/main.jpg" class="w-full px-3 py-1.5 bg-white border border-amber-200 rounded-lg text-xs focus:outline-none focus:border-amber-400 text-slate-700">
                                                                        </div>
                                                                        <div>
                                                                            <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Zoom URL <span class="font-normal text-slate-400">(optional)</span></label>
                                                                            <input type="url" wire:model="imageSets.{{ $index }}.zoom_path" placeholder="https://example.com/zoom.jpg" class="w-full px-3 py-1.5 bg-white border border-amber-200 rounded-lg text-xs focus:outline-none focus:border-amber-400 text-slate-700">
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @elseif($set['image_s3'] == 2)
                                                            {{-- Custom S3 credentials for this image set --}}
                                                            <tr class="bg-indigo-50/20">
                                                                <td colspan="7" class="px-4 py-3 border-b border-indigo-100">
                                                                    <div class="flex items-center gap-2 mb-2">
                                                                        <span class="text-[9px] font-extrabold text-indigo-700 uppercase tracking-wider">Custom S3 Credentials — This Image Set</span>
                                                                    </div>
                                                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                                                        <div>
                                                                            <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">S3 Region</label>
                                                                            <input type="text" wire:model="imageSets.{{ $index }}.image_s3_region" placeholder="us-east-1" class="w-full px-3 py-1.5 bg-white border border-indigo-100 rounded-lg text-xs focus:outline-none focus:border-indigo-500 text-slate-700">
                                                                            @error("imageSets.{$index}.image_s3_region") <span class="text-[9px] text-red-500 font-semibold block mt-0.5">{{ $message }}</span> @enderror
                                                                        </div>
                                                                        <div>
                                                                            <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">S3 Bucket</label>
                                                                            <input type="text" wire:model="imageSets.{{ $index }}.image_s3_bucket_name" placeholder="my-bucket" class="w-full px-3 py-1.5 bg-white border border-indigo-100 rounded-lg text-xs focus:outline-none focus:border-indigo-500 text-slate-700">
                                                                            @error("imageSets.{$index}.image_s3_bucket_name") <span class="text-[9px] text-red-500 font-semibold block mt-0.5">{{ $message }}</span> @enderror
                                                                        </div>
                                                                        <div>
                                                                            <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Access Key ID</label>
                                                                            <input type="text" wire:model="imageSets.{{ $index }}.image_s3_access_key_id" class="w-full px-3 py-1.5 bg-white border border-indigo-100 rounded-lg text-xs focus:outline-none focus:border-indigo-500 text-slate-700">
                                                                            @error("imageSets.{$index}.image_s3_access_key_id") <span class="text-[9px] text-red-500 font-semibold block mt-0.5">{{ $message }}</span> @enderror
                                                                        </div>
                                                                        <div>
                                                                            <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Secret Access Key</label>
                                                                            <input type="password" wire:model="imageSets.{{ $index }}.image_s3_secret_access_key" class="w-full px-3 py-1.5 bg-white border border-indigo-100 rounded-lg text-xs focus:outline-none focus:border-indigo-500 text-slate-700">
                                                                            @error("imageSets.{$index}.image_s3_secret_access_key") <span class="text-[9px] text-red-500 font-semibold block mt-0.5">{{ $message }}</span> @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="mt-2">
                                                                        <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">CloudFront / S3 CDN URL Prefix <span class="font-normal">(optional)</span></label>
                                                                        <input type="text" wire:model="imageSets.{{ $index }}.cdn_url" placeholder="https://d1234abcd.cloudfront.net" class="w-full md:w-96 px-3 py-1.5 bg-white border border-indigo-100 rounded-lg text-xs focus:outline-none focus:border-indigo-500 text-slate-700">
                                                                        <span class="text-[9px] text-slate-400 block mt-0.5">Overrides the S3 endpoint with a CloudFront or custom CDN domain when serving images.</span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @elseif(($set['image_s3'] ?? 0) == 1)
                                                            {{-- Global S3: just CDN prefix --}}
                                                            <tr class="bg-sky-50/20">
                                                                <td colspan="7" class="px-4 py-3 border-b border-sky-100">
                                                                    <div class="flex items-center gap-3">
                                                                        <div class="flex-1 max-w-md">
                                                                            <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">CloudFront / S3 CDN URL Prefix <span class="font-normal">(optional)</span></label>
                                                                            <input type="text" wire:model="imageSets.{{ $index }}.cdn_url" placeholder="https://d1234abcd.cloudfront.net" class="w-full px-3 py-1.5 bg-white border border-sky-200 rounded-lg text-xs focus:outline-none focus:border-sky-400 text-slate-700">
                                                                            <span class="text-[9px] text-slate-400 block mt-0.5">Overrides the global S3 endpoint with a CloudFront or CDN domain when serving images.</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif

                                        <!-- Add New Image Set Sub-Card -->
                                        <div class="p-5 bg-white border border-slate-200 rounded-2xl space-y-4 shadow-sm">
                                            <div class="pb-2 border-b border-slate-100 flex items-center justify-between">
                                                <h6 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Add New Image Set</h6>
                                                <!-- URL Mode Toggle -->
                                                <label class="flex items-center gap-2 cursor-pointer select-none">
                                                    <div class="relative">
                                                        <input type="checkbox" wire:model.live="new_image_url_source" class="sr-only peer">
                                                        <div class="w-8 h-4 bg-slate-200 rounded-full peer peer-checked:bg-amber-400 transition-colors duration-200"></div>
                                                        <div class="absolute top-0.5 left-0.5 w-3 h-3 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-4"></div>
                                                    </div>
                                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Enter Image URLs</span>
                                                </label>
                                            </div>

                                            {{-- URL Entry Panel --}}
                                            @if($new_image_url_source)
                                                <div class="p-3 bg-amber-50/60 border border-amber-200 rounded-xl">
                                                    <p class="text-[10px] text-amber-700 font-semibold">Enter direct external URLs for each image. These will be stored and served as-is — no local or S3 disk resolution.</p>
                                                </div>
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                    <div class="p-3 bg-amber-50/40 border border-amber-200 rounded-xl space-y-2">
                                                        <label class="text-[10px] font-extrabold text-amber-700 block uppercase tracking-wider">Thumbnail URL (Req)</label>
                                                        <input type="url" wire:model.live="new_thumbnail_url" placeholder="https://example.com/thumb.jpg"
                                                               class="w-full px-3 py-2 bg-white border border-amber-200 text-slate-800 rounded-xl focus:outline-none focus:border-amber-400 text-xs">
                                                        @error('new_thumbnail_url') <span class="text-xs text-red-500 font-semibold block">{{ $message }}</span> @enderror
                                                        @if($new_thumbnail_url)
                                                            <img src="{{ $new_thumbnail_url }}" class="w-12 h-12 object-cover rounded-lg border border-amber-200 mt-1 shadow-sm" alt="Preview" onerror="this.style.display='none'">
                                                        @endif
                                                    </div>
                                                    <div class="p-3 bg-amber-50/40 border border-amber-200 rounded-xl space-y-2">
                                                        <label class="text-[10px] font-extrabold text-amber-700 block uppercase tracking-wider">Main Image URL (Req)</label>
                                                        <input type="url" wire:model.live="new_main_url" placeholder="https://example.com/main.jpg"
                                                               class="w-full px-3 py-2 bg-white border border-amber-200 text-slate-800 rounded-xl focus:outline-none focus:border-amber-400 text-xs">
                                                        @error('new_main_url') <span class="text-xs text-red-500 font-semibold block">{{ $message }}</span> @enderror
                                                        @if($new_main_url)
                                                            <img src="{{ $new_main_url }}" class="w-12 h-12 object-cover rounded-lg border border-amber-200 mt-1 shadow-sm" alt="Preview" onerror="this.style.display='none'">
                                                        @endif
                                                    </div>
                                                    <div class="p-3 bg-amber-50/40 border border-amber-200 rounded-xl space-y-2">
                                                        <label class="text-[10px] font-extrabold text-amber-700 block uppercase tracking-wider">Zoom URL (Opt)</label>
                                                        <input type="url" wire:model.live="new_zoom_url" placeholder="https://example.com/zoom.jpg"
                                                               class="w-full px-3 py-2 bg-white border border-amber-200 text-slate-800 rounded-xl focus:outline-none focus:border-amber-400 text-xs">
                                                        @error('new_zoom_url') <span class="text-xs text-red-500 font-semibold block">{{ $message }}</span> @enderror
                                                        @if($new_zoom_url)
                                                            <img src="{{ $new_zoom_url }}" class="w-12 h-12 object-cover rounded-lg border border-amber-200 mt-1 shadow-sm" alt="Preview" onerror="this.style.display='none'">
                                                        @endif
                                                    </div>
                                                </div>

                                            {{-- File Upload Panel (default) --}}
                                            @else
                                                {{-- Storage Destination Selector --}}
                                                <div>
                                                    <label class="text-[10px] font-extrabold text-slate-400 block mb-1 uppercase tracking-wider">Image Storage Destination</label>
                                                    <select wire:model.live="new_image_s3" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs">
                                                        <option value="0">Local Storage (public disk)</option>
                                                        <option value="1">Global S3 (.env config)</option>
                                                        <option value="2">Custom S3 Credentials</option>
                                                    </select>
                                                </div>

                                                {{-- Custom S3 Credentials (only when Custom S3 is selected) --}}
                                                @if((int)$new_image_s3 === 2)
                                                    <div class="p-4 bg-indigo-50/70 border border-indigo-200 rounded-xl space-y-3">
                                                        <span class="text-[10px] font-extrabold text-indigo-700 block uppercase tracking-wider">Custom S3 Credentials</span>
                                                        <div class="grid grid-cols-2 gap-3">
                                                            <div>
                                                                <label class="text-[9px] font-bold text-slate-500 block mb-1 uppercase tracking-wider">S3 Region</label>
                                                                <input type="text" wire:model="new_image_s3_region" placeholder="us-east-1" class="w-full px-3 py-1.5 bg-white border border-indigo-200 text-slate-800 rounded-lg focus:outline-none focus:border-indigo-500 text-xs">
                                                                @error('new_image_s3_region') <span class="text-xs text-red-500 font-semibold block mt-0.5">{{ $message }}</span> @enderror
                                                            </div>
                                                            <div>
                                                                <label class="text-[9px] font-bold text-slate-500 block mb-1 uppercase tracking-wider">S3 Bucket Name</label>
                                                                <input type="text" wire:model="new_image_s3_bucket_name" placeholder="my-bucket-name" class="w-full px-3 py-1.5 bg-white border border-indigo-200 text-slate-800 rounded-lg focus:outline-none focus:border-indigo-500 text-xs">
                                                                @error('new_image_s3_bucket_name') <span class="text-xs text-red-500 font-semibold block mt-0.5">{{ $message }}</span> @enderror
                                                            </div>
                                                            <div>
                                                                <label class="text-[9px] font-bold text-slate-500 block mb-1 uppercase tracking-wider">Access Key ID</label>
                                                                <input type="text" wire:model="new_image_s3_access_key_id" class="w-full px-3 py-1.5 bg-white border border-indigo-200 text-slate-800 rounded-lg focus:outline-none focus:border-indigo-500 text-xs">
                                                                @error('new_image_s3_access_key_id') <span class="text-xs text-red-500 font-semibold block mt-0.5">{{ $message }}</span> @enderror
                                                            </div>
                                                            <div>
                                                                <label class="text-[9px] font-bold text-slate-500 block mb-1 uppercase tracking-wider">Secret Access Key</label>
                                                                <input type="password" wire:model="new_image_s3_secret_access_key" class="w-full px-3 py-1.5 bg-white border border-indigo-200 text-slate-800 rounded-lg focus:outline-none focus:border-indigo-500 text-xs">
                                                                @error('new_image_s3_secret_access_key') <span class="text-xs text-red-500 font-semibold block mt-0.5">{{ $message }}</span> @enderror
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label class="text-[9px] font-bold text-slate-500 block mb-1 uppercase tracking-wider">CloudFront / S3 CDN URL Prefix <span class="font-normal text-slate-400">(optional)</span></label>
                                                            <input type="text" wire:model="new_cdn_url" placeholder="https://d1234abcd.cloudfront.net" class="w-full px-3 py-1.5 bg-white border border-indigo-200 text-slate-800 rounded-lg focus:outline-none focus:border-indigo-500 text-xs">
                                                            <span class="text-[9px] text-slate-400 block mt-0.5">Overrides the S3 endpoint with a CloudFront or custom CDN domain.</span>
                                                        </div>
                                                    </div>
                                                @elseif((int)$new_image_s3 === 1)
                                                    {{-- Global S3: just CDN prefix --}}
                                                    <div class="p-3 bg-sky-50/50 border border-sky-200 rounded-xl">
                                                        <label class="text-[9px] font-bold text-slate-500 block mb-1 uppercase tracking-wider">CloudFront / S3 CDN URL Prefix <span class="font-normal text-slate-400">(optional)</span></label>
                                                        <input type="text" wire:model="new_cdn_url" placeholder="https://d1234abcd.cloudfront.net" class="w-full px-3 py-1.5 bg-white border border-sky-200 text-slate-800 rounded-lg focus:outline-none focus:border-sky-400 text-xs">
                                                        <span class="text-[9px] text-slate-400 block mt-0.5">Overrides the global S3 endpoint with a CloudFront or CDN domain when serving images.</span>
                                                    </div>
                                                @endif

                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                    <div class="p-3 bg-slate-50/50 border border-slate-200 rounded-xl space-y-2">
                                                        <label class="text-[10px] font-extrabold text-slate-505 block uppercase tracking-wider">Thumbnail Image (Req)</label>
                                                        <input type="file" wire:model="new_thumbnail" class="w-full text-[9px] text-slate-505 file:py-1 file:px-2 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                                        @error('new_thumbnail') <span class="text-xs text-red-500 font-semibold block">{{ $message }}</span> @enderror
                                                        @if($new_thumbnail)
                                                            <img src="{{ $new_thumbnail->temporaryUrl() }}" class="w-12 h-12 object-cover rounded-lg border border-slate-200 mt-2 shadow-sm" alt="Thumbnail Preview">
                                                        @endif
                                                    </div>
                                                    <div class="p-3 bg-slate-50/50 border border-slate-200 rounded-xl space-y-2">
                                                        <label class="text-[10px] font-extrabold text-slate-505 block uppercase tracking-wider">Main Image (Req)</label>
                                                        <input type="file" wire:model="new_main" class="w-full text-[9px] text-slate-505 file:py-1 file:px-2 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                                        @error('new_main') <span class="text-xs text-red-500 font-semibold block">{{ $message }}</span> @enderror
                                                        @if($new_main)
                                                            <img src="{{ $new_main->temporaryUrl() }}" class="w-12 h-12 object-cover rounded-lg border border-slate-200 mt-2 shadow-sm" alt="Main Preview">
                                                        @endif
                                                    </div>
                                                    <div class="p-3 bg-slate-50/50 border border-slate-200 rounded-xl space-y-2">
                                                        <label class="text-[10px] font-extrabold text-slate-505 block uppercase tracking-wider">Zoom Image (Opt)</label>
                                                        <input type="file" wire:model="new_zoom" class="w-full text-[9px] text-slate-505 file:py-1 file:px-2 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                                        @error('new_zoom') <span class="text-xs text-red-500 font-semibold block">{{ $message }}</span> @enderror
                                                        @if($new_zoom)
                                                            <img src="{{ $new_zoom->temporaryUrl() }}" class="w-12 h-12 object-cover rounded-lg border border-slate-200 mt-2 shadow-sm" alt="Zoom Preview">
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Alt Text and Zoom Description inputs (independent of upload type) --}}
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                                <div>
                                                    <label class="text-[10px] font-extrabold text-slate-400 block mb-1 uppercase tracking-wider font-sans">Alt Text (All Upload Types)</label>
                                                    <input type="text" wire:model="new_image_alt" placeholder="Image description..."
                                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs">
                                                </div>
                                                <div>
                                                    <label class="text-[10px] font-extrabold text-slate-400 block mb-1 uppercase tracking-wider font-sans">Zoom Description (Modal Only)</label>
                                                    <input type="text" wire:model="new_zoom_label" placeholder="Zoom image description..."
                                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs">
                                                </div>
                                            </div>

                                            <div class="flex justify-end pt-1">
                                                <button type="button" wire:click.prevent="addImageSet" class="px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold rounded-xl text-xs transition duration-150 flex items-center gap-1.5 shadow-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                    Add Image Set
                                                </button>
                                            </div>
                                        </div>                                    </div>

                                    <!-- Horizontal Card 2: Download Settings -->
                                    <div class="p-6 bg-slate-50/50 border border-slate-200/60 rounded-3xl space-y-4">
                                        <div class="flex items-center justify-between border-b border-slate-200/60 pb-3">
                                            <div class="space-y-0.5">
                                                <h5 class="text-sm font-bold text-slate-800">2. Digital Download Configuration</h5>
                                                <p class="text-[11px] text-slate-400">Configure file storage settings and upload the digital product file.</p>
                                            </div>
                                            <label class="flex items-center gap-2 cursor-pointer bg-white px-3 py-1.5 border border-slate-200 rounded-xl shadow-sm hover:bg-slate-50 transition-colors">
                                                <input type="checkbox" wire:model.live="download_item" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                                <span class="text-xs font-bold text-slate-700">Digital Product</span>
                                            </label>
                                        </div>

                                        @if($download_item)
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div class="space-y-3">
                                                    <div>
                                                        <label class="text-xs font-bold text-slate-505 block mb-1 uppercase tracking-wider">File Storage Destination</label>
                                                        <select wire:model.live="download_s3" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                                            <option value="0">Local Storage (public disk)</option>
                                                            <option value="1">Global S3 Storage (.env config)</option>
                                                            <option value="2">Custom S3 Credentials</option>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">
                                                            Direct Download URL <span class="text-indigo-600 font-extrabold normal-case">(Overrides Uploaded File)</span>
                                                        </label>
                                                        <input type="url" wire:model="direct_download_url" placeholder="https://example.com/downloads/file.zip" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs font-mono">
                                                        @error('direct_download_url') <span class="text-xs text-red-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                                                        <p class="text-[10px] text-slate-400 font-medium mt-1">If entered, order download links will force-download directly from this URL and override local/S3 uploaded files.</p>
                                                    </div>

                                                    <div>
                                                        <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">
                                                            Digital Item Label / Badge Text
                                                        </label>
                                                        <input type="text" wire:model="download_label" placeholder="Digital Item (Instant Download)" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs font-medium">
                                                        @error('download_label') <span class="text-xs text-red-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                                                        <p class="text-[10px] text-slate-400 font-medium mt-1">Custom text displayed on item view page for digital download variants (defaults to &ldquo;Digital Item (Instant Download)&rdquo;).</p>
                                                    </div>

                                                    <div>
                                                        <label class="text-xs font-bold text-slate-550 block mb-1 uppercase tracking-wider">Upload Product File</label>
                                                        <input type="file" wire:model="downloadFile" class="w-full text-xs text-slate-550 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                                        @error('downloadFile') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                                        <div wire:loading wire:target="downloadFile" class="text-xs text-indigo-600 mt-1">Uploading temporary file...</div>
                                                    </div>

                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="text-xs font-bold text-slate-505 block mb-1 uppercase tracking-wider">Expiration Date</label>
                                                            <input type="datetime-local" wire:model="download_expiration" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs">
                                                            @error('download_expiration') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                                        </div>
                                                        <div>
                                                            <label class="text-xs font-bold text-slate-505 block mb-1 uppercase tracking-wider">Max Downloads</label>
                                                            <input type="number" wire:model="downloads_max_allowed" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs">
                                                            @error('downloads_max_allowed') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="flex flex-col justify-end">
                                                    <div class="h-full flex items-center justify-center border-2 border-dashed border-slate-200 rounded-2xl p-4 bg-slate-50/50">
                                                        <p class="text-xs text-slate-400 italic">No file uploaded yet.</p>
                                                    </div>
                                                </div>
                                            </div>

                                            @if($download_s3 == 2)
                                                <div class="p-5 bg-indigo-50/70 border border-indigo-200 rounded-2xl space-y-4 mt-4">
                                                    <span class="text-xs font-extrabold text-indigo-700 block uppercase tracking-wider">Custom Download S3 Credentials</span>
                                                    
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">S3 Region</label>
                                                            <input type="text" wire:model="download_s3_region" placeholder="us-east-1" class="w-full px-4 py-2 bg-white border border-indigo-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                                            @error('download_s3_region') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                                        </div>
                                                        <div>
                                                            <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">S3 Bucket Name</label>
                                                            <input type="text" wire:model="download_s3_bucket_name" placeholder="my-bucket-name" class="w-full px-4 py-2 bg-white border border-indigo-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                                            @error('download_s3_bucket_name') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>

                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">S3 Access Key ID</label>
                                                            <input type="text" wire:model="download_s3_access_key_id" class="w-full px-4 py-2 bg-white border border-indigo-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                                            @error('download_s3_access_key_id') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                                        </div>
                                                        <div>
                                                            <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">S3 Secret Access Key</label>
                                                            <input type="password" wire:model="download_s3_secret_access_key" class="w-full px-4 py-2 bg-white border border-indigo-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                                            @error('download_s3_secret_access_key') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>

                                                    <div class="pt-3 border-t border-indigo-100 grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">CDN URL Override <span class="font-normal">(Optional)</span></label>
                                                            <input type="text" wire:model="cdn_url" placeholder="https://cdn.mywebsite.com" class="w-full px-4 py-2 bg-white border border-indigo-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                                            <span class="text-[10px] text-slate-400 block mt-1">Overrides default S3 endpoint URL. Resolves files through this CDN domain.</span>
                                                        </div>
                                                        <div>
                                                            <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">S3 Folder / Prefix <span class="font-normal">(Optional)</span></label>
                                                            <input type="text" wire:model="s3_folder" placeholder="e.g., custom-folder" class="w-full px-4 py-2 bg-white border border-indigo-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                                            <span class="text-[10px] text-slate-400 block mt-1">Specify custom prefix for uploads. Defaults to 'downloads' and 'images'.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($download_s3 == 1)
                                                {{-- Global S3: just CDN + Folder --}}
                                                <div class="p-4 bg-sky-50/50 border border-sky-200 rounded-2xl grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                                    <div>
                                                        <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">CDN URL Override <span class="font-normal">(Optional)</span></label>
                                                        <input type="text" wire:model="cdn_url" placeholder="https://cdn.mywebsite.com" class="w-full px-4 py-2 bg-white border border-sky-200 text-slate-800 rounded-xl focus:outline-none focus:border-sky-400 text-sm">
                                                        <span class="text-[10px] text-slate-400 block mt-1">Overrides default S3 endpoint URL. Resolves files through this CDN domain.</span>
                                                    </div>
                                                    <div>
                                                        <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">S3 Folder / Prefix <span class="font-normal">(Optional)</span></label>
                                                        <input type="text" wire:model="s3_folder" placeholder="e.g., custom-folder" class="w-full px-4 py-2 bg-white border border-sky-200 text-slate-800 rounded-xl focus:outline-none focus:border-sky-400 text-sm">
                                                        <span class="text-[10px] text-slate-400 block mt-1">Specify custom prefix for uploads. Defaults to 'downloads' and 'images'.</span>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Inline validation error summary — visible even when scrolled down to images --}}
                            @if($errors->any())
                                <div class="flex items-start gap-3 p-4 bg-rose-50 border border-rose-200 rounded-2xl">
                                    <svg class="w-5 h-5 text-rose-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-bold text-rose-700 mb-1">Please fix the following before saving:</p>
                                        <ul class="list-disc list-inside space-y-0.5">
                                            @foreach($errors->all() as $error)
                                                <li class="text-xs text-rose-600">{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            <div class="flex gap-4 pt-2">
                                <button type="submit" wire:loading.attr="disabled" wire:target="saveVariant" class="px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-2xl shadow-md hover:opacity-90 flex items-center justify-center gap-2">
                                    <svg wire:loading wire:target="saveVariant" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Save Variant & Stock</span>
                                </button>
                                <button type="button" wire:click="cancelCreateVariant" class="px-6 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-2xl">Cancel</button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- Product Variants & Inventory Panel -->
                @if(!$isEditingVariant && !$isCreatingVariant)
                <div id="section-variants-list" class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-lg font-bold text-slate-900">Pricing | Variants | Images | Download Settings</h3>
                        <button wire:click="startCreateVariant" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-xl shadow-sm hover:opacity-90 transition duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add New Price | Variant
                        </button>
                    </div>

                    {{-- Variant Selector Label --}}
                    <div class="flex items-end gap-3 py-3 border-b border-slate-100">
                        <div class="flex-1">
                            <label for="variant_label_input" class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1.5">
                                Variant Selector Label
                                <span class="ml-1.5 text-[10px] font-normal text-slate-400 normal-case tracking-normal">Shown above the variant options on the product page (only when 2+ variants exist)</span>
                            </label>
                            <input
                                id="variant_label_input"
                                type="text"
                                wire:model="variant_label"
                                placeholder="Select Option:"
                                maxlength="255"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 text-sm font-medium transition"
                            >
                            @error('variant_label')
                                <span class="text-xs text-rose-500 font-semibold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <button
                            wire:click="updateVariantLabel"
                            wire:loading.attr="disabled"
                            wire:target="updateVariantLabel"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-2xl shadow-sm transition duration-150 shrink-0"
                        >
                            <svg wire:loading.remove wire:target="updateVariantLabel" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            <svg wire:loading wire:target="updateVariantLabel" class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                            </svg>
                            Save Label
                        </button>
                    </div>

                    @if($product->variants->isEmpty())
                        <p class="text-sm text-slate-500 text-center py-6">No variants created for this product yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-500">
                                <thead class="text-xs text-slate-400 uppercase bg-slate-50 rounded-xl">
                                    <tr>
                                        <th class="px-4 py-3">Actions</th>
                                        <th class="px-4 py-3">Thumb</th>
                                        <th class="px-4 py-3">SKU</th>
                                        <th class="px-4 py-3">Attributes</th>
                                        <th class="px-4 py-3">Pricing</th>
                                        <th class="px-4 py-3">Tax</th>
                                        <th class="px-4 py-3">Type</th>
                                        <th class="px-4 py-3">Stock Available</th>
                                        <th class="px-4 py-3">Reserved</th>
                                        <th class="px-4 py-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($product->variants as $variant)
                                        @php
                                            $qty = $variant->inventory ? $variant->inventory->quantity_available : 0;
                                            $res = $variant->inventory ? $variant->inventory->reserved_stock : 0;
                                            $net = $qty - $res;
                                            $thumbUrl = $variant->thumbnailImageUrl();
                                        @endphp
                                        <tr class="hover:bg-slate-50/50">

                                            {{-- Actions (front) --}}
                                            <td class="px-3 py-3.5">
                                                <div class="flex flex-col items-start gap-1.5">
                                                    <a href="#section-variants" wire:click="startEditVariant({{ $variant->id }})" class="w-full text-center px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 text-xs font-bold rounded-lg transition duration-150 whitespace-nowrap block">Edit &amp; Inventory</a>
                                                    <button wire:click="duplicateVariant({{ $variant->id }})" class="w-full px-3 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 text-xs font-bold rounded-lg transition duration-150">Duplicate</button>
                                                    <button onclick="confirm('Are you sure you want to delete this variant?') || event.stopImmediatePropagation()" wire:click="deleteVariant({{ $variant->id }})" class="w-full px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 text-xs font-bold rounded-lg transition duration-150">Delete</button>
                                                </div>
                                            </td>

                                            {{-- Thumbnail --}}
                                            <td class="px-3 py-3.5">
                                                <div class="flex flex-col gap-2 min-w-[140px]">
                                                    @forelse($variant->images as $img)
                                                        <div class="flex items-center gap-2">
                                                            <img src="{{ $img->thumbnailUrl() }}" alt="Variant image" class="w-10 h-10 object-cover rounded-lg border border-slate-200 shadow-sm">
                                                            <div class="flex flex-col gap-0.5 text-[9px] font-bold">
                                                                @if($img->search_image == 1)
                                                                    <span class="px-1 py-0.5 bg-indigo-50 text-indigo-700 rounded border border-indigo-200 whitespace-nowrap">🔍 Search Image</span>
                                                                @endif
                                                                @if($img->active == 1)
                                                                    <span class="px-1 py-0.5 bg-emerald-50 text-emerald-700 rounded border border-emerald-200 whitespace-nowrap">👁️ Active</span>
                                                                @else
                                                                    <span class="px-1 py-0.5 bg-slate-50 text-slate-400 rounded border border-slate-200 whitespace-nowrap">🚫 Inactive</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <div class="w-10 h-10 rounded-xl border border-dashed border-slate-300 bg-slate-50 flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                        </div>
                                                    @endforelse
                                                </div>
                                            </td>

                                            {{-- SKU --}}
                                            <td class="px-4 py-3.5 font-semibold text-slate-800 whitespace-nowrap">{{ $variant->sku }}</td>

                                            {{-- Attributes --}}
                                            <td class="px-4 py-3.5 text-xs">
                                                @php $attrs = json_decode($variant->attributes, true); @endphp
                                                @forelse($attrs ?? [] as $k => $v)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-800 mr-1 mb-1 font-medium">
                                                        <strong class="text-slate-500 mr-1">{{ $k }}:</strong> {{ $v }}
                                                    </span>
                                                @empty
                                                    <span class="text-slate-400">None</span>
                                                @endforelse
                                            </td>

                                            {{-- Pricing (stacked) --}}
                                            <td class="px-4 py-3.5">
                                                <div class="flex flex-col gap-0.5 text-xs leading-snug">
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="text-slate-400 w-24 shrink-0">Public Price:</span>
                                                        @if($variant->on_sale)
                                                            <span class="font-bold text-red-500">${{ number_format($variant->sale_price, 2) }}</span>
                                                            <span class="line-through text-slate-400">${{ number_format($variant->public_price, 2) }}</span>
                                                        @else
                                                            <span class="font-bold text-slate-800">${{ number_format($variant->public_price, 2) }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="text-slate-400 w-24 shrink-0">Wholesale:</span>
                                                        <span class="font-bold text-slate-800">${{ number_format($variant->wholesale_price, 2) }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="text-slate-400 w-24 shrink-0">Retail Fee:</span>
                                                        <span class="font-semibold text-indigo-600">${{ number_format($variant->variant_fee, 2) }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="text-slate-400 w-24 shrink-0">Wholesale Fee:</span>
                                                        <span class="font-semibold text-indigo-600">${{ number_format($variant->wholesale_variant_fee, 2) }}</span>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- Tax --}}
                                            <td class="px-4 py-3.5">
                                                @if($variant->charge_tax ?? 1)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Taxable
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Exempt
                                                    </span>
                                                @endif
                                            </td>

                                            {{-- Type (stacked: Shipping / Download / Event / Personalization) --}}
                                            <td class="px-4 py-3.5">
                                                <div class="flex flex-col gap-1 text-xs">
                                                    {{-- Shipping --}}
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="text-slate-400 w-16 shrink-0">Shipping:</span>
                                                        @if($variant->shipping)
                                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                                                <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span> Yes
                                                            </span>
                                                        @else
                                                            <span class="text-slate-400">No</span>
                                                        @endif
                                                    </div>
                                                    {{-- Download --}}
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="text-slate-400 w-16 shrink-0">Download:</span>
                                                        @if($variant->download_item)
                                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-violet-50 text-violet-700 border border-violet-100">
                                                                <span class="h-1.5 w-1.5 rounded-full bg-violet-500"></span> Yes
                                                            </span>
                                                        @else
                                                            <span class="text-slate-400">No</span>
                                                        @endif
                                                    </div>
                                                    {{-- Event --}}
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="text-slate-400 w-16 shrink-0">Event:</span>
                                                        @if($variant->is_event)
                                                            @php $evt = $variant->eventDetails; @endphp
                                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[10px] font-bold border"
                                                                  style="background-color: {{ $evt->label_background ?? '#4f46e5' }}20; color: {{ $evt->label_background ?? '#4f46e5' }}; border-color: {{ $evt->label_background ?? '#4f46e5' }}40;">
                                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                                {{ $evt ? \Carbon\Carbon::parse($evt->event_start_date)->format('M j') : 'Event' }}
                                                            </span>
                                                        @else
                                                            <span class="text-slate-400">No</span>
                                                        @endif
                                                    </div>
                                                    {{-- Personalization --}}
                                                    <div class="flex items-[flex-start] gap-1.5">
                                                        <span class="text-slate-400 w-24 shrink-0 mt-0.5">Personalization:</span>
                                                        @if($variant->personalization_active)
                                                            <div class="flex flex-col gap-0.5">
                                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> Active ({{ $variant->personalization_fee > 0 ? '+$' . number_format($variant->personalization_fee, 2) : 'Free' }})
                                                                </span>
                                                                @if($variant->personalization_label)
                                                                    <span class="text-[10px] font-semibold text-slate-700 truncate max-w-[180px]" title="{{ $variant->personalization_label }}">
                                                                        Label: {{ $variant->personalization_label }}
                                                                    </span>
                                                                @endif
                                                                @if($variant->personalization_details_label)
                                                                    <span class="text-[9px] text-slate-400 truncate max-w-[180px]" title="{{ $variant->personalization_details_label }}">
                                                                        Details: {{ $variant->personalization_details_label }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <span class="text-slate-400">No</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- Stock --}}
                                            <td class="px-4 py-3.5">
                                                @if($net > 0)
                                                    <span class="font-bold text-emerald-600">{{ $qty }}</span>
                                                @else
                                                    <span class="font-bold text-red-500">{{ $qty }} (Out of Stock)</span>
                                                @endif
                                            </td>

                                            {{-- Reserved --}}
                                            <td class="px-4 py-3.5">{{ $res }}</td>

                                            {{-- Actions (end) --}}
                                            <td class="px-4 py-3.5 text-right">
                                                <div class="flex flex-col items-end gap-1.5">
                                                    <button wire:click="startEditVariant({{ $variant->id }})" class="w-full px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 text-xs font-bold rounded-lg transition duration-150 whitespace-nowrap">Edit &amp; Inventory</button>
                                                    <button wire:click="duplicateVariant({{ $variant->id }})" class="w-full px-3 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 text-xs font-bold rounded-lg transition duration-150">Duplicate</button>
                                                    <button onclick="confirm('Are you sure you want to delete this variant?') || event.stopImmediatePropagation()" wire:click="deleteVariant({{ $variant->id }})" class="w-full px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 text-xs font-bold rounded-lg transition duration-150">Delete</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                @endif
</div>
