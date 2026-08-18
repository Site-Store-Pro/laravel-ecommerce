<div class="py-12">
    <script src="{{ asset('build/node_modules/tinymce/tinymce.min.js') }}"></script>
    <div class="max-w-[1700px] w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
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
                        {{-- Active Status Card --}}
                        <div class="p-4 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" wire:model.live="active" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 w-4 h-4 bg-white dark:bg-slate-800 mt-0.5">
                                <div class="flex flex-col">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Product Active</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-200' : 'bg-rose-100 text-rose-800 dark:bg-rose-900/60 dark:text-rose-200' }}">
                                            {{ $active ? 'Active (Live)' : 'Inactive (Hidden)' }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">When active, this product will be visible in the catalog, search, and plugins. If inactive, it is hidden and direct URL access returns a 404 error.</span>
                                </div>
                            </label>
                        </div>

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

                        <!-- Bullet Points -->
                        <div class="border-t border-slate-100 dark:border-slate-800 pt-4">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3">Key Feature Bullet Points (Optional)</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 block mb-1">Bullet Point 1</label>
                                    <input type="text" wire:model="bullet_point_1" placeholder="e.g. 100% Organic Cotton" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 block mb-1">Bullet Point 2</label>
                                    <input type="text" wire:model="bullet_point_2" placeholder="e.g. Water resistant up to 50m" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 block mb-1">Bullet Point 3</label>
                                    <input type="text" wire:model="bullet_point_3" placeholder="e.g. 2-Year Manufacturer Warranty" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 block mb-1">Bullet Point 4</label>
                                    <input type="text" wire:model="bullet_point_4" placeholder="e.g. Free Eco-friendly Packaging" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                </div>
                            </div>
                        </div>

                        {{-- Brand --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-1 uppercase tracking-wider">Brand</label>
                            <select wire:model="brand_id" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 rounded-2xl focus:outline-none focus:border-indigo-500 shadow-sm">
                                <option value="">-- No Brand Selected --</option>
                                @foreach($brands as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
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
