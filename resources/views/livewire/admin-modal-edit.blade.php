@php
    $primaryColor = \App\Models\CmsSetting::get('theme_primary_color', '#4f46e5');
    $hoverColor   = \App\Models\CmsSetting::get('theme_hover_color', '#4338ca');
    $textColor    = \App\Models\CmsSetting::get('theme_text_color', '#ffffff');
    $borderRadius = \App\Models\CmsSetting::get('theme_border_radius', '0.75rem');
@endphp
<div class="py-12" x-data="{
    activeTab: 'details',
    showWidgetLibrary: false,
    showPluginsPanel: false,
    showLinkGenerator: false,
    sidebarOpen: true
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">
            <div>
                <a href="{{ route('admin.modals.index') }}" wire:navigate
                   class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 hover:text-indigo-800 uppercase tracking-wider mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Modals
                </a>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 bg-gradient-to-r from-slate-900 to-indigo-950 bg-clip-text text-transparent">
                    {{ $modalId ? 'Edit Modal' : 'Create Modal' }}
                </h1>
            </div>
            <div class="flex items-center gap-3">
                {{-- Active toggle --}}
                <div class="flex items-center gap-2.5 px-3 py-1.5 bg-slate-100 rounded-2xl border border-slate-200/40">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Status:</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="is_active" class="sr-only peer">
                        <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                        <span class="ml-2 text-xs font-bold text-slate-700 uppercase tracking-wider" x-text="$wire.is_active ? 'Active' : 'Inactive'"></span>
                    </label>
                </div>
                {{-- Save --}}
                <button wire:click="save" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl shadow-md shadow-indigo-100 transition">
                    <span wire:loading.remove wire:target="save">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                    <span wire:loading wire:target="save" class="inline-block w-4 h-4 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                    Save Modal
                </button>
                @if($modalId)
                <button wire:click="delete" wire:confirm="Are you sure you want to delete this modal? All translations will be removed."
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-sm font-bold rounded-2xl border border-rose-100 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete
                </button>
                @endif
            </div>
        </div>

        <x-toast-alert />

        {{-- Shortcode badge (when editing existing) --}}
        @if($modalId)
        <div class="mb-6" x-data="{ copied: false }">
            <div class="inline-flex items-center gap-3 px-4 py-2.5 bg-indigo-50 border border-indigo-100 rounded-2xl">
                <span class="text-xs font-bold text-indigo-500 uppercase tracking-wider">Shortcode:</span>
                <code class="text-sm font-mono text-indigo-800 font-bold">[plugin:modal id={{ $modalId }}]</code>
                <button x-on:click="navigator.clipboard.writeText('[plugin:modal id={{ $modalId }}]').then(()=>{copied=true;setTimeout(()=>copied=false,2000);})"
                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-bold transition"
                        :class="copied ? 'bg-emerald-100 text-emerald-700' : 'bg-indigo-100 text-indigo-600 hover:bg-indigo-200'">
                    <span x-text="copied ? '✓ Copied!' : '📋 Copy'"></span>
                </button>
            </div>
        </div>
        @endif

        {{-- Layout Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start relative">

            {{-- Left Sidebar --}}
            <div x-show="sidebarOpen"
                 x-transition:enter="transition ease-out duration-200 transform"
                 x-transition:enter-start="opacity-0 -translate-x-4"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-150 transform"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 -translate-x-4"
                 class="lg:col-span-3 bg-white/80 backdrop-blur-md border border-slate-200/60 rounded-3xl p-6 shadow-sm space-y-2 sticky top-6">

                {{-- Sidebar toggle --}}
                <button @click="sidebarOpen = false"
                        class="absolute -right-3 top-6 w-6 h-6 bg-white border border-slate-200 rounded-full flex items-center justify-center shadow-sm text-slate-400 hover:text-indigo-600 transition z-10 lg:flex hidden">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                {{-- Tab: Details --}}
                <button @click="activeTab = 'details'"
                        :class="activeTab === 'details' ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600 hover:bg-slate-50 font-medium'"
                        class="w-full text-left px-4 py-3 rounded-2xl transition duration-150 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Modal Details
                </button>

                {{-- Tab: Appearance --}}
                <button @click="activeTab = 'appearance'"
                        :class="activeTab === 'appearance' ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600 hover:bg-slate-50 font-medium'"
                        class="w-full text-left px-4 py-3 rounded-2xl transition duration-150 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                    </svg>
                    Appearance
                </button>

                {{-- Tab: Cookie & Behavior --}}
                <button @click="activeTab = 'cookie'"
                        :class="activeTab === 'cookie' ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600 hover:bg-slate-50 font-medium'"
                        class="w-full text-left px-4 py-3 rounded-2xl transition duration-150 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Cookie &amp; Behavior
                </button>

                {{-- Tab: Custom CSS --}}
                <button @click="activeTab = 'css'"
                        :class="activeTab === 'css' ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600 hover:bg-slate-50 font-medium'"
                        class="w-full text-left px-4 py-3 rounded-2xl transition duration-150 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                    Custom CSS
                </button>

                @if($modalId && $activeLanguages->isNotEmpty())
                <div class="pt-2 border-t border-slate-100">
                    <button @click="activeTab = 'translations'"
                            :class="activeTab === 'translations' ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600 hover:bg-slate-50 font-medium'"
                            class="w-full text-left px-4 py-3 rounded-2xl transition duration-150 text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                        </svg>
                        Translations
                    </button>
                </div>
                @endif
            </div>

            {{-- Sidebar collapsed open button --}}
            <div x-show="!sidebarOpen" class="lg:col-span-1 hidden lg:flex">
                <button @click="sidebarOpen = true"
                        class="mt-4 w-8 h-8 bg-white border border-slate-200 rounded-full flex items-center justify-center shadow-sm text-slate-400 hover:text-indigo-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            {{-- Main Content Area --}}
            <div :class="sidebarOpen ? 'lg:col-span-9' : 'lg:col-span-11'" class="col-span-1 space-y-6">

                {{-- ── Tab: Modal Details ─────────────────────────────────────── --}}
                <div x-show="activeTab === 'details'" class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                    <h3 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-indigo-600 rounded"></span> Modal Details
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Title <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="title" id="modal_title"
                                   class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 shadow-sm"
                                   placeholder="e.g. Email Signup Popup">
                            @error('title') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- TinyMCE Body Editor --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Modal Body Content</label>
                        <div wire:ignore x-data="{
                            body: @entangle('body'),
                            initTiny() {
                                tinymce.init({
                                    selector: '#cms_modal_body_editor',
                                    license_key: 'gpl',
                                    promotion: false,
                                    height: 600,
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
                                    extended_valid_elements: '*[class|style|id|name|open],svg[*],path[*],circle[*],rect[*],g[*],line[*],polyline[*],polygon[*],button[*]',
                                    convert_urls: false,
                                    relative_urls: false,
                                    remove_script_host: false,
                                    valid_children: '+a[button]',
                                    setup: (editor) => {
                                        editor.on('init', () => {
                                            const html = this.body || '';
                                            editor.setContent(window.ensureProseWrapper ? window.ensureProseWrapper(html) : html);
                                        });
                                        editor.on('change', () => { this.body = editor.getContent(); });
                                        editor.on('blur',   () => { this.body = editor.getContent(); });
                                    }
                                });
                            },
                            destroy() { tinymce.remove('#cms_modal_body_editor'); }
                        }" x-init="initTiny()">
                            <textarea id="cms_modal_body_editor" class="w-full"></textarea>
                        </div>
                        @error('body') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- ── Tab: Appearance ────────────────────────────────────────── --}}
                <div x-show="activeTab === 'appearance'" class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                    <h3 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-indigo-600 rounded"></span> Appearance Settings
                    </h3>

                    {{-- Position --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Display Position</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @foreach(['center' => ['Center', 'M12 4v16m0-16C8 4 4 8 4 12s4 8 8 8 8-4 8-8-4-8-8-8z', 'Centered overlay with backdrop'], 'left' => ['Left', 'M11 19l-7-7 7-7m8 14l-7-7 7-7', 'Slides in from left'], 'right' => ['Right', 'M13 5l7 7-7 7M5 5l7 7-7 7', 'Slides in from right'], 'bottom' => ['Bottom', 'M19 9l-7 7-7-7', 'Slides up from bottom']] as $val => $info)
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="position" value="{{ $val }}" class="sr-only peer">
                                <div class="flex flex-col items-center gap-2 p-4 border-2 rounded-2xl transition peer-checked:border-indigo-500 peer-checked:bg-indigo-50 border-slate-200 hover:border-indigo-300">
                                    <svg class="w-6 h-6 text-slate-500 peer-checked:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $info[1] }}"/>
                                    </svg>
                                    <span class="text-xs font-bold text-slate-700">{{ $info[0] }}</span>
                                    <span class="text-[10px] text-slate-400 text-center leading-tight">{{ $info[2] }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('position') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    {{-- Max Width (center only) --}}
                    @if($position === 'center')
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Max Width <span class="text-slate-400 font-normal text-xs">(center position only)</span></label>
                        <input type="text" wire:model="max_width"
                               class="w-48 px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400 shadow-sm"
                               placeholder="640px">
                        <p class="text-xs text-slate-400 mt-1">CSS value, e.g. 640px, 50rem, 90vw</p>
                    </div>
                    @endif

                    {{-- Show Dismiss Button --}}
                    <div class="flex items-center justify-between p-4 bg-slate-50 border border-slate-100 rounded-2xl">
                        <div>
                            <span class="text-sm font-semibold text-slate-800">Show Dismiss Button</span>
                            <p class="text-xs text-slate-400 mt-0.5">Display an ✕ close button with "Dismiss" label</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="show_close_button" class="sr-only peer">
                            <div class="w-10 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    {{-- Overlay Dismissible --}}
                    <div class="flex items-center justify-between p-4 bg-slate-50 border border-slate-100 rounded-2xl">
                        <div>
                            <span class="text-sm font-semibold text-slate-800">Click Overlay to Close</span>
                            <p class="text-xs text-slate-400 mt-0.5">Allow clicking the backdrop to dismiss the modal</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="overlay_dismissible" class="sr-only peer">
                            <div class="w-10 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                </div>

                {{-- ── Tab: Cookie & Behavior ──────────────────────────────────── --}}
                <div x-show="activeTab === 'cookie'" class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                    <h3 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-indigo-600 rounded"></span> Cookie &amp; Behavior Settings
                    </h3>

                    {{-- Auto Open --}}
                    <div class="flex items-center justify-between p-4 bg-slate-50 border border-slate-100 rounded-2xl">
                        <div>
                            <span class="text-sm font-semibold text-slate-800">Auto-Open on Page Load</span>
                            <p class="text-xs text-slate-400 mt-0.5">Automatically display this modal when the page loads (if cookie not set)</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="auto_open" class="sr-only peer">
                            <div class="w-10 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    @if($auto_open)
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Open Delay (milliseconds)</label>
                        <input type="number" wire:model="open_delay" min="0" step="100"
                               class="w-48 px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400 shadow-sm"
                               placeholder="0">
                        <p class="text-xs text-slate-400 mt-1">0 = immediately on load. 2000 = 2 seconds after page load.</p>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Cookie Name</label>
                            <input type="text" wire:model="cookie_name"
                                   class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400 shadow-sm"
                                   placeholder="auto: cms_modal_{{ $modalId ?? 'N' }}">
                            <p class="text-xs text-slate-400 mt-1">Leave blank to auto-generate from modal ID.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Cookie Lifetime (days)</label>
                            <input type="number" wire:model="cookie_lifetime" min="0"
                                   class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400 shadow-sm"
                                   placeholder="30">
                            <p class="text-xs text-slate-400 mt-1">0 = session only (resets on browser close).</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Trigger Selector <span class="text-slate-400 font-normal">(optional)</span></label>
                        <input type="text" wire:model="trigger_selector"
                               class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-mono text-slate-800 focus:outline-none focus:border-indigo-400 shadow-sm"
                               placeholder=".open-my-modal, #signup-btn">
                        <p class="text-xs text-slate-400 mt-1">CSS selector for elements that trigger this modal on click. Leave blank if using auto-open only.</p>
                    </div>
                </div>

                {{-- ── Tab: Custom CSS ─────────────────────────────────────────── --}}
                <div x-show="activeTab === 'css'" class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                    <h3 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-indigo-600 rounded"></span> Custom CSS Override
                    </h3>
                    <div>
                        <p class="text-xs text-slate-400 mb-3">
                            CSS rules entered here are scoped to the modal panel via <code class="bg-slate-100 px-1.5 py-0.5 rounded text-xs font-mono">#cms-modal-outer-{{ $modalId ?? 'N' }} .cms-modal-panel { ... }</code>
                        </p>
                        <textarea wire:model="custom_css" rows="12"
                                  class="w-full px-4 py-3 bg-slate-900 text-green-400 border border-slate-700 rounded-2xl text-sm font-mono focus:outline-none focus:border-indigo-400 shadow-sm"
                                  placeholder="background: linear-gradient(135deg, #667eea, #764ba2);&#10;border-radius: 1.5rem;&#10;color: #fff;"></textarea>
                    </div>
                </div>

                {{-- ── Tab: Translations ───────────────────────────────────────── --}}
                @if($modalId && $activeLanguages->isNotEmpty())
                <div x-show="activeTab === 'translations'" class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                    <h3 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-indigo-600 rounded"></span> Modal Translations
                    </h3>

                    {{-- Language pills --}}
                    <div class="flex flex-wrap gap-2">
                        @foreach($activeLanguages as $lang)
                        @php $tRecord = \App\Models\CmsModalTranslation::where('cms_modal_id', $modalId)->where('language_id', $lang->id)->first(); @endphp
                        <button type="button" wire:click="selectTlLang({{ $lang->id }})"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border transition
                                       {{ $tlLangId === $lang->id ? 'bg-indigo-600 text-white border-indigo-600 shadow' : 'bg-white text-slate-600 border-slate-200 hover:border-indigo-300 hover:bg-indigo-50' }}">
                            <span class="text-base">{{ $lang->flag_emoji }}</span>
                            {{ $lang->name }}
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

                    @if($tlLangId > 0)
                    <div class="border border-slate-200 rounded-2xl p-6 space-y-5 bg-slate-50/40">
                        {{-- Status + actions --}}
                        <div class="flex items-center justify-between flex-wrap gap-3">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-bold text-slate-700">Status:</span>
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold
                                    {{ $tlStatus === 'reviewed' ? 'bg-emerald-100 text-emerald-800' : ($tlStatus === 'ai_translated' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600') }}">
                                    {{ $tlStatus === 'reviewed' ? 'Reviewed' : ($tlStatus === 'ai_translated' ? 'AI Translated' : 'Pending') }}
                                </span>
                                @if($tlTranslatedAt)
                                <span class="text-xs text-slate-400">Last translated: {{ $tlTranslatedAt }}</span>
                                @endif
                            </div>
                            <button wire:click="aiTlModal" wire:loading.attr="disabled" wire:target="aiTlModal" type="button"
                                    class="flex items-center gap-2 px-4 py-2 bg-violet-50 hover:bg-violet-100 text-violet-700 border border-violet-200 rounded-xl text-xs font-bold transition">
                                <span wire:loading wire:target="aiTlModal" class="animate-spin inline-block w-3.5 h-3.5 border-2 border-violet-400 border-t-transparent rounded-full"></span>
                                <svg class="w-4 h-4" wire:loading.remove wire:target="aiTlModal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                ✦ AI Translate All
                            </button>
                        </div>

                        {{-- Title field --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Title <span class="text-slate-400 font-normal">(Default: "{{ $title }}")</span></label>
                            <input type="text" wire:model="tlBuffer.title"
                                   placeholder="Translated title..."
                                   class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                        </div>

                        {{-- Body field (textarea — not TinyMCE for translation workflow) --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Body Content (HTML)</label>
                            <textarea wire:model="tlBuffer.body" rows="10"
                                      placeholder="Translated modal body HTML..."
                                      class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 font-mono focus:outline-none focus:border-indigo-400"></textarea>
                            <p class="text-xs text-slate-400 mt-1">HTML is supported. Plugin shortcodes are preserved automatically during AI translation.</p>
                        </div>

                        <div class="flex justify-end pt-1">
                            <button wire:click="saveTlModal" wire:loading.attr="disabled" wire:target="saveTlModal" type="button"
                                    class="flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow transition">
                                <span wire:loading wire:target="saveTlModal" class="animate-spin inline-block w-4 h-4 border-2 border-white/30 border-t-white rounded-full"></span>
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

            </div>{{-- end main content --}}
        </div>{{-- end grid --}}
    </div>{{-- end container --}}

    {{-- ── Right Floating Sidebar Tab Buttons ─────────────────────────────── --}}
    <div class="fixed right-0 top-1/2 -translate-y-1/2 z-40 flex flex-col gap-3.5 items-end">
        <button type="button"
                x-on:click.stop="showWidgetLibrary = !showWidgetLibrary; showPluginsPanel = false; showLinkGenerator = false"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-2 py-3.5 rounded-l-2xl shadow-xl hover:shadow-2xl transition-all flex flex-col items-center gap-2 border-l border-y border-indigo-500/30 group w-[36px]"
                title="Toggle Widgets Panel">
            <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            <span class="text-[9px] font-extrabold uppercase tracking-widest [writing-mode:vertical-lr]">Widgets</span>
        </button>

        <button type="button"
                x-on:click.stop="showPluginsPanel = !showPluginsPanel; showWidgetLibrary = false; showLinkGenerator = false"
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-2 py-3.5 rounded-l-2xl shadow-xl hover:shadow-2xl transition-all flex flex-col items-center gap-2 border-l border-y border-emerald-500/30 group w-[36px]"
                title="Toggle Plugins Panel">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"/>
            </svg>
            <span class="text-[9px] font-extrabold uppercase tracking-widest [writing-mode:vertical-lr]">Plugins</span>
        </button>

        <button type="button"
                x-on:click.stop="showLinkGenerator = !showLinkGenerator; showWidgetLibrary = false; showPluginsPanel = false"
                class="bg-orange-500 hover:bg-orange-600 text-white px-2 py-3.5 rounded-l-2xl shadow-xl hover:shadow-2xl transition-all flex flex-col items-center gap-2 border-l border-y border-orange-400/30 group w-[36px]"
                title="Toggle Link Generator">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
            </svg>
            <span class="text-[9px] font-extrabold uppercase tracking-widest [writing-mode:vertical-lr]">Links</span>
        </button>
    </div>

    {{-- ── Right Slide-Over: Widget Library ────────────────────────────────── --}}
    @include('partials.html-widgets-drawer')


    {{-- ── Right Slide-Over: Plugins Panel ─────────────────────────────────── --}}
    <div x-show="showPluginsPanel" x-cloak
         x-transition:enter="transform transition ease-in-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in-out duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         @click.outside="showPluginsPanel = false"
         class="fixed inset-y-0 right-0 w-96 bg-white shadow-2xl z-50 flex flex-col overflow-hidden border-l border-slate-200">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-emerald-600">
            <span class="font-bold text-white text-sm">Plugin Shortcodes</span>
            <button @click="showPluginsPanel = false" class="text-emerald-200 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-6 space-y-3">
            @forelse($displayPlugins as $dp)
            <div class="p-3 bg-slate-50 border border-slate-200 rounded-2xl space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-slate-800">{{ $dp->name }}</span>
                </div>
                <code class="text-xs bg-white border border-slate-200 rounded-lg px-2 py-1 font-mono text-indigo-700 block">
                    [plugin:{{ $dp->shortcode }}]
                </code>
                <button type="button"
                        onclick="window.insertPluginShortcode ? window.insertPluginShortcode('[plugin:{{ $dp->shortcode }}]') : null"
                        class="text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-xl transition">
                    Insert into Editor
                </button>
            </div>
            @empty
            <p class="text-slate-400 text-sm text-center py-8">No active display plugins found.</p>
            @endforelse
        </div>
    </div>

    {{-- ── Right Slide-Over: Link Generator ────────────────────────────────── --}}
    <div x-show="showLinkGenerator" x-cloak
         x-transition:enter="transform transition ease-in-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in-out duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         @click.outside="showLinkGenerator = false"
         class="fixed inset-y-0 right-0 w-96 bg-white shadow-2xl z-50 flex flex-col overflow-hidden border-l border-slate-200">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-orange-500">
            <span class="font-bold text-white text-sm">Link Generator</span>
            <button @click="showLinkGenerator = false" class="text-orange-200 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-6 space-y-6">
            {{-- Products --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Products</label>
                <input type="text" wire:model.live.debounce.300ms="searchProduct" placeholder="Search products..."
                       class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400">
                @if($searchProduct && strlen($searchProduct) >= 2)
                <div class="mt-2 space-y-1">
                    @foreach($this->searchedProducts as $item)
                    <button type="button" onclick="
                        var ed = tinymce.get('cms_modal_body_editor');
                        if(ed) ed.insertContent('<a href=\'[product:{{ $item->id }} label=\'{{ addslashes($item->title) }}\']\'> {{ addslashes($item->title) }}</a>');
                    " class="w-full text-left px-3 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-xl transition truncate">
                        {{ $item->title }}
                    </button>
                    @endforeach
                </div>
                @endif
            </div>
            {{-- Pages --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pages</label>
                <input type="text" wire:model.live.debounce.300ms="searchPage" placeholder="Search pages..."
                       class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400">
                @if($searchPage && strlen($searchPage) >= 2)
                <div class="mt-2 space-y-1">
                    @foreach($this->searchedPages as $item)
                    <button type="button" onclick="
                        var ed = tinymce.get('cms_modal_body_editor');
                        if(ed) ed.insertContent('<a href=\'/{{ $item->slug }}\'>{{ addslashes($item->title) }}</a>');
                    " class="w-full text-left px-3 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-xl transition truncate">
                        {{ $item->title }}
                    </button>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    <script src="{{ asset('build/node_modules/tinymce/tinymce.min.js') }}"></script>
    <script>
        window.insertPluginShortcode = function(shortcodeString) {
            var editor = tinymce.activeEditor || tinymce.get('cms_modal_body_editor');
            if (editor) {
                editor.undoManager.transact(function() {
                    var body = editor.getBody();
                    var p = editor.dom.create('p', {}, shortcodeString);
                    body.appendChild(p);
                    var spacer = editor.dom.create('p', {}, '<br data-mce-bogus="1">');
                    body.appendChild(spacer);
                });
                editor.focus();
                editor.selection.select(editor.getBody(), true);
                editor.selection.collapse(false);
                editor.nodeChanged();
                editor.dispatch('change');
            }
        };

        window.ensureProseWrapper = window.ensureProseWrapper || function(html) {
            if (!html || !html.trim()) {
                return '<div class="prose prose-slate max-w-none"><p>&nbsp;</p></div>';
            }
            var trimmed = html.trim();
            var parser = new DOMParser();
            var doc = parser.parseFromString(trimmed, 'text/html');
            var hasProse = doc.querySelector('[class*="prose"]');
            if (hasProse) return html;
            return '<div class="prose prose-slate max-w-none">' + html + '</div>';
        };

        window.cmsTinyMCEImageUploadHandler = window.cmsTinyMCEImageUploadHandler || function(blobInfo, progress) {
            return new Promise(function(resolve, reject) {
                var xhr = new XMLHttpRequest();
                xhr.withCredentials = true;
                xhr.open('POST', '/admin/cms-pages/upload-image');
                var token = document.querySelector('meta[name="csrf-token"]');
                if (token) xhr.setRequestHeader('X-CSRF-TOKEN', token.getAttribute('content'));
                xhr.upload.onprogress = function(e) { progress(e.loaded / e.total * 100); };
                xhr.onload = function() {
                    if (xhr.status < 200 || xhr.status >= 300) { reject({ message: 'HTTP Error: ' + xhr.status, remove: true }); return; }
                    var json;
                    try { json = JSON.parse(xhr.responseText); } catch(e) { reject('Invalid JSON'); return; }
                    if (!json || typeof json.location !== 'string') { reject('Invalid JSON: ' + xhr.responseText); return; }
                    resolve(json.location);
                };
                xhr.onerror = function() { reject('XHR error'); };
                var fd = new FormData();
                fd.append('file', blobInfo.blob(), blobInfo.filename());
                xhr.send(fd);
            });
        };
    </script>
</div>
