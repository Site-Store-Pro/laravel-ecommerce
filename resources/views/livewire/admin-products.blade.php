<div class="py-12">
    <script src="{{ asset('build/node_modules/tinymce/tinymce.min.js') }}"></script>
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

                    <a href="{{ route('admin.ecommerce.products') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm bg-indigo-50 text-indigo-600 transition duration-150">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Products
                    </a>

                    <a href="{{ route('admin.ecommerce.import') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 hover:bg-slate-50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Import Products
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


                </div>
            </div>

            <!-- Main Panel Content -->
            <div class="lg:col-span-9 space-y-8">
                <!-- Status/Success Notifications -->
                @if(session()->has('status'))
                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center gap-3 text-emerald-800 text-sm font-semibold">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Recently Edited Products Quick List -->
                @if(isset($recentlyEdited) && $recentlyEdited->isNotEmpty())
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                        <div class="flex items-center gap-3 border-b border-slate-100 pb-4 mb-6">
                            <span class="p-2.5 bg-indigo-50 rounded-2xl text-indigo-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 leading-snug">Recently Edited Products</h3>
                                <p class="text-xs text-slate-400 mt-0.5 font-medium">Quick dashboard access to the last 5 modified products</p>
                            </div>
                        </div>

                        <div class="overflow-x-auto border border-slate-100 rounded-2xl">
                            <table class="w-full text-left text-xs text-slate-700">
                                <thead class="bg-slate-50 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-200">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">Thumbnail</th>
                                        <th scope="col" class="px-4 py-3">Product Name</th>
                                        <th scope="col" class="px-4 py-3">Price Range</th>
                                        <th scope="col" class="px-4 py-3">Last Modified</th>
                                        <th scope="col" class="px-4 py-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @foreach($recentlyEdited as $p)
                                        <tr class="hover:bg-slate-50/50 transition">
                                            <!-- Thumbnail -->
                                            <td class="px-4 py-3">
                                                @if($p->primaryThumbnailUrl())
                                                    <img src="{{ $p->primaryThumbnailUrl() }}" class="w-10 h-10 object-cover rounded-xl border border-slate-200 shadow-sm" alt="Thumbnail">
                                                @else
                                                    <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 text-[10px] font-bold">N/A</div>
                                                @endif
                                            </td>
                                            <!-- Product Name -->
                                            <td class="px-4 py-3 font-bold text-slate-800 text-sm">
                                                {{ $p->title }}
                                            </td>
                                            <!-- Price Range -->
                                            <td class="px-4 py-3 font-semibold text-indigo-600">
                                                {{ $p->price_range }}
                                            </td>
                                            <!-- Last Modified -->
                                            <td class="px-4 py-3 text-slate-500 font-mono text-[11px]">
                                                {{ $p->updated_at->format('Y-m-d H:i') }}
                                            </td>
                                            <!-- Actions -->
                                            <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                                                <a href="{{ route('admin.ecommerce.product-edit', ['id' => $p->id]) }}"
                                                   class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-[11px] font-extrabold rounded-lg transition duration-150 shadow-sm">
                                                    Edit
                                                </a>
                                                @if($p->seo_slug)
                                                    <a href="{{ route('shop.product', ['seo_link' => $p->seo_slug]) }}" target="_blank"
                                                       class="inline-flex items-center px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-600 text-[11px] font-extrabold rounded-lg transition duration-150 border border-slate-200 shadow-sm">
                                                        View Site ↗
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Add Product / Variant Forms -->
                @if($isCreatingProduct)
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-slate-900 mb-6">Create New Product</h3>
                        <form wire:submit.prevent="saveProduct" class="space-y-4">
                            <div>
                                <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Title</label>
                                <input type="text" wire:model="title" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                @error('title') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Short Description</label>
                                <input type="text" wire:model="short_description" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Long Description</label>

                                @if ($showAiButton)
                                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 mb-4 space-y-3 animate-fade-in">
                                        <div>
                                            <x-input-label for="aiPrompt" :value="__('AI Instruction Prompt')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-1" />
                                            <input type="text" wire:model="aiPrompt" id="aiPrompt"
                                                   class="block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm"
                                                   placeholder="e.g. Please write a high-converting, detailed product description highlighting key features and benefits" />
                                            <p class="text-slate-400 text-[10px] mt-1.5 leading-relaxed">
                                                The 'Generate with OPENAI' button will send your prompt, product title, category, short &amp; long description context to OpenAI to return AI-generated content.
                                            </p>
                                            <x-input-error :messages="$errors->get('ai_content_error')" class="mt-2 text-xs" />
                                        </div>
                                        <div class="flex justify-end">
                                            <button type="button" wire:click="generateAiContent" wire:loading.attr="disabled"
                                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl transition-all shadow-sm">
                                                <span wire:loading.remove wire:target="generateAiContent" class="flex items-center gap-1.5">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                    </svg>
                                                    Generate with OPENAI
                                                </span>
                                                <span wire:loading wire:target="generateAiContent" class="flex items-center gap-1.5">
                                                    <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Processing...
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                @endif

                                @if (!empty($aiResponse))
                                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 mb-4 space-y-2 animate-fade-in"
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
                                            <button type="button" @click="copyToEditor()" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-colors border border-indigo-150 shadow-sm">
                                                Copy to Editor
                                            </button>
                                        </div>
                                        <textarea readonly rows="6" class="block w-full rounded-xl border-slate-200 bg-white text-sm text-slate-600 shadow-sm focus:ring-0 focus:border-slate-200">{{ $aiResponse }}</textarea>
                                    </div>
                                @endif

                                <div wire:ignore
                                     x-data="{
                                         long_description: @entangle('long_description'),
                                         initTiny() {
                                             if (typeof tinymce === 'undefined') return;
                                             let existing = tinymce.get('new_product_long_description_editor');
                                             if (existing) existing.remove();
                                             tinymce.init({
                                                 selector: '#new_product_long_description_editor',
                                                 license_key: 'gpl',
                                                 promotion: false,
                                                 height: 500,
                                                 menubar: 'insert format tools table',
                                                 content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px; padding: 1rem; } .btn-theme-primary { background-color: #4f46e5 !important; color: #ffffff !important; border-radius: 0.75rem !important; border: none !important; padding: 10px 20px !important; font-weight: 700 !important; font-family: inherit !important; cursor: pointer !important; display: inline-block !important; text-align: center !important; text-decoration: none !important; transition: background-color 0.2s !important; } .btn-theme-primary:hover { background-color: #4338ca !important; }',
                                                 content_css: [
                                                     'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css',
                                                     '/css/prose.css'
                                                 ],
                                                 convert_urls: false,
                                                 remove_script_host: false,
                                                 images_upload_handler: window.cmsTinyMCEImageUploadHandler,
                                                 plugins: 'advlist autolink lists link image charmap preview anchor searchreplace wordcount visualblocks supercode fullscreen insertdatetime media table help emoticons pagebreak directionality',
                                                 toolbar: [
                                                     'supercode fullscreen | undo redo | styles blocks | bold italic underline strikethrough | forecolor backcolor',
                                                     'fontfamily fontsize lineheight | alignleft aligncenter alignright alignjustify | outdent indent | removeformat | numlist bullist | pagebreak | charmap emoticons | link image media anchor | ltr rtl | preview'
                                                 ],
                                                 toolbar_mode: 'wrap',
                                                 cache_suffix: '?v=' + new Date().getTime(),
                                                 protect: [
                                                     /\{\{[\s\S]*?\}\}/g,
                                                     /\{!![\s\S]*?!!\}/g,
                                                     /@\w+(\([^)]*\))?/g
                                                 ],
                                                 branding: false,
                                                 contextmenu: 'link image imagetools',
                                                 style_formats: [
                                                     { title: 'Callout (Yellow/Warning)', block: 'div', classes: 'p-4 bg-amber-50 dark:bg-amber-950/20 border-l-4 border-amber-500 text-amber-900 dark:text-amber-200 rounded-r-lg my-4', wrapper: true },
                                                     { title: 'Callout (Blue/Info)', block: 'div', classes: 'p-4 bg-blue-50 dark:bg-blue-950/20 border-l-4 border-blue-500 text-blue-900 dark:text-blue-200 rounded-r-lg my-4', wrapper: true },
                                                     { title: 'Callout (Green/Success)', block: 'div', classes: 'p-4 bg-emerald-50 dark:bg-emerald-950/20 border-l-4 border-emerald-500 text-emerald-900 dark:text-emerald-200 rounded-r-lg my-4', wrapper: true },
                                                     { title: 'Callout (Red/Danger)', block: 'div', classes: 'p-4 bg-rose-50 dark:bg-rose-950/20 border-l-4 border-rose-500 text-rose-900 dark:text-rose-200 rounded-r-lg my-4', wrapper: true },
                                                     { title: 'Feature Card', block: 'div', classes: 'p-6 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50 rounded-2xl shadow-sm my-6', wrapper: true },
                                                     { title: 'Premium Button (Primary)', selector: 'a', classes: 'inline-block px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-colors no-underline' },
                                                     { title: 'Premium Button (Outline)', selector: 'a', classes: 'inline-block px-5 py-2.5 border border-indigo-600 text-indigo-600 hover:bg-indigo-50 font-medium rounded-xl transition-colors no-underline' },
                                                     { title: 'Badge Primary', inline: 'span', classes: 'inline-block px-2.5 py-0.5 text-xs font-semibold bg-indigo-100 text-indigo-800 rounded-full' },
                                                     { title: 'Badge Success', inline: 'span', classes: 'inline-block px-2.5 py-0.5 text-xs font-semibold bg-emerald-100 text-emerald-800 rounded-full' },
                                                     { title: 'Lead Paragraph', block: 'p', classes: 'text-lg text-slate-600 dark:text-slate-400 font-medium leading-relaxed' },
                                                     { title: 'Highlight Text', inline: 'span', styles: { color: '#ff0000', textDecoration: 'underline' } }
                                                 ],
                                                 extended_valid_elements: '*[class|style|id|name|open],svg[*],path[*],circle[*],rect[*],g[*],line[*],polyline[*],polygon[*]',
                                                 supercode: {
                                                     theme: 'monokai',
                                                     fontSize: 14,
                                                     autocomplete: true,
                                                     dark: true
                                                 },
                                                 setup: (editor) => {
                                                     editor.on('init', () => {
                                                         if (this.long_description) {
                                                             editor.setContent(this.long_description);
                                                         }
                                                     });
                                                     editor.on('change blur', () => {
                                                         this.long_description = editor.getContent();
                                                     });
                                                 }
                                             });
                                         },
                                         destroy() {
                                             let ed = tinymce.get('new_product_long_description_editor');
                                             if (ed) ed.remove();
                                         }
                                     }"
                                     x-init="$nextTick(() => initTiny())"
                                     x-on:livewire:navigated.window="destroy()">
                                    <textarea id="new_product_long_description_editor" wire:model="long_description" rows="8" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500"></textarea>
                                </div>
                            </div>
                            


                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">SEO Slug</label>
                                    <input type="text" wire:model="seo_slug" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                    @error('seo_slug') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Meta Title</label>
                                    <input type="text" wire:model="meta_title" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                    @error('meta_title') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Meta Description</label>
                                <input type="text" wire:model="meta_description" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                @error('meta_description') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider font-sans">Assign Categories</label>
                                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 h-40 overflow-y-auto space-y-1">
                                        @if($categoryTree->isEmpty())
                                            <span class="text-xs text-slate-400">No categories available.</span>
                                        @else
                                            @foreach($categoryTree as $node)
                                                @include('livewire.category-checkbox-node', ['node' => $node, 'depth' => 0])
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider font-sans">Assign Brand</label>
                                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 h-40 overflow-y-auto space-y-2">
                                        <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                                            <input type="radio" wire:model="brand_id" value="" class="rounded-full border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 bg-white">
                                            <span class="text-slate-400 font-normal">None (No Brand)</span>
                                        </label>
                                        @if($brands->isEmpty())
                                            <span class="text-xs text-slate-400">No brands available.</span>
                                        @else
                                            @foreach($brands as $brand)
                                                <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                                                    <input type="radio" wire:model="brand_id" value="{{ $brand->id }}" class="rounded-full border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 bg-white">
                                                    <span>{{ $brand->name }}</span>
                                                </label>
                                            @endforeach
                                        @endif
                                    </div>
                                    @error('brand_id') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-2xl shadow-md hover:opacity-90">Save Product</button>
                                <button type="button" wire:click="toggleCreateProduct" class="px-6 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-2xl">Cancel</button>
                            </div>
                        </form>
                    </div>
                @endif




                <!-- Products Table card -->
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">

                    {{-- ─── Header row ─── --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-slate-100 pb-4 mb-4 gap-4">
                        <h3 class="text-lg font-bold text-slate-900">Manage Catalog</h3>

                        <div class="flex items-center gap-2 flex-wrap">
                            {{-- Keyword search --}}
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </span>
                                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search catalog…"
                                       class="pl-9 pr-4 py-2 w-56 bg-slate-50 border border-slate-200 text-slate-700 placeholder-slate-400 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition">
                            </div>

                            {{-- Advanced Filters toggle --}}
                            <button wire:click="toggleAdvancedFilters"
                                    class="relative inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold rounded-xl border transition duration-150
                                           {{ $showAdvancedFilters ? 'bg-indigo-600 text-white border-indigo-600 shadow-md' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                                </svg>
                                Filters
                                @if($activeFilterCount > 0)
                                    <span class="absolute -top-1.5 -right-1.5 inline-flex items-center justify-center w-4 h-4 text-[9px] font-black bg-rose-500 text-white rounded-full">{{ $activeFilterCount }}</span>
                                @endif
                            </button>

                            {{-- Reset (only when filters active) --}}
                            @if($activeFilterCount > 0 || $search)
                                <button wire:click="resetFilters"
                                        class="inline-flex items-center gap-1 px-3 py-2 text-xs font-bold text-rose-600 bg-rose-50 border border-rose-200 rounded-xl hover:bg-rose-100 transition duration-150">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Clear
                                </button>
                            @endif

                            {{-- New Product button --}}
                            <button wire:click="toggleCreateProduct"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl shadow-sm hover:opacity-90 transition duration-150">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                New Product
                            </button>
                        </div>
                    </div>

                    {{-- ─── Advanced Filter Panel ─── --}}
                    @if($showAdvancedFilters)
                    <div class="mb-6 p-5 bg-slate-50 border border-slate-200 rounded-2xl space-y-5 animate-fade-in">

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                            {{-- Brand --}}
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Brand</label>
                                <select wire:model.live="filterBrandId"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                                    <option value="">All Brands</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Product Type --}}
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Product Type</label>
                                <select wire:model.live="filterProductType"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                                    <option value="">All Types</option>
                                    <option value="shippable">Shippable / Physical</option>
                                    <option value="download">Digital Download</option>
                                    <option value="event">Event</option>
                                    <option value="featured">Featured</option>
                                </select>
                            </div>

                            {{-- Stock Status --}}
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Stock Status</label>
                                <select wire:model.live="filterStockStatus"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                                    <option value="">All</option>
                                    <option value="in_stock">In Stock</option>
                                    <option value="out_of_stock">Out of Stock</option>
                                </select>
                            </div>

                            {{-- Price Range --}}
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Price Range</label>
                                <div class="flex items-center gap-2">
                                    <input wire:model.live.debounce.400ms="filterPriceMin" type="number" min="0" step="0.01" placeholder="Min $"
                                           class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                                    <span class="text-slate-400 text-xs font-bold shrink-0">–</span>
                                    <input wire:model.live.debounce.400ms="filterPriceMax" type="number" min="0" step="0.01" placeholder="Max $"
                                           class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                                </div>
                            </div>

                            {{-- Sort Order --}}
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Sort By</label>
                                <select wire:model.live="filterSortBy"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                                    <option value="newest">Newest First</option>
                                    <option value="oldest">Oldest First</option>
                                    <option value="alpha_asc">A → Z</option>
                                    <option value="alpha_desc">Z → A</option>
                                    <option value="price_asc">Price: Low → High</option>
                                    <option value="price_desc">Price: High → Low</option>
                                </select>
                            </div>

                            {{-- Attribute Filter --}}
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Attribute</label>
                                <div class="flex items-center gap-2">
                                    <input wire:model.live.debounce.400ms="filterAttribute" type="text" placeholder="Key (e.g. Color)"
                                           class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                                    <input wire:model.live.debounce.400ms="filterAttributeValue" type="text" placeholder="Value (opt.)"
                                           class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                                </div>
                            </div>

                        </div>

                        {{-- Category multi-select --}}
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Categories</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-1.5 max-h-40 overflow-y-auto pr-1">
                                @foreach($categoryTree as $cat)
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" wire:model.live="filterCategoryIds" value="{{ $cat->id }}"
                                               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-3.5 h-3.5">
                                        <span class="text-xs text-slate-700 group-hover:text-indigo-600 font-semibold truncate">{{ $cat->name }}</span>
                                    </label>
                                    @if($cat->children && $cat->children->count())
                                        @foreach($cat->children as $child)
                                            <label class="flex items-center gap-2 cursor-pointer group pl-4">
                                                <input type="checkbox" wire:model.live="filterCategoryIds" value="{{ $child->id }}"
                                                       class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-3.5 h-3.5">
                                                <span class="text-xs text-slate-500 group-hover:text-indigo-600 truncate">↳ {{ $child->name }}</span>
                                            </label>
                                        @endforeach
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        {{-- Active filter summary pills --}}
                        @if($activeFilterCount > 0 || $search)
                        <div class="flex flex-wrap gap-2 pt-2 border-t border-slate-200">
                            @if($search)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold bg-indigo-100 text-indigo-700 rounded-full">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    Keyword: "{{ $search }}"
                                </span>
                            @endif
                            @if($filterBrandId)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold bg-violet-100 text-violet-700 rounded-full">
                                    Brand: {{ $brands->find($filterBrandId)?->name ?? $filterBrandId }}
                                </span>
                            @endif
                            @if(!empty($filterCategoryIds))
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold bg-teal-100 text-teal-700 rounded-full">
                                    {{ count($filterCategoryIds) }} {{ Str::plural('Category', count($filterCategoryIds)) }}
                                </span>
                            @endif
                            @if($filterPriceMin !== '' || $filterPriceMax !== '')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold bg-amber-100 text-amber-700 rounded-full">
                                    Price: ${{ $filterPriceMin ?: '0' }} – ${{ $filterPriceMax ?: '∞' }}
                                </span>
                            @endif
                            @if($filterProductType)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold bg-sky-100 text-sky-700 rounded-full">
                                    Type: {{ ucfirst($filterProductType) }}
                                </span>
                            @endif
                            @if($filterStockStatus)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold bg-emerald-100 text-emerald-700 rounded-full">
                                    Stock: {{ $filterStockStatus === 'in_stock' ? 'In Stock' : 'Out of Stock' }}
                                </span>
                            @endif
                            @if($filterAttribute)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold bg-pink-100 text-pink-700 rounded-full">
                                    Attr: {{ $filterAttribute }}{{ $filterAttributeValue ? ' = '.$filterAttributeValue : '' }}
                                </span>
                            @endif
                        </div>
                        @endif

                    </div>
                    @endif

                    {{-- Results count --}}
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-xs text-slate-400 font-semibold">
                            {{ $products->total() }} {{ Str::plural('product', $products->total()) }} found
                            @if($activeFilterCount > 0 || $search) <span class="text-indigo-500">(filtered)</span>@endif
                        </p>
                    </div>

                    @if($products->isEmpty())
                        <p class="text-sm text-slate-500 text-center py-6">Your search returned no matches or the catalog is empty.</p>
                    @else
                        <div class="space-y-6">
                            @foreach($products as $product)
                                <div class="p-6 bg-slate-50 border border-slate-100 rounded-2xl space-y-4">
                                    <div class="flex items-start gap-4">
                                        <!-- Thumbnail Image or Placeholder -->
                                        <div class="flex-shrink-0">
                                            @if($product->primaryThumbnailUrl())
                                                <img src="{{ $product->primaryThumbnailUrl() }}" class="w-12 h-12 object-cover rounded-xl border border-slate-200 shadow-sm" alt="Thumbnail">
                                            @else
                                                <div class="w-12 h-12 rounded-xl bg-slate-200 border border-slate-300 flex items-center justify-center text-slate-400">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Text info & action buttons -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                                <div>
                                                    <button type="button" wire:click="toggleProductExpand({{ $product->id }})" class="flex items-center gap-2 group text-left focus:outline-none">
                                                        <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition-transform duration-200 {{ in_array($product->id, $expandedProducts) ? 'rotate-90' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                                        </svg>
                                                        <span class="font-extrabold text-slate-900 group-hover:text-indigo-600 transition">{{ $product->title }}</span>
                                                    </button>
                                                    <span class="text-xs text-slate-400 block mt-1">Slug: <a href="{{ route('shop.product', $product->seo_slug) }}" target="_blank" class="text-indigo-600 hover:underline">/items/{{ $product->seo_slug }}</a></span>
                                                </div>
                                                <div class="flex gap-2">
                                                    <a href="{{ route('admin.ecommerce.product-edit', $product->id) }}" class="inline-flex items-center gap-1.5 justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:text-slate-900 transition-all shadow-sm">
                                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-2.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                        </svg>
                                                        Edit
                                                    </a>
                                                    <a href="{{ route('admin.ecommerce.product-edit', $product->id) }}?create_variant=1" class="inline-flex items-center gap-1.5 justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-all shadow-sm">
                                                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                        </svg>
                                                        Add Price | Variant
                                                    </a>
                                                    <button type="button" wire:click="openCopyModal({{ $product->id }})" class="inline-flex items-center gap-1.5 justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 transition-all shadow-sm">
                                                        <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                        </svg>
                                                        Copy
                                                    </button>
                                                    <button onclick="confirm('Are you sure you want to delete this product?') || event.stopImmediatePropagation()" wire:click="deleteProduct({{ $product->id }})" class="inline-flex items-center gap-1.5 justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 transition-all shadow-sm">
                                                        <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Variants details (Only shown if expanded) -->
                                    @if(in_array($product->id, $expandedProducts))
                                        <div class="overflow-x-auto pt-4 border-t border-slate-200/50 mt-2">
                                            <table class="w-full text-left text-xs text-slate-500">
                                                <thead>
                                                    <tr class="text-slate-400 uppercase font-semibold">
                                                        <th class="py-2">SKU</th>
                                                        <th class="py-2">Attributes</th>
                                                        <th class="py-2">Public Price</th>
                                                        <th class="py-2">Wholesale Price</th>
                                                        <th class="py-2">Sale Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100">
                                                    @foreach($product->variants as $variant)
                                                        @php
                                                            $attrs = json_decode($variant->attributes, true) ?: [];
                                                            $attrStr = collect($attrs)->map(fn($v, $k) => "$k: $v")->implode(', ');
                                                        @endphp
                                                        <tr>
                                                            <td class="py-2.5 font-bold text-slate-800">{{ $variant->sku }}</td>
                                                            <td class="py-2.5">{{ $attrStr ?: 'None' }}</td>
                                                            <td class="py-2.5">${{ number_format($variant->public_price, 2) }}</td>
                                                            <td class="py-2.5">${{ number_format($variant->wholesale_price, 2) }}</td>
                                                            <td class="py-2.5">
                                                                @if($variant->on_sale)
                                                                    <span class="px-2 py-0.5 bg-red-50 text-red-600 rounded-full font-bold">Sale: ${{ number_format($variant->sale_price, 2) }}</span>
                                                                @else
                                                                    <span class="text-slate-400">Regular</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    @if($product->variants->isEmpty())
                                                        <tr>
                                                            <td colspan="5" class="py-4 text-center text-slate-400 italic">No variants added yet.</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @if ($products->hasPages())
                            <div class="mt-6 pt-4 border-t border-slate-100">
                                {{ $products->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Duplicate / Copy Product Modal -->
    @if($showCopyModal)
        <div class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fade-in">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 space-y-5 animate-scale-up" @click.outside="$wire.closeCopyModal()">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-amber-50 rounded-2xl text-amber-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900">Duplicate / Copy Product</h3>
                            <p class="text-xs text-slate-400 font-medium">Create a complete copy of this product</p>
                        </div>
                    </div>
                    <button type="button" wire:click="closeCopyModal" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <!-- Original Product Title (Info) -->
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-2xl">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Original Product</span>
                        <span class="text-sm font-bold text-slate-800">{{ $copyOriginalTitle }}</span>
                    </div>

                    <!-- New Product Title Input -->
                    <div>
                        <label class="text-xs font-bold text-slate-600 block mb-1 uppercase tracking-wider">New Product Title</label>
                        <input type="text" wire:model.live="copyProductTitle" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 font-medium text-sm">
                        @error('copyProductTitle') <span class="text-xs text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- New SEO Slug Input -->
                    <div>
                        <label class="text-xs font-bold text-slate-600 block mb-1 uppercase tracking-wider">New SEO Slug</label>
                        <input type="text" wire:model="copyProductSlug" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 font-medium text-sm font-mono">
                        @error('copyProductSlug') <span class="text-xs text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Duplicate Variants & Images Toggle -->
                    <div class="p-4 bg-amber-50/60 border border-amber-100 rounded-2xl">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" wire:model="copyVariantsAndImages" class="w-4 h-4 mt-0.5 rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                            <div>
                                <span class="text-xs font-extrabold text-slate-800 block">Duplicate All Variants, Inventory &amp; Images</span>
                                <span class="text-[11px] text-slate-500 font-medium leading-relaxed block mt-0.5">When checked, all pricing variants, stock levels (with unique SKUs), and image sets will be duplicated for the new item.</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" wire:click="closeCopyModal" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-2xl transition">
                        Cancel
                    </button>
                    <button type="button" wire:click="duplicateProduct" wire:loading.attr="disabled" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-extrabold rounded-2xl shadow-md transition flex items-center gap-2">
                        <svg wire:loading wire:target="duplicateProduct" class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Duplicate Product</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
