@php
    $primaryColor = \App\Models\CmsSetting::get('theme_primary_color', '#4f46e5');
    $hoverColor = \App\Models\CmsSetting::get('theme_hover_color', '#4338ca');
    $textColor = \App\Models\CmsSetting::get('theme_text_color', '#ffffff');
    $borderRadius = \App\Models\CmsSetting::get('theme_border_radius', '0.75rem');
@endphp
<div class="py-12" x-data="{
    activeTab: 'details', 
    showWidgetLibrary: false,
    showPluginsPanel: false,
    showLinkGenerator: false,
    showShortcodeGenerator: false,
    showAnimatePanel: false,
    selectedRecord: null,
    sidebarOpen: true,
    autosaveStatus: '',
    idleTimer: null,
    initIdleWatch() {
        const resetTimer = () => {
            clearTimeout(this.idleTimer);
            this.idleTimer = setTimeout(() => {
                this.autosaveStatus = 'Saving draft...';
                $wire.saveAutoSaveRevision().then(() => {
                    this.autosaveStatus = 'Draft auto-saved at ' + new Date().toLocaleTimeString();
                    setTimeout(() => { this.autosaveStatus = ''; }, 5000);
                });
            }, 10 * 60 * 1000); // 10 minutes
        };
        window.addEventListener('input', resetTimer);
        window.addEventListener('keydown', resetTimer);
        resetTimer();
    }
}" x-init="initIdleWatch()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">
            <div>
                <a href="{{ route('admin.cms-pages.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 hover:text-indigo-800 uppercase tracking-wider mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Pages
                </a>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 bg-gradient-to-r from-slate-900 to-indigo-950 bg-clip-text text-transparent">
                    {{ $pageId ? 'Edit CMS Page' : 'Create Custom Page' }}
                </h1>
            </div>
            
            <div class="flex items-center gap-3">
                <span x-text="autosaveStatus" class="text-xs text-slate-400 font-medium italic"></span>
                
                <!-- Status Toggle Switch -->
                <div class="flex items-center gap-2.5 px-3 py-1.5 bg-slate-100 rounded-2xl border border-slate-200/40">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Status:</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="is_active" class="sr-only peer">
                        <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                        <span class="ml-2 text-xs font-bold text-slate-700 uppercase tracking-wider" x-text="$wire.is_active ? 'Active' : 'Draft'"></span>
                    </label>
                </div>

                @if($pageId && $slug)
                    <a href="{{ route('page.show', $slug) }}" target="_blank" 
                       class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-2xl transition duration-150 inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        View Live Page
                    </a>
                @endif
            </div>
        </div>

        <x-toast-alert />

        <!-- Layout Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start relative">
            
            <!-- Left Navigation Sidebar -->
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition ease-out duration-205 transform"
                 x-transition:enter-start="opacity-0 -translate-x-4"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-195 transform"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 -translate-x-4"
                 class="lg:col-span-3 bg-white/80 backdrop-blur-md border border-slate-200/60 rounded-3xl p-6 shadow-sm space-y-2">
                <button @click="activeTab = 'details'" 
                        :class="activeTab === 'details' ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600 hover:bg-slate-50 font-medium'"
                        class="w-full text-left px-4 py-3 rounded-2xl transition duration-150 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Page Details
                </button>
                <button @click="activeTab = 'publishing'" 
                        :class="activeTab === 'publishing' ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600 hover:bg-slate-50 font-medium'"
                        class="w-full text-left px-4 py-3 rounded-2xl transition duration-150 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Publishing Info
                </button>
                <button @click="activeTab = 'security'" 
                        :class="activeTab === 'security' ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600 hover:bg-slate-50 font-medium'"
                        class="w-full text-left px-4 py-3 rounded-2xl transition duration-150 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Security &amp; Gating
                </button>
                <button @click="activeTab = 'layout'" 
                        :class="activeTab === 'layout' ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600 hover:bg-slate-50 font-medium'"
                        class="w-full text-left px-4 py-3 rounded-2xl transition duration-150 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Header &amp; Background Images | Video Settings
                </button>
                <button @click="activeTab = 'code'" 
                        :class="activeTab === 'code' ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600 hover:bg-slate-50 font-medium'"
                        class="w-full text-left px-4 py-3 rounded-2xl transition duration-150 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    Custom CSS &amp; JS
                </button>
                @if($pageId)
                    <button @click="activeTab = 'revisions'" 
                            :class="activeTab === 'revisions' ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600 hover:bg-slate-50 font-medium'"
                            class="w-full text-left px-4 py-3 rounded-2xl transition duration-150 text-sm flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Revisions History
                    </button>
                @endif
                @if($pageId && $activeLanguages->count() > 0)
                    <button @click="activeTab = 'translations'"
                            :class="activeTab === 'translations' ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600 hover:bg-slate-50 font-medium'"
                            class="w-full text-left px-4 py-3 rounded-2xl transition duration-150 text-sm flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                        Translations
                        <span class="ml-auto px-1.5 py-0.5 rounded-full bg-indigo-100 text-indigo-700 text-[10px] font-bold">{{ $activeLanguages->count() }}</span>
                    </button>
                @endif
            </div>

            <!-- Content Area Form -->
            <div :class="sidebarOpen ? 'lg:col-span-9' : 'lg:col-span-12'" class="space-y-6 transition-all duration-200">
                <!-- Toggle Button to collapse/expand Left Sidebar -->
                <div class="flex items-center justify-start mb-2">
                    <button type="button" 
                            x-on:click="sidebarOpen = !sidebarOpen" 
                            class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold uppercase tracking-wider rounded-2xl flex items-center gap-1.5 transition-all shadow-sm">
                        <svg class="w-4 h-4 transition-transform duration-200" :class="sidebarOpen ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        <span x-text="sidebarOpen ? 'Collapse Menu' : 'Expand Menu'"></span>
                    </button>
                </div>
                <form wire:submit="save" class="space-y-6">
                    
                    <!-- Page Details Tab -->
                    <div x-show="activeTab === 'details'" class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                        <h3 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-indigo-600 rounded"></span> Page Content &amp; Meta
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Page Title</label>
                                <input type="text" wire:model.live="title" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500" placeholder="e.g. Help Page" />
                                @error('title') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">SEO Slug</label>
                                <input type="text" wire:model.live="slug" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500" placeholder="e.g. help-page" />
                                @error('slug') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>
   <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                            <div>
                                <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Meta Title (SEO)</label>
                                <input type="text" wire:model="meta_title" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500" />
                                @error('meta_title') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Meta Description (SEO) (Optional)</label>
                                <textarea wire:model="meta_description" rows="3" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 h-24"></textarea>
                                @error('meta_description') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <!-- Layout Selector -->
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 block uppercase tracking-wider block mb-1">Page Layout Option</label>
                            <select wire:model.live="layout_type" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 font-medium">
                                @foreach($layouts as $l)
                                    <option value="{{ $l->id }}">{{ $l->name }}</option>
                                @endforeach
                            </select>
                            @error('layout_type') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <!-- Dynamic Editors Interface based on Selected Layout -->
                        <div class="flex flex-col lg:flex-row gap-6 items-stretch">
                            
                            @if(in_array($layout_type, [2, 4]))
                                <!-- Left Sidebar Editor -->
                                <div class="w-full lg:w-1/4 space-y-2 flex flex-col" wire:key="left-editor-container">
                                    <label class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Left Sidebar Column</label>
                                    <div class="flex-1" wire:ignore 
                                         x-data="{
                                             leftCol: @entangle('left_col'),
                                             initTiny() {
                                                 tinymce.init({
                                                     selector: '#cms_page_left_col_editor',
                                                     license_key: 'gpl',
                                                     promotion: false,
                                                     height: 850,
                                                     menubar: 'insert format tools table',
                                                     content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px; padding: 1rem; } .prose, .prose-slate { max-width: none !important; } :root { --theme-primary: {{ $primaryColor }}; --theme-primary-hover: {{ $hoverColor }}; --theme-text: {{ $textColor }}; --theme-border-radius: {{ $borderRadius }}; } .btn-theme-primary { background-color: var(--theme-primary) !important; color: var(--theme-text) !important; border-radius: var(--theme-border-radius) !important; border: none !important; padding: 10px 20px !important; font-weight: 700 !important; font-family: inherit !important; cursor: pointer !important; display: inline-block !important; text-align: center !important; text-decoration: none !important; transition: background-color 0.2s !important; } .btn-theme-primary:hover { background-color: var(--theme-primary-hover) !important; } [data-aos] { outline: 2px dashed #7c3aed !important; outline-offset: 3px; position: relative; } [data-aos]::before { content: \"◆ \" attr(data-aos); position: absolute; top: -1.4em; left: 0; background: #7c3aed; color: #fff; font-size: 9px; font-family: monospace; padding: 1px 6px; border-radius: 3px; pointer-events: none; white-space: nowrap; z-index: 9999; }',
                                                     content_css: [
                                                         'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css',
                                                         '/css/prose.css'
                                                     ],
                                                     images_upload_handler: window.cmsTinyMCEImageUploadHandler,
                                                     plugins: 'advlist autolink lists link image charmap preview anchor searchreplace wordcount visualblocks supercode fullscreen insertdatetime media table help emoticons pagebreak directionality',
                                                     toolbar: [
                                                         'supercode fullscreen | undo redo | styles blocks | bold italic underline strikethrough | forecolor backcolor',
                                                         'fontfamily fontsize lineheight | alignleft aligncenter alignright alignjustify | outdent indent | removeformat | numlist bullist | pagebreak | charmap emoticons | link image media anchor | ltr rtl | preview'
                                                     ],
                                                     toolbar_mode: 'wrap',
                                                     supercode: { theme: 'monokai', fontSize: 14, autocomplete: true, dark: true },
                                                     branding: false,
                                                     contextmenu: 'link image imagetools',
                                                     style_formats: [
                                                         { title: 'Callout (Yellow/Warning)', block: 'div', classes: 'p-4 bg-amber-50 dark:bg-amber-950/20 border-l-4 border-amber-500 text-amber-900 dark:text-amber-200 rounded-r-lg my-4' },
                                                         { title: 'Callout (Blue/Info)', block: 'div', classes: 'p-4 bg-blue-50 dark:bg-blue-950/20 border-l-4 border-blue-500 text-blue-900 dark:text-blue-200 rounded-r-lg my-4' },
                                                         { title: 'Callout (Green/Success)', block: 'div', classes: 'p-4 bg-emerald-50 dark:bg-emerald-950/20 border-l-4 border-emerald-500 text-emerald-900 dark:text-emerald-200 rounded-r-lg my-4' },
                                                         { title: 'Callout (Red/Danger)', block: 'div', classes: 'p-4 bg-rose-50 dark:bg-rose-950/20 border-l-4 border-rose-500 text-rose-900 dark:text-rose-200 rounded-r-lg my-4' },
                                                         { title: 'Feature Card', block: 'div', classes: 'p-6 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50 rounded-2xl shadow-sm my-6' },
                                                         { title: 'Premium Button (Primary)', selector: 'a', classes: 'inline-block px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-colors no-underline' },
                                                         { title: 'Premium Button (Outline)', selector: 'a', classes: 'inline-block px-5 py-2.5 border border-indigo-600 text-indigo-600 hover:bg-indigo-50 font-medium rounded-xl transition-colors no-underline' },
                                                         { title: 'Badge Primary', inline: 'span', classes: 'inline-block px-2.5 py-0.5 text-xs font-semibold bg-indigo-100 text-indigo-800 rounded-full' },
                                                         { title: 'Badge Success', inline: 'span', classes: 'inline-block px-2.5 py-0.5 text-xs font-semibold bg-emerald-100 text-emerald-800 rounded-full' },
                                                         { title: 'Lead Paragraph', block: 'p', classes: 'text-lg text-slate-600 dark:text-slate-400 font-medium leading-relaxed' },
                                                         { title: 'Highlight Text', inline: 'span', styles: { color: '#ff0000', textDecoration: 'underline' } }
                                                     ],
                                                     extended_valid_elements: '*[class|style|id|name|open|data-aos|data-aos-duration|data-aos-delay|data-aos-offset|data-aos-easing|data-aos-once|data-aos-mirror|data-aos-mobile],svg[*],path[*],circle[*],rect[*],g[*],line[*],polyline[*],polygon[*],button[*]',
                                                     convert_urls: false,
                                                     relative_urls: false,
                                                     remove_script_host: false,
                                                     valid_children: '+a[button]',
                                                     setup: (editor) => {
                                                         editor.on('init', () => {
                                                             editor.setContent(window.ensureProseWrapper(this.leftCol || ''));
                                                             editor.getBody().querySelectorAll('.prose').forEach(el => {
                                                                 el.style.setProperty('max-width', 'none', 'important');
                                                                 el.style.setProperty('width', '100%');
                                                             });
                                                         });
                                                     }
                                                 });
                                             },
                                             destroy() {
                                                 tinymce.remove('#cms_page_left_col_editor');
                                             }
                                         }"
                                         x-init="initTiny()">
                                        <textarea id="cms_page_left_col_editor" class="w-full h-full"></textarea>
                                    </div>
                                    @error('left_col') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                                </div>
                            @endif

                            <!-- Main Column Editor -->
                            <div class="w-full lg:flex-1 space-y-2 flex flex-col" wire:key="main-editor-container">
                                <label class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Page Body / Main Column</label>

                                @if ($showAiButton)
                                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 mb-4 space-y-3 animate-fade-in">
                                        <div>
                                            <x-input-label for="aiPrompt" :value="__('AI Instruction Prompt')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-1" />
                                            <input type="text" wire:model="aiPrompt" id="aiPrompt"
                                                   class="block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm"
                                                   placeholder="e.g. Please rewrite this page content to be engaging, professional, and SEO-optimized" />
                                            <p class="text-slate-400 text-[10px] mt-1.5 leading-relaxed">
                                                The 'Generate with OPENAI' button will send your prompt and existing page content to OpenAI to return AI-generated content.
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
                                                 let editor = tinymce.get('cms_page_content_editor');
                                                 if (editor) {
                                                     editor.setContent(content);
                                                     editor.triggerSave();
                                                 }
                                                 $wire.set('content', content);
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
                                <div class="flex-1" wire:ignore 
                                     x-data="{
                                         content: @entangle('content'),
                                         initTiny() {
                                             tinymce.init({
                                                 selector: '#cms_page_content_editor',
                                                 license_key: 'gpl',
                                                 promotion: false,
                                                 height: 850,
                                                 menubar: 'insert format tools table',
                                                 content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px; padding: 1rem; } :root { --theme-primary: {{ $primaryColor }}; --theme-primary-hover: {{ $hoverColor }}; --theme-text: {{ $textColor }}; --theme-border-radius: {{ $borderRadius }}; } .btn-theme-primary { background-color: var(--theme-primary) !important; color: var(--theme-text) !important; border-radius: var(--theme-border-radius) !important; border: none !important; padding: 10px 20px !important; font-weight: 700 !important; font-family: inherit !important; cursor: pointer !important; display: inline-block !important; text-align: center !important; text-decoration: none !important; transition: background-color 0.2s !important; } .btn-theme-primary:hover { background-color: var(--theme-primary-hover) !important; }',
                                                 content_css: [
                                                     'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css',
                                                     '/css/prose.css'
                                                 ],
                                                 images_upload_handler: window.cmsTinyMCEImageUploadHandler,
                                                 plugins: 'advlist autolink lists link image charmap preview anchor searchreplace wordcount visualblocks supercode fullscreen insertdatetime media table help emoticons pagebreak directionality',
                                                 toolbar: [
                                                     'supercode fullscreen | undo redo | styles blocks | bold italic underline strikethrough | forecolor backcolor',
                                                     'fontfamily fontsize lineheight | alignleft aligncenter alignright alignjustify | outdent indent | removeformat | numlist bullist | pagebreak | charmap emoticons | link image media anchor | ltr rtl | preview'
                                                 ],
                                                 toolbar_mode: 'wrap',
                                                 supercode: { theme: 'monokai', fontSize: 14, autocomplete: true, dark: true },
                                                 branding: false,
                                                 contextmenu: 'link image imagetools',
                                                 style_formats: [
                                                     { title: 'Callout (Yellow/Warning)', block: 'div', classes: 'p-4 bg-amber-50 dark:bg-amber-950/20 border-l-4 border-amber-500 text-amber-900 dark:text-amber-200 rounded-r-lg my-4' },
                                                     { title: 'Callout (Blue/Info)', block: 'div', classes: 'p-4 bg-blue-50 dark:bg-blue-950/20 border-l-4 border-blue-500 text-blue-900 dark:text-blue-200 rounded-r-lg my-4' },
                                                     { title: 'Callout (Green/Success)', block: 'div', classes: 'p-4 bg-emerald-50 dark:bg-emerald-950/20 border-l-4 border-emerald-500 text-emerald-900 dark:text-emerald-200 rounded-r-lg my-4' },
                                                     { title: 'Callout (Red/Danger)', block: 'div', classes: 'p-4 bg-rose-50 dark:bg-rose-950/20 border-l-4 border-rose-500 text-rose-900 dark:text-rose-200 rounded-r-lg my-4' },
                                                     { title: 'Feature Card', block: 'div', classes: 'p-6 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50 rounded-2xl shadow-sm my-6' },
                                                     { title: 'Premium Button (Primary)', selector: 'a', classes: 'inline-block px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-colors no-underline' },
                                                     { title: 'Premium Button (Outline)', selector: 'a', classes: 'inline-block px-5 py-2.5 border border-indigo-600 text-indigo-600 hover:bg-indigo-50 font-medium rounded-xl transition-colors no-underline' },
                                                     { title: 'Badge Primary', inline: 'span', classes: 'inline-block px-2.5 py-0.5 text-xs font-semibold bg-indigo-100 text-indigo-800 rounded-full' },
                                                     { title: 'Badge Success', inline: 'span', classes: 'inline-block px-2.5 py-0.5 text-xs font-semibold bg-emerald-100 text-emerald-800 rounded-full' },
                                                     { title: 'Lead Paragraph', block: 'p', classes: 'text-lg text-slate-600 dark:text-slate-400 font-medium leading-relaxed' },
                                                     { title: 'Highlight Text', inline: 'span', styles: { color: '#ff0000', textDecoration: 'underline' } }
                                                 ],
                                                 extended_valid_elements: '*[class|style|id|name|open|data-aos|data-aos-duration|data-aos-delay|data-aos-offset|data-aos-easing|data-aos-once|data-aos-mirror|data-aos-mobile],svg[*],path[*],circle[*],rect[*],g[*],line[*],polyline[*],polygon[*],button[*]',
                                                     convert_urls: false,
                                                     relative_urls: false,
                                                     remove_script_host: false,
                                                     valid_children: '+a[button]',
                                                 setup: (editor) => {
                                                     editor.on('init', () => {
                                                         const html = this.content || '';
                                                         editor.setContent(window.ensureProseWrapper(html));
                                                         editor.getBody().querySelectorAll('.prose').forEach(el => {
                                                             el.style.setProperty('max-width', 'none', 'important');
                                                             el.style.setProperty('width', '100%');
                                                         });
                                                     });
                                                     editor.on('change', () => {
                                                         this.content = editor.getContent();
                                                     });
                                                     editor.on('blur', () => {
                                                         this.content = editor.getContent();
                                                     });
                                                     window.addEventListener('content-restored', (e) => {
                                                         editor.setContent(window.ensureProseWrapper(e.detail.content || ''));
                                                     });
                                                 }
                                             });
                                             },
                                             destroy() {
                                                 tinymce.remove('#cms_page_content_editor');
                                             }
                                             }"
                                             x-init="initTiny()">
                                    <textarea id="cms_page_content_editor" class="w-full"></textarea>
                                </div>
                                @error('content') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                            </div>

                            @if(in_array($layout_type, [3, 4]))
                                <!-- Right Sidebar Editor -->
                                <div class="w-full lg:w-1/4 space-y-2 flex flex-col" wire:key="right-editor-container">
                                    <label class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Right Sidebar Column</label>
                                    <div class="flex-1" wire:ignore 
                                         x-data="{
rightCol: @entangle('right_col'),
                                             initTiny() {
                                                 tinymce.init({
                                                     selector: '#cms_page_right_col_editor',
                                                     license_key: 'gpl',
                                                     promotion: false,
                                                     height: 850,
                                                     menubar: 'insert format tools table',
                                                     content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px; padding: 1rem; } :root { --theme-primary: {{ $primaryColor }}; --theme-primary-hover: {{ $hoverColor }}; --theme-text: {{ $textColor }}; --theme-border-radius: {{ $borderRadius }}; } .btn-theme-primary { background-color: var(--theme-primary) !important; color: var(--theme-text) !important; border-radius: var(--theme-border-radius) !important; border: none !important; padding: 10px 20px !important; font-weight: 700 !important; font-family: inherit !important; cursor: pointer !important; display: inline-block !important; text-align: center !important; text-decoration: none !important; transition: background-color 0.2s !important; } .btn-theme-primary:hover { background-color: var(--theme-primary-hover) !important; }',
                                                     content_css: [
                                                         'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css',
                                                         '/css/prose.css'
                                                     ],
                                                     images_upload_handler: window.cmsTinyMCEImageUploadHandler,
                                                     plugins: 'advlist autolink lists link image charmap preview anchor searchreplace wordcount visualblocks supercode fullscreen insertdatetime media table help emoticons pagebreak directionality',
                                                     toolbar: [
                                                         'supercode fullscreen | undo redo | styles blocks | bold italic underline strikethrough | forecolor backcolor',
                                                         'fontfamily fontsize lineheight | alignleft aligncenter alignright alignjustify | outdent indent | removeformat | numlist bullist | pagebreak | charmap emoticons | link image media anchor | ltr rtl | preview'
                                                     ],
                                                     toolbar_mode: 'wrap',
                                                     supercode: { theme: 'monokai', fontSize: 14, autocomplete: true, dark: true },
                                                     branding: false,
                                                     contextmenu: 'link image imagetools',
                                                     style_formats: [
                                                         { title: 'Callout (Yellow/Warning)', block: 'div', classes: 'p-4 bg-amber-50 dark:bg-amber-950/20 border-l-4 border-amber-500 text-amber-900 dark:text-amber-200 rounded-r-lg my-4' },
                                                         { title: 'Callout (Blue/Info)', block: 'div', classes: 'p-4 bg-blue-50 dark:bg-blue-950/20 border-l-4 border-blue-500 text-blue-900 dark:text-blue-200 rounded-r-lg my-4' },
                                                         { title: 'Callout (Green/Success)', block: 'div', classes: 'p-4 bg-emerald-50 dark:bg-emerald-950/20 border-l-4 border-emerald-500 text-emerald-900 dark:text-emerald-200 rounded-r-lg my-4' },
                                                         { title: 'Callout (Red/Danger)', block: 'div', classes: 'p-4 bg-rose-50 dark:bg-rose-950/20 border-l-4 border-rose-500 text-rose-900 dark:text-rose-200 rounded-r-lg my-4' },
                                                         { title: 'Feature Card', block: 'div', classes: 'p-6 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50 rounded-2xl shadow-sm my-6' },
                                                         { title: 'Premium Button (Primary)', selector: 'a', classes: 'inline-block px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-colors no-underline' },
                                                         { title: 'Premium Button (Outline)', selector: 'a', classes: 'inline-block px-5 py-2.5 border border-indigo-600 text-indigo-600 hover:bg-indigo-50 font-medium rounded-xl transition-colors no-underline' },
                                                         { title: 'Badge Primary', inline: 'span', classes: 'inline-block px-2.5 py-0.5 text-xs font-semibold bg-indigo-100 text-indigo-800 rounded-full' },
                                                         { title: 'Badge Success', inline: 'span', classes: 'inline-block px-2.5 py-0.5 text-xs font-semibold bg-emerald-100 text-emerald-800 rounded-full' },
                                                         { title: 'Lead Paragraph', block: 'p', classes: 'text-lg text-slate-600 dark:text-slate-400 font-medium leading-relaxed' },
                                                         { title: 'Highlight Text', inline: 'span', styles: { color: '#ff0000', textDecoration: 'underline' } }
                                                     ],
                                                     extended_valid_elements: '*[class|style|id|name|open|data-aos|data-aos-duration|data-aos-delay|data-aos-offset|data-aos-easing|data-aos-once|data-aos-mirror|data-aos-mobile],svg[*],path[*],circle[*],rect[*],g[*],line[*],polyline[*],polygon[*],button[*]',
                                                     convert_urls: false,
                                                     relative_urls: false,
                                                     remove_script_host: false,
                                                     valid_children: '+a[button]',
                                                     setup: (editor) => {
                                                         editor.on('init', () => {
                                                             const html = this.rightCol || '';
                                                             editor.setContent(window.ensureProseWrapper(html));
                                                             editor.getBody().querySelectorAll('.prose').forEach(el => {
                                                                 el.style.setProperty('max-width', 'none', 'important');
                                                                 el.style.setProperty('width', '100%');
                                                             });
                                                         });
                                                         editor.on('change', () => {
                                                             this.rightCol = editor.getContent();
                                                         });
                                                         editor.on('blur', () => {
                                                             this.rightCol = editor.getContent();
                                                         });
                                                         window.addEventListener('content-restored', (e) => {
                                                             editor.setContent(window.ensureProseWrapper(e.detail.right_col || ''));
                                                         });
                                                     }
                                                 });
                                             },
                                             destroy() {
                                                 tinymce.remove('#cms_page_right_col_editor');
                                             }
                                             }"
                                             x-init="initTiny()">
                                        <textarea id="cms_page_right_col_editor" class="w-full h-full"></textarea>
                                    </div>
                                    @error('right_col') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                                </div>
                            @endif

                        </div>

                     
                    </div>

                    <!-- Publishing Info Tab -->
                    <div x-show="activeTab === 'publishing'" class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                        <h3 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-indigo-600 rounded"></span> Publishing &amp; Visibility Settings
                        </h3>

                        <!-- Draft / Active Mode Switch -->
                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-bold text-slate-800">Page Status</h4>
                                <p class="text-xs text-slate-400 mt-1">Activate the page to make it visible to visitors. Draft pages can only be viewed by administrators.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="is_active" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                <span class="ml-3 text-sm font-bold text-slate-700 uppercase tracking-wider" x-text="$wire.is_active ? 'Active' : 'Draft'"></span>
                            </label>
                        </div>

                        <!-- Exclude from Search Toggle -->
                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-bold text-slate-800">Exclude from Search</h4>
                                <p class="text-xs text-slate-400 mt-1">If enabled, this page will be hidden from Live Search dropdowns and site search results.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="exclude_from_search" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                <span class="ml-3 text-sm font-bold text-slate-700 uppercase tracking-wider" x-text="$wire.exclude_from_search ? 'Hidden' : 'Visible'"></span>
                            </label>
                        </div>

                        <!-- Search Index & Lock Control -->
                        <div class="p-5 bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-100 dark:border-indigo-900/50 rounded-2xl space-y-4">
                            <div class="flex items-center justify-between flex-wrap gap-3">
                                <div>
                                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        Search Index Keywords &amp; Lock Control
                                    </h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Collated keywords used by Live Search to index this page. When locked, saving this record will not overwrite custom admin keywords.</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button type="button" wire:click="rebuildIndexKeywords" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        Rebuild Index
                                    </button>
                                     <label class="relative inline-flex items-center cursor-pointer select-none">
                                         <input type="checkbox" wire:model="cms_search_index_locked" class="sr-only peer">
                                         <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-600"></div>
                                         <span class="ml-2.5 px-2.5 py-1 rounded-lg text-2xs font-black uppercase tracking-wider transition-all inline-flex items-center gap-1 shadow-xs"
                                               :class="$wire.cms_search_index_locked ? 'bg-amber-500 text-white ring-2 ring-amber-400/40 shadow-amber-500/20' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400'">
                                             <svg class="w-3 h-3" x-show="$wire.cms_search_index_locked" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                             <svg class="w-3 h-3" x-show="!$wire.cms_search_index_locked" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                                             <span x-text="$wire.cms_search_index_locked ? 'Locked' : 'Unlocked'"></span>
                                         </span>
                                     </label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Collated Search Index (Editable)</label>
                                <textarea wire:model="cms_search_index" rows="4" placeholder="Add custom search keywords, synonyms, promo codes, misspellings..." class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-mono text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                            </div>
                        </div>

                        <!-- Publishing Options checkboxes -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <label class="flex items-center gap-3 p-4 bg-slate-50/50 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-50">
                                <input type="checkbox" wire:model="show_title" class="rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 h-4 w-4">
                                <div>
                                    <span class="text-sm font-bold text-slate-700 block">Show Title</span>
                                    <span class="text-xs text-slate-400">Display page title on the header</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 p-4 bg-slate-50/50 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-50">
                                <input type="checkbox" wire:model="show_author" class="rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 h-4 w-4">
                                <div>
                                    <span class="text-sm font-bold text-slate-700 block">Show Author</span>
                                    <span class="text-xs text-slate-400">Display author credentials</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 p-4 bg-slate-50/50 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-50">
                                <input type="checkbox" wire:model="show_date" class="rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 h-4 w-4">
                                <div>
                                    <span class="text-sm font-bold text-slate-700 block">Show Date</span>
                                    <span class="text-xs text-slate-400">Display publishing date</span>
                                </div>
                            </label>
                        </div>

                        <!-- Custom Author Field -->
                        <div>
                            <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Custom Author Override</label>
                            <input type="text" wire:model="custom_author" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500" placeholder="Leave blank to use logged-in user: {{ auth()->user()->name }}" />
                            <p class="text-xs text-slate-400 mt-1.5 font-medium">You can manually type a custom author name (e.g. Guest Contributor, Editorial Staff) to override the author display name.</p>
                            @error('custom_author') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <!-- Page Type Selection -->
                        <div class="border-t border-slate-100 pt-6">
                            <label class="text-xs font-bold text-slate-400 block mb-3 uppercase tracking-wider">Page Type</label>
                            <div class="flex items-center gap-6">
                                @foreach($pageTypes as $pt)
                                    <label class="inline-flex items-center gap-2.5 cursor-pointer">
                                        <input type="radio" name="page_type" value="{{ $pt->id }}" wire:model="page_type" class="text-indigo-600 focus:ring-indigo-500 border-slate-300 h-4 w-4">
                                        <span class="text-sm font-semibold text-slate-700">{{ $pt->title }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('page_type') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <!-- Hide Page Ranking Gating -->
                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-bold text-slate-800">Hide Page Ranking (Thumbs Up/Down)</h4>
                                <p class="text-xs text-slate-400 mt-1">If enabled, the Thumbs Up/Down rating section will be hidden on the page.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="hide_page_ranking" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                <span class="ml-3 text-sm font-bold text-slate-700 uppercase tracking-wider" x-text="$wire.hide_page_ranking ? 'Hidden' : 'Visible'"></span>
                            </label>
                        </div>

                        <!-- Sorting & Ranking inputs -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-slate-100 pt-6">
                            <div>
                                <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Page Ranking (Score)</label>
                                <input type="number" wire:model="page_ranking" class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200 text-slate-500 rounded-2xl cursor-not-allowed focus:outline-none" readonly />
                                <p class="text-xs text-slate-400 mt-1.5 font-medium">Accumulated thumbs up/down score from visitors (read-only).</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Custom Sorting Position</label>
                                <input type="number" step="0.01" wire:model="custom_sorting" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500" />
                                <p class="text-xs text-slate-400 mt-1.5 font-medium">Floating point number to control custom ordering positions.</p>
                                @error('custom_sorting') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Categories and Tags Selection -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-slate-100 pt-6">
                            <div>
                                <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Category</label>
                                <select wire:model="selected_category_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                    <option value="">No Category</option>
                                    @foreach($categoriesList as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-slate-400 mt-1.5 font-medium">Assign a category for landing page indexing.</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Tags</label>
                                <div class="grid grid-cols-2 gap-2 bg-slate-50 border border-slate-200 rounded-2xl p-4 max-h-36 overflow-y-auto">
                                    @foreach($tagsList as $tag)
                                        <label class="inline-flex items-center gap-2.5 cursor-pointer">
                                            <input type="checkbox" value="{{ $tag->id }}" wire:model="selected_tag_ids" class="rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                            <span class="text-sm text-slate-700 font-medium">{{ $tag->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="text-xs text-slate-400 mt-1.5 font-medium">Select tags to display at the bottom of the page.</p>
                            </div>
                        </div>

                        <!-- Featured Image Upload & Storage Section -->
                        <div class="border-t border-slate-100 pt-6 space-y-6">
                            <h4 class="text-sm font-bold text-slate-800">Featured Image Settings</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Featured Image File</label>
                                    <input type="file" wire:model="featured_image_upload" class="w-full text-slate-700 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                    @error('featured_image_upload') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                                    
                                    @if($featured_image_path)
                                        <div class="mt-4">
                                            <span class="text-2xs text-slate-400 font-semibold block mb-1 uppercase">Current Featured Image:</span>
                                            <div class="relative w-36 aspect-[16/10] rounded-xl overflow-hidden border border-slate-200">
                                                @php
                                                    $featuredUrl = '';
                                                    try {
                                                        if ($page) {
                                                            $featuredUrl = $page->featuredImageUrl();
                                                        } else {
                                                            if ($featured_image_cdn_url || config('app.cdn_url')) {
                                                                $cdn = $featured_image_cdn_url ?: config('app.cdn_url');
                                                                $featuredUrl = rtrim($cdn, '/') . '/' . ltrim($featured_image_path, '/');
                                                            } else {
                                                                if ($featured_image_s3 == 0) {
                                                                    $featuredUrl = asset('storage/' . $featured_image_path);
                                                                } elseif ($featured_image_s3 == 1) {
                                                                    $featuredUrl = Storage::disk('s3')->url($featured_image_path);
                                                                } else {
                                                                    $diskName = 'custom_s3_cms_' . ($pageId ?: 'new');
                                                                    config([
                                                                        "filesystems.disks.{$diskName}" => [
                                                                            'driver' => 's3',
                                                                            'key' => $featured_image_access_key_id,
                                                                            'secret' => $featured_image_secret_access_key,
                                                                            'region' => $featured_image_region,
                                                                            'bucket' => $featured_image_bucket_name,
                                                                            'use_path_style_endpoint' => false,
                                                                        ]
                                                                    ]);
                                                                    $featuredUrl = Storage::disk($diskName)->url($featured_image_path);
                                                                }
                                                            }
                                                        }
                                                    } catch (\Exception $e) {
                                                        $featuredUrl = '';
                                                    }
                                                @endphp
                                                @if($featuredUrl)
                                                    <img src="{{ $featuredUrl }}" class="w-full h-full object-cover" />
                                                @else
                                                    <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400 text-xs">No preview</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Storage Destination</label>
                                    <select wire:model.live="featured_image_s3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                        <option value="0">Local Server</option>
                                        <option value="1">Global S3 Storage</option>
                                        <option value="2">Custom S3 Bucket Credentials</option>
                                    </select>
                                    @error('featured_image_s3') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror

                                    @if($featured_image_s3 > 0)
                                        <div class="mt-4">
                                            <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">CDN URL Override (Optional)</label>
                                            <input type="text" wire:model="featured_image_cdn_url" placeholder="https://cdn.mywebsite.com" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                                            <span class="text-[10px] text-slate-400 block mt-1">Loads the featured image from CloudFront instead of the direct S3 URL.</span>
                                            @error('featured_image_cdn_url') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Custom S3 Credentials Inputs (conditional) -->
                            @if($featured_image_s3 == 2)
                                <div class="p-5 bg-indigo-50/30 border border-indigo-100/50 rounded-2xl space-y-4">
                                    <h5 class="text-xs font-bold text-indigo-900 uppercase tracking-widest">Custom S3 Credentials</h5>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-2xs font-bold text-slate-400 block mb-1 uppercase">Access Key ID</label>
                                            <input type="text" wire:model="featured_image_access_key_id" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm" placeholder="AKIA..." />
                                            @error('featured_image_access_key_id') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        
                                        <div>
                                            <label class="text-2xs font-bold text-slate-400 block mb-1 uppercase">Secret Access Key</label>
                                            <input type="password" wire:model="featured_image_secret_access_key" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm" placeholder="••••••••••••••••" />
                                            @error('featured_image_secret_access_key') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="text-2xs font-bold text-slate-400 block mb-1 uppercase">S3 Bucket Name</label>
                                            <input type="text" wire:model="featured_image_bucket_name" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm" placeholder="my-custom-bucket" />
                                            @error('featured_image_bucket_name') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="text-2xs font-bold text-slate-400 block mb-1 uppercase">S3 Region</label>
                                            <input type="text" wire:model="featured_image_region" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm" placeholder="us-east-1" />
                                            @error('featured_image_region') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Security & Gating Tab -->
                    <div x-show="activeTab === 'security'" class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                        <h3 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-indigo-600 rounded"></span> Page Expiration &amp; Gating Security
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Page Expiration Date &amp; Time</label>
                                <input type="datetime-local" wire:model="expires_at" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500" />
                                @error('expires_at') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-6 space-y-6">
                            <h4 class="text-sm font-bold text-slate-800">Restriction Options (Access Gating)</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-slate-50 border border-slate-200 rounded-3xl p-6 space-y-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" wire:model.live="requires_code" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                                        <span class="text-sm font-bold text-slate-700">Requires Access Code</span>
                                    </label>
                                    
                                    @if($requires_code)
                                        <div>
                                            <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Access Code Value</label>
                                            <input type="text" wire:model="access_code" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500" placeholder="e.g. VIPPASS123" />
                                            @error('access_code') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                                        </div>
                                    @endif
                                </div>

                                <div class="bg-slate-50 border border-slate-200 rounded-3xl p-6 space-y-4">
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Gated by Product Purchase</label>

                                        {{-- Live Search Combobox --}}
                                        <div x-data="{ open: false }" @click.away="open = false" class="relative">

                                            {{-- Current selection badge --}}
                                            @if($selectedGatingProduct)
                                                <div class="flex items-center gap-2 mb-2 px-3 py-2 bg-indigo-50 border border-indigo-200 rounded-2xl text-xs">
                                                    <span class="font-bold text-indigo-700 truncate flex-1">{{ $selectedGatingProduct->title }}</span>
                                                    <span class="text-indigo-400 font-mono shrink-0">ID: {{ $selectedGatingProduct->id }}</span>
                                                    <button type="button"
                                                            wire:click="$set('required_product_id', null); $set('gatingProductSearch', '')"
                                                            class="shrink-0 text-indigo-400 hover:text-rose-500 transition ml-1"
                                                            title="Clear selection">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </div>
                                            @else
                                                <p class="text-[10px] text-slate-400 mb-2 italic">No product selected — free access</p>
                                            @endif

                                            {{-- Search input --}}
                                            <div class="relative">
                                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                                <input
                                                    type="text"
                                                    wire:model.live.debounce.250ms="gatingProductSearch"
                                                    @focus="open = true"
                                                    placeholder="Search by name or enter ID…"
                                                    class="w-full pl-9 pr-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 text-xs transition"
                                                >
                                                <div wire:loading wire:target="gatingProductSearch" class="absolute right-3 top-1/2 -translate-y-1/2">
                                                    <svg class="animate-spin w-3.5 h-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                                                </div>
                                            </div>

                                            {{-- Results dropdown --}}
                                            @if($gatingProductResults->isNotEmpty() && $gatingProductSearch !== '')
                                                <div class="absolute z-50 mt-1.5 w-full bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden"
                                                     x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                                                    <ul class="max-h-52 overflow-y-auto divide-y divide-slate-50 py-1">
                                                        @foreach($gatingProductResults as $gp)
                                                            <li>
                                                                <button type="button"
                                                                        wire:click="$set('required_product_id', {{ $gp->id }}); $set('gatingProductSearch', '')"
                                                                        @click="open = false"
                                                                        class="w-full text-left px-4 py-2.5 hover:bg-indigo-50 transition flex items-center justify-between gap-3 group">
                                                                    <span class="text-xs font-semibold text-slate-800 group-hover:text-indigo-700 truncate">{{ $gp->title }}</span>
                                                                    <span class="text-[10px] font-mono text-slate-400 group-hover:text-indigo-400 shrink-0">ID: {{ $gp->id }}</span>
                                                                </button>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                    <div class="px-4 py-2 border-t border-slate-100 bg-slate-50 text-[10px] text-slate-400">
                                                        Showing {{ $gatingProductResults->count() }} result(s) · max 15 · type an ID number to match directly
                                                    </div>
                                                </div>
                                            @elseif($gatingProductSearch !== '' && $gatingProductResults->isEmpty())
                                                <div class="absolute z-50 mt-1.5 w-full bg-white border border-slate-200 rounded-2xl shadow-lg px-4 py-3 text-xs text-slate-500 italic"
                                                     x-show="open">
                                                    No products found matching "{{ $gatingProductSearch }}"
                                                </div>
                                            @endif

                                            {{-- Hidden wire input to keep required_product_id in sync --}}
                                            <input type="hidden" wire:model="required_product_id">
                                        </div>

                                        <p class="text-[10px] text-slate-400 mt-2">Allows viewing only if the customer has a paid/completed order (Status: 7) containing this product.</p>
                                        @error('required_product_id') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Layout & Media Tab -->
                    <div x-show="activeTab === 'layout'" class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                        <h3 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-indigo-600 rounded"></span> Header &amp; Background Images | Video Settings
                        </h3>

                        {{-- ── Media Storage Destination (moved to top) ──────────────── --}}
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-4">
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Media Storage Destination</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Storage Destination</label>
                                    <select wire:model.live="media_image_s3" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                        <option value="0">Local Server</option>
                                        <option value="1">Global S3 Storage</option>
                                        <option value="2">Custom S3 Bucket Credentials</option>
                                    </select>
                                    @error('media_image_s3') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                                </div>

                                @if($media_image_s3 > 0)
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">CDN URL Override (Optional)</label>
                                        <input type="text" wire:model="media_image_cdn_url" placeholder="https://cdn.mywebsite.com" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                                        <span class="text-[10px] text-slate-400 block mt-1">Loads layout media files from CloudFront instead of the direct S3 URL.</span>
                                        @error('media_image_cdn_url') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                                    </div>
                                @endif
                            </div>

                            @if($media_image_s3 == 2)
                                <div class="p-5 bg-indigo-50/30 border border-indigo-100/50 rounded-2xl space-y-4">
                                    <h5 class="text-xs font-bold text-indigo-900 uppercase tracking-widest">Custom S3 Credentials for Layout Media</h5>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-2xs font-bold text-slate-400 block mb-1 uppercase">Access Key ID</label>
                                            <input type="text" wire:model="media_image_access_key_id" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm" placeholder="AKIA..." />
                                            @error('media_image_access_key_id') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        
                                        <div>
                                            <label class="text-2xs font-bold text-slate-400 block mb-1 uppercase">Secret Access Key</label>
                                            <input type="password" wire:model="media_image_secret_access_key" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm" placeholder="••••••••••••••••" />
                                            @error('media_image_secret_access_key') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="text-2xs font-bold text-slate-400 block mb-1 uppercase">S3 Bucket Name</label>
                                            <input type="text" wire:model="media_image_bucket_name" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm" placeholder="my-custom-bucket" />
                                            @error('media_image_bucket_name') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="text-2xs font-bold text-slate-400 block mb-1 uppercase">S3 Region</label>
                                            <input type="text" wire:model="media_image_region" class="w-full px-4 py-2 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm" placeholder="us-east-1" />
                                            @error('media_image_region') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- ── Header Image + Background Image ──────────────────────── --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <label class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Header Image Banner</label>
                                @if($header_image_path)
                                    @php
                                        $resolvedHeaderUrl = '';
                                        try {
                                            if ($page) {
                                                $resolvedHeaderUrl = $page->headerImageUrl();
                                            } else {
                                                if ($media_image_cdn_url || config('app.cdn_url')) {
                                                    $cdn = $media_image_cdn_url ?: config('app.cdn_url');
                                                    $resolvedHeaderUrl = rtrim($cdn, '/') . '/' . ltrim($header_image_path, '/');
                                                } else {
                                                    if ($media_image_s3 == 0) {
                                                        $resolvedHeaderUrl = asset('storage/' . $header_image_path);
                                                    } elseif ($media_image_s3 == 1) {
                                                        $resolvedHeaderUrl = Storage::disk('s3')->url($header_image_path);
                                                    } else {
                                                        $diskName = 'custom_s3_cms_media_' . ($pageId ?: 'new');
                                                        config([
                                                            "filesystems.disks.{$diskName}" => [
                                                                'driver' => 's3',
                                                                'key' => $media_image_access_key_id,
                                                                'secret' => $media_image_secret_access_key,
                                                                'region' => $media_image_region,
                                                                'bucket' => $media_image_bucket_name,
                                                                'use_path_style_endpoint' => false,
                                                            ]
                                                        ]);
                                                        $resolvedHeaderUrl = Storage::disk($diskName)->url($header_image_path);
                                                    }
                                                }
                                            }
                                        } catch (\Exception $e) {
                                            $resolvedHeaderUrl = '';
                                        }
                                    @endphp
                                    <div class="w-full h-32 bg-slate-100 rounded-2xl overflow-hidden relative">
                                        @if($resolvedHeaderUrl)
                                            <img src="{{ $resolvedHeaderUrl }}" class="w-full h-full object-cover" />
                                        @else
                                            <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400 text-xs">No preview</div>
                                        @endif
                                    </div>
                                @endif
                                <input type="file" wire:model="header_image_upload" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                @error('header_image_upload') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                                @if($header_image_path)
                                    <div class="flex justify-end">
                                        <button type="button" wire:click="clearHeaderImage"
                                                wire:confirm="Clear the header image? This will remove it from the page after saving."
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 border border-red-200 text-red-600 text-xs font-bold rounded-xl hover:bg-red-100 transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Clear Header Image
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <div class="space-y-4">
                                <label class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Background Image</label>
                                @if($background_image_path)
                                    @php
                                        $resolvedBgUrl = '';
                                        try {
                                            if ($page) {
                                                $resolvedBgUrl = $page->backgroundImageUrl();
                                            } else {
                                                if ($media_image_cdn_url || config('app.cdn_url')) {
                                                    $cdn = $media_image_cdn_url ?: config('app.cdn_url');
                                                    $resolvedBgUrl = rtrim($cdn, '/') . '/' . ltrim($background_image_path, '/');
                                                } else {
                                                    if ($media_image_s3 == 0) {
                                                        $resolvedBgUrl = asset('storage/' . $background_image_path);
                                                    } elseif ($media_image_s3 == 1) {
                                                        $resolvedBgUrl = Storage::disk('s3')->url($background_image_path);
                                                    } else {
                                                        $diskName = 'custom_s3_cms_media_' . ($pageId ?: 'new');
                                                        config([
                                                            "filesystems.disks.{$diskName}" => [
                                                                'driver' => 's3',
                                                                'key' => $media_image_access_key_id,
                                                                'secret' => $media_image_secret_access_key,
                                                                'region' => $media_image_region,
                                                                'bucket' => $media_image_bucket_name,
                                                                'use_path_style_endpoint' => false,
                                                            ]
                                                        ]);
                                                        $resolvedBgUrl = Storage::disk($diskName)->url($background_image_path);
                                                    }
                                                }
                                            }
                                        } catch (\Exception $e) {
                                            $resolvedBgUrl = '';
                                        }
                                    @endphp
                                    <div class="w-full h-32 bg-slate-100 rounded-2xl overflow-hidden relative">
                                        @if($resolvedBgUrl)
                                            <img src="{{ $resolvedBgUrl }}" class="w-full h-full object-cover" />
                                        @else
                                            <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400 text-xs">No preview</div>
                                        @endif
                                    </div>
                                @endif
                                <input type="file" wire:model="background_image_upload" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                @error('background_image_upload') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                                @if($background_image_path)
                                    <div class="flex justify-end">
                                        <button type="button" wire:click="clearBackgroundImage"
                                                wire:confirm="Clear the background image? This will remove it from the page after saving."
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 border border-red-200 text-red-600 text-xs font-bold rounded-xl hover:bg-red-100 transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Clear Background Image
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Per-Page Background Video Settings -->
                        <div class="border-t border-slate-100 pt-6 space-y-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-bold text-slate-800">Background Video Settings</h4>
                                    <p class="text-xs text-slate-400 mt-0.5">Configuring a background video for this page will override the global video background and page background image.</p>
                                </div>
                                <span class="px-2.5 py-1 bg-violet-50 text-violet-700 text-xs font-extrabold rounded-full border border-violet-150">
                                    📹 Per-Page Video
                                </span>
                            </div>

                            <div class="p-4 bg-slate-50 dark:bg-slate-700/50 rounded-2xl border border-slate-200 dark:border-slate-600 space-y-4">
                                {{-- Direct URL Override --}}
                                <div class="p-3 bg-violet-50/50 dark:bg-violet-950/30 rounded-xl border border-violet-200 dark:border-violet-800 space-y-1.5">
                                    <label class="block text-[11px] font-bold text-violet-700 dark:text-violet-300 uppercase tracking-wider">Direct Video URL Override (Highest Priority)</label>
                                    <input type="text" wire:model="background_video_url" placeholder="https://cdn.example.com/background.mp4" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-violet-300 dark:border-violet-700 rounded-xl text-xs font-medium focus:outline-none focus:border-violet-500">
                                    <p class="text-[11px] text-violet-600 dark:text-violet-400">Entering a direct video URL here overrides all other video upload sources below and overrides global background video.</p>
                                    @error('background_video_url') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="space-y-3">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Upload Video File (Local or S3 Bucket — MP4/WebM)</label>
                                    <input type="file" wire:model="background_video_upload" accept="video/mp4,video/webm" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700">
                                    @error('background_video_upload') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                                    @if($background_video_path)
                                        <p class="text-xs text-emerald-600 mt-1">✓ Active path: <code>{{ $background_video_path }}</code></p>
                                    @endif
                                </div>

                                @php
                                    $resolvedPageBgVid = $page ? $page->resolveBackgroundVideoUrl() : ($background_video_url ?: null);
                                @endphp
                                @if($resolvedPageBgVid)
                                    <div class="mt-2 p-3 bg-white dark:bg-slate-800 rounded-xl border flex items-center gap-3">
                                        <video src="{{ $resolvedPageBgVid }}" class="h-14 w-24 object-cover rounded-lg" autoplay loop muted playsinline></video>
                                        <span class="text-xs text-slate-500 break-all flex-1">{{ $resolvedPageBgVid }}</span>
                                        <button type="button" wire:click="clearBackgroundVideo" class="px-3 py-1.5 bg-red-50 border border-red-200 text-red-600 text-xs font-bold rounded-xl hover:bg-red-100 transition-all">
                                            Clear
                                        </button>
                                    </div>
                                @else
                                    <div class="pt-2 flex justify-end">
                                        <button type="button" wire:click="clearBackgroundVideo" class="px-3 py-1.5 bg-red-50 border border-red-200 text-red-600 text-xs font-bold rounded-xl hover:bg-red-100 transition-all flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Reset / Clear Page Background Video
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>


                        <!-- Custom Title & Alignment Customizations -->
                        <div class="border-t border-slate-100 pt-6 space-y-6">
                            <h4 class="text-sm font-bold text-slate-800">Page Header & Title Customizations</h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Alternate Page Title</label>
                                    <input type="text" wire:model="alternate_page_title" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500" placeholder="e.g. Special Title Override" />
                                    @error('alternate_page_title') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Page Title Alignment</label>
                                    <select wire:model="page_title_alignment" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 font-medium">
                                        <option value="top-left">Top-Left</option>
                                        <option value="middle-left">Middle-Left</option>
                                        <option value="bottom-left">Bottom-Left</option>
                                        <option value="top-center">Top-Center</option>
                                        <option value="middle-center">Middle-Center (Default)</option>
                                        <option value="bottom-center">Bottom-Center</option>
                                        <option value="top-right">Top-Right</option>
                                        <option value="middle-right">Middle-Right</option>
                                        <option value="bottom-right">Bottom-Right</option>
                                    </select>
                                    @error('page_title_alignment') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Include Slideshow Plugin</label>
                                    <input type="text" wire:model="include_slideshow" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500" placeholder="e.g. [plugin:slideshow-2026]" />
                                    @error('include_slideshow') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Custom Page Title CSS (Optional)</label>
                                    <textarea wire:model="page_title_css" rows="3" class="w-full p-4 bg-slate-950 text-emerald-400 font-mono text-xs rounded-2xl focus:outline-none focus:ring-1 focus:ring-emerald-500" placeholder="/* CSS styles target matching page title elements */"></textarea>
                                    @error('page_title_css') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Minimum Header Height</label>
                                    <input type="text" wire:model="min_header_height" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500" placeholder="e.g. 320px" />
                                    @error('min_header_height') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Custom CSS & JS Tab -->
                    <div x-show="activeTab === 'code'" class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                        <h3 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-indigo-600 rounded"></span> Custom CSS Stylesheets &amp; Scripts
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Custom CSS / Links</label>
                                <textarea wire:model="custom_css" rows="6" class="w-full p-4 bg-slate-950 text-emerald-400 font-mono text-xs rounded-2xl focus:outline-none focus:ring-1 focus:ring-emerald-500" placeholder="/* Custom style rules or <link> tags */"></textarea>
                                @error('custom_css') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Custom JavaScript / Embeds</label>
                                <textarea wire:model="custom_js" rows="6" class="w-full p-4 bg-slate-950 text-emerald-400 font-mono text-xs rounded-2xl focus:outline-none focus:ring-1 focus:ring-emerald-500" placeholder="/* Custom script rules or <script> tags */"></textarea>
                                @error('custom_js') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Controls -->
                    <div class="flex items-center gap-3">
                        <button type="submit" wire:loading.attr="disabled" wire:target="save" class="bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-semibold px-6 py-3 rounded-2xl shadow-md flex items-center gap-2">
                            <svg wire:loading wire:target="save" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Save Changes</span>
                        </button>
                    </div>
                </form>

                <!-- Revisions Tab -->
                @if($pageId)
                    <div x-show="activeTab === 'revisions'" class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                        <h3 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-indigo-600 rounded"></span> Page Revision History Log
                        </h3>

                        <div class="overflow-hidden border border-slate-100 rounded-2xl">
                            <table class="w-full text-left border-collapse text-sm">
                                <thead>
                                    <tr class="bg-slate-50/80 text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                                        <th class="py-3 px-4">Saved On</th>
                                        <th class="py-3 px-4">Type</th>
                                        <th class="py-3 px-4">Author</th>
                                        <th class="py-3 px-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($revisionsList as $rev)
                                        <tr class="hover:bg-slate-50/20">
                                            <td class="py-3.5 px-4 font-semibold text-slate-700">
                                                {{ \Carbon\Carbon::parse($rev['created_at'])->format('M d, Y H:i:s') }}
                                            </td>
                                            <td class="py-3.5 px-4">
                                                @if($rev['revision_type'] === 'autosave')
                                                    <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-xs font-medium uppercase tracking-wider">Autosave</span>
                                                @elseif($rev['revision_type'] === 'backup')
                                                    <span class="px-2 py-0.5 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-xs font-medium uppercase tracking-wider">Pre-Restore Backup</span>
                                                @else
                                                    <span class="px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-xs font-medium uppercase tracking-wider">Manual Save</span>
                                                @endif
                                            </td>
                                            <td class="py-3.5 px-4 text-slate-500">
                                                {{ $rev['author'] ? $rev['author']['name'] : 'System' }}
                                            </td>
                                            <td class="py-3.5 px-4 text-right space-x-2">
                                                <button wire:click="previewRevision({{ $rev['id'] }})" class="text-indigo-600 hover:text-indigo-800 font-semibold text-xs">Preview</button>
                                                <button wire:click="restoreRevision({{ $rev['id'] }})" class="text-emerald-600 hover:text-emerald-800 font-semibold text-xs">Restore</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-6 text-center text-slate-400 text-xs">No revisions saved yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

            </div>
        </div>

    <!-- Preview Modal -->
    @if($previewingRevision)
        <div class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-6">
            <div class="bg-white border border-slate-100 rounded-3xl max-w-4xl w-full h-[80vh] flex flex-col shadow-2xl overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Revision Preview</h3>
                        <p class="text-xs text-slate-500">Saved: {{ \Carbon\Carbon::parse($previewingRevision->created_at)->format('M d, Y H:i:s') }} ({{ ucfirst($previewingRevision->revision_type) }})</p>
                    </div>
                    <button wire:click="closePreview()" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="flex-1 p-8 overflow-y-auto bg-slate-50">
                    <div class="max-w-6xl mx-auto">
                        <div class="flex flex-col lg:flex-row gap-6 items-start">
                            @if(in_array($previewingRevision->layout_type, [2, 4]) && !empty($previewingRevision->left_col))
                                <div class="w-full lg:w-1/4 bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm">
                                    {!! $previewingRevision->left_col !!}
                                </div>
                            @endif

                            <div class="w-full lg:flex-1 bg-white p-8 rounded-2xl border border-slate-200/60 shadow-sm">
                                <h1 class="text-2xl font-extrabold mb-4 tracking-tight">{{ $previewingRevision->title }}</h1>
                                {!! $previewingRevision->content !!}
                            </div>

                            @if(in_array($previewingRevision->layout_type, [3, 4]) && !empty($previewingRevision->right_col))
                                <div class="w-full lg:w-1/4 bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm">
                                    {!! $previewingRevision->right_col !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="p-6 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50/50">
                    <button wire:click="closePreview()" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-700 text-sm font-semibold rounded-xl">Close</button>
                    <button wire:click="restoreRevision({{ $previewingRevision->id }})" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-xl">Restore into Editor</button>
                </div>
            </div>
        </div>
    @endif

    
    <!-- Unified Floating Sidebar Tab Container: perfectly centered vertically & auto-spaced -->
    <div class="fixed right-0 top-1/2 -translate-y-1/2 z-40 flex flex-col gap-3.5 items-end">
        
        <!-- Widgets (Toggle Widget Library) -->
        <button type="button" 
                x-on:click.stop="showWidgetLibrary = !showWidgetLibrary; showPluginsPanel = false; showShortcodeGenerator = false; showLinkGenerator = false; showAnimatePanel = false" 
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-2 py-3.5 rounded-l-2xl shadow-xl hover:shadow-2xl transition-all flex flex-col items-center gap-2 border-l border-y border-indigo-500/30 group w-[36px]"
                title="Toggle Widgets Panel">
            <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            <span class="text-[9px] font-extrabold uppercase tracking-widest [writing-mode:vertical-lr] group-hover:scale-105 transition-transform duration-200">Widgets</span>
        </button>

        <!-- Plugins (Toggle Plugins Panel) -->
        <button type="button" 
                x-on:click.stop="showPluginsPanel = !showPluginsPanel; showWidgetLibrary = false; showShortcodeGenerator = false; showLinkGenerator = false; showAnimatePanel = false" 
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-2 py-3.5 rounded-l-2xl shadow-xl hover:shadow-2xl transition-all flex flex-col items-center gap-2 border-l border-y border-emerald-500/30 group w-[36px]"
                title="Toggle Plugins Panel">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"/>
            </svg>
            <span class="text-[9px] font-extrabold uppercase tracking-widest [writing-mode:vertical-lr] group-hover:scale-105 transition-transform duration-200">Plugins</span>
        </button>

        <!-- Shortcodes (Toggle Shortcode Generator) -->
        <button type="button" 
                x-on:click.stop="showShortcodeGenerator = !showShortcodeGenerator; showWidgetLibrary = false; showPluginsPanel = false; showLinkGenerator = false; showAnimatePanel = false" 
                class="bg-blue-900 hover:bg-blue-950 text-white px-2 py-3.5 rounded-l-2xl shadow-xl hover:shadow-2xl transition-all flex flex-col items-center gap-2 border-l border-y border-blue-800/30 group w-[36px]"
                title="Toggle Shortcode Generator">
            <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
            </svg>
            <span class="text-[9px] font-extrabold uppercase tracking-widest [writing-mode:vertical-lr] group-hover:scale-105 transition-transform duration-200">Shortcodes</span>
        </button>

        <!-- Link Generator (Toggle Link Generator) -->
        <button type="button" 
                x-on:click.stop="showLinkGenerator = !showLinkGenerator; showWidgetLibrary = false; showPluginsPanel = false; showShortcodeGenerator = false; showAnimatePanel = false" 
                class="bg-orange-500 hover:bg-orange-600 text-white px-2 py-3.5 rounded-l-2xl shadow-xl hover:shadow-2xl transition-all flex flex-col items-center gap-2 border-l border-y border-orange-400/30 group w-[36px]"
                title="Toggle Link Generator">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
            </svg>
            <span class="text-[9px] font-extrabold uppercase tracking-widest [writing-mode:vertical-lr] group-hover:scale-105 transition-transform duration-200">Links</span>
        </button>

        <!-- Animations (Toggle Animate Panel) -->
        <button type="button"
                x-on:click.stop="showAnimatePanel = !showAnimatePanel; showWidgetLibrary = false; showPluginsPanel = false; showShortcodeGenerator = false; showLinkGenerator = false"
                class="bg-violet-600 hover:bg-violet-700 text-white px-2 py-3.5 rounded-l-2xl shadow-xl hover:shadow-2xl transition-all flex flex-col items-center gap-2 border-l border-y border-violet-500/30 group w-[36px]"
                title="Toggle Animation Panel">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l14 9-14 9V3z"/>
            </svg>
            <span class="text-[9px] font-extrabold uppercase tracking-widest [writing-mode:vertical-lr] group-hover:scale-105 transition-transform duration-200">Animate</span>
        </button>
        
    </div>
    
    @include('partials.html-widgets-drawer')
    @include('partials.display-plugins-drawer')
    @include('partials.link-generator-drawer')
    @include('partials.shortcodes-generator-drawer')
    @include('partials.animate-drawer')

    <script src="{{ asset('build/node_modules/tinymce/tinymce.min.js') }}"></script>
    <script>
        @if($pageId)
            window.name = "cms_edit_{{ $pageId }}";
        @endif

        window.ensureProseWrapper = function (html) {
            if (!html || !html.trim()) {
                return '<' + 'div class="prose prose-slate max-w-none"><p>&nbsp;</p></' + 'div>';
            }
            const trimmed = html.trim();
            const parser = new DOMParser();
            const doc = parser.parseFromString(trimmed, 'text/html');
            
            // Check if there is an element with class containing 'prose' or 'not-prose'
            const hasProse = doc.querySelector('[class*="prose"]');
            const hasNotProse = doc.querySelector('[class*="not-prose"]');
            
            if (hasProse || hasNotProse) {
                return html; // Already has typography or explicit override
            }
            
            // Otherwise, wrap the entire content in prose
            return '<' + 'div class="prose prose-slate max-w-none">' + html + '</' + 'div>';
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
                editor = tinymce.get('cms_page_content_editor');
            }
            if (editor) {
                editor.undoManager.transact(() => {
                    let body = editor.getBody();
                    
                    // Create a temporary div using TinyMCE's DOMUtils to parse the HTML string
                    let tempDiv = editor.dom.create('div', {}, htmlString);
                    
                    // If the body has children and the last child is not a paragraph spacer, add one
                    let lastChild = body.lastChild;
                    if (lastChild && lastChild.nodeName !== 'P') {
                        let spacer = editor.dom.create('p', {}, '<br data-mce-bogus="1">');
                        body.appendChild(spacer);
                    }
                    
                    // Append all top-level widget nodes directly to the root of the body
                    while (tempDiv.firstChild) {
                        body.appendChild(tempDiv.firstChild);
                    }
                    
                    // Add a trailing spacer paragraph at the very end to ensure user can click and type below the widget
                    let trailingSpacer = editor.dom.create('p', {}, '<br data-mce-bogus="1">');
                    body.appendChild(trailingSpacer);
                });
                
                // Focus the editor, collapse selection at the end, and scroll into view
                editor.focus();
                editor.selection.select(editor.getBody(), true);
                editor.selection.collapse(false);
                editor.selection.scrollIntoView();
                
                // Signal update changes to TinyMCE
                editor.nodeChanged();
                editor.dispatch('change');
            } else {
                alert('TinyMCE editor is not initialized.');
            }
        };

        window.insertPluginShortcode = function(shortcodeString) {
            let editor = tinymce.activeEditor;
            if (!editor) {
                editor = tinymce.get('cms_page_content_editor');
            }
            if (editor) {
                editor.undoManager.transact(() => {
                    let body = editor.getBody();
                    
                    // Create a paragraph containing the shortcode
                    let shortcodeParagraph = editor.dom.create('p', {}, shortcodeString);
                    body.appendChild(shortcodeParagraph);
                    
                    // Add a trailing spacer paragraph at the very end to ensure user can type below
                    let trailingSpacer = editor.dom.create('p', {}, '<br data-mce-bogus="1">');
                    body.appendChild(trailingSpacer);
                });
                
                // Focus the editor, collapse selection at the end, and scroll into view
                editor.focus();
                editor.selection.select(editor.getBody(), true);
                editor.selection.collapse(false);
                editor.selection.scrollIntoView();
                
                // Signal update changes to TinyMCE
                editor.nodeChanged();
                editor.dispatch('change');
            } else {
                alert('TinyMCE editor is not initialized.');
            }
        };
    </script>

                    {{-- ── Translations Tab ─────────────────────────────── --}}
                    @if($pageId && $activeLanguages->count() > 0)
                    <div x-show="activeTab === 'translations'" class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                        <h3 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-indigo-600 rounded"></span> Page Translations
                        </h3>

                        {{-- Flash message --}}
                        @if(session()->has('success'))
                            <div class="p-3 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-800 text-sm font-semibold">{{ session('success') }}</div>
                        @endif

                        {{-- Language selector pills --}}
                        <div class="flex flex-wrap gap-2">
                            @foreach($activeLanguages as $lang)
                                <button wire:click="selectTranslationLang('{{ $lang->code }}', {{ $lang->id }})"
                                        type="button"
                                        class="flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-bold transition border
                                               {{ $activeLangCode === $lang->code
                                                   ? 'bg-indigo-600 text-white border-indigo-600 shadow'
                                                   : 'bg-white text-slate-600 border-slate-200 hover:border-indigo-300 hover:bg-indigo-50' }}">
                                    <span class="text-base">{{ $lang->flag_emoji }}</span>
                                    {{ $lang->native_name }}
                                    @php
                                        $tRecord = \App\Models\CmsPageTranslation::where('cms_page_id', $pageId)->where('language_id', $lang->id)->first();
                                    @endphp
                                    @if($tRecord)
                                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full {{ $tRecord->translation_status === 'reviewed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                            {{ $tRecord->translation_status === 'reviewed' ? '✓' : 'AI' }}
                                        </span>
                                    @else
                                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-slate-100 text-slate-500">—</span>
                                    @endif
                                </button>
                            @endforeach
                        </div>

                        @if($activeLangCode)
                        <div class="border border-slate-200 rounded-2xl p-6 space-y-5 bg-slate-50/40">
                            {{-- Status bar --}}
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-bold text-slate-700">Status:</span>
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold
                                        {{ $trans_status === 'reviewed' ? 'bg-emerald-100 text-emerald-800' : ($trans_status === 'ai_translated' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600') }}">
                                        {{ $trans_status === 'reviewed' ? 'Reviewed' : ($trans_status === 'ai_translated' ? 'AI Translated' : 'Pending') }}
                                    </span>
                                    @if($trans_translated_at)
                                        <span class="text-xs text-slate-400">Last translated: {{ $trans_translated_at }}</span>
                                    @endif
                                </div>
                                <div class="flex items-center flex-wrap gap-2">
                                <button wire:click="aiTranslatePageInline" wire:loading.attr="disabled" type="button"
                                        title="Generate a fresh AI translation now. Results fill the fields below for your review before saving."
                                        class="flex items-center gap-2 px-4 py-2 bg-violet-50 hover:bg-violet-100 text-violet-700 border border-violet-200 rounded-xl text-xs font-bold transition">
                                    <span wire:loading wire:target="aiTranslatePageInline" class="animate-spin inline-block w-3.5 h-3.5 border-2 border-violet-400 border-t-transparent rounded-full"></span>
                                    <svg class="w-4 h-4" wire:loading.remove wire:target="aiTranslatePageInline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    ✦ Generate Translation
                                </button>
                                <button wire:click="autoTranslatePage" wire:loading.attr="disabled" type="button"
                                        title="Queue a background translation job. Page will refresh with AI-translated content."
                                        class="flex items-center gap-2 px-4 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 rounded-xl text-xs font-bold transition">
                                    <span wire:loading wire:target="autoTranslatePage" class="animate-spin inline-block w-3.5 h-3.5 border-2 border-amber-400 border-t-transparent rounded-full"></span>
                                    <svg class="w-4 h-4" wire:loading.remove wire:target="autoTranslatePage" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                                    Queue Bulk Job
                                </button>
                                </div>
                            </div>

                            {{-- Translation fields --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Title</label>
                                <input type="text" wire:model="trans_title"
                                       placeholder="Translated title..."
                                       class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Alternate Page Title</label>
                                <input type="text" wire:model="trans_alternate_page_title"
                                       placeholder="Translated alternate heading..."
                                       class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                            </div>
                            <div wire:ignore wire:key="trans-editor-container-{{ $activeLangCode }}"
                                 x-data="{
                                     transContent: @entangle('trans_content'),
                                     initTiny() {
                                         if (tinymce.get('cms_page_trans_content_editor')) {
                                             tinymce.remove('#cms_page_trans_content_editor');
                                         }
                                         tinymce.init({
                                             selector: '#cms_page_trans_content_editor',
                                             license_key: 'gpl',
                                             promotion: false,
                                             height: 650,
                                             menubar: 'insert format tools table',
                                             content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px; padding: 1rem; } :root { --theme-primary: {{ $primaryColor }}; --theme-primary-hover: {{ $hoverColor }}; --theme-text: {{ $textColor }}; --theme-border-radius: {{ $borderRadius }}; } .btn-theme-primary { background-color: var(--theme-primary) !important; color: var(--theme-text) !important; border-radius: var(--theme-border-radius) !important; border: none !important; padding: 10px 20px !important; font-weight: 700 !important; font-family: inherit !important; cursor: pointer !important; display: inline-block !important; text-align: center !important; text-decoration: none !important; transition: background-color 0.2s !important; } .btn-theme-primary:hover { background-color: var(--theme-primary-hover) !important; }',
                                             content_css: [
                                                 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css',
                                                 '/css/prose.css'
                                             ],
                                             images_upload_handler: window.cmsTinyMCEImageUploadHandler,
                                             plugins: 'advlist autolink lists link image charmap preview anchor searchreplace wordcount visualblocks supercode fullscreen insertdatetime media table help emoticons pagebreak directionality',
                                             toolbar: [
                                                 'supercode fullscreen | undo redo | styles blocks | bold italic underline strikethrough | forecolor backcolor',
                                                 'fontfamily fontsize lineheight | alignleft aligncenter alignright alignjustify | outdent indent | removeformat | numlist bullist | pagebreak | charmap emoticons | link image media anchor | ltr rtl | preview'
                                             ],
                                             toolbar_mode: 'wrap',
                                             supercode: { theme: 'monokai', fontSize: 14, autocomplete: true, dark: true },
                                             branding: false,
                                             contextmenu: 'link image imagetools',
                                             style_formats: [
                                                 { title: 'Callout (Yellow/Warning)', block: 'div', classes: 'p-4 bg-amber-50 dark:bg-amber-950/20 border-l-4 border-amber-500 text-amber-900 dark:text-amber-200 rounded-r-lg my-4' },
                                                 { title: 'Callout (Blue/Info)', block: 'div', classes: 'p-4 bg-blue-50 dark:bg-blue-950/20 border-l-4 border-blue-500 text-blue-900 dark:text-blue-200 rounded-r-lg my-4' },
                                                 { title: 'Callout (Green/Success)', block: 'div', classes: 'p-4 bg-emerald-50 dark:bg-emerald-950/20 border-l-4 border-emerald-500 text-emerald-900 dark:text-emerald-200 rounded-r-lg my-4' },
                                                 { title: 'Callout (Red/Danger)', block: 'div', classes: 'p-4 bg-rose-50 dark:bg-rose-950/20 border-l-4 border-rose-500 text-rose-900 dark:text-rose-200 rounded-r-lg my-4' },
                                                 { title: 'Feature Card', block: 'div', classes: 'p-6 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50 rounded-2xl shadow-sm my-6' },
                                                 { title: 'Premium Button (Primary)', selector: 'a', classes: 'inline-block px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-colors no-underline' },
                                                 { title: 'Premium Button (Outline)', selector: 'a', classes: 'inline-block px-5 py-2.5 border border-indigo-600 text-indigo-600 hover:bg-indigo-50 font-medium rounded-xl transition-colors no-underline' },
                                                 { title: 'Badge Primary', inline: 'span', classes: 'inline-block px-2.5 py-0.5 text-xs font-semibold bg-indigo-100 text-indigo-800 rounded-full' },
                                                 { title: 'Badge Success', inline: 'span', classes: 'inline-block px-2.5 py-0.5 text-xs font-semibold bg-emerald-100 text-emerald-800 rounded-full' },
                                                 { title: 'Lead Paragraph', block: 'p', classes: 'text-lg text-slate-600 dark:text-slate-400 font-medium leading-relaxed' },
                                                 { title: 'Highlight Text', inline: 'span', styles: { color: '#ff0000', textDecoration: 'underline' } }
                                             ],
                                             extended_valid_elements: '*[class|style|id|name|open|data-aos|data-aos-duration|data-aos-delay|data-aos-offset|data-aos-easing|data-aos-once|data-aos-mirror|data-aos-mobile],svg[*],path[*],circle[*],rect[*],g[*],line[*],polyline[*],polygon[*],button[*]',
                                             convert_urls: false,
                                             relative_urls: false,
                                             remove_script_host: false,
                                             valid_children: '+a[button]',
                                             setup: (editor) => {
                                                 editor.on('init', () => {
                                                     const html = this.transContent || '';
                                                     editor.setContent(window.ensureProseWrapper(html));
                                                     editor.getBody().querySelectorAll('.prose').forEach(el => {
                                                         el.style.setProperty('max-width', 'none', 'important');
                                                         el.style.setProperty('width', '100%');
                                                     });
                                                 });
                                                 editor.on('change', () => {
                                                     this.transContent = editor.getContent();
                                                 });
                                                 editor.on('blur', () => {
                                                     this.transContent = editor.getContent();
                                                 });
                                             }
                                         });
                                         this.$watch('transContent', (val) => {
                                             let editor = tinymce.get('cms_page_trans_content_editor');
                                             if (editor && editor.getContent() !== val) {
                                                 editor.setContent(window.ensureProseWrapper(val || ''));
                                             }
                                         });
                                     },
                                     destroy() {
                                         tinymce.remove('#cms_page_trans_content_editor');
                                     }
                                 }"
                                 x-init="initTiny()">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Content (HTML Editor)</label>
                                <textarea id="cms_page_trans_content_editor" class="w-full"></textarea>
                                <p class="text-xs text-slate-400 mt-1">Rich visual editor enabled. Plugin shortcodes [plugin:...] are preserved automatically during AI translation.</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Meta Title</label>
                                    <input type="text" wire:model="trans_meta_title"
                                           placeholder="Translated SEO title..."
                                           class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Meta Description</label>
                                    <input type="text" wire:model="trans_meta_description"
                                           placeholder="Translated SEO description..."
                                           class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                                </div>
                            </div>

                            {{-- Save button --}}
                            <div class="flex justify-end pt-2">
                                <button wire:click="saveTranslation" wire:loading.attr="disabled" type="button"
                                        class="flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow transition">
                                    <span wire:loading wire:target="saveTranslation" class="animate-spin inline-block w-4 h-4 border-2 border-white/30 border-t-white rounded-full"></span>
                                    Save Translation
                                </button>
                            </div>
                        </div>
                        @else
                            <div class="py-8 text-center text-slate-400 text-sm">
                                Select a language above to view or edit its translation.
                            </div>
                        @endif
                    </div>
                    @endif

</div>
</div>
