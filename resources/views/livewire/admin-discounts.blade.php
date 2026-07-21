<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Wrapper Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-3 space-y-2">
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-1">
                    <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 px-3">Shop Administration</h2>
                    
                    <a href="{{ route('admin.ecommerce.products') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 hover:bg-slate-50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Products
                    </a>

                    <a href="{{ route('admin.ecommerce.categories') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 hover:bg-slate-50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                        Categories
                    </a>

                    <a href="{{ route('admin.ecommerce.brands') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 hover:bg-slate-50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                        Brands
                    </a>

                    <a href="{{ route('admin.ecommerce.orders') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 hover:bg-slate-50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        Orders
                    </a>

                    <a href="{{ route('admin.ecommerce.inventory') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 hover:bg-slate-50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Inventory
                    </a>

                    <a href="{{ route('admin.discounts.index') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm bg-indigo-50 text-indigo-600 transition duration-150">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm0 0h4m-4 0h-4m0 0v13m0 13h12"/>
                        </svg>
                        Discounts
                    </a>
                </div>
            </div>

            <!-- Main Panel Content -->
            <div class="lg:col-span-9 space-y-8">
                <!-- Status/Success Notifications -->
                @if(session()->has('status'))
                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center gap-3 text-emerald-800 text-sm font-semibold animate-fade-in">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Title & Buttons Row -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Discounts & Coupons</h1>
                        <p class="text-sm text-slate-500 mt-1">Manage standard store discounts, BOGO offers, and custom gift certificates.</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.discounts.config') }}" wire:navigate class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl font-bold text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 transition duration-150">
                            Configure Store
                        </a>
                        <a href="{{ route('admin.discounts.create') }}" wire:navigate class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl font-bold text-xs bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-150 transition duration-150">
                            (+) Add Discount
                        </a>
                    </div>
                </div>

                <!-- Custom Tabs -->
                <div class="flex border-b border-slate-150 gap-4">
                    <button wire:click="$set('activeTab', 'standard')" class="pb-3 text-sm font-bold transition-all relative {{ $activeTab === 'standard' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-slate-400 hover:text-slate-600' }}">
                        Standard Discounts & Promo Codes
                    </button>
                    <button wire:click="$set('activeTab', 'certificates')" class="pb-3 text-sm font-bold transition-all relative {{ $activeTab === 'certificates' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-slate-400 hover:text-slate-600' }}">
                        Single-Use Gift Certificates
                    </button>
                </div>

                <!-- Filter Row -->
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex items-center justify-between gap-4">
                    <div class="relative flex-1 max-w-md">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input type="text" wire:model.live="search" placeholder="Search by name or coupon code..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 text-slate-700 rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                    </div>
                </div>

                <!-- Listing Table -->
                <div class="bg-white border border-slate-100 rounded-3xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-left text-sm text-slate-700">
                            <thead class="bg-slate-50 text-xs font-bold text-slate-400 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">Name</th>
                                    <th class="px-6 py-4">Discount Type</th>
                                    <th class="px-6 py-4">Trigger Code</th>
                                    <th class="px-6 py-4">Value</th>
                                    <th class="px-6 py-4">Expiration</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($discounts as $disc)
                                    <tr class="hover:bg-slate-50/50 transition">
                                        <td class="px-6 py-4 font-bold text-slate-900">
                                            <a href="{{ route('admin.discounts.edit', $disc->id) }}" wire:navigate class="hover:text-indigo-600">
                                                {{ $disc->name }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-slate-500 text-xs font-semibold">
                                            {{ $disc->discountType->name }}
                                        </td>
                                        <td class="px-6 py-4 text-slate-800 font-mono text-xs">
                                            @if($disc->code)
                                                <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-lg">{{ $disc->code }}</span>
                                            @else
                                                <span class="text-slate-400">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 font-bold text-slate-900 text-xs">
                                            @if($disc->value_type == 2)
                                                {{ $disc->value }}% OFF
                                            @else
                                                ${{ number_format($disc->value, 2) }} OFF
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-slate-500 text-xs">
                                            {{ $disc->expiration_date ? $disc->expiration_date->format('M j, Y') : 'Never Expires' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($disc->is_active == 1)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                    <span class="h-1 w-1 rounded-full bg-emerald-500"></span> Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-50 text-slate-500 border border-slate-200">
                                                    <span class="h-1 w-1 rounded-full bg-slate-400"></span> Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-1.5">
                                                <a href="{{ route('admin.discounts.edit', $disc->id) }}" wire:navigate class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-100 transition-colors">
                                                    Edit
                                                </a>
                                                <button wire:click="deleteDiscount({{ $disc->id }})" wire:confirm="Are you sure you want to delete this discount?" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-100 transition-colors">
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                            No discount records found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div>
                    {{ $discounts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
