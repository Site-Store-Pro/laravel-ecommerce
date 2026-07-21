<div class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('admin.discounts.index') }}" wire:navigate class="p-2 rounded-xl border border-slate-200 bg-white text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Discount Settings</h1>
                <p class="text-sm text-slate-500 mt-1">Configure which discounts are enabled store-wide and define order-level stacking rules.</p>
            </div>
        </div>

        <form wire:submit.prevent="save" class="space-y-6">
            <!-- Order Stacking Configuration Card -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                <h3 class="text-base font-bold text-slate-800 pb-3 border-b border-slate-100 uppercase tracking-wider text-xs">Stacking & Restrictions</h3>
                
                <div>
                    <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Allow Multiple Order-Level Discount Types?</label>
                    <select wire:model="allow_multiple_order_discounts" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                        <option value="1">Yes, Allow Multiple Order Discounts (Cumulative)</option>
                        <option value="0">No, Restrict to One Order Discount At A Time (Single Selection)</option>
                    </select>
                    <span class="text-[10px] text-slate-400 block mt-2 leading-relaxed">
                        Note: This setting applies only to Coupon Codes, General Order, Preferred Customers, and New Customer discount types. Item-specific and quantity discounts will always run regardless.
                    </span>
                </div>
            </div>

            <!-- Discount Toggles Card -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                <h3 class="text-base font-bold text-slate-800 pb-3 border-b border-slate-100 uppercase tracking-wider text-xs">Active Discount Types</h3>
                
                <div class="divide-y divide-slate-100">
                    <div class="flex items-center justify-between py-4">
                        <div>
                            <span class="text-sm font-bold text-slate-900 block">Category | SubCat | Styles (Collections)</span>
                            <span class="text-xs text-slate-400">Enables specific item discounts associated with product categories.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="category_discounts" value="1" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between py-4">
                        <div>
                            <span class="text-sm font-bold text-slate-900 block">Item-Specific Discounts</span>
                            <span class="text-xs text-slate-400">Enables discounts targeted at specific product items.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="item_specific" value="1" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between py-4">
                        <div>
                            <span class="text-sm font-bold text-slate-900 block">Quantity-Based Breaks</span>
                            <span class="text-xs text-slate-400">Enables variant quantity-range discount tables defined in the product manager.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="quantity_based" value="1" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between py-4">
                        <div>
                            <span class="text-sm font-bold text-slate-900 block">Coupon & Gift Certificates</span>
                            <span class="text-xs text-slate-400">Enables promotional checkout codes and single-use gift cards.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="coupon_codes" value="1" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between py-4">
                        <div>
                            <span class="text-sm font-bold text-slate-900 block">General Order Discounts</span>
                            <span class="text-xs text-slate-400">Enables store-wide cart subtotal percentage or amount deductions.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="value_based" value="1" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between py-4">
                        <div>
                            <span class="text-sm font-bold text-slate-900 block">Preferred Customers</span>
                            <span class="text-xs text-slate-400">Enables automatic profile discount associations for approved accounts.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="preferred_customers" value="1" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between py-4">
                        <div>
                            <span class="text-sm font-bold text-slate-900 block">New Customers</span>
                            <span class="text-xs text-slate-400">Enables promotional discount rules applied only to 1st orders.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="new_customer_discount" value="1" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center justify-center px-6 py-3 rounded-2xl font-bold text-sm bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-150 transition duration-150">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
