<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Wrapper Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-3 space-y-2">
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-1">
                    <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 px-3">Shop Administration</h2>
                    
                    <a href="{{ route('admin.ecommerce.pending-orders') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 hover:bg-slate-50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pending Orders
                    </a>

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

                    <a href="{{ route('admin.ecommerce.brands') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm bg-indigo-50 text-indigo-600 transition duration-150">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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


                </div>
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-9 space-y-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Brands Management</h1>
                        <p class="text-slate-500 text-sm mt-1">Manage e-commerce product manufacturing and trade brands.</p>
                    </div>
                    <button wire:click="startCreate" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-md flex items-center gap-2 transition duration-150">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Brand
                    </button>
                </div>

                <!-- Flash messages -->
                @if(session()->has('status'))
                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center gap-3 text-emerald-800 text-sm font-semibold animate-fade-in">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
                    <!-- Brands List -->
                    <div class="md:col-span-7 bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 border-b border-slate-100">
                            <h2 class="text-base font-bold text-slate-900">Registered Brands</h2>
                            <!-- Search input -->
                            <div class="relative w-full sm:w-48">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </span>
                                <input type="text" wire:model.live="search" placeholder="Search..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 text-xs rounded-xl focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>

                        @if($brands->isEmpty())
                            <div class="text-center py-12 text-slate-400 text-sm">
                                No brands found.
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($brands as $brand)
                                    <div class="flex items-center justify-between p-4 bg-slate-50 border border-slate-100 rounded-2xl hover:bg-slate-100/55 transition duration-150">
                                        <div class="flex items-center gap-4">
                                            <!-- Brand Logo Image -->
                                            <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200/60 overflow-hidden flex items-center justify-center text-slate-500 font-bold shrink-0">
                                                @if($brand->brand_icon_direct_url)
                                                    <img src="{{ $brand->brand_icon_direct_url }}" alt="{{ $brand->name }}" class="w-full h-full object-contain p-1">
                                                @elseif($brand->brand_icon)
                                                    @php
                                                        $logoUrl = $brand->brand_logo_s3 == 1
                                                            ? Storage::disk('s3')->url($brand->brand_icon)
                                                            : Storage::disk('public')->url($brand->brand_icon);
                                                    @endphp
                                                    <img src="{{ $logoUrl }}" alt="{{ $brand->name }}" class="w-full h-full object-contain p-1">
                                                @else
                                                    <span class="capitalize text-lg">{{ substr($brand->name, 0, 1) }}</span>
                                                @endif
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                                                    {{ $brand->name }}
                                                    <a href="{{ route('shop.brand', ['brand_slug' => $brand->slug]) }}" target="_blank" class="text-slate-400 hover:text-slate-600 transition" title="View Brand on Storefront">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                        </svg>
                                                    </a>
                                                    <button type="button" wire:click="showProducts({{ $brand->id }}, '{{ addslashes($brand->name) }}')" class="text-[10px] bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full border border-indigo-100 font-bold transition focus:outline-none" title="View products under this brand">
                                                        {{ $brand->products_count }} {{ Str::plural('product', $brand->products_count) }}
                                                    </button>
                                                    @if($brand->is_visible_in_menu)
                                                        <span class="text-[10px] bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-0.5 rounded-full font-bold">Visible in Menu</span>
                                                    @else
                                                        <span class="text-[10px] bg-slate-100 text-slate-500 border border-slate-200 px-2 py-0.5 rounded-full font-bold">Hidden from Menu</span>
                                                    @endif
                                                </h3>
                                                <p class="text-xs text-slate-400 font-medium">Slug: {{ $brand->slug }} | Sort: {{ $brand->sort_order }}</p>
                                                @if($brand->description)
                                                    <p class="text-xs text-slate-500 mt-1 line-clamp-1 max-w-sm">{{ $brand->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button wire:click="editBrand({{ $brand->id }})" class="inline-flex items-center gap-1.5 justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:text-slate-900 transition-all shadow-sm">
                                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-2.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                </svg>
                                                Edit
                                            </button>
                                            <button wire:click="deleteBrand({{ $brand->id }})" wire:confirm="Are you sure you want to delete this brand? Products linked to this brand will remain but have no brand assigned." class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition duration-150" title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Pagination --}}
                            @if($brands->hasPages())
                                <div class="mt-4 px-1">
                                    {{ $brands->links() }}
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Edit / Add Side Panel -->
                    <div class="md:col-span-5 bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                        @if($selectedBrandIdForProducts)
                            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                                <div>
                                    <h2 class="text-base font-bold text-slate-900 font-sans">Assigned Products</h2>
                                    <p class="text-[11px] text-slate-400 mt-0.5 font-medium">Brand: <span class="font-bold text-slate-600">{{ $selectedBrandName }}</span></p>
                                </div>
                                <button wire:click="closeProductsList" class="text-slate-400 hover:text-slate-600 focus:outline-none p-1.5 hover:bg-slate-50 rounded-lg transition">✕</button>
                            </div>

                            @if(empty($brandProducts))
                                <p class="text-xs text-slate-400 text-center py-12">No products assigned to this brand.</p>
                            @else
                                <div class="space-y-2.5 max-h-[32rem] overflow-y-auto pr-1">
                                    @foreach($brandProducts as $prod)
                                        <div class="flex items-center justify-between p-3.5 bg-slate-50 border border-slate-200/60 rounded-xl hover:bg-slate-100/50 transition">
                                            <span class="text-xs font-bold text-slate-700 truncate max-w-[200px]" title="{{ $prod['title'] }}">{{ $prod['title'] }}</span>
                                            <a href="{{ route('admin.ecommerce.product-edit', $prod['id']) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-lg text-xs font-semibold shadow-sm transition">
                                                <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-2.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                </svg>
                                                Edit
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @elseif($isEditing || $isCreating)
                            <h2 class="text-base font-bold text-slate-900 pb-4 border-b border-slate-100 mb-6">
                                {{ $isEditing ? 'Edit Brand' : 'Create New Brand' }}
                            </h2>

                            <form wire:submit.prevent="saveBrand" class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Brand Name</label>
                                    <input type="text" wire:model.live="name" placeholder="e.g. Antigravity Gear" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:outline-none focus:border-indigo-500 @error('name') border-rose-500 @enderror">
                                    @error('name') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Slug</label>
                                    <input type="text" wire:model="slug" placeholder="e.g. antigravity-gear" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:outline-none focus:border-indigo-500 @error('slug') border-rose-500 @enderror">
                                    @error('slug') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Description</label>
                                    <textarea wire:model="description" rows="3" placeholder="Brief details about the brand..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:outline-none focus:border-indigo-500 @error('description') border-rose-500 @enderror"></textarea>
                                    @error('description') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Sort Order</label>
                                        <input type="number" wire:model="sort_order" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:outline-none focus:border-indigo-500 @error('sort_order') border-rose-500 @enderror">
                                        @error('sort_order') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Brand Logo / Icon</label>


                                    {{-- Storage Mode --}}
                                    <div class="mb-3">
                                        <label class="block text-xs font-semibold text-slate-500 mb-1">Storage Destination</label>
                                        <select wire:model.live="brand_logo_s3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:outline-none focus:border-indigo-500">
                                            <option value="0">Local Public Storage</option>
                                            <option value="1">Default S3 (.env credentials)</option>
                                            <option value="2">Custom S3 (own credentials)</option>
                                        </select>
                                    </div>

                                    {{-- CDN prefix — shown for S3 modes --}}
                                    @if($brand_logo_s3 >= 1)
                                    <div class="mb-3">
                                        <label class="block text-xs font-semibold text-slate-500 mb-1">CDN / CloudFront URL Prefix <span class="font-normal text-slate-400">(optional)</span></label>
                                        <input type="text" wire:model="brand_logo_cdn_url" placeholder="https://dxxxxxx.cloudfront.net" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:outline-none focus:border-indigo-500 @error('brand_logo_cdn_url') border-rose-500 @enderror">
                                        <p class="text-[10px] text-slate-400 mt-1">Prepended to the stored file path to build the public URL.</p>
                                        @error('brand_logo_cdn_url') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    @endif

                                    {{-- Custom S3 credentials — shown for mode=2 --}}
                                    @if($brand_logo_s3 == 2)
                                    <div class="space-y-2 mb-3 p-3 bg-amber-50 border border-amber-200 rounded-xl">
                                        <p class="text-[10px] font-bold text-amber-700 uppercase tracking-wider">Custom S3 Credentials</p>
                                        <input type="text" wire:model="brand_logo_region" placeholder="Region (e.g. us-east-1)" class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-800 text-xs rounded-lg focus:outline-none focus:border-indigo-500">
                                        <input type="text" wire:model="brand_logo_bucket_name" placeholder="Bucket Name" class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-800 text-xs rounded-lg focus:outline-none focus:border-indigo-500">
                                        <input type="text" wire:model="brand_logo_access_key_id" placeholder="Access Key ID" class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-800 text-xs rounded-lg focus:outline-none focus:border-indigo-500">
                                        <input type="password" wire:model="brand_logo_secret_access_key" placeholder="Secret Access Key" class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-800 text-xs rounded-lg focus:outline-none focus:border-indigo-500">
                                    </div>
                                    @endif

                                    {{-- Current logo preview --}}
                                    @if($brand_icon || $brand_icon_direct_url)
                                    @php
                                        $logoPreview = $brand_icon_direct_url ?: (
                                            $brand_logo_s3 == 1
                                                ? (class_exists('\\Illuminate\\Support\\Facades\\Storage') ? \Illuminate\Support\Facades\Storage::disk('s3')->url($brand_icon) : $brand_icon)
                                                : ($brand_logo_s3 == 0 ? \Illuminate\Support\Facades\Storage::disk('public')->url($brand_icon) : $brand_icon)
                                        );
                                    @endphp
                                    <div class="mb-2 flex items-center gap-3 p-2 bg-slate-50 border border-slate-200 rounded-xl">
                                        <img src="{{ $logoPreview }}" class="w-12 h-12 object-contain bg-white border border-slate-150 rounded-lg p-1" alt="Current Logo">
                                        <div class="text-[10px] text-slate-400 font-mono truncate max-w-[200px]">{{ basename($brand_icon_direct_url ?: $brand_icon) }}</div>
                                    </div>
                                    @endif

                                    {{-- Direct URL option --}}
                                    <div class="mb-3">
                                        <label class="block text-xs font-semibold text-slate-500 mb-1">Direct Image URL <span class="font-normal text-slate-400">(bypasses file upload)</span></label>
                                        <input type="text" wire:model="brand_icon_direct_url" placeholder="https://example.com/logo.png" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:outline-none focus:border-indigo-500 @error('brand_icon_direct_url') border-rose-500 @enderror">
                                        <p class="text-[10px] text-slate-400 mt-1">If set, this URL is used as the brand icon — no upload required.</p>
                                        @error('brand_icon_direct_url') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- File upload --}}
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 mb-1">Upload File <span class="font-normal text-slate-400">(overrides direct URL if provided)</span></label>
                                        <input type="file" wire:model="logoFile" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:outline-none focus:border-indigo-500">
                                        <p class="text-[10px] text-slate-400 mt-1 font-medium">PNG or JPG, max 2 MB. Leave blank to keep existing logo.</p>
                                        @error('logoFile') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Brand Website URL</label>
                                    <input type="text" wire:model="brand_url" placeholder="e.g. https://antigravitygear.local" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:outline-none focus:border-indigo-500 @error('brand_url') border-rose-500 @enderror">
                                    @error('brand_url') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-200 rounded-xl">
                                        <input type="checkbox" id="is_visible_in_menu" wire:model="is_visible_in_menu" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                                        <label for="is_visible_in_menu" class="text-xs font-bold text-slate-700 cursor-pointer">
                                            Visible in Menus &amp; Shop Filters
                                        </label>
                                    </div>

                                    <div class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-200 rounded-xl">
                                        <input type="checkbox" id="show_image" wire:model="show_image" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                                        <label for="show_image" class="text-xs font-bold text-slate-700 cursor-pointer">
                                            Show Image in Menus
                                        </label>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 pt-4">
                                    <button type="submit" class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-md transition duration-150">
                                        Save Brand
                                    </button>
                                    <button type="button" wire:click="cancel" class="px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-sm rounded-xl transition duration-150">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="text-center py-12 text-slate-400 text-sm">
                                Select a brand to edit, or click <strong>Add Brand</strong> to register a new one.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
