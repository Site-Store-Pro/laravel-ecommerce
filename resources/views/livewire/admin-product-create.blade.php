<div class="py-12" x-data="{ sidebarOpen: false, showWidgetLibrary: false, showPluginsPanel: false, showLinkGenerator: false, showShortcodeGenerator: false }">
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
                    <form wire:submit.prevent="saveProduct"
                          @submit="if (typeof tinymce !== 'undefined') { if (tinymce.get('new_product_short_description_editor')) { $wire.set('short_description', tinymce.get('new_product_short_description_editor').getContent(), false); } if (tinymce.get('new_product_long_description_editor')) { $wire.set('long_description', tinymce.get('new_product_long_description_editor').getContent(), false); } }"
                          class="space-y-6">
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

                        {{-- Short Description with TinyMCE --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400 dark:text-slate-500 block mb-1 uppercase tracking-wider">Short Description (Standard Item View)</label>
                            <div wire:ignore
                                 x-data="{
                                     short_description: @entangle('short_description'),
                                     initTiny() {
                                         let attempts = 0;
                                         const tryInit = () => {
                                             if (typeof tinymce === 'undefined') {
                                                 if (attempts++ < 40) setTimeout(tryInit, 100);
                                                 return;
                                             }
                                             if (tinymce.get('new_product_short_description_editor')) {
                                                 tinymce.get('new_product_short_description_editor').remove();
                                             }
                                             tinymce.init({
                                                 selector: '#new_product_short_description_editor',
                                                 license_key: 'gpl',
                                                 promotion: false,
                                                 base_url: '/build/node_modules/tinymce',
                                                 suffix: '.min',
                                                 height: 320,
                                                 menubar: 'insert format tools table',
                                                 font_size_formats: '12px 13px 14px 16px 18px 24px 30px 32px 34px 36px 1rem 1.125rem 1.25rem 1.375rem 1.5rem 1.75rem 1.875rem 2rem 2.25rem 2.5rem 3rem 1em 1.125em 1.25em 1.375em 1.5em 1.75em 1.875em 2em 2.25em 2.5em 3em 1vh 1.5vh 2vh 2.5vh 3vh 4vh 5vh 6vh 1vw 1.5vw 2vw 2.5vw 3vw 4vw 5vw 6vw',
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
                                                     editor.on('focus', () => {
                                                         window.lastActiveEditor = editor;
                                                     });
                                                     editor.on('init', () => {
                                                         let initialHtml = (typeof this.short_description !== 'undefined' && this.short_description !== null && this.short_description !== '') 
                                                             ? this.short_description 
                                                             : (@js($short_description) || '');
                                                         let ensureWrapper = (typeof window.ensureProseWrapper === 'function')
                                                             ? window.ensureProseWrapper
                                                             : (raw) => (raw && raw.trim() ? raw : '<p>&nbsp;</p>');
                                                         let formatted = ensureWrapper(initialHtml);
                                                         editor.setContent(formatted);
                                                         this.short_description = editor.getContent();
                                                         
                                                         editor.getBody().querySelectorAll('.prose').forEach(el => {
                                                             el.style.setProperty('max-width', 'none', 'important');
                                                             el.style.setProperty('width', '100%');
                                                         });
                                                     });
                                                     editor.on('change blur keyup NodeChange SetContent Undo Redo input', () => {
                                                         let content = editor.getContent();
                                                         this.short_description = content;
                                                         $wire.set('short_description', content, false);
                                                     });
                                                 }
                                             });
                                         };
                                         tryInit();
                                     },
                                     destroy() {
                                         if (typeof tinymce !== 'undefined' && tinymce.get('new_product_short_description_editor')) {
                                             tinymce.get('new_product_short_description_editor').remove();
                                         }
                                     }
                                 }"
                                 x-init="initTiny()">
                                <textarea id="new_product_short_description_editor" class="w-full"></textarea>
                            </div>
                            @error('short_description') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
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
                                             let editor = (typeof tinymce !== 'undefined') ? (tinymce.get('new_product_long_description_editor') || tinymce.activeEditor) : null;
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
                                     long_description: @entangle('long_description'),
                                     initTinyMCE() {
                                         let attempts = 0;
                                         const tryInit = () => {
                                             if (typeof tinymce === 'undefined') {
                                                 if (attempts++ < 40) setTimeout(tryInit, 100);
                                                 return;
                                             }
                                             if (tinymce.get('new_product_long_description_editor')) {
                                                 tinymce.get('new_product_long_description_editor').remove();
                                             }
                                             tinymce.init({
                                                 selector: '#new_product_long_description_editor',
                                                 license_key: 'gpl',
                                                 promotion: false,
                                                 base_url: '/build/node_modules/tinymce',
                                                 suffix: '.min',
                                                 height: 450,
                                                 menubar: 'insert format tools table',
                                                 font_size_formats: '12px 13px 14px 16px 18px 24px 30px 32px 34px 36px 1rem 1.125rem 1.25rem 1.375rem 1.5rem 1.75rem 1.875rem 2rem 2.25rem 2.5rem 3rem 1em 1.125em 1.25em 1.375em 1.5em 1.75em 1.875em 2em 2.25em 2.5em 3em 1vh 1.5vh 2vh 2.5vh 3vh 4vh 5vh 6vh 1vw 1.5vw 2vw 2.5vw 3vw 4vw 5vw 6vw',
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
                                                     editor.on('focus', () => {
                                                         window.lastActiveEditor = editor;
                                                     });
                                                     editor.on('init', () => {
                                                         if (this.long_description) {
                                                             let ensureWrapper = (typeof window.ensureProseWrapper === 'function')
                                                                 ? window.ensureProseWrapper
                                                                 : (raw) => (raw && raw.trim() ? raw : '<p>&nbsp;</p>');
                                                             editor.setContent(ensureWrapper(this.long_description));
                                                         }
                                                     });
                                                     editor.on('change blur keyup NodeChange SetContent Undo Redo input', () => {
                                                         let content = editor.getContent();
                                                         this.long_description = content;
                                                         $wire.set('long_description', content, false);
                                                     });
                                                 }
                                             });
                                         };
                                         tryInit();
                                     },
                                     destroy() {
                                         if (typeof tinymce !== 'undefined' && tinymce.get('new_product_long_description_editor')) {
                                             tinymce.get('new_product_long_description_editor').remove();
                                         }
                                     }
                                 }"
                                 x-init="initTinyMCE()">
                                <textarea id="new_product_long_description_editor" class="w-full"></textarea>
                            </div>
                            @error('long_description') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-700">
                            <a href="{{ route('admin.ecommerce.products') }}" wire:navigate class="px-5 py-2.5 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-600 dark:text-slate-300 text-sm font-semibold rounded-2xl transition">
                                Cancel
                            </a>
                            <button type="submit"
                                    @click="if (typeof tinymce !== 'undefined') { if (tinymce.get('new_product_short_description_editor')) { $wire.set('short_description', tinymce.get('new_product_short_description_editor').getContent(), false); } if (tinymce.get('new_product_long_description_editor')) { $wire.set('long_description', tinymce.get('new_product_long_description_editor').getContent(), false); } }"
                                    wire:loading.attr="disabled"
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

    <!-- Unified Floating Sidebar Tab Container -->
    <div class="fixed right-0 top-1/2 -translate-y-1/2 z-40 flex flex-col gap-3.5 items-end">
        <!-- Widgets -->
        <button type="button"
                x-on:click.stop="showWidgetLibrary = !showWidgetLibrary; showPluginsPanel = false; showShortcodeGenerator = false; showLinkGenerator = false"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-2 py-3.5 rounded-l-2xl shadow-xl hover:shadow-2xl transition-all flex flex-col items-center gap-2 border-l border-y border-indigo-500/30 group w-[36px]"
                title="Toggle Widgets Panel">
            <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            <span class="text-[9px] font-extrabold uppercase tracking-widest [writing-mode:vertical-lr] group-hover:scale-105 transition-transform duration-200">Widgets</span>
        </button>

        <!-- Plugins -->
        <button type="button"
                x-on:click.stop="showPluginsPanel = !showPluginsPanel; showWidgetLibrary = false; showShortcodeGenerator = false; showLinkGenerator = false"
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-2 py-3.5 rounded-l-2xl shadow-xl hover:shadow-2xl transition-all flex flex-col items-center gap-2 border-l border-y border-emerald-500/30 group w-[36px]"
                title="Toggle Plugins Panel">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"/>
            </svg>
            <span class="text-[9px] font-extrabold uppercase tracking-widest [writing-mode:vertical-lr] group-hover:scale-105 transition-transform duration-200">Plugins</span>
        </button>

        <!-- Shortcodes -->
        <button type="button"
                x-on:click.stop="showShortcodeGenerator = !showShortcodeGenerator; showWidgetLibrary = false; showPluginsPanel = false; showLinkGenerator = false"
                class="bg-blue-900 hover:bg-blue-950 text-white px-2 py-3.5 rounded-l-2xl shadow-xl hover:shadow-2xl transition-all flex flex-col items-center gap-2 border-l border-y border-blue-800/30 group w-[36px]"
                title="Toggle Shortcode Generator">
            <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
            </svg>
            <span class="text-[9px] font-extrabold uppercase tracking-widest [writing-mode:vertical-lr] group-hover:scale-105 transition-transform duration-200">Shortcodes</span>
        </button>

        <!-- Link Generator -->
        <button type="button"
                x-on:click.stop="showLinkGenerator = !showLinkGenerator; showWidgetLibrary = false; showPluginsPanel = false; showShortcodeGenerator = false"
                class="bg-orange-500 hover:bg-orange-600 text-white px-2 py-3.5 rounded-l-2xl shadow-xl hover:shadow-2xl transition-all flex flex-col items-center gap-2 border-l border-y border-orange-400/30 group w-[36px]"
                title="Toggle Link Generator">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
            </svg>
            <span class="text-[9px] font-extrabold uppercase tracking-widest [writing-mode:vertical-lr] group-hover:scale-105 transition-transform duration-200">Links</span>
        </button>
    </div>

    @include('partials.html-widgets-drawer')
    @include('partials.display-plugins-drawer')
    @include('partials.link-generator-drawer')
    @include('partials.shortcodes-generator-drawer')

    <script>
        window.lastActiveEditor = null;

        window.ensureProseWrapper = function (html) {
            if (!html || !html.trim()) {
                return '<p>&nbsp;</p>';
            }
            return html;
        };

        window.cmsTinyMCEImageUploadHandler = function (blobInfo, progress) {
            return new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.withCredentials = true;
                xhr.open('POST', '/admin/cms-pages/upload-image');

                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (token) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', token);
                }

                xhr.upload.onprogress = (e) => {
                    progress(e.loaded / e.total * 100);
                };

                xhr.onload = () => {
                    if (xhr.status === 403) {
                        reject({ message: 'HTTP Error: ' + xhr.status, remove: true });
                        return;
                    }
                    if (xhr.status < 200 || xhr.status >= 300) {
                        reject('HTTP Error: ' + xhr.status);
                        return;
                    }
                    let json;
                    try {
                        json = JSON.parse(xhr.responseText);
                    } catch (err) {
                        console.error('[TinyMCE Upload Error] Invalid JSON:', xhr.responseText);
                        reject('Invalid JSON: ' + xhr.responseText);
                        return;
                    }
                    if (!json || typeof json.location !== 'string') {
                        console.error('[TinyMCE Upload Error] Missing location in response:', xhr.responseText);
                        reject('Invalid JSON: ' + xhr.responseText);
                        return;
                    }
                    resolve(json.location);
                };

                xhr.onerror = () => {
                    reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
                };

                const formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());

                xhr.send(formData);
            });
        };

        window.insertHtmlWidget = function(htmlString) {
            let editor = window.lastActiveEditor || tinymce.activeEditor;
            if (!editor || editor.isDestroyed) {
                editor = tinymce.get('new_product_short_description_editor') || tinymce.get('new_product_long_description_editor');
            }
            if (editor) {
                editor.undoManager.transact(() => {
                    let selectedContent = editor.selection.getContent({ format: 'html' }) || editor.selection.getContent({ format: 'text' });
                    let htmlToInsert = htmlString;
                    
                    if (selectedContent && selectedContent.trim()) {
                        let tempNode = document.createElement('div');
                        tempNode.innerHTML = htmlString;
                        
                        let target = tempNode.querySelector('blockquote') ||
                                     tempNode.querySelector('a span') ||
                                     tempNode.querySelector('details p') ||
                                     tempNode.querySelector('p') ||
                                     tempNode.querySelector('h2') ||
                                     tempNode.querySelector('h3') ||
                                     tempNode.querySelector('div');
                                     
                        if (target) {
                            if (target.nodeName === 'BLOCKQUOTE') {
                                target.innerHTML = '&ldquo;' + selectedContent.replace(/^(&ldquo;|&rdquo;|“|”)/g, '') + '&rdquo;';
                            } else {
                                target.innerHTML = selectedContent;
                            }
                        }
                        htmlToInsert = tempNode.innerHTML;
                    }
                    let body = editor.getBody();
                    let tempDiv = editor.dom.create('div', {}, htmlToInsert);
                    
                    let lastChild = body.lastChild;
                    if (lastChild && lastChild.nodeName !== 'P') {
                        let spacer = editor.dom.create('p', {}, '<br data-mce-bogus="1">');
                        body.appendChild(spacer);
                    }
                    
                    while (tempDiv.firstChild) {
                        body.appendChild(tempDiv.firstChild);
                    }
                    
                    let trailingSpacer = editor.dom.create('p', {}, '<br data-mce-bogus="1">');
                    body.appendChild(trailingSpacer);
                });
                
                editor.focus();
                editor.selection.select(editor.getBody(), true);
                editor.selection.collapse(false);
                editor.selection.scrollIntoView();
                
                editor.nodeChanged();
                editor.dispatch('change');
            } else {
                alert('TinyMCE editor is not initialized.');
            }
        };

        window.insertPluginShortcode = function(shortcodeString) {
            let editor = window.lastActiveEditor || tinymce.activeEditor;
            if (!editor || editor.isDestroyed) {
                editor = tinymce.get('new_product_short_description_editor') || tinymce.get('new_product_long_description_editor');
            }
            if (editor) {
                editor.undoManager.transact(() => {
                    let body = editor.getBody();
                    let shortcodeParagraph = editor.dom.create('p', {}, shortcodeString);
                    body.appendChild(shortcodeParagraph);
                    let trailingSpacer = editor.dom.create('p', {}, '<br data-mce-bogus="1">');
                    body.appendChild(trailingSpacer);
                });

                editor.focus();
                editor.selection.select(editor.getBody(), true);
                editor.selection.collapse(false);
                editor.selection.scrollIntoView();

                editor.nodeChanged();
                editor.dispatch('change');
            } else {
                alert('TinyMCE editor is not initialized.');
            }
        };
    </script>
</div>
