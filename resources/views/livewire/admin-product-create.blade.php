<div class="py-12">
    <script src="{{ asset('build/node_modules/tinymce/tinymce.min.js') }}"></script>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Wrapper Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-3 space-y-2">
                <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/80 rounded-3xl p-6 shadow-sm space-y-1">
                    <h2 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-4 px-3">Shop Administration</h2>
                    
                    <a href="{{ route('admin.ecommerce.pending-orders') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pending Orders
                    </a>

                    <a href="{{ route('admin.ecommerce.products') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 transition duration-150">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Products
                    </a>

                    <a href="{{ route('admin.ecommerce.import') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Import Products
                    </a>

                    <a href="{{ route('admin.ecommerce.categories') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                        Categories
                    </a>

                    <a href="{{ route('admin.ecommerce.brands') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                        Brands
                    </a>

                    <a href="{{ route('admin.ecommerce.orders') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        Orders
                    </a>

                    <a href="{{ route('admin.ecommerce.inventory') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Inventory
                    </a>
                </div>
            </div>

            <!-- Main Panel Content -->
            <div class="lg:col-span-9 space-y-8">
                <!-- Page Header -->
                <div class="flex items-center justify-between">
                    <div>
                        <a href="{{ route('admin.ecommerce.products') }}" wire:navigate class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline mb-2">
                            &larr; Back to Products Manager
                        </a>
                        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100 bg-gradient-to-r from-slate-900 to-indigo-950 dark:from-slate-100 dark:to-indigo-200 bg-clip-text text-transparent">
                            Add New Product
                        </h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Create a new product entry. Afterwards, you will be redirected to configure pricing and variants.</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/80 rounded-3xl p-8 shadow-sm">
                    <form wire:submit.prevent="saveProduct" class="space-y-6">
                        <div>
                            <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-1 uppercase tracking-wider">Product Title <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model.live.debounce.300ms="title" autofocus
                                   placeholder="e.g. Ergonomic Executive Chair"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 rounded-2xl focus:outline-none focus:border-indigo-500 shadow-sm">
                            @error('title') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-1 uppercase tracking-wider">SEO Slug / Permalink <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model.live.debounce.300ms="seo_slug"
                                   placeholder="ergonomic-executive-chair"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 rounded-2xl focus:outline-none focus:border-indigo-500 shadow-sm">
                            @error('seo_slug') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-1 uppercase tracking-wider">Short Description</label>
                            <input type="text" wire:model="short_description"
                                   placeholder="Brief overview shown on catalog cards and quick views"
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 rounded-2xl focus:outline-none focus:border-indigo-500 shadow-sm">
                        </div>

                        {{-- Brand & Categories Grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-1 uppercase tracking-wider">Brand</label>
                                <select wire:model="brand_id" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 rounded-2xl focus:outline-none focus:border-indigo-500 shadow-sm">
                                    <option value="">-- No Brand Selected --</option>
                                    @foreach($brands as $b)
                                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-1 uppercase tracking-wider">Type / Delivery</label>
                                <div class="flex items-center gap-6 pt-2">
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" wire:model="product_shipping" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Requires Shipping</span>
                                    </label>
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" wire:model="product_download_item" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Digital Download Item</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Categories checklist --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-2 uppercase tracking-wider">Categories</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 bg-slate-50 dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 max-h-48 overflow-y-auto">
                                @foreach($categories as $cat)
                                    <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        <input type="checkbox" wire:model="selectedCategories" value="{{ $cat->id }}" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                        {{ $cat->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Long Description with TinyMCE & OpenAI --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-1 uppercase tracking-wider">Long Description</label>

                            @if ($showAiButton)
                                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 mb-4 space-y-3">
                                    <div>
                                        <x-input-label for="aiPrompt" :value="__('AI Instruction Prompt')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-1" />
                                        <input type="text" wire:model="aiPrompt" id="aiPrompt"
                                               class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 text-sm shadow-sm"
                                               placeholder="e.g. Write a detailed, compelling description highlighting key benefits and specifications" />
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="button" wire:click="generateAiContent" wire:loading.attr="disabled"
                                                class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl transition shadow-sm cursor-pointer">
                                            <span wire:loading.remove wire:target="generateAiContent" class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                                Generate with OPENAI
                                            </span>
                                            <span wire:loading wire:target="generateAiContent" class="flex items-center gap-1.5">
                                                <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                                </svg>
                                                Processing...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            @endif

                            @if (!empty($aiResponse))
                                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 mb-4 space-y-2"
                                     x-data="{
                                         copyToEditor() {
                                             let content = @js($aiResponse);
                                             let editor = typeof tinymce !== 'undefined' ? tinymce.get('new_product_long_description_editor') : null;
                                             if (editor) {
                                                 editor.setContent(content);
                                                 editor.triggerSave();
                                             }
                                             $wire.set('long_description', content);
                                         }
                                     }">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                            AI Suggested Content
                                        </span>
                                        <button type="button" @click="copyToEditor()" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-indigo-600 hover:text-indigo-700 bg-indigo-50 dark:bg-indigo-950/60 rounded-xl transition border border-indigo-200 shadow-sm cursor-pointer">
                                            Copy to Editor
                                        </button>
                                    </div>
                                    <textarea readonly rows="5" class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs text-slate-600 dark:text-slate-300 shadow-sm">{{ $aiResponse }}</textarea>
                                </div>
                            @endif

                            <div wire:ignore
                                 x-data="{
                                     initTinyMCE() {
                                         if (typeof tinymce === 'undefined') return;
                                         if (tinymce.get('new_product_long_description_editor')) {
                                             tinymce.get('new_product_long_description_editor').remove();
                                         }
                                         tinymce.init({
                                             selector: '#new_product_long_description_editor',
                                             license_key: 'gpl',
                                             base_url: '/build/node_modules/tinymce',
                                             suffix: '.min',
                                             height: 350,
                                             menubar: false,
                                             plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code help wordcount',
                                             toolbar: 'undo redo | blocks | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help | code',
                                             setup: (editor) => {
                                                 editor.on('change blur keyup', () => {
                                                     $wire.set('long_description', editor.getContent());
                                                 });
                                             }
                                         });
                                     }
                                 }"
                                 x-init="initTinyMCE()">
                                <textarea id="new_product_long_description_editor" wire:model="long_description" class="w-full"></textarea>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-700">
                            <a href="{{ route('admin.ecommerce.products') }}" wire:navigate class="px-5 py-2.5 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-600 dark:text-slate-300 text-sm font-semibold rounded-2xl transition">
                                Cancel
                            </a>
                            <button type="submit" wire:loading.attr="disabled"
                                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl shadow-md transition cursor-pointer">
                                <span wire:loading.remove wire:target="saveProduct">Create Product &rarr;</span>
                                <span wire:loading wire:target="saveProduct" class="inline-flex items-center gap-2">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    Creating...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
