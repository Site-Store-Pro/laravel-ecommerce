<div class="py-12" x-data="{ sidebarOpen: false, showWidgetLibrary: false, showPluginsPanel: false, showLinkGenerator: false, showShortcodeGenerator: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('admin.ecommerce.products') }}" wire:navigate
               class="p-2.5 rounded-2xl border border-slate-200 bg-white text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">Edit Product: {{ $product->title }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">Manage details, active variants, pricing, and variant stock counts</p>
            </div>
        </div>

        <div class="flex gap-6 items-start">
            <!-- Sidebar Navigation (collapsible) -->
            <div
                class="flex-shrink-0 transition-all duration-300 ease-in-out"
                :style="sidebarOpen ? 'width:220px' : 'width:44px'"
            >
                <!-- Toggle Button -->
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="w-full flex items-center justify-center h-10 rounded-2xl bg-white border border-slate-200 shadow-sm text-slate-400 hover:text-indigo-600 hover:border-indigo-300 hover:bg-indigo-50 transition-all duration-200 mb-2"
                    :title="sidebarOpen ? 'Collapse menu' : 'Expand menu'"
                >
                    <svg class="w-4 h-4 transition-transform duration-300" :class="sidebarOpen ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7M18 19l-7-7 7-7"/>
                    </svg>
                </button>

                <!-- Menu Panel -->
                <div
                    x-show="sidebarOpen"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-x-2"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-2"
                    class="bg-white border border-slate-100 rounded-3xl p-4 shadow-sm space-y-1 overflow-hidden"
                    style="display:none"
                >
                    <h2 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-3 px-2 whitespace-nowrap">Shop Admin</h2>

                    <a href="{{ route('admin.ecommerce.pending-orders') }}" wire:navigate class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-bold text-xs text-slate-600 hover:bg-slate-50 transition duration-150">
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="truncate">Pending Orders</span>
                    </a>

                    <a href="{{ route('admin.ecommerce.products') }}" wire:navigate class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-bold text-xs bg-indigo-50 text-indigo-600 transition duration-150">
                        <svg class="w-4 h-4 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span class="truncate">Products</span>
                    </a>

                    <a href="{{ route('admin.ecommerce.categories') }}" wire:navigate class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-bold text-xs text-slate-600 hover:bg-slate-50 transition duration-150">
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                        <span class="truncate">Categories</span>
                    </a>

                    <a href="{{ route('admin.ecommerce.brands') }}" wire:navigate class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-bold text-xs text-slate-600 hover:bg-slate-50 transition duration-150">
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                        <span class="truncate">Brands</span>
                    </a>

                    <a href="{{ route('admin.ecommerce.orders') }}" wire:navigate class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-bold text-xs text-slate-600 hover:bg-slate-50 transition duration-150">
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span class="truncate">Orders</span>
                    </a>

                    <a href="{{ route('admin.ecommerce.inventory') }}" wire:navigate class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-bold text-xs text-slate-600 hover:bg-slate-50 transition duration-150">
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <span class="truncate">Inventory</span>
                    </a>
                    
                    <a href="javascript:void(0)" wire:click="selectTranslationLang('', 0)" @click="document.querySelector('#translations-section').scrollIntoView({behavior: 'smooth'})" class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-bold text-xs text-slate-600 hover:bg-slate-50 transition duration-150">
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                        <span class="truncate">Translations</span>
                    </a>
                </div>
            </div>

            <!-- Edit Panels Content -->
            <div class="flex-1 min-w-0 space-y-8">
                <!-- Status Message -->
                <x-toast-alert />

                <!-- Quick Nav Anchor Bar -->
                <div class="flex flex-wrap items-center justify-between gap-4 px-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mr-1">Jump to:</span>
                        <a href="#section-product-details"
                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-slate-200 text-slate-600 hover:border-indigo-400 hover:text-indigo-600 text-xs font-semibold rounded-xl shadow-sm transition duration-150">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            Product Details
                        </a>
                        <a href="#section-variants"
                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-slate-200 text-slate-600 hover:border-indigo-400 hover:text-indigo-600 text-xs font-semibold rounded-xl shadow-sm transition duration-150">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Pricing | Variants | Images | Downloads
                        </a>
                        <a href="#section-advanced"
                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-slate-200 text-slate-600 hover:border-indigo-400 hover:text-indigo-600 text-xs font-semibold rounded-xl shadow-sm transition duration-150">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Advanced Settings
                        </a>
                        <a href="#section-layout"
                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-slate-200 text-slate-600 hover:border-violet-400 hover:text-violet-600 text-xs font-semibold rounded-xl shadow-sm transition duration-150">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87V15.13a1 1 0 01-1.447.9L15 14M3 8h12a1 1 0 011 1v6a1 1 0 01-1 1H3a1 1 0 01-1-1V9a1 1 0 011-1z"/></svg>
                            Layout &amp; Video
                        </a>
                        <a href="#section-customizations"
                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-slate-200 text-slate-600 hover:border-amber-400 hover:text-amber-600 text-xs font-semibold rounded-xl shadow-sm transition duration-150">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Customization &amp; Personalization
                        </a>
                        <a href="#section-cross-selling"
                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-slate-200 text-slate-600 hover:border-rose-400 hover:text-rose-600 text-xs font-semibold rounded-xl shadow-sm transition duration-150">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Cross-Selling
                        </a>
                    </div>
                    @if($product->seo_slug)
                        <a href="{{ route('shop.product', ['seo_link' => $product->seo_slug]) }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-2xl shadow-sm hover:shadow-md transition duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            View Product Page
                        </a>
                    @endif
                </div>

                <!-- Product Details Edit Panel -->
                <div id="section-product-details" class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                    <h3 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100">Product Details</h3>
                    <form wire:submit.prevent="updateProduct" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Product Title</label>
                                <input type="text" wire:model.live.debounce.300ms="title" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                @error('title') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">SEO Slug</label>
                                <input type="text" wire:model.live.debounce.300ms="seo_slug" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                            </div>
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
                                             let editor = tinymce.get('long_description_editor');
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
                                         tinymce.init({
                                             selector: '#long_description_editor',
                                             license_key: 'gpl',
                                             promotion: false,
                                             height: 850,
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
                                                     const html = this.long_description || '';
                                                     editor.setContent(window.ensureProseWrapper(html));
                                                     // Force max-width:none on .prose via inline style — beats all CSS cascade & browser cache issues
                                                     editor.getBody().querySelectorAll('.prose').forEach(el => {
                                                         el.style.setProperty('max-width', 'none', 'important');
                                                         el.style.setProperty('width', '100%');
                                                     });
                                                 });
                                                 editor.on('change blur keyup NodeChange SetContent Undo Redo', () => {
                                                      let content = editor.getContent();
                                                      this.long_description = content;
                                                      $wire.set('long_description', content, false);
                                                  });
                                                 editor.on('NodeChange', () => {
                                                     setTimeout(() => {
                                                         let node = editor.selection.getNode();
                                                         let body = editor.getBody();
                                                         let blockNode = node;
                                                         while (blockNode && blockNode.parentNode !== body) {
                                                             blockNode = blockNode.parentNode;
                                                         }
                                                         if (blockNode && blockNode !== body) {
                                                             if (blockNode.nodeName === 'DIV' || blockNode.nodeName === 'BLOCKQUOTE' || blockNode.nodeName === 'DETAILS' || (blockNode.classList && blockNode.classList.contains('faq-accordion'))) {
                                                                 if (!blockNode.nextSibling) {
                                                                     let p = editor.dom.create('p', {}, '<br data-mce-bogus=\'1\'>');
                                                                     editor.dom.insertAfter(p, blockNode);
                                                                     editor.nodeChanged();
                                                                 }
                                                             }
                                                         }
                                                     }, 50);
                                                 });
                                             }
                                         });
                                     },
                                     destroy() {
                                         tinymce.remove('#long_description_editor');
                                     }
                                     }"
                                     x-init="initTiny()">
                                 <textarea id="long_description_editor" class="w-full"></textarea>
                            </div>
                            @error('long_description') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                            <div>
                                <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Meta Title (SEO) <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="meta_title" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                @error('meta_title') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Meta Description (SEO)</label>
                                <textarea wire:model="meta_description" rows="3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 h-24 resize-none"></textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
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

                        <div class="pt-6">
                            <button type="submit" wire:loading.attr="disabled" wire:target="updateProduct" class="px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-2xl shadow-md hover:opacity-90 flex items-center justify-center gap-2">
                                <svg wire:loading wire:target="updateProduct" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Save Details</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Product Variants & Inventory Panel (Moved to Top) -->
                @include('livewire.partials.variant-management')


                <!-- Advanced Settings Panel -->
                <div id="section-advanced" class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                    <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Advanced Settings</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Configure checkout, purchasing, and display rules for this product.</p>
                        </div>
                    </div>
                    
                    <form wire:submit.prevent="updateAdvancedSettings" class="space-y-4">
                        <div class="space-y-4">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" wire:model="max_qty" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 bg-white mt-0.5">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-700">Max 1 per order</span>
                                    <span class="text-xs text-slate-400">Disable Qty Change On Both Item View and shopping cart. Recommended for digital downloads.</span>
                                </div>
                            </label>

                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" wire:model="checkout_redirect" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 bg-white mt-0.5">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-700">Redirect To Checkout After Adding To Cart</span>
                                    <span class="text-xs text-slate-400">Automatically redirect the user to the checkout page instead of the shopping cart or a modal pop-up.</span>
                                </div>
                            </label>

                            {{-- ── Completion Redirect (post-order) ── --}}
                            <div class="pt-4 border-t border-slate-100 space-y-2">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-violet-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <label for="completion_redirect" class="text-xs font-bold text-slate-700 uppercase tracking-wider">Post-Order Completion Redirect</label>
                                </div>
                                <p class="text-xs text-slate-400 leading-relaxed">
                                    When set, the customer will be redirected to this destination <strong class="text-slate-600">instead of the default order confirmation page</strong> after successfully completing checkout.
                                    Enter a full URL (e.g. <code class="font-mono bg-slate-100 px-1 rounded">https://example.com/thank-you</code>) or a CMS page shortcode (e.g. <code class="font-mono bg-slate-100 px-1 rounded">[page:5]</code>).
                                    Leave blank to use the default order confirmation page.
                                </p>
                                <div class="relative">
                                    <input type="text"
                                           id="completion_redirect"
                                           wire:model="completion_redirect"
                                           placeholder="https://… or [page:ID]"
                                           class="w-full px-4 py-2.5 pr-10 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent font-mono text-xs placeholder:font-sans placeholder:text-slate-400 transition">
                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                        @if(trim($completion_redirect))
                                            <span class="w-2 h-2 rounded-full bg-violet-500 animate-pulse"></span>
                                        @else
                                            <span class="w-2 h-2 rounded-full bg-slate-200"></span>
                                        @endif
                                    </div>
                                </div>
                                @error('completion_redirect') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                                @if(trim($completion_redirect))
                                    <div class="flex items-center gap-2 px-3 py-2 bg-violet-50 dark:bg-violet-950/30 border border-violet-200 dark:border-violet-800/40 rounded-xl text-xs text-violet-700 dark:text-violet-300 font-medium">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Redirect active — customers will be sent to: <span class="font-mono ml-1 truncate max-w-xs">{{ $completion_redirect }}</span>
                                    </div>

                                    {{-- Button Label field (only shown when a redirect URL is set) --}}
                                    <div class="flex flex-col gap-1 pt-1">
                                        <label for="completion_redirect_label" class="text-xs font-semibold text-slate-600">
                                            Button Label
                                            <span class="ml-1 font-normal text-slate-400">(shown in order emails next to this item)</span>
                                        </label>
                                        <input type="text"
                                               id="completion_redirect_label"
                                               wire:model="completion_redirect_label"
                                               placeholder="View Content"
                                               maxlength="255"
                                               class="w-full px-3.5 py-2 bg-white border border-violet-200 dark:border-violet-700 text-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent text-xs transition">
                                        @error('completion_redirect_label') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                                        <p class="text-xs text-slate-400">
                                            Leave blank to use the default label: <strong class="text-violet-600">"View Content"</strong>
                                        </p>
                                        {{-- Live button preview --}}
                                        <div class="flex items-center gap-2 pt-1">
                                            <span class="text-xs text-slate-400">Preview:</span>
                                            <span class="inline-block bg-violet-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg">
                                                {{ trim($completion_redirect_label) ?: 'View Content' }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" wire:model="standalone_purchase" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 bg-white mt-0.5">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-700">Standalone purchase only</span>
                                    <span class="text-xs text-slate-400">This is a stand-alone item and cannot be bundled with other products on the same order.</span>
                                </div>
                            </label>

                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" wire:model="dependent_variants" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 bg-white mt-0.5">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-700">Use Dependent Variant Selectors (Drill-down style)</span>
                                    <span class="text-xs text-slate-400">Enable progressive drill-down selector buttons (e.g. Color then Size) instead of listing all variants in a flat radio row.</span>
                                </div>
                            </label>

                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" wire:model="hide_inventory_levels" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 bg-white mt-0.5">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-700">Hide Inventory Levels</span>
                                    <span class="text-xs text-slate-400">If checked, the public stock level displays (e.g. "X in stock") will be hidden on the storefront details view.</span>
                                </div>
                            </label>

                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" wire:model="reviews_enabled" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 bg-white mt-0.5">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-700">Enable Product Reviews</span>
                                    <span class="text-xs text-slate-400">If checked, customers will be able to read and submit reviews for this product on the storefront details view.</span>
                                </div>
                            </label>

                            {{-- Featured Item Toggle --}}
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" wire:model="featured_item" class="rounded border-slate-300 text-amber-500 focus:ring-amber-400 w-4 h-4 bg-white mt-0.5">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold {{ $featured_item ? 'text-amber-600' : 'text-slate-700' }} transition-colors">
                                        ★ Featured Item
                                        @if($featured_item)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-amber-100 text-amber-700 ml-1">Active</span>
                                        @endif
                                    </span>
                                    <span class="text-xs text-slate-400">Mark this product as a featured item. Featured products appear in <strong class="text-slate-600">[plugin:featured-items]</strong> shortcode sections on your CMS pages.</span>
                                </div>
                            </label>

                            {{-- Show Item Total Toggle --}}
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" wire:model="show_item_total" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 bg-white mt-0.5">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold {{ $show_item_total ? 'text-indigo-600' : 'text-slate-700' }} transition-colors">
                                        Show Live Item Total Below Add to Cart
                                        @if($show_item_total)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-indigo-100 text-indigo-700 ml-1">Active</span>
                                        @endif
                                    </span>
                                    <span class="text-xs text-slate-400">When enabled, a live <strong class="text-slate-600">Item Total</strong> (unit price × quantity) is displayed below the Add to Cart button on the product detail page. Updates automatically as the customer changes their quantity.</span>
                                </div>
                            </label>

                            {{-- Donation Or Bill Pay Item Toggle & Settings --}}
                            <div class="pt-4 border-t border-slate-100 space-y-3">
                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input type="checkbox" wire:model.live="is_donation_or_bill_pay" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 w-4 h-4 bg-white mt-0.5">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold {{ $is_donation_or_bill_pay ? 'text-emerald-700' : 'text-slate-700' }} transition-colors">
                                            💚 Donation Or Bill Pay Item
                                            @if($is_donation_or_bill_pay)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-emerald-100 text-emerald-800 ml-1">Active</span>
                                            @endif
                                        </span>
                                        <span class="text-xs text-slate-400">Configure this product as a donation or invoice bill pay item. Overrides standard variant prices, hides quantity controls, and locks cart quantity to 1.</span>
                                    </div>
                                </label>

                                @if($is_donation_or_bill_pay)
                                    <div class="ml-7 p-4 bg-emerald-50/70 border border-emerald-200 rounded-2xl space-y-4 animate-fade-in">
                                        <label class="flex items-start gap-3 cursor-pointer">
                                            <input type="checkbox" wire:model.live="allow_custom_amount" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 w-4 h-4 bg-white mt-0.5">
                                            <div class="flex flex-col">
                                                <span class="text-xs font-bold text-slate-800">Allow Customer to Enter Amount</span>
                                                <span class="text-[11px] text-slate-500">If enabled, an open price input field is presented on the storefront. If disabled, preset options are shown.</span>
                                            </div>
                                        </label>

                                        @if($allow_custom_amount)
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                                                <div>
                                                    <label class="text-[11px] font-bold text-slate-600 uppercase tracking-wider block mb-1">Minimum Amount ($)</label>
                                                    <input type="number" step="0.01" min="0" wire:model="custom_amount_min" placeholder="e.g. 5.00 (optional)" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-emerald-500">
                                                    @error('custom_amount_min') <span class="text-[11px] text-rose-500 font-medium block mt-0.5">{{ $message }}</span> @enderror
                                                </div>
                                                <div>
                                                    <label class="text-[11px] font-bold text-slate-600 uppercase tracking-wider block mb-1">Maximum Amount ($)</label>
                                                    <input type="number" step="0.01" min="0" wire:model="custom_amount_max" placeholder="e.g. 1000.00 (optional)" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-emerald-500">
                                                    @error('custom_amount_max') <span class="text-[11px] text-rose-500 font-medium block mt-0.5">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        @else
                                            <div class="pt-2">
                                                <label class="text-[11px] font-bold text-slate-600 uppercase tracking-wider block mb-1">Preset Amounts List (Comma-Separated)</label>
                                                <input type="text" wire:model="custom_amount_options" placeholder="e.g. 10, 25, 50, 100, 500" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-emerald-500">
                                                <p class="text-[11px] text-slate-400 mt-1">Enter a comma-delimited list of numbers. These will render as select options on the storefront.</p>
                                                @error('custom_amount_options') <span class="text-[11px] text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Search Index & Lock Control Card -->
                            <div class="pt-6 border-t border-slate-100 space-y-4">
                                <div class="flex items-center justify-between flex-wrap gap-3">
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                            Search Index Keywords &amp; Lock Control
                                        </h4>
                                        <p class="text-xs text-slate-400 mt-0.5">Collated keywords used by Live Search to index this product. When locked, saving this product will not overwrite custom admin keywords.</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <button type="button" wire:click="rebuildIndexKeywords" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            Rebuild Index
                                        </button>
                                        <label class="relative inline-flex items-center cursor-pointer select-none">
                                             <input type="checkbox" wire:model="product_search_index_locked" class="sr-only peer">
                                             <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-600"></div>
                                             <span class="ml-2.5 px-2.5 py-1 rounded-lg text-2xs font-black uppercase tracking-wider transition-all inline-flex items-center gap-1 shadow-xs"
                                                   :class="$wire.product_search_index_locked ? 'bg-amber-500 text-white ring-2 ring-amber-400/40 shadow-amber-500/20' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400'">
                                                 <svg class="w-3 h-3" x-show="$wire.product_search_index_locked" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                 <svg class="w-3 h-3" x-show="!$wire.product_search_index_locked" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                                                 <span x-text="$wire.product_search_index_locked ? 'Locked' : 'Unlocked'"></span>
                                             </span>
                                         </label>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 mb-1">Collated Search Index (Editable)</label>
                                    <textarea wire:model="product_search_index" rows="4" placeholder="Add custom search keywords, synonyms, promo codes, misspellings..." class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- ── Out-of-Stock Alert Message ──────────────────────────── --}}
                        <div class="pt-6 border-t border-slate-100 space-y-3">
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <label for="inventory_alert_id" class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                                        Out-of-Stock Message
                                    </label>
                                    <p class="text-xs text-slate-400 mt-0.5 leading-relaxed">
                                        Shown on the storefront when this product has zero available stock. Leave as default to display the standard "Currently Unavailable" label.
                                    </p>
                                </div>
                            </div>

                            <select id="inventory_alert_id" wire:model="inventory_alert_id"
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 shadow-sm">
                                <option value="">— Default: Currently Unavailable —</option>
                                @foreach($inventoryAlerts as $alert)
                                    <option value="{{ $alert->id }}">{{ $alert->message }}</option>
                                @endforeach
                            </select>

                            @if($inventory_alert_id)
                            <div class="flex items-center gap-2 px-3 py-2 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 font-medium">
                                <svg class="w-3.5 h-3.5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                                <span>When out of stock, customers will see:
                                    <strong class="text-amber-900 ml-1">
                                        "{{ $inventoryAlerts->firstWhere('id', $inventory_alert_id)?->message ?? 'Currently Unavailable' }}"
                                    </strong>
                                </span>
                            </div>
                            @else
                            <div class="flex items-center gap-2 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-500">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Using default: <strong class="text-slate-600">"Currently Unavailable"</strong></span>
                            </div>
                            @endif

                            @error('inventory_alert_id') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror

                            <p class="text-[11px] text-slate-400">
                                Manage the full list of messages at
                                <a href="{{ route('admin.inventory-alerts.index') }}" wire:navigate
                                   class="text-indigo-600 hover:underline font-semibold">Admin → Inventory Alert Messages</a>.
                            </p>
                        </div>

                        <div class="pt-6 border-t border-slate-100 flex justify-end">
                            <button type="submit" wire:loading.attr="disabled" wire:target="updateAdvancedSettings" class="px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-2xl shadow-md hover:opacity-90 flex items-center justify-center gap-2">
                                <svg wire:loading wire:target="updateAdvancedSettings" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Save Advanced Settings</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ═══════ Layout & Video Section ═══════ -->
                <div id="section-layout" class="rounded-3xl shadow-md overflow-hidden border border-violet-200/70">

                    {{-- Header gradient --}}
                    <div class="bg-gradient-to-r from-violet-600 to-purple-600 px-8 py-5 flex items-center gap-4">
                        <div class="p-2.5 bg-white/20 rounded-2xl backdrop-blur-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87V15.13a1 1 0 01-1.447.9L15 14M3 8h12a1 1 0 011 1v6a1 1 0 01-1 1H3a1 1 0 01-1-1V9a1 1 0 011-1z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-white tracking-tight">Layout &amp; Video Embed</h3>
                            <p class="text-violet-200 text-xs mt-0.5">Choose the product page layout and optionally embed a video for layouts 3 &amp; 5.</p>
                        </div>
                    </div>

                    <form wire:submit.prevent="updateLayoutSettings" class="bg-white p-8 space-y-6">

                        {{-- Layout Type --}}
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-700 block uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10-3a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1v-7z"/></svg>
                                Product Page Layout
                            </label>
                            <select wire:model.live="layout_type" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-violet-500 font-medium text-sm">
                                <option value="1">Layout 1 — Right Side Images (Default)</option>
                                <option value="2">Layout 2 — Left Side Images</option>
                                <option value="3">Layout 3 — Right Side Images + Large Video Player Below</option>
                                <option value="4">Layout 4 — Centered Layout With Images On Top</option>
                                <option value="5">Layout 5 — Centered Layout + Large Video Player On Top</option>
                                <option value="6">Layout 6 — No Images | Video On Page</option>
                            </select>
                            @error('layout_type') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                        </div>

                        {{-- Video Embed (for layouts 3, 5 & 6) --}}
                        @if($layout_type == 3 || $layout_type == 5 || $layout_type == 6)
                            <div class="space-y-3 p-5 bg-violet-50 border border-violet-200 rounded-2xl">
                                <div class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-violet-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <label class="text-xs font-bold text-violet-800 block uppercase tracking-wider">Video Embed for Layout {{ $layout_type }}</label>
                                        <p class="text-[11px] text-violet-600 mt-0.5">Enter a CMS code embed shortcode <strong>[code-embed:ID]</strong> — or paste raw <strong>&lt;iframe&gt;</strong> / HTML embed code directly.</p>
                                    </div>
                                </div>
                                <textarea
                                    wire:model="product_video_embed"
                                    rows="5"
                                    placeholder="e.g.  [code-embed:12]   OR   <iframe src=&quot;https://www.youtube.com/embed/...&quot; ...></iframe>"
                                    class="w-full px-4 py-3 bg-white border border-violet-200 text-slate-800 rounded-xl focus:outline-none focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 text-xs font-mono resize-y"
                                ></textarea>
                                @error('product_video_embed') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                                @if($product_video_embed)
                                    <p class="text-[10px] text-violet-500 font-medium">
                                        ✓ Video embed set &mdash; will render in the video area of layout {{ $layout_type }} on the product page.
                                    </p>
                                @endif
                            </div>
                        @else
                            <div class="flex items-center gap-2 px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs text-slate-400">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Video embed is only available for <strong class="text-slate-600 mx-1">Layout 3</strong> and <strong class="text-slate-600 mx-1">Layout 5</strong>. Select one of those layouts above to enable this field.
                            </div>
                        @endif

                        <div class="pt-4 border-t border-slate-100 flex justify-end">
                            <button type="submit"
                                    wire:loading.attr="disabled"
                                    wire:target="updateLayoutSettings"
                                    class="px-6 py-2.5 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white font-bold rounded-2xl shadow-md hover:shadow-violet-500/30 flex items-center gap-2 transition-all duration-200">
                                <svg wire:loading.remove wire:target="updateLayoutSettings" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <svg wire:loading wire:target="updateLayoutSettings" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                                Save Layout Settings
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Customization Fields Builder Panel -->
                <div id="section-customizations" class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                    <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                        <div class="p-2 bg-indigo-50 rounded-xl">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Product Customization Fields</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Define custom input fields, selects, and checkboxes for users to customize this product.</p>
                        </div>
                    </div>

                    @if($product->fields->isEmpty())
                        <div class="p-6 bg-slate-50 border border-dashed border-slate-200 rounded-2xl text-center">
                            <p class="text-xs font-bold text-slate-500">No customization fields defined yet.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($product->fields as $field)
                                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-between gap-4">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-slate-800">{{ $field->label }}</span>
                                            <span class="px-2 py-0.5 bg-slate-200 text-slate-600 rounded text-[9px] font-bold uppercase">{{ $field->field_type }}</span>
                                            @if($field->is_required)
                                                <span class="px-2 py-0.5 bg-red-50 text-red-600 rounded text-[9px] font-bold uppercase">Required</span>
                                            @endif
                                            <span class="text-[10px] text-slate-400 font-medium">Order: {{ $field->sort_order }}</span>
                                        </div>
                                        @if($field->options->isNotEmpty())
                                            <div class="flex flex-wrap gap-1.5 mt-1.5">
                                                @foreach($field->options as $opt)
                                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-white border border-slate-200 text-[10px] font-semibold text-slate-600 rounded-lg">
                                                        <span>{{ $opt->option_value }}</span>
                                                        @if($opt->option_price_modifier > 0 || $opt->option_wholesale_price_modifier > 0)
                                                            <span class="text-indigo-600 font-bold">
                                                                (R: +${{ number_format($opt->option_price_modifier, 2) }} / W: +${{ number_format($opt->option_wholesale_price_modifier, 2) }})
                                                            </span>
                                                        @endif
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        <button type="button" wire:click="editCustomField({{ $field->id }})" class="px-3 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-[10px] font-bold rounded-lg transition duration-150">Edit</button>
                                        <button type="button" onclick="confirm('Are you sure you want to delete this field?') || event.stopImmediatePropagation()" wire:click="deleteCustomField({{ $field->id }})" class="px-3 py-1 bg-red-50 hover:bg-red-100 text-red-600 text-[10px] font-bold rounded-lg transition duration-150">Delete</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Add/Edit Field Form --}}
                    <div class="bg-slate-50 border border-slate-200 rounded-3xl p-6 space-y-4">
                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ $isEditingField ? 'Edit Field' : 'Add Custom Field' }}</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="md:col-span-2">
                                <label class="text-[10px] font-bold text-slate-400 block mb-1 uppercase tracking-wider">Field Label / Prompt</label>
                                <input type="text" wire:model="customFieldLabel" placeholder="e.g. Engraving Text, Size options" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                @error('customFieldLabel') <span class="text-xs text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 block mb-1 uppercase tracking-wider">Field Input Type</label>
                                <select wire:model.live="customFieldType" class="w-full px-3 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                    <option value="text">Single Line Text</option>
                                    <option value="textarea">Multi-line Text</option>
                                    <option value="select">Dropdown Menu (List)</option>
                                    <option value="radio">Radio Buttons Group</option>
                                    <option value="checkbox">Single On/Off Checkbox</option>
                                    <option value="multiselect_checkbox">Multiple Checkboxes Group</option>
                                </select>
                                @error('customFieldType') <span class="text-xs text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 block mb-1 uppercase tracking-wider">Sort Order</label>
                                <input type="number" wire:model="customFieldSortOrder" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                @error('customFieldSortOrder') <span class="text-xs text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex items-center gap-6 py-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="customFieldIsRequired" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 bg-white">
                                <span class="text-xs font-bold text-slate-700">Required Field</span>
                            </label>
                            {{-- Charge Tax Toggle --}}
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <div class="relative">
                                    <input type="checkbox" wire:model.number="customFieldChargeTax" class="sr-only peer" true-value="1" false-value="0">
                                    <div class="w-10 h-5 bg-slate-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-emerald-400 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                                </div>
                                <span class="text-xs font-bold {{ $customFieldChargeTax ? 'text-emerald-700' : 'text-slate-400' }} transition-colors">
                                    {{ $customFieldChargeTax ? 'Tax Price Modifier' : 'Tax Exempt (No Tax)' }}
                                </span>
                            </label>
                        </div>

                        {{-- Options/Choices List for multi-choice fields --}}
                        @if(in_array($customFieldType, ['select', 'radio', 'checkbox', 'multiselect_checkbox']))
                            <div class="pt-4 border-t border-slate-200 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">
                                        {{ $customFieldType === 'checkbox' ? 'Checkbox Configuration' : 'Choices / Options List' }}
                                    </span>
                                    @if($customFieldType !== 'checkbox')
                                        <button type="button" wire:click="addFieldOptionRow" class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 focus:outline-none">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                            Add Option
                                        </button>
                                    @endif
                                </div>

                                @if($customFieldType === 'checkbox' && empty($fieldOptions))
                                    @php $this->addFieldOptionRow(); @endphp
                                @endif

                                <div class="space-y-2">
                                    @foreach($fieldOptions as $index => $opt)
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end bg-white border border-slate-200 p-4 rounded-2xl relative shadow-sm" wire:key="field-option-{{ $index }}">
                                            <div class="md:col-span-2">
                                                <label class="text-[9px] font-bold text-slate-400 block mb-1 uppercase tracking-wider">Option Value / Label</label>
                                                <input type="text" wire:model="fieldOptions.{{ $index }}.option_value" placeholder="e.g. Include Gift Wrapping, Red, XL" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-lg focus:outline-none focus:border-indigo-500 text-xs">
                                                @error("fieldOptions.{$index}.option_value") <span class="text-xs text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="text-[9px] font-bold text-slate-400 block mb-1 uppercase tracking-wider">Retail Surcharge ($)</label>
                                                <input type="number" step="0.01" wire:model="fieldOptions.{{ $index }}.option_price_modifier" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-lg focus:outline-none focus:border-indigo-500 text-xs">
                                            </div>
                                            <div class="relative">
                                                <label class="text-[9px] font-bold text-slate-400 block mb-1 uppercase tracking-wider">Wholesale Surcharge ($)</label>
                                                <input type="number" step="0.01" wire:model="fieldOptions.{{ $index }}.option_wholesale_price_modifier" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-lg focus:outline-none focus:border-indigo-500 text-xs">
                                                
                                                @if($customFieldType !== 'checkbox')
                                                    <button type="button" wire:click="removeFieldOptionRow({{ $index }})" class="absolute -right-2 top-0 text-red-500 hover:text-red-700 focus:outline-none">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="pt-4 border-t border-slate-200 flex justify-end gap-2">
                            @if($isEditingField)
                                <button type="button" wire:click="resetFieldForm" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-xs transition duration-150">Cancel</button>
                            @endif
                            <button type="button" wire:click="saveCustomField" wire:loading.attr="disabled" wire:target="saveCustomField" class="px-5 py-2.5 bg-indigo-600 text-white font-bold rounded-xl shadow-md hover:opacity-90 flex items-center justify-center gap-2 text-xs">
                                <svg wire:loading wire:target="saveCustomField" class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>{{ $isEditingField ? 'Update Field' : 'Save Field' }}</span>
                            </button>
                        </div>

                        {{-- ── Field & Option Translations (only when editing an existing field) ── --}}
                        @if($isEditingField && isset($activeLanguages) && $activeLanguages->count() > 0)
                        <div class="mt-2 border border-violet-200 bg-violet-50/40 rounded-2xl p-5 space-y-4">
                            <div class="flex items-center gap-2 pb-2 border-b border-violet-100">
                                <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                                </svg>
                                <h4 class="text-xs font-bold text-violet-700 uppercase tracking-wider">Field &amp; Option Translations</h4>
                                <span class="ml-auto text-[10px] text-slate-400 hidden sm:block">Translate the field label and option values shown on the storefront</span>
                            </div>

                            {{-- Language pills --}}
                            <div class="flex flex-wrap gap-2">
                                @foreach($activeLanguages as $lang)
                                    @php
                                        $fTrans   = \App\Models\ProductFieldTranslation::where('product_field_id', $selectedFieldId)
                                                        ->where('language_id', $lang->id)->first();
                                        $fHasData = $fTrans && $fTrans->label;
                                    @endphp
                                    <button type="button"
                                            wire:click="selectFieldTranslationLang('{{ $lang->code }}', {{ $lang->id }})"
                                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold border transition
                                                   {{ $fieldTransLangCode === $lang->code
                                                          ? 'bg-violet-600 text-white border-violet-600'
                                                          : 'bg-white text-slate-600 border-slate-200 hover:border-violet-300' }}">
                                        <span>{{ $lang->flag_emoji }}</span>
                                        {{ $lang->native_name }}
                                        @if($fHasData)
                                            <span class="text-[9px] px-1.5 py-0.5 rounded-full
                                                         {{ $fieldTransLangCode === $lang->code ? 'bg-white/30 text-white' : 'bg-emerald-200 text-emerald-800' }}">✓</span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>

                            @if($fieldTransLangCode)
                                <div class="space-y-4 bg-white rounded-xl p-4 border border-violet-100">
                                    {{-- Field Label Translation --}}
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Field Label / Prompt</label>
                                        <input type="text" wire:model="trans_field_label"
                                               placeholder="Translated field label…"
                                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-violet-400">
                                    </div>

                                    {{-- Option Value Translations (only for choice-based fields with saved options) --}}
                                    @if(!empty($fieldOptions) && in_array($customFieldType, ['select', 'radio', 'checkbox', 'multiselect_checkbox']))
                                        @php $hasOptionIds = collect($fieldOptions)->filter(fn($o) => !empty($o['id']))->isNotEmpty(); @endphp
                                        @if($hasOptionIds)
                                            <div class="pt-3 border-t border-slate-100 space-y-2">
                                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Option Value Translations</p>
                                                @foreach($fieldOptions as $opt)
                                                    @if(!empty($opt['id']))
                                                        <div class="flex items-center gap-3">
                                                            <span class="text-xs text-slate-500 font-medium min-w-[130px] truncate" title="{{ $opt['option_value'] }}">{{ $opt['option_value'] }}</span>
                                                            <svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                                            <input type="text" wire:model="trans_field_options.{{ $opt['id'] }}"
                                                                   placeholder="Translated value…"
                                                                   class="flex-1 px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-violet-400">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif

                                    <div class="flex justify-end pt-1">
                                        <button type="button" wire:click="saveFieldTranslation" wire:loading.attr="disabled"
                                                class="flex items-center gap-2 px-5 py-2 bg-violet-600 hover:bg-violet-700 text-white rounded-xl text-xs font-bold shadow transition">
                                            <span wire:loading wire:target="saveFieldTranslation"
                                                  class="animate-spin w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full inline-block"></span>
                                            Save Field Translation
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                <!-- Cross-Selling Section -->
                @include('livewire.partials.cross-selling')

                <!-- Product Reviews Section -->
                <div id="section-reviews" class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-amber-50 rounded-xl">
                                <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Product Reviews & Comments</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Manage, approve, edit, and delete user-submitted reviews for this product.</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-extrabold text-indigo-600">Avg Rating: {{ number_format($product->reviews_rating, 2) }} ★</span>
                            <span class="text-xs text-slate-400 block">Total Reviews: {{ $product->reviews->count() }}</span>
                        </div>
                    </div>

                    {{-- Edit Review Form --}}
                    @if($isEditingReview)
                        <div class="p-6 bg-slate-50 border border-slate-200 rounded-3xl space-y-4">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700">Edit Product Review</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Reviewer Name</label>
                                    <input type="text" wire:model="reviewName" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs">
                                    @error('reviewName') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Location</label>
                                    <input type="text" wire:model="reviewLocation" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs">
                                    @error('reviewLocation') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Rating (1-5)</label>
                                    <select wire:model="reviewRating" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs">
                                        <option value="1">1 Star</option>
                                        <option value="2">2 Stars</option>
                                        <option value="3">3 Stars</option>
                                        <option value="4">4 Stars</option>
                                        <option value="5">5 Stars</option>
                                    </select>
                                    @error('reviewRating') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Comments</label>
                                <textarea wire:model="reviewComments" rows="3" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs"></textarea>
                                @error('reviewComments') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex items-center gap-3">
                                <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                                    <input type="checkbox" wire:model="reviewApproved" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 bg-white">
                                    <span>Approved</span>
                                </label>
                            </div>
                            <div class="flex justify-end gap-2">
                                <button type="button" wire:click="cancelEditReview" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-xs transition duration-150">Cancel</button>
                                <button type="button" wire:click="saveReview" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition duration-150">Save Changes</button>
                            </div>
                        </div>
                    @endif

                    {{-- Reviews List Table --}}
                    @if($product->reviews->isEmpty())
                        <div class="p-6 bg-slate-50 border border-dashed border-slate-200 rounded-2xl text-center">
                            <p class="text-xs font-bold text-slate-500">No reviews submitted yet for this product.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                                        <th class="py-3 px-4">Reviewer</th>
                                        <th class="py-3 px-4">Location</th>
                                        <th class="py-3 px-4">Rating</th>
                                        <th class="py-3 px-4">Comment</th>
                                        <th class="py-3 px-4">Status</th>
                                        <th class="py-3 px-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-700">
                                    @foreach($product->reviews as $review)
                                        <tr class="hover:bg-slate-50 transition duration-150">
                                            <td class="py-3 px-4 font-bold">{{ $review->name }}</td>
                                            <td class="py-3 px-4">{{ $review->location ?? '-' }}</td>
                                            <td class="py-3 px-4 text-amber-500 font-bold">{{ $review->rating }} ★</td>
                                            <td class="py-3 px-4 max-w-xs truncate" title="{{ $review->comments }}">{{ $review->comments ?? '-' }}</td>
                                            <td class="py-3 px-4">
                                                @if($review->approved)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600">Approved</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-600">Pending</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4 text-right space-x-1.5 whitespace-nowrap">
                                                <button type="button" wire:click="toggleReviewApproval({{ $review->id }})" class="inline-flex items-center px-2 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition duration-150">
                                                    {{ $review->approved ? 'Unapprove' : 'Approve' }}
                                                </button>
                                                <button type="button" wire:click="editReview({{ $review->id }})" class="inline-flex items-center px-2 py-1 rounded bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold transition duration-150">
                                                    Edit
                                                </button>
                                                <button type="button" wire:click="deleteReview({{ $review->id }})" wire:confirm="Are you sure you want to delete this review?" class="inline-flex items-center px-2 py-1 rounded bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold transition duration-150">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        {{-- ── Translations Section ───────────────────────────────────────── --}}
        @if(isset($activeLanguages) && $activeLanguages->count() > 0)
        <div id="translations-section" class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
            <div x-data="{ open: false }">
                <button @click="open = !open" type="button"
                        class="w-full flex items-center justify-between px-6 py-4 hover:bg-slate-50 transition">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                        <span class="font-bold text-slate-800 text-sm">Translations</span>
                        <span class="px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold">{{ $activeLanguages->count() }} language(s)</span>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div x-show="open" class="border-t border-slate-100 p-6 space-y-5" style="display:none">
                    {{-- Flash --}}
                    @if(session()->has('success'))
                        <div class="p-3 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-800 text-sm font-semibold">{{ session('success') }}</div>
                    @endif

                    {{-- Language pills --}}
                    <div class="flex flex-wrap gap-2">
                        @foreach($activeLanguages as $lang)
                            @php $tRecord = \App\Models\ProductTranslation::where('product_id', $productId)->where('language_id', $lang->id)->first(); @endphp
                            <button wire:click="selectTranslationLang('{{ $lang->code }}', {{ $lang->id }})" type="button"
                                    class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold border transition
                                           {{ $activeLangCode === $lang->code ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-600 border-slate-200 hover:border-indigo-300' }}">
                                <span>{{ $lang->flag_emoji }}</span>
                                {{ $lang->native_name }}
                                @if($tRecord)
                                    <span class="text-[10px] px-1.5 py-0.5 rounded-full {{ $tRecord->translation_status === 'reviewed' ? 'bg-emerald-200 text-emerald-800' : 'bg-amber-200 text-amber-800' }}">{{ $tRecord->translation_status === 'reviewed' ? '✓' : 'AI' }}</span>
                                @endif
                            </button>
                        @endforeach
                    </div>

                    @if($activeLangCode)
                    <div class="space-y-4 bg-slate-50 rounded-2xl p-5 border border-slate-200">
                        {{-- Status + auto-translate --}}
                        <div class="flex items-center justify-between flex-wrap gap-3">
                            <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $trans_status === 'reviewed' ? 'bg-emerald-100 text-emerald-800' : ($trans_status === 'ai_translated' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600') }}">
                                {{ $trans_status === 'reviewed' ? 'Reviewed' : ($trans_status === 'ai_translated' ? 'AI Translated' : 'Pending') }}
                                @if($trans_translated_at) &nbsp;· {{ $trans_translated_at }}@endif
                            </span>
                        <div class="flex items-center flex-wrap gap-2">
                            <button wire:click="aiTranslateProductInline" wire:loading.attr="disabled" type="button"
                                    title="Generate a fresh AI translation now. Results fill the fields below for your review before saving."
                                    class="flex items-center gap-2 px-4 py-2 bg-violet-50 hover:bg-violet-100 text-violet-700 border border-violet-200 rounded-xl text-xs font-bold transition">
                                <span wire:loading wire:target="aiTranslateProductInline" class="animate-spin w-3.5 h-3.5 border-2 border-violet-400 border-t-transparent rounded-full inline-block"></span>
                                <svg class="w-4 h-4" wire:loading.remove wire:target="aiTranslateProductInline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                ✦ Generate Translation
                            </button>
                            <button wire:click="autoTranslateProduct" type="button" wire:loading.attr="disabled"
                                    title="Queue a background translation job. Page will refresh with AI-translated content."
                                    class="flex items-center gap-2 px-4 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 rounded-xl text-xs font-bold transition">
                                <span wire:loading wire:target="autoTranslateProduct" class="animate-spin w-3.5 h-3.5 border-2 border-amber-500 border-t-transparent rounded-full inline-block"></span>
                                Queue Bulk Job
                            </button>
                        </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Product Title</label>
                            <input type="text" wire:model="trans_title" placeholder="Translated product name..."
                                   class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Short Description</label>
                            <textarea wire:model="trans_short_description" rows="3" placeholder="Translated short description..."
                                      class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Long Description (HTML)</label>
                            <textarea wire:model="trans_long_description" rows="10" placeholder="Translated full description (HTML supported)..."
                                      class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:border-indigo-400"></textarea>
                            <p class="text-xs text-slate-400 mt-1">Plugin shortcodes [plugin:...] are preserved automatically during AI translation.</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Meta Title</label>
                                <input type="text" wire:model="trans_meta_title" placeholder="Translated SEO title..."
                                       class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Meta Description</label>
                                <input type="text" wire:model="trans_meta_description" placeholder="Translated SEO description..."
                                       class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400">
                            </div>
                        </div>
                        <div class="flex justify-end pt-2">
                            <button wire:click="saveProductTranslation" type="button" wire:loading.attr="disabled"
                                    class="flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow transition">
                                <span wire:loading wire:target="saveProductTranslation" class="animate-spin w-4 h-4 border-2 border-white/30 border-t-white rounded-full inline-block"></span>
                                Save Translation
                            </button>
                        </div>
                    </div>
                    @else
                        <p class="text-slate-400 text-sm text-center py-4">Select a language above to view or edit its translation.</p>
                    @endif
                </div>
            </div>
        </div>
        @endif
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

    <script src="{{ asset('build/node_modules/tinymce/tinymce.min.js') }}"></script>
    <script>
        window.ensureProseWrapper = function (html) {
            if (!html || !html.trim()) {
                return '<p>&nbsp;</p>';
            }
            // Preserve prose wrapper markup intact on page load
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
                    if (xhr.status < 200 || xhr.status >= 300) {
                        let errMsg = 'HTTP Error: ' + xhr.status;
                        try {
                            const errJson = JSON.parse(xhr.responseText);
                            if (errJson && errJson.error) errMsg += ' — ' + errJson.error;
                        } catch(e) {}
                        console.error('[TinyMCE Upload Error] Status:', xhr.status, 'Body:', xhr.responseText);
                        reject({ message: errMsg, remove: true });
                        return;
                    }
                    let json;
                    try { json = JSON.parse(xhr.responseText); } catch(e) {
                        console.error('[TinyMCE Upload Error] Invalid JSON:', xhr.responseText);
                        reject('Invalid JSON response from server');
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
            let editor = tinymce.activeEditor;
            if (!editor) {
                editor = tinymce.get('long_description_editor');
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
                    // Append the widget elements to the bottom of the body
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
            let editor = tinymce.activeEditor;
            if (!editor) {
                editor = tinymce.get('long_description_editor');
            }
            if (editor) {
                editor.undoManager.transact(() => {
                    let body = editor.getBody();

                    // Create a paragraph containing the shortcode
                    let shortcodeParagraph = editor.dom.create('p', {}, shortcodeString);
                    body.appendChild(shortcodeParagraph);

                    // Add a trailing spacer paragraph so the user can type below
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

    <!-- Floating Save All Sections Button -->
    <div class="fixed bottom-8 right-8 z-40">
        <button @click="if (typeof tinymce !== 'undefined') { let ed = tinymce.get('long_description_editor'); if (ed) $wire.set('long_description', ed.getContent()); }"
                wire:click="saveAllSections"
                wire:loading.attr="disabled"
                type="button"
                class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-extrabold text-sm rounded-2xl shadow-xl hover:shadow-2xl flex items-center gap-2.5 transition-all duration-200 group border border-indigo-500/30 backdrop-blur-sm">
            <svg wire:loading.remove wire:target="saveAllSections" class="w-5 h-5 text-indigo-200 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
            </svg>
            <svg wire:loading wire:target="saveAllSections" class="animate-spin w-5 h-5 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Save All Sections</span>
        </button>
    </div>
</div>
