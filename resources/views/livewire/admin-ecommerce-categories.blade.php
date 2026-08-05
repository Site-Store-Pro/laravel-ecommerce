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

                    <a href="{{ route('admin.ecommerce.categories') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm bg-indigo-50 text-indigo-600 transition duration-150">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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


                </div>
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-9 space-y-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">Categories Management</h1>
                        <p class="text-slate-500 text-sm mt-1">Organize products into recursive nested catalog collections.</p>
                    </div>
                    <button wire:click="startCreate" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-md flex items-center gap-2 transition duration-150">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Category
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
                    <!-- Category Tree Structure -->
                    <div class="md:col-span-7 bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 border-b border-slate-100">
                            <h2 class="text-base font-bold text-slate-900">Categories Tree</h2>
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

                        @if($categoryTree->isEmpty())
                            <div class="text-center py-12 text-slate-400 text-sm">
                                No categories found.
                            </div>
                        @else
                            <div class="divide-y divide-slate-150">
                                @foreach($categoryTree as $node)
                                    @include('livewire.category-tree-node', ['node' => $node, 'depth' => 0])
                                @endforeach
                            </div>

                            {{-- Pagination --}}
                            @if($categories->hasPages())
                                <div class="mt-4 px-1">
                                    {{ $categories->links() }}
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Add / Edit Form Panel -->
                    <div class="md:col-span-5 bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                        @if($selectedCategoryIdForProducts)
                            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                                <div>
                                    <h2 class="text-base font-bold text-slate-900 font-sans">Assigned Products</h2>
                                    <p class="text-[11px] text-slate-400 mt-0.5 font-medium">Category: <span class="font-bold text-slate-600">{{ $selectedCategoryName }}</span></p>
                                </div>
                                <button wire:click="closeProductsList" class="text-slate-400 hover:text-slate-600 focus:outline-none p-1.5 hover:bg-slate-50 rounded-lg transition">✕</button>
                            </div>

                            @if(empty($categoryProducts))
                                <p class="text-xs text-slate-400 text-center py-12">No products assigned directly or indirectly to this category.</p>
                            @else
                                <div class="space-y-2.5 max-h-[32rem] overflow-y-auto pr-1">
                                    @foreach($categoryProducts as $prod)
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
                        @elseif($isCreating || $isEditing)
                            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-4 mb-6">
                                {{ $isEditing ? 'Edit Category' : 'Create Category' }}
                            </h2>

                            <form wire:submit.prevent="saveCategory" class="space-y-4">
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider font-sans">Category Name</label>
                                    <input type="text" wire:model.live="name" placeholder="e.g. Computers" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:outline-none focus:border-indigo-500">
                                    @error('name') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider font-sans">SEO Slug</label>
                                    <input type="text" wire:model="slug" placeholder="e.g. computers" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:outline-none focus:border-indigo-500 font-mono">
                                    @error('slug') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider font-sans">Description <span class="normal-case text-slate-300 font-normal">(shown on catalog page)</span></label>
                                    <textarea wire:model="description" rows="3" placeholder="Brief description shown as the subtitle when this category is filtered on the shop..." class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:outline-none focus:border-indigo-500 resize-none"></textarea>
                                    @error('description') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider font-sans">Category Image</label>

                                    {{-- Storage Mode --}}
                                    <div class="mb-3">
                                        <label class="block text-xs font-semibold text-slate-500 mb-1">Storage Destination</label>
                                        <select wire:model.live="category_image_s3" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:outline-none focus:border-indigo-500">
                                            <option value="0">Local Public Storage</option>
                                            <option value="1">Default S3 (.env credentials)</option>
                                            <option value="2">Custom S3 (own credentials)</option>
                                        </select>
                                    </div>

                                    {{-- CDN prefix — shown for S3 modes --}}
                                    @if($category_image_s3 >= 1)
                                    <div class="mb-3">
                                        <label class="block text-xs font-semibold text-slate-500 mb-1">CDN / CloudFront URL Prefix <span class="font-normal text-slate-400">(optional)</span></label>
                                        <input type="text" wire:model="category_image_cdn_url" placeholder="https://dxxxxxx.cloudfront.net" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 text-xs rounded-xl focus:outline-none focus:border-indigo-500 @error('category_image_cdn_url') border-rose-500 @enderror">
                                        <p class="text-[10px] text-slate-400 mt-1">Prepended to the stored file path to build the public URL.</p>
                                        @error('category_image_cdn_url') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    @endif

                                    {{-- Custom S3 credentials — shown for mode=2 --}}
                                    @if($category_image_s3 == 2)
                                    <div class="space-y-2 mb-3 p-3 bg-amber-50 border border-amber-200 rounded-xl">
                                        <p class="text-[10px] font-bold text-amber-700 uppercase tracking-wider">Custom S3 Credentials</p>
                                        <input type="text" wire:model="category_image_region" placeholder="Region (e.g. us-east-1)" class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-800 text-xs rounded-lg focus:outline-none focus:border-indigo-500">
                                        <input type="text" wire:model="category_image_bucket_name" placeholder="Bucket Name" class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-800 text-xs rounded-lg focus:outline-none focus:border-indigo-500">
                                        <input type="text" wire:model="category_image_access_key_id" placeholder="Access Key ID" class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-800 text-xs rounded-lg focus:outline-none focus:border-indigo-500">
                                        <input type="password" wire:model="category_image_secret_access_key" placeholder="Secret Access Key" class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-800 text-xs rounded-lg focus:outline-none focus:border-indigo-500">
                                    </div>
                                    @endif

                                    {{-- Current image preview --}}
                                    @if($category_image || $category_image_direct_url)
                                    <div class="mb-2 flex items-center gap-2 mt-1">
                                        <img src="{{ $category_image_direct_url ?: $category_image }}" alt="Category Preview" class="w-12 h-12 object-cover rounded-lg border border-slate-200 shadow-sm">
                                        <span class="text-2xs text-slate-400 truncate max-w-[200px]">{{ basename($category_image_direct_url ?: $category_image) }}</span>
                                    </div>
                                    @endif

                                    {{-- Direct URL option --}}
                                    <div class="mb-3">
                                        <label class="block text-xs font-semibold text-slate-500 mb-1">Direct Image URL <span class="font-normal text-slate-400">(bypasses file upload)</span></label>
                                        <input type="text" wire:model="category_image_direct_url" placeholder="https://example.com/category.jpg" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 text-xs rounded-xl focus:outline-none focus:border-indigo-500 @error('category_image_direct_url') border-rose-500 @enderror">
                                        <p class="text-[10px] text-slate-400 mt-1">If set, used as the image — no upload required.</p>
                                        @error('category_image_direct_url') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- File upload --}}
                                    <div class="space-y-1">
                                        <label class="block text-xs font-semibold text-slate-500 mb-1">Upload File <span class="font-normal text-slate-400">(overrides direct URL if provided)</span></label>
                                        <input type="file" wire:model="category_image_file" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700">
                                        @error('category_image') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        @error('category_image_file') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>


                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider font-sans">Parent Category</label>
                                    <select wire:model="parent_id" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:outline-none focus:border-indigo-500">
                                        <option value="">Top-Level Category (None)</option>
                                        @foreach($parentOptions as $parent)
                                            <option value="{{ $parent->id }}">
                                                {{ $parent->name }} ({{ $parent->slug }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parent_id') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider font-sans">Display Sort Order</label>
                                    <input type="number" wire:model="sort_order" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:outline-none focus:border-indigo-500">
                                    @error('sort_order') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="flex items-center gap-2.5 cursor-pointer">
                                        <input type="checkbox" wire:model="is_visible_in_menu" class="rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4 bg-slate-50 border-slate-300">
                                        <span class="text-xs font-bold text-slate-700 select-none">Show in Public Nav Menu</span>
                                    </label>
                                    @error('is_visible_in_menu') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                {{-- ─── Translations Section ──────────────────────────────────────────────── --}}
                                @if($activeLanguages->isNotEmpty() && $categoryId)
                                <div x-data="{ tlOpen: false }" class="border-t border-slate-100 dark:border-slate-700 pt-4 mt-6">
                                    <button type="button" @click="tlOpen = !tlOpen"
                                            class="flex items-center justify-between w-full text-left">
                                        <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                                            Translations
                                        </span>
                                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="tlOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>

                                    <div x-show="tlOpen" x-cloak class="mt-4 space-y-4">
                                        {{-- Language selector pills --}}
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($activeLanguages as $lang)
                                                <button type="button"
                                                        wire:click="selectTlLang({{ $lang->id }})"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border transition
                                                            {{ $tlLangId === $lang->id
                                                                ? 'bg-indigo-600 text-white border-indigo-600 shadow'
                                                                : 'bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-600 hover:border-indigo-400' }}">
                                                    <span class="fi fi-{{ strtolower($lang->flag_emoji) }}" style="width:1em;height:0.75em;font-size:1rem;"></span>
                                                    {{ $lang->name }}
                                                </button>
                                            @endforeach
                                        </div>

                                        @if($tlLangId > 0)
                                            <div class="space-y-3 bg-slate-50 dark:bg-slate-900/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
                                                {{-- per-field inputs --}}
                                                <div>
                                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Name (Default: "{{ $name }}")</label>
                                                    <input type="text" wire:model="tlBuffer.name"
                                                           placeholder="Translation..."
                                                           class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-lg focus:outline-none focus:border-indigo-500">
                                                </div>
                                                <div>
                                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Description (Default: "{{ strip_tags($description) }}")</label>
                                                    <textarea wire:model="tlBuffer.description" rows="3"
                                                           placeholder="Translation..."
                                                           class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-lg focus:outline-none focus:border-indigo-500 resize-none"></textarea>
                                                </div>

                                                <div class="flex gap-2 pt-1">
                                                    <button type="button" wire:click="aiTlCategory({{ $categoryId }})"
                                                            wire:loading.attr="disabled" wire:target="aiTlCategory({{ $categoryId }})"
                                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-violet-50 hover:bg-violet-100 text-violet-600 dark:bg-violet-900/30 dark:text-violet-400 text-xs font-bold rounded-lg transition disabled:opacity-60">
                                                        <span wire:loading.remove wire:target="aiTlCategory({{ $categoryId }})">
                                                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L9.09 9.09 2 12l7.09 2.91L12 22l2.91-7.09L22 12l-7.09-2.91L12 2z"/></svg>
                                                        </span>
                                                        <span wire:loading wire:target="aiTlCategory({{ $categoryId }})" class="inline-flex">
                                                            <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                                        </span>
                                                        AI Translate All
                                                    </button>
                                                    <button type="button" wire:click="saveTlCategory({{ $categoryId }})"
                                                            class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition">
                                                        Save Translation
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                <div class="flex gap-3 pt-6 border-t border-slate-100">
                                    <button type="submit" class="flex-1 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-md transition duration-150">
                                        Save
                                    </button>
                                    <button type="button" wire:click="cancel" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm rounded-xl transition duration-150">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="text-center py-12 text-slate-400 text-xs space-y-4">
                                <svg class="w-12 h-12 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <p>Select a category to edit, or click "Add Category" to create a new one.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
