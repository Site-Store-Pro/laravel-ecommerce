<div x-data="{
    showLinkGenerator: false,
    showShortcodeGenerator: false,
    showPluginsPanel: false,
    showWidgetLibrary: false
}" class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6 relative">

    {{-- TinyMCE Library --}}
    <script src="{{ asset('build/node_modules/tinymce/tinymce.min.js') }}" onerror="if(!window.tinymce){var s=document.createElement('script');s.src='https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js';document.head.appendChild(s);}"></script>

    {{-- Image Upload Handler & TinyMCE Helper Functions --}}
    <script>
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
                    if (e.lengthComputable) {
                        progress(e.loaded / e.total * 100);
                    }
                };

                xhr.onload = () => {
                    if (xhr.status < 200 || xhr.status >= 300) {
                        reject('HTTP Error: ' + xhr.status);
                        return;
                    }
                    try {
                        const json = JSON.parse(xhr.responseText);
                        if (json && json.location) {
                            resolve(json.location);
                        } else {
                            reject('Invalid response: ' + xhr.responseText);
                        }
                    } catch (e) {
                        reject('JSON parse error: ' + xhr.responseText);
                    }
                };

                xhr.onerror = () => {
                    reject('XHR transport error');
                };

                const formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                xhr.send(formData);
            });
        };

        window.insertHtmlWidget = function(htmlString) {
            let editor = (typeof tinymce !== 'undefined') ? (tinymce.get('block_content_editor') || tinymce.activeEditor) : null;
            if (editor) {
                editor.undoManager.transact(() => {
                    let body = editor.getBody();
                    let tempDiv = editor.dom.create('div', {}, htmlString);
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
            let editor = (typeof tinymce !== 'undefined') ? (tinymce.get('block_content_editor') || tinymce.activeEditor) : null;
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

    {{-- Flash Notification --}}
    @if (session()->has('message'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 flex items-center gap-3 text-emerald-800 dark:text-emerald-200 text-sm font-medium">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    {{-- Header Title & Section Navigation Tabs --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Header & Footer Layout Builder</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Visual wireframe mockup canvas matching actual layout geometry, inline TinyMCE editor, live iframe preview, and background image manager.
            </p>
        </div>

        {{-- Section Tabs --}}
        <div class="flex items-center gap-1.5 bg-slate-100 dark:bg-slate-700/60 p-1.5 rounded-xl border border-slate-200 dark:border-slate-600 self-stretch md:self-auto overflow-x-auto">
            <button wire:click="setSectionTab('header')" class="px-4 py-2 text-xs font-bold rounded-lg transition-all {{ $activeTab === 'header' ? 'bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900' }}">
                Header Canvas
            </button>
            <button wire:click="setSectionTab('footer')" class="px-4 py-2 text-xs font-bold rounded-lg transition-all {{ $activeTab === 'footer' ? 'bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900' }}">
                Footer Canvas
            </button>
            <button wire:click="setSectionTab('css_manager')" class="px-4 py-2 text-xs font-bold rounded-lg transition-all {{ $activeTab === 'css_manager' ? 'bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900' }}">
                CSS & Theme Variables
            </button>
            <button wire:click="setSectionTab('full_preview')" class="px-4 py-2 text-xs font-bold rounded-lg transition-all {{ $activeTab === 'full_preview' ? 'bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900' }}">
                Full Page Sandbox
            </button>
        </div>
    </div>

    {{-- Viewport Device Switcher & Control Buttons --}}
    @if($activeTab === 'header' || $activeTab === 'footer')
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            {{-- Device Selection --}}
            <div class="flex items-center gap-3 flex-wrap">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Device Mode:</span>
                    <div class="flex items-center gap-1 bg-slate-100 dark:bg-slate-700/60 p-1 rounded-xl">
                        <button wire:click="setDeviceView('desktop')" class="px-3 py-1.5 text-xs font-bold rounded-lg transition {{ $deviceView === 'desktop' ? 'bg-indigo-600 text-white shadow' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900' }}">
                            🖥️ Desktop (&ge; 1024px)
                        </button>
                        <button wire:click="setDeviceView('tablet')" class="px-3 py-1.5 text-xs font-bold rounded-lg transition {{ $deviceView === 'tablet' ? 'bg-indigo-600 text-white shadow' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900' }}">
                            📱 Tablet (768px - 1023px)
                        </button>
                        <button wire:click="setDeviceView('mobile')" class="px-3 py-1.5 text-xs font-bold rounded-lg transition {{ $deviceView === 'mobile' ? 'bg-indigo-600 text-white shadow' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900' }}">
                            📱 Mobile (&lt; 768px)
                        </button>
                    </div>
                </div>

                {{-- Single Responsive Header Override Toggle --}}
                @if($activeTab === 'header')
                    <div class="flex items-center gap-2 border-l border-slate-200 dark:border-slate-700 pl-3">
                        <label for="singleHeaderToggle" class="text-xs font-bold text-slate-700 dark:text-slate-200 cursor-pointer flex items-center gap-2">
                            <span>Single Responsive Header:</span>
                            <button type="button" wire:click="toggleSingleHeaderConfig" id="singleHeaderToggle" class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $singleHeaderConfig ? 'bg-indigo-600' : 'bg-slate-300 dark:bg-slate-600' }}" role="switch" aria-checked="{{ $singleHeaderConfig ? 'true' : 'false' }}">
                                <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $singleHeaderConfig ? 'translate-x-4' : 'translate-x-0' }}"></span>
                            </button>
                        </label>
                        <span class="px-2 py-0.5 text-[10px] font-extrabold rounded-full {{ $singleHeaderConfig ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-200' : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400' }}">
                            {{ $singleHeaderConfig ? 'ON (Single Responsive Header)' : 'OFF (Multi-Device Config)' }}
                        </span>
                    </div>
                @endif
            </div>

            {{-- Control Actions --}}
            <div class="flex items-center gap-2">
                <button wire:click="openAddModal" class="px-3.5 py-2 text-xs font-bold bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition flex items-center gap-1.5">
                    <span>+ Create Custom Block</span>
                </button>
                <button wire:click="seedDefaultBlocks" onclick="confirm('Reset all header and footer blocks to default seed templates?') || event.stopImmediatePropagation()" class="px-3.5 py-2 text-xs font-bold bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl hover:bg-slate-200 transition">
                    🌱 Seed Defaults
                </button>
            </div>
        </div>
    @endif

    {{-- SECTION TAB #1 & #2: HEADER / FOOTER VISUAL WIREFRAME BUILDER CANVAS --}}
    @if($activeTab === 'header' || $activeTab === 'footer')
        <div class="space-y-6">

            {{-- Single Responsive Header Mode Banner --}}
            @if($activeTab === 'header' && $singleHeaderConfig)
                <div class="p-3.5 bg-indigo-50/90 dark:bg-indigo-950/50 border border-indigo-200 dark:border-indigo-800 rounded-2xl flex items-center gap-3 text-indigo-900 dark:text-indigo-200 text-xs font-medium shadow-sm">
                    <svg class="w-5 h-5 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span><strong>Single Responsive Header is Active:</strong> A single unified header layout is configured. Top navigation displays on Desktop (&ge; 1024px) and automatically collapses into the hamburger menu &amp; mobile menu drawer on Tablet &amp; Mobile (&le; 1023px) for optimal speed and streamlined setup.</span>
                </div>
            @endif

            {{-- 1. Inactive / Available Elements Pool Tray --}}
            @php
                $evalDevice = ($activeTab === 'header' && $singleHeaderConfig) ? 'desktop' : $deviceView;
                $currentBlocks = $activeTab === 'header' ? $headerBlocks : $footerBlocks;
                $inactiveBlocks = $currentBlocks->filter(fn($b) => !$b->isActiveForDevice($evalDevice));
            @endphp
            @if($inactiveBlocks->isNotEmpty())
                <div class="bg-amber-50/70 dark:bg-amber-950/20 border-2 border-dashed border-amber-300 dark:border-amber-700/60 rounded-2xl p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-extrabold text-amber-800 dark:text-amber-300 uppercase tracking-wider">Available / Inactive Layout Blocks Pool</span>
                            <span class="px-2 py-0.5 text-2xs font-bold bg-amber-200 dark:bg-amber-800 text-amber-900 dark:text-amber-100 rounded-full">{{ $inactiveBlocks->count() }} Available</span>
                        </div>
                        <span class="text-2xs text-amber-700 dark:text-amber-400">Click "+ Activate" to place a block onto the canvas wireframe</span>
                    </div>

                    <div class="flex flex-wrap gap-2.5">
                        @foreach($inactiveBlocks as $inBlock)
                            <div class="bg-white dark:bg-slate-800 border border-amber-200 dark:border-amber-700/60 rounded-xl p-3 shadow-sm flex items-center justify-between gap-3 min-w-[200px]">
                                <div>
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-100 block">{{ $inBlock->title }}</span>
                                    <span class="text-2xs text-slate-400 block font-mono">{{ $inBlock->target_element ?? $inBlock->type }}</span>
                                </div>
                                <button wire:click="toggleActive({{ $inBlock->id }})" class="px-2.5 py-1 rounded-lg bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition">
                                    + Activate
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- 2. Visual Wireframe Mockup Canvas --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-3">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                            <span>{{ ucfirst($activeTab) }} Visual Wireframe Canvas</span>
                            <span class="text-2xs font-mono px-2 py-0.5 rounded bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300">{{ ucfirst($deviceView) }} Geometry</span>
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Mimicking the exact layout geometry of the {{ $activeTab }}. Dashed outlined elements sit in their true layout positions with Edit and Remove buttons.
                        </p>
                    </div>

                    {{-- Search & Navigation Placement Toolbar Control --}}
                    @if($activeTab === 'header')
                        <div class="flex items-center gap-4 bg-slate-50 dark:bg-slate-900/60 p-3 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 flex-wrap">
                            @if($deviceView === 'desktop')
                                @php $navPlacement = $cssVars['nav_placement'] ?? 'standalone'; @endphp
                                <div class="flex items-center gap-2">
                                    <span class="text-indigo-600 dark:text-indigo-400 font-extrabold uppercase text-2xs tracking-wider">Navigation Bar Location:</span>
                                    <select wire:change="setNavPlacement($event.target.value)" class="text-xs font-bold bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg px-2.5 py-1 text-slate-800 dark:text-white">
                                        <option value="standalone" {{ $navPlacement === 'standalone' ? 'selected' : '' }}>Standalone Row Below Header</option>
                                        <option value="main_header" {{ $navPlacement === 'main_header' ? 'selected' : '' }}>Main Header Bar (Center Embedded)</option>
                                        <option value="header_col1" {{ $navPlacement === 'header_col1' ? 'selected' : '' }}>Header Column #1 (Left Slot)</option>
                                        <option value="header_col2" {{ $navPlacement === 'header_col2' ? 'selected' : '' }}>Header Column #2 (Right Slot)</option>
                                    </select>
                                </div>
                                <div class="flex items-center gap-2 border-l border-slate-200 dark:border-slate-700 pl-3">
                                    <span class="text-indigo-600 dark:text-indigo-400 font-extrabold uppercase text-2xs tracking-wider">Desktop Search Bar Placement:</span>
                                    <select wire:model.live="cssVars.search_placement_desktop" wire:change="saveCssVars" class="text-xs font-bold bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg px-2.5 py-1 text-slate-800 dark:text-white">
                                        <option value="main_header">Main Header Bar (Center/Embedded)</option>
                                        <option value="header_col1">Header Column #1 (Left Slot)</option>
                                        <option value="header_col2">Header Column #2 (Right Slot)</option>
                                        <option value="top_sharing_container">Top Sharing / Alert Bar Row</option>
                                        <option value="standalone">Standalone Row Below Header</option>
                                        <option value="disabled">Disabled / Off (Not Displayed)</option>
                                    </select>
                                </div>
                            @elseif($deviceView === 'tablet')
                                <div class="flex items-center gap-2">
                                    <span class="text-indigo-600 dark:text-indigo-400 font-extrabold uppercase text-2xs tracking-wider">Tablet Search Bar Placement:</span>
                                    <select wire:model.live="cssVars.search_placement_tablet" wire:change="saveCssVars" class="text-xs font-bold bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg px-2.5 py-1 text-slate-800 dark:text-white">
                                        <option value="main_header">Main Header Bar (Center/Embedded)</option>
                                        <option value="header_col1">Header Column #1 (Left Slot)</option>
                                        <option value="header_col2">Header Column #2 (Right Slot)</option>
                                        <option value="top_sharing_container">Top Sharing / Alert Bar Row</option>
                                        <option value="standalone">Standalone Row Below Header</option>
                                        <option value="disabled">Disabled / Off (Not Displayed)</option>
                                    </select>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <span class="text-indigo-600 dark:text-indigo-400 font-extrabold uppercase text-2xs tracking-wider">Mobile Menu Search Position:</span>
                                    <select wire:model.live="cssVars.mobile_search_position" wire:change="saveCssVars" class="text-xs font-bold bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg px-2.5 py-1 text-slate-800 dark:text-white">
                                        <option value="top">Top of Mobile Menu Drawer</option>
                                        <option value="bottom">Bottom of Mobile Menu Drawer</option>
                                        <option value="disabled">Disabled / Off (Not Displayed)</option>
                                    </select>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                @if($activeTab === 'header')
                    {{-- HEADER GEOMETRIC WIREFRAME MOCKUP WITH DRAGGABLE ROWS --}}
                    @php
                        $evalDevice = $singleHeaderConfig ? 'desktop' : $deviceView;
                        $sortCol = match($evalDevice) {
                            'tablet' => 'sort_tablet',
                            'mobile' => 'sort_mobile',
                            default  => 'sort_desktop',
                        };
                        $activeHeaderRows = $headerBlocks->where('type', 1)
                            ->filter(fn($b) => $b->isActiveForDevice($evalDevice))
                            ->sortBy(fn($b) => $b->{$sortCol})
                            ->values();
                        $isNavEmbedded = !empty($cssVars['nav_inside_main_header']);
                        $searchPlacementDesktop = $cssVars['search_placement_desktop'] ?? 'main_header';
                        $searchPlacementTablet  = $singleHeaderConfig ? $searchPlacementDesktop : ($cssVars['search_placement_tablet'] ?? 'main_header');
                        $mobileSearchPosition   = $cssVars['mobile_search_position'] ?? 'top';
                        $activeSearchPlacement  = match($evalDevice) {
                            'tablet' => $searchPlacementTablet,
                            'mobile' => 'mobile_menu',
                            default  => $searchPlacementDesktop,
                        };
                    @endphp

                    <div x-data="{ draggingIndex: null, targetIndex: null }" class="header-wireframe-mockup rounded-2xl border-2 border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 p-4 space-y-4 shadow-inner">
                        
                        @foreach($activeHeaderRows as $rowIndex => $rowBlock)
                            <div draggable="true"
                                 @dragstart="draggingIndex = {{ $rowIndex }}; $event.dataTransfer.effectAllowed = 'move';"
                                 @dragover.prevent="targetIndex = {{ $rowIndex }}"
                                 @dragleave="if(targetIndex === {{ $rowIndex }}) targetIndex = null"
                                 @drop.prevent="if(draggingIndex !== null && draggingIndex !== {{ $rowIndex }}) { $wire.reorderHeaderRows(draggingIndex, {{ $rowIndex }}); } draggingIndex = null; targetIndex = null"
                                 :class="{ 'opacity-40 border-indigo-500 scale-[0.99]': draggingIndex === {{ $rowIndex }}, 'ring-2 ring-indigo-500 ring-offset-2': targetIndex === {{ $rowIndex }} }"
                                 class="header-row-wrapper transition-all duration-200 relative">
                                
                                {{-- Row Drag Handle & Control Header --}}
                                <div class="flex items-center justify-between gap-2 px-3 py-1.5 bg-slate-200/80 dark:bg-slate-700/80 rounded-t-xl border border-slate-300 dark:border-slate-600 text-2xs font-extrabold uppercase tracking-wider text-slate-700 dark:text-slate-200">
                                    <div class="flex items-center gap-2">
                                        <span class="cursor-grab active:cursor-grabbing text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 transition flex items-center gap-1 font-extrabold" title="Drag to reorder row sequence">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 8h16M4 16h16"/></svg>
                                            <span>DRAG HANDLE</span>
                                        </span>
                                        <span class="text-slate-400">|</span>
                                        <span>Row #{{ $rowIndex + 1 }}: {{ $rowBlock->title }} ({{ $rowBlock->target_element }})</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        @if($rowIndex > 0)
                                            <button wire:click="moveHeaderRowUp({{ $rowBlock->id }})" class="px-2 py-0.5 bg-white dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-slate-700 text-indigo-600 dark:text-indigo-300 rounded border border-slate-200 dark:border-slate-600 font-bold transition flex items-center gap-1" title="Move Row Up">
                                                ▲ Up
                                            </button>
                                        @endif
                                        @if($rowIndex < count($activeHeaderRows) - 1)
                                            <button wire:click="moveHeaderRowDown({{ $rowBlock->id }})" class="px-2 py-0.5 bg-white dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-slate-700 text-indigo-600 dark:text-indigo-300 rounded border border-slate-200 dark:border-slate-600 font-bold transition flex items-center gap-1" title="Move Row Down">
                                                ▼ Down
                                            </button>
                                        @endif
                                        <button wire:click="toggleActive({{ $rowBlock->id }})" class="px-2 py-0.5 bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 rounded border border-rose-200 dark:border-rose-800 font-bold hover:bg-rose-100 transition" title="Hide/Deactivate Row">
                                            Hide Row
                                        </button>
                                    </div>
                                </div>

                                {{-- ROW CONTENT TYPE: Top Sharing & Alerts --}}
                                @if($rowBlock->target_element === 'top_sharing_container')
                                    <div class="top-alerts-wireframe-row rounded-b-xl border-2 border-dashed border-indigo-300 dark:border-indigo-700/60 bg-indigo-50/30 dark:bg-indigo-950/20 p-3">
                                        <div class="flex flex-wrap md:flex-nowrap items-center gap-3 w-full">
                                            @foreach(['sharing_col1', 'sharing_col2', 'sharing_col3'] as $slotName)
                                                @php $bSlot = $headerBlocks->firstWhere('target_element', $slotName); @endphp
                                                @if($bSlot && $bSlot->isActiveForDevice($deviceView))
                                                    <div class="dashed-wire-slot flex-1 min-w-[180px] rounded-lg border-2 border-dashed border-indigo-500 bg-indigo-50 dark:bg-indigo-900/30 p-3 relative transition-all duration-300">
                                                        <div class="flex items-center justify-between gap-2">
                                                            <div>
                                                                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate block">{{ $bSlot->title }}</span>
                                                                <span class="text-2xs font-mono text-slate-400 block">{{ $slotName }}</span>
                                                            </div>
                                                            <div class="flex items-center gap-1 shrink-0">
                                                                <button wire:click="editBlock({{ $bSlot->id }})" class="px-2 py-1 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700 transition flex items-center gap-1" title="Edit Block">
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                                    <span>Edit</span>
                                                                </button>
                                                                <button wire:click="toggleActive({{ $bSlot->id }})" class="px-2 py-1 rounded-lg bg-rose-500 text-white text-xs font-medium hover:bg-rose-600 transition flex items-center gap-1" title="Remove Block">
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                    <span>Remove</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach

                                            {{-- Header Search Bar in Top Sharing Container --}}
                                            @php $bSearch = $headerBlocks->firstWhere('target_element', 'header_search'); @endphp
                                            @if($activeSearchPlacement === 'top_sharing_container' && $bSearch && $bSearch->isActiveForDevice($deviceView))
                                                <div class="flex-1 min-w-[180px] max-w-xs rounded-lg border-2 border-dashed border-purple-500 bg-purple-50/50 dark:bg-purple-900/20 p-3 transition-all duration-300">
                                                    <div class="flex items-center justify-between gap-2">
                                                        <div>
                                                            <span class="text-xs font-extrabold text-slate-800 dark:text-slate-100 block">{{ $bSearch->title }}</span>
                                                            <span class="text-2xs text-slate-400 block font-mono">top_sharing_container</span>
                                                        </div>
                                                        <div class="flex items-center gap-1 shrink-0">
                                                            <button wire:click="editBlock({{ $bSearch->id }})" class="px-2 py-1 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700 transition flex items-center gap-1" title="Edit Search Bar Block">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                                <span>Edit</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                {{-- ROW CONTENT TYPE: Main Site Header Bar --}}
                                @elseif($rowBlock->target_element === 'site_header_container')
                                    <div class="main-header-wireframe-row rounded-b-xl border-2 border-dashed border-indigo-400 dark:border-indigo-600 bg-white dark:bg-slate-800 p-4 shadow-sm space-y-3">
                                        <div class="flex flex-wrap lg:flex-nowrap gap-3 items-center w-full">
                                            {{-- Logo Section --}}
                                            @php $bLogo = $headerBlocks->firstWhere('target_element', 'header_logo'); @endphp
                                            @if($bLogo && $bLogo->isActiveForDevice($deviceView))
                                                <div class="shrink-0 rounded-lg border-2 border-dashed border-indigo-500 bg-indigo-50/50 dark:bg-indigo-900/20 p-3 min-w-[180px]">
                                                    <div class="flex items-center justify-between gap-2">
                                                        <div>
                                                            <span class="text-xs font-extrabold text-slate-800 dark:text-slate-100 block">{{ $bLogo->title }}</span>
                                                            <span class="text-2xs text-slate-400 block font-mono">header_logo</span>
                                                        </div>
                                                        <div class="flex items-center gap-1 shrink-0">
                                                            <button wire:click="editBlock({{ $bLogo->id }})" class="px-2 py-1 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700 transition flex items-center gap-1" title="Edit Logo Content">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                                <span>Edit</span>
                                                            </button>
                                                            <button wire:click="toggleActive({{ $bLogo->id }})" class="px-2 py-1 rounded-lg bg-rose-500 text-white text-xs font-medium hover:bg-rose-600 transition flex items-center gap-1" title="Remove Logo">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                <span>Remove</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Header Col 1 --}}
                                            @php $bCol1 = $headerBlocks->firstWhere('target_element', 'header_col1'); @endphp
                                            @if($bCol1 && $bCol1->isActiveForDevice($deviceView))
                                                <div class="flex-1 min-w-[200px] rounded-lg border-2 border-dashed border-indigo-500 bg-indigo-50/50 dark:bg-indigo-900/20 p-3 transition-all duration-300">
                                                    <div class="flex items-center justify-between gap-2">
                                                        <div>
                                                            <span class="text-xs font-extrabold text-slate-800 dark:text-slate-100 block">{{ $bCol1->title }}</span>
                                                            <span class="text-2xs text-slate-400 block font-mono">header_col1</span>
                                                        </div>
                                                        <div class="flex items-center gap-1 shrink-0">
                                                            <button wire:click="editBlock({{ $bCol1->id }})" class="px-2 py-1 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700 transition flex items-center gap-1" title="Edit Block">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                                <span>Edit</span>
                                                            </button>
                                                            <button wire:click="toggleActive({{ $bCol1->id }})" class="px-2 py-1 rounded-lg bg-rose-500 text-white text-xs font-medium hover:bg-rose-600 transition flex items-center gap-1" title="Remove">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                <span>Remove</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @if($navPlacement === 'header_col1')
                                                        <div class="mt-2 p-2 bg-indigo-100 dark:bg-indigo-900/60 rounded border border-indigo-300 dark:border-indigo-700 text-2xs font-bold text-indigo-900 dark:text-indigo-200 flex items-center gap-1.5">
                                                            <span class="px-1.5 py-0.5 rounded bg-indigo-600 text-white font-mono uppercase">Embedded Nav</span>
                                                            <span>Home, Shop, KB</span>
                                                        </div>
                                                    @endif
                                                    @if($featuresPlacement === 'header_col1')
                                                        <div class="mt-2 p-2 bg-blue-100 dark:bg-blue-900/60 rounded border border-blue-300 dark:border-blue-700 text-2xs font-bold text-blue-900 dark:text-blue-200 flex items-center gap-1.5">
                                                            <span class="px-1.5 py-0.5 rounded bg-blue-600 text-white font-mono uppercase">Cart &amp; Account</span>
                                                            <span>Cart Icon, User Profile</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            {{-- Header Col 2 --}}
                                            @php $bCol2 = $headerBlocks->firstWhere('target_element', 'header_col2'); @endphp
                                            @if($bCol2 && $bCol2->isActiveForDevice($deviceView))
                                                <div class="flex-1 min-w-[200px] rounded-lg border-2 border-dashed border-indigo-500 bg-indigo-50/50 dark:bg-indigo-900/20 p-3 transition-all duration-300">
                                                    <div class="flex items-center justify-between gap-2">
                                                        <div>
                                                            <span class="text-xs font-extrabold text-slate-800 dark:text-slate-100 block">{{ $bCol2->title }}</span>
                                                            <span class="text-2xs text-slate-400 block font-mono">header_col2</span>
                                                        </div>
                                                        <div class="flex items-center gap-1 shrink-0">
                                                            <button wire:click="editBlock({{ $bCol2->id }})" class="px-2 py-1 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700 transition flex items-center gap-1" title="Edit Block">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                                <span>Edit</span>
                                                            </button>
                                                            <button wire:click="toggleActive({{ $bCol2->id }})" class="px-2 py-1 rounded-lg bg-rose-500 text-white text-xs font-medium hover:bg-rose-600 transition flex items-center gap-1" title="Remove">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                <span>Remove</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @if($navPlacement === 'header_col2')
                                                        <div class="mt-2 p-2 bg-indigo-100 dark:bg-indigo-900/60 rounded border border-indigo-300 dark:border-indigo-700 text-2xs font-bold text-indigo-900 dark:text-indigo-200 flex items-center gap-1.5">
                                                            <span class="px-1.5 py-0.5 rounded bg-indigo-600 text-white font-mono uppercase">Embedded Nav</span>
                                                            <span>Home, Shop, KB</span>
                                                        </div>
                                                    @endif
                                                    @if($featuresPlacement === 'header_col2')
                                                        <div class="mt-2 p-2 bg-blue-100 dark:bg-blue-900/60 rounded border border-blue-300 dark:border-blue-700 text-2xs font-bold text-blue-900 dark:text-blue-200 flex items-center gap-1.5">
                                                            <span class="px-1.5 py-0.5 rounded bg-blue-600 text-white font-mono uppercase">Cart &amp; Account</span>
                                                            <span>Cart Icon, User Profile</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            {{-- Removable Cart / Account Features Bar --}}
                                            @php $bFeatures = $headerBlocks->firstWhere('target_element', 'header_features'); @endphp
                                            @if($featuresPlacement === 'main_header' && $bFeatures && $bFeatures->isActiveForDevice($deviceView))
                                                <div class="shrink-0 rounded-lg border-2 border-dashed border-indigo-500 bg-indigo-50/50 dark:bg-indigo-900/20 p-3 min-w-[150px]">
                                                    <div class="flex items-center justify-between gap-2">
                                                        <div>
                                                            <span class="text-xs font-extrabold text-slate-800 dark:text-slate-100 block">{{ $bFeatures->title }}</span>
                                                            <span class="text-2xs text-slate-400 block font-mono">header_features</span>
                                                        </div>
                                                        <div class="flex items-center gap-1 shrink-0">
                                                            <button wire:click="editBlock({{ $bFeatures->id }})" class="px-2 py-1 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700 transition flex items-center gap-1" title="Edit Features Block">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                                <span>Edit</span>
                                                            </button>
                                                            <button wire:click="toggleActive({{ $bFeatures->id }})" class="px-2 py-1 rounded-lg bg-rose-500 text-white text-xs font-medium hover:bg-rose-600 transition flex items-center gap-1" title="Remove Features Bar">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                <span>Remove</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Embedded Navigation Bar (Center Area) --}}
                                        @if($navPlacement === 'main_header')
                                            @php $bNav = $headerBlocks->firstWhere('target_element', 'top_nav_container'); @endphp
                                            <div class="w-full pt-2">
                                                <div class="flex items-center justify-between p-2.5 bg-indigo-50/80 dark:bg-indigo-950/40 rounded-lg border-2 border-dashed border-indigo-400">
                                                    <div class="flex items-center gap-3 text-xs font-semibold text-slate-700 dark:text-slate-200 overflow-x-auto">
                                                        <span class="px-2 py-0.5 rounded bg-indigo-600 text-white text-2xs font-extrabold uppercase">
                                                            {{ $activeNavMenu ? $activeNavMenu->name : 'EMBEDDED NAV' }}
                                                        </span>
                                                        @if(isset($activeNavItems) && count($activeNavItems) > 0)
                                                            @foreach($activeNavItems as $navItem)
                                                                <span class="inline-flex items-center gap-1 bg-white dark:bg-slate-800 px-2 py-0.5 rounded border border-slate-200 dark:border-slate-700">
                                                                    <span>{{ $navItem->label }}</span>
                                                                </span>
                                                            @endforeach
                                                        @else
                                                            <span>Home</span>
                                                            <span>Shop</span>
                                                            <span>Knowledge Base</span>
                                                        @endif
                                                    </div>
                                                    @if($bNav && $bNav->isActiveForDevice($deviceView))
                                                        <div class="flex items-center gap-1.5 shrink-0">
                                                            <button wire:click="editBlock({{ $bNav->id }})" class="px-2 py-1 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700 transition flex items-center gap-1">
                                                                <span>Edit</span>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                {{-- ROW CONTENT TYPE: Standalone Top Navigation Bar --}}
                                @elseif($rowBlock->target_element === 'top_nav_container' && $navPlacement === 'standalone')
                                    <div class="top-nav-wireframe-row rounded-b-xl border-2 border-dashed border-indigo-500 bg-slate-100 dark:bg-slate-800/80 p-3">
                                        <div class="flex items-center justify-between gap-3 p-2.5 bg-white dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600">
                                            <div class="flex items-center gap-3 text-xs font-semibold text-slate-700 dark:text-slate-200 overflow-x-auto">
                                                <span class="px-2 py-0.5 rounded bg-indigo-600 text-white text-2xs font-extrabold uppercase">
                                                    {{ $activeNavMenu ? $activeNavMenu->name : 'PRIMARY NAV' }}
                                                </span>
                                                @if(isset($activeNavItems) && count($activeNavItems) > 0)
                                                    @foreach($activeNavItems as $navItem)
                                                        <span class="inline-flex items-center gap-1 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded border border-slate-200 dark:border-slate-700">
                                                            <span>{{ $navItem->label }}</span>
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span>Home</span>
                                                    <span>Shop</span>
                                                    <span>Knowledge Base</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-1.5 shrink-0">
                                                <button wire:click="editBlock({{ $rowBlock->id }})" class="px-2 py-1 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700 transition flex items-center gap-1" title="Edit Nav Block">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    <span>Edit</span>
                                                </button>
                                                <button wire:click="toggleActive({{ $rowBlock->id }})" class="px-2 py-1 rounded-lg bg-rose-500 text-white text-xs font-medium hover:bg-rose-600 transition flex items-center gap-1" title="Remove Nav Bar">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    <span>Remove</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                {{-- Custom Row (header_row1, header_row2, custom) --}}
                                @else
                                    <div class="custom-wireframe-row rounded-b-xl border-2 border-dashed border-indigo-400 bg-indigo-50/50 dark:bg-indigo-900/30 p-3">
                                        <div class="flex items-center justify-between gap-3">
                                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ $rowBlock->title }} ({!! e($rowBlock->getContentForDevice($deviceView)) !!})</span>
                                            <div class="flex items-center gap-1.5 shrink-0">
                                                <button wire:click="editBlock({{ $rowBlock->id }})" class="px-2 py-1 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700 transition flex items-center gap-1">
                                                    <span>Edit</span>
                                                </button>
                                                <button wire:click="toggleActive({{ $rowBlock->id }})" class="px-2 py-1 rounded-lg bg-rose-500 text-white text-xs font-medium hover:bg-rose-600 transition flex items-center gap-1">
                                                    <span>Remove</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endforeach

                        {{-- REALTIME PLACEMENT CONTROLS CARD --}}
                        <div class="p-4 bg-indigo-50/80 dark:bg-indigo-950/40 rounded-xl border-2 border-dashed border-indigo-300 dark:border-indigo-700 space-y-4 shadow-sm">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-indigo-600/10 dark:bg-indigo-400/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </div>
                                <div>
                                    <span class="text-xs font-extrabold text-indigo-900 dark:text-indigo-200 block">Header Elements Placement &amp; Embedding Controls</span>
                                    <p class="text-2xs text-slate-500 dark:text-slate-400 mt-0.5">Embed the primary navigation menu or cart/account features inside Header Columns, main header center, or standalone rows.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="space-y-1">
                                    <label class="text-2xs font-extrabold uppercase tracking-wider text-slate-700 dark:text-slate-300 block">Navigation Bar Location</label>
                                    <select wire:change="setNavPlacement($event.target.value)" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500">
                                        <option value="standalone" {{ $navPlacement === 'standalone' ? 'selected' : '' }}>Standalone Row Below Header</option>
                                        <option value="main_header" {{ $navPlacement === 'main_header' ? 'selected' : '' }}>Embedded in Main Header Center</option>
                                        <option value="header_col1" {{ $navPlacement === 'header_col1' ? 'selected' : '' }}>Embedded in Header Column 1 (header_col1)</option>
                                        <option value="header_col2" {{ $navPlacement === 'header_col2' ? 'selected' : '' }}>Embedded in Header Column 2 (header_col2)</option>
                                    </select>
                                </div>

                                <div class="space-y-1">
                                    <label class="text-2xs font-extrabold uppercase tracking-wider text-slate-700 dark:text-slate-300 block">Cart &amp; Account Features Location</label>
                                    <select wire:change="setFeaturesPlacement($event.target.value)" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500">
                                        <option value="main_header" {{ $featuresPlacement === 'main_header' ? 'selected' : '' }}>Main Header Right Bar (Default)</option>
                                        <option value="header_col1" {{ $featuresPlacement === 'header_col1' ? 'selected' : '' }}>Embedded in Header Column 1 (header_col1)</option>
                                        <option value="header_col2" {{ $featuresPlacement === 'header_col2' ? 'selected' : '' }}>Embedded in Header Column 2 (header_col2)</option>
                                    </select>
                                </div>

                                <div class="space-y-1">
                                    <label class="text-2xs font-extrabold uppercase tracking-wider text-slate-700 dark:text-slate-300 block">Sticky Header Navigation</label>
                                    <label class="flex items-center gap-2 cursor-pointer pt-2">
                                        <input type="checkbox" wire:model.live="topNavSticky" wire:change="saveCssVars" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-xs font-bold text-indigo-900 dark:text-indigo-200">Sticky Nav (Fixed on Scroll)</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>

                @else
                    {{-- FOOTER GEOMETRIC WIREFRAME MOCKUP --}}
                    <div class="footer-wireframe-mockup rounded-2xl border-2 border-slate-300 dark:border-slate-700 bg-slate-900 text-white p-4 space-y-4 shadow-inner">
                        <div class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-400 mb-2 flex items-center justify-between">
                            <span>Main Footer Layout Grid (footer_container)</span>
                            <span class="text-2xs text-slate-400">4 Columns</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach(['footer_col1', 'footer_col2', 'footer_col3', 'footer_col4'] as $fCol)
                                @php $bFCol = $footerBlocks->firstWhere('target_element', $fCol); @endphp
                                <div class="rounded-xl border-2 border-dashed {{ $bFCol && $bFCol->isActiveForDevice($deviceView) ? 'border-indigo-500 bg-slate-800/80' : 'border-slate-700 bg-slate-800/30' }} p-3">
                                    @if($bFCol && $bFCol->isActiveForDevice($deviceView))
                                        <div class="flex items-center justify-between gap-2">
                                            <div>
                                                <span class="text-xs font-bold text-white block">{{ $bFCol->title }}</span>
                                                <span class="text-2xs font-mono text-slate-400 block">{{ $fCol }}</span>
                                            </div>
                                            <div class="flex items-center gap-1 shrink-0">
                                                <button wire:click="editBlock({{ $bFCol->id }})" class="px-2 py-1 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700 transition flex items-center gap-1" title="Edit Block">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    <span>Edit</span>
                                                </button>
                                                <button wire:click="toggleActive({{ $bFCol->id }})" class="px-2 py-1 rounded-lg bg-rose-500 text-white text-xs font-medium hover:bg-rose-600 transition flex items-center gap-1" title="Remove">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    <span>Remove</span>
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-xs text-slate-500 text-center py-4">{{ $fCol }} (Inactive)</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Bottom Copyright Bar --}}
                        @php $bCopy = $footerBlocks->firstWhere('target_element', 'copyright_container'); @endphp
                        <div class="rounded-xl border-2 border-dashed {{ $bCopy && $bCopy->isActiveForDevice($deviceView) ? 'border-indigo-500 bg-slate-800' : 'border-slate-700 bg-slate-800/30' }} p-3">
                            <div class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Copyright &amp; Bottom Links Bar (copyright_container)</div>
                            @if($bCopy && $bCopy->isActiveForDevice($deviceView))
                                <div class="flex items-center justify-between gap-3 p-2 bg-slate-900 rounded border border-slate-700">
                                    <span class="text-xs text-slate-300 font-semibold truncate">{{ $bCopy->title }}</span>
                                    <div class="flex items-center gap-1 shrink-0">
                                        <button wire:click="editBlock({{ $bCopy->id }})" class="px-2 py-1 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700 transition flex items-center gap-1" title="Edit Block">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>Edit</span>
                                        </button>
                                        <button wire:click="toggleActive({{ $bCopy->id }})" class="px-2 py-1 rounded-lg bg-rose-500 text-white text-xs font-medium hover:bg-rose-600 transition flex items-center gap-1" title="Remove">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>Remove</span>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="text-xs text-slate-500 text-center py-2">Copyright Bar (Inactive)</div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- 3. Inline TinyMCE Editor Section (Opens when Edit is clicked) --}}
            @if($editingBlockId)
                <div id="inline-tinymce-editor-section" class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border-2 border-indigo-500 p-6 space-y-6 transition-all">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-4">
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <span>Section Editor: {{ $editTitle }}</span>
                                <span class="text-2xs font-mono px-2 py-0.5 rounded bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300">Target: {{ $editTargetElement }}</span>
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Edit HTML content with TinyMCE (SuperCode, Tailwind CSS prose styling, image uploader, and drawers). Saves instantly to realtime preview.
                            </p>
                        </div>
                        <button wire:click="cancelEdit" class="text-slate-500 hover:text-slate-700 dark:hover:text-slate-200 text-xs font-semibold px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-700 transition flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span>Close Editor</span>
                        </button>
                    </div>

                    {{-- Title and Device Selection --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Block Title / Label</label>
                            <input type="text" wire:model="editTitle" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white rounded-xl text-xs font-semibold focus:outline-none focus:border-indigo-500" />
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Layout Target Slot</label>
                            <input type="text" wire:model="editTargetElement" class="w-full px-4 py-2 bg-slate-100 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 rounded-xl text-xs font-mono" readonly />
                        </div>
                    </div>

                    @if($editTargetElement === 'header_logo')
                        <div class="p-6 bg-amber-50 dark:bg-amber-950/40 border-2 border-amber-300 dark:border-amber-700 rounded-2xl flex items-start gap-4 text-amber-900 dark:text-amber-200">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div class="space-y-2 flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-amber-900 dark:text-amber-100">Site Logo Content Is Managed Globally</h4>
                                    <span class="px-2.5 py-0.5 rounded-full text-2xs font-extrabold bg-amber-200 dark:bg-amber-800 text-amber-900 dark:text-amber-100 font-mono">NON-EDITABLE LOGO BLOCK</span>
                                </div>
                                <p class="text-xs leading-relaxed text-amber-800 dark:text-amber-300 font-medium">
                                    The site logo image, text branding, and SVG graphics are configured globally under <strong>CMS Admin Settings &rarr; Logo &amp; Branding</strong>. Manual HTML editing for this logo block is locked to guarantee layout integrity. You can toggle logo visibility on desktop, tablet, and mobile using the device toggles in the wireframe editor.
                                </p>
                                <div class="pt-2">
                                    <a href="{{ route('admin.settings') }}" wire:navigate class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span>Open Logo &amp; Branding in Admin Settings &rarr;</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @elseif($editTargetElement === 'top_nav_container')
                        <div class="p-6 bg-indigo-50 dark:bg-indigo-950/40 border-2 border-indigo-300 dark:border-indigo-700 rounded-2xl flex items-start gap-4 text-indigo-900 dark:text-indigo-200">
                            <div class="w-10 h-10 rounded-xl bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </div>
                            <div class="space-y-2 flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-indigo-900 dark:text-indigo-100">Navigation Bar Managed With Navigation Builder</h4>
                                    <span class="px-2.5 py-0.5 rounded-full text-2xs font-extrabold bg-indigo-200 dark:bg-indigo-800 text-indigo-900 dark:text-indigo-100 font-mono">MANAGED VIA NAVIGATION BUILDER</span>
                                </div>
                                <p class="text-xs leading-relaxed text-indigo-800 dark:text-indigo-300 font-medium">
                                    The navigation bar links, dropdowns, categories, brands, and menu structure are managed with the <strong>Navigation Builder</strong>. Manual HTML editing for this navigation block is locked to guarantee menu functionality and responsive layout integrity. You can toggle navigation bar visibility on desktop, tablet, and mobile using the device toggles in the wireframe editor.
                                </p>
                                <div class="pt-2">
                                    <a href="{{ route('admin.nav-builder.index') }}" wire:navigate class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                        <span>Open Navigation Builder in Admin &rarr;</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @elseif($editTargetElement === 'header_features')
                        <div class="p-6 bg-blue-50 dark:bg-blue-950/40 border-2 border-blue-300 dark:border-blue-700 rounded-2xl flex items-start gap-4 text-blue-900 dark:text-blue-200">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <div class="space-y-2 flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-blue-900 dark:text-blue-100">Cart &amp; Account Features Managed Systemically</h4>
                                    <span class="px-2.5 py-0.5 rounded-full text-2xs font-extrabold bg-blue-200 dark:bg-blue-800 text-blue-900 dark:text-blue-100 font-mono">DYNAMIC FEATURES BLOCK</span>
                                </div>
                                <p class="text-xs leading-relaxed text-blue-800 dark:text-blue-300 font-medium">
                                    The shopping cart badge, account menu, and sign-in features are dynamic interactive elements managed automatically by the e-commerce engine. Manual HTML editing for this features block is locked to ensure cart count updates and authentication state function correctly. You can toggle feature bar visibility on desktop, tablet, and mobile using the device toggles in the wireframe editor.
                                </p>
                            </div>
                        </div>
                    @elseif($editTargetElement === 'header_search')
                        <div class="p-6 bg-indigo-50 dark:bg-indigo-950/40 border-2 border-indigo-300 dark:border-indigo-700 rounded-2xl space-y-4 text-indigo-900 dark:text-indigo-200">
                            <div class="flex items-center justify-between">
                                <h4 class="text-xs font-extrabold uppercase tracking-wider text-indigo-900 dark:text-indigo-100 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    Search Bar Viewport Placement &amp; Position Configuration
                                </h4>
                                <span class="px-2.5 py-0.5 rounded-full text-2xs font-extrabold bg-indigo-200 dark:bg-indigo-800 text-indigo-900 dark:text-indigo-100 font-mono">SEARCH BAR PLACEMENT</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                                {{-- Desktop Placement --}}
                                <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-indigo-200 dark:border-indigo-800">
                                    <label class="block text-xs font-extrabold text-slate-800 dark:text-slate-100 mb-1">Desktop View Placement</label>
                                    <p class="text-2xs text-slate-500 dark:text-slate-400 mb-2">Choose where the search bar embeds in desktop view.</p>
                                    <select wire:model.live="cssVars.search_placement_desktop" wire:change="saveCssVars" class="w-full text-xs font-bold bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2 text-slate-800 dark:text-white">
                                        <option value="main_header">Main Header Bar (Center Slot)</option>
                                        <option value="header_col1">Header Column #1 (Left Slot)</option>
                                        <option value="header_col2">Header Column #2 (Right Slot)</option>
                                        <option value="top_sharing_container">Top Sharing Bar</option>
                                        <option value="disabled">Hidden / Disabled (Off)</option>
                                    </select>
                                </div>

                                {{-- Tablet Placement --}}
                                <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-indigo-200 dark:border-indigo-800">
                                    <label class="block text-xs font-extrabold text-slate-800 dark:text-slate-100 mb-1">Tablet View Placement</label>
                                    <p class="text-2xs text-slate-500 dark:text-slate-400 mb-2">Choose where the search bar embeds in tablet view.</p>
                                    <select wire:model.live="cssVars.search_placement_tablet" wire:change="saveCssVars" class="w-full text-xs font-bold bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2 text-slate-800 dark:text-white">
                                        <option value="main_header">Main Header Bar (Center Slot)</option>
                                        <option value="header_col1">Header Column #1 (Left Slot)</option>
                                        <option value="header_col2">Header Column #2 (Right Slot)</option>
                                        <option value="top_sharing_container">Top Sharing Bar</option>
                                        <option value="disabled">Hidden / Disabled (Off)</option>
                                    </select>
                                </div>

                                {{-- Mobile Position --}}
                                <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-indigo-200 dark:border-indigo-800">
                                    <label class="block text-xs font-extrabold text-slate-800 dark:text-slate-100 mb-1">Mobile Menu Position</label>
                                    <p class="text-2xs text-slate-500 dark:text-slate-400 mb-2">Select top, bottom, or disabled in mobile drawer.</p>
                                    <select wire:model.live="cssVars.mobile_search_position" wire:change="saveCssVars" class="w-full text-xs font-bold bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg p-2 text-slate-800 dark:text-white">
                                        <option value="top">Top of Mobile Menu</option>
                                        <option value="bottom">Bottom of Mobile Menu</option>
                                        <option value="disabled">Hidden / Disabled (Off)</option>
                                    </select>
                                </div>
                            </div>

                            <p class="text-xs text-indigo-700 dark:text-indigo-300 font-medium pt-1">
                                Shortcode Default Content: <code class="font-mono bg-indigo-100 dark:bg-indigo-900 px-2 py-0.5 rounded text-indigo-800 dark:text-indigo-200">[plugin:live-search-2026]</code>
                            </p>
                        </div>
                    @else
                        {{-- TinyMCE Editor Container --}}
                        <div wire:ignore x-data="{
                            initTiny() {
                                if (typeof tinymce === 'undefined') return;
                                tinymce.remove('#block_content_editor');
                                tinymce.init({
                                    selector: '#block_content_editor',
                                    license_key: 'gpl',
                                    promotion: false,
                                    height: 420,
                                    forced_root_block: false,
                                    force_p_newlines: false,
                                    force_br_newlines: true,
                                    menubar: 'insert format tools table',
                                    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px; padding: 1rem; } :root { --theme-primary: #4f46e5; }',
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
                                    setup(editor) {
                                        editor.on('init', function () {
                                            editor.setContent($wire.editContentDesktop || '');
                                        });
                                        editor.on('change input undo redo', function () {
                                            var val = editor.getContent();
                                            $wire.set('editContentDesktop', val);
                                            $wire.set('editContentTablet', val);
                                            $wire.set('editContentMobile', val);
                                            var iframe = document.getElementById('headerFooterPreviewIframe');
                                            if (iframe && iframe.contentWindow) {
                                                iframe.contentWindow.location.reload();
                                            }
                                        });
                                    }
                                });
                            },
                            destroy() {
                                if (typeof tinymce !== 'undefined') {
                                    tinymce.remove('#block_content_editor');
                                }
                            }
                        }" x-init="initTiny()" x-destroy="destroy()">
                            <textarea id="block_content_editor" class="w-full h-full min-h-[350px]"></textarea>
                        </div>
                    @endif

                    {{-- Save Controls --}}
                    <div class="flex items-center justify-between gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                        {{-- Translate action (left side) --}}
                        <button wire:click="translateBlock({{ $editingBlockId }})"
                                wire:loading.attr="disabled"
                                wire:target="translateBlock"
                                title="Auto-translate this block into all active languages using OpenAI"
                                class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold rounded-xl border border-violet-200 dark:border-violet-700 text-violet-700 dark:text-violet-300 bg-violet-50 dark:bg-violet-900/20 hover:bg-violet-100 dark:hover:bg-violet-900/40 transition disabled:opacity-50">
                            <svg wire:loading.remove wire:target="translateBlock" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                            <svg wire:loading wire:target="translateBlock" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span wire:loading.remove wire:target="translateBlock">Translate All Languages</span>
                            <span wire:loading wire:target="translateBlock">Queueing…</span>
                        </button>

                        {{-- Cancel / Save (right side) --}}
                        <div class="flex items-center gap-3">
                            <button wire:click="cancelEdit" class="px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-slate-900 transition">
                                Cancel
                            </button>
                            <button wire:click="saveBlock" class="px-5 py-2.5 text-xs font-bold bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 shadow-md transition flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                <span>Save Section Changes</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- 4. Realtime Iframe Viewport Preview Generator --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-3">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            <span>Dynamic Viewport Iframe Preview Generator</span>
                            <span class="text-2xs font-mono px-2 py-0.5 rounded bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300">{{ ucfirst($deviceView) }} ({{ $deviceView === 'mobile' ? '375px' : ($deviceView === 'tablet' ? '768px' : '100%') }})</span>
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Isolated document context executing true responsive @media queries, hamburger menus, and collapsible drawers.
                        </p>
                    </div>
                    <button onclick="document.getElementById('headerFooterPreviewIframe').contentWindow.location.reload()" class="px-3 py-1.5 text-xs font-semibold bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg hover:bg-slate-200 transition flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Refresh Frame</span>
                    </button>
                </div>

                {{-- Responsive Width Bounded Iframe Frame Container --}}
                <div class="flex justify-center bg-slate-100 dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-700">
                    <div class="transition-all duration-300 w-full overflow-hidden rounded-xl border border-slate-300 dark:border-slate-700 bg-white shadow-xl" style="max-width: {{ $deviceView === 'mobile' ? '375px' : ($deviceView === 'tablet' ? '768px' : '100%') }}; height: 420px;">
                        <iframe id="headerFooterPreviewIframe" src="{{ route('admin.cms-header-footer.preview', ['device' => $deviceView, 'tab' => $activeTab]) }}" class="w-full h-full border-0"></iframe>
                    </div>
                </div>
            </div>

        </div>
    @endif

    {{-- SECTION TAB #3: CSS MANAGER & BACKGROUND IMAGE SETTINGS --}}
    @if($activeTab === 'css_manager')
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 space-y-8">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                        <span>Header &amp; Footer CSS Variables &amp; Background Image Manager</span>
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Configure root design tokens, header/footer background images, menu hover colors, dropdown styles, and custom CSS overrides.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <button wire:click="resetCssVars" onclick="confirm('Reset all CSS settings to default values?') || event.stopImmediatePropagation()" class="px-3.5 py-2 text-xs font-semibold bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl hover:bg-slate-200 transition flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Reset Defaults</span>
                    </button>
                    <button wire:click="saveCssVars" class="px-4 py-2 text-xs font-bold bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 shadow-md transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        <span>Save CSS Variables</span>
                    </button>
                </div>
            </div>

            {{-- Sticky Header Navigation Toggle --}}
            <div class="p-4 bg-indigo-50/60 dark:bg-indigo-950/40 rounded-2xl border border-indigo-100 dark:border-indigo-800 flex items-center justify-between gap-4">
                <div>
                    <h4 class="text-xs font-bold text-indigo-900 dark:text-indigo-200 uppercase tracking-wider">Sticky Header Navigation</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">When enabled, the primary site header and top navigation bar remain fixed at the top of the browser window as visitors scroll down.</p>
                </div>
                <label class="flex items-center gap-2 cursor-pointer shrink-0">
                    <input type="checkbox" wire:model.live="topNavSticky" wire:change="saveCssVars" class="w-5 h-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-xs font-bold {{ $topNavSticky ? 'text-indigo-700 dark:text-indigo-300' : 'text-slate-400' }}">
                        {{ $topNavSticky ? 'Sticky Header Active' : 'Sticky Header Off' }}
                    </span>
                </label>
            </div>

            {{-- 1. Header & Footer Background Images (CDN URL or File Upload) --}}
            <div class="space-y-4 border-b border-slate-100 dark:border-slate-700 pb-6">
                <h4 class="text-sm font-extrabold text-slate-800 dark:text-white uppercase tracking-wider text-indigo-600 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Header &amp; Footer Background Images (Upload or CDN URL)</span>
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Header Background Image --}}
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700 space-y-3">
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">Header Background Image</span>
                        <div>
                            <label class="text-2xs font-bold text-slate-400 block mb-1">CDN Image URL</label>
                            <input type="text" wire:model="cssVars.header_bg_image_url" class="w-full px-3 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs" placeholder="https://cdn.example.com/header-bg.jpg" />
                        </div>
                        <div>
                            <label class="text-2xs font-bold text-slate-400 block mb-1">Or File Upload</label>
                            <input type="file" wire:model="headerBgFile" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700" />
                        </div>
                        <div class="grid grid-cols-3 gap-2 pt-2">
                            <div>
                                <label class="text-2xs font-bold text-slate-400 block mb-1">Repeat</label>
                                <select wire:model="cssVars.header_bg_repeat" class="w-full px-2 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded text-2xs">
                                    <option value="no-repeat">No Repeat</option>
                                    <option value="repeat">Repeat</option>
                                    <option value="repeat-x">Repeat X</option>
                                    <option value="repeat-y">Repeat Y</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-2xs font-bold text-slate-400 block mb-1">Size</label>
                                <select wire:model="cssVars.header_bg_size" class="w-full px-2 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded text-2xs">
                                    <option value="cover">Cover</option>
                                    <option value="contain">Contain</option>
                                    <option value="auto">Auto</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-2xs font-bold text-slate-400 block mb-1">Position</label>
                                <input type="text" wire:model="cssVars.header_bg_position" class="w-full px-2 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded text-2xs" placeholder="center center" />
                            </div>
                        </div>
                    </div>

                    {{-- Footer Background Image --}}
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700 space-y-3">
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">Footer Background Image</span>
                        <div>
                            <label class="text-2xs font-bold text-slate-400 block mb-1">CDN Image URL</label>
                            <input type="text" wire:model="cssVars.footer_bg_image_url" class="w-full px-3 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs" placeholder="https://cdn.example.com/footer-bg.jpg" />
                        </div>
                        <div>
                            <label class="text-2xs font-bold text-slate-400 block mb-1">Or File Upload</label>
                            <input type="file" wire:model="footerBgFile" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700" />
                        </div>
                        <div class="grid grid-cols-3 gap-2 pt-2">
                            <div>
                                <label class="text-2xs font-bold text-slate-400 block mb-1">Repeat</label>
                                <select wire:model="cssVars.footer_bg_repeat" class="w-full px-2 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded text-2xs">
                                    <option value="no-repeat">No Repeat</option>
                                    <option value="repeat">Repeat</option>
                                    <option value="repeat-x">Repeat X</option>
                                    <option value="repeat-y">Repeat Y</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-2xs font-bold text-slate-400 block mb-1">Size</label>
                                <select wire:model="cssVars.footer_bg_size" class="w-full px-2 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded text-2xs">
                                    <option value="cover">Cover</option>
                                    <option value="contain">Contain</option>
                                    <option value="auto">Auto</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-2xs font-bold text-slate-400 block mb-1">Position</label>
                                <input type="text" wire:model="cssVars.footer_bg_position" class="w-full px-2 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded text-2xs" placeholder="center center" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Color Palettes Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Accent & Header Colors --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Accent &amp; Header Colors</h4>
                    
                    <div>
                        <label class="text-2xs font-bold text-slate-500 block mb-1">Primary Accent Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" wire:model="cssVars.primary_accent_color" class="h-8 w-10 rounded cursor-pointer border-0" />
                            <input type="text" wire:model="cssVars.primary_accent_color" class="w-full px-3 py-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs rounded" />
                        </div>
                    </div>

                    <div>
                        <label class="text-2xs font-bold text-slate-500 block mb-1">Secondary Accent Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" wire:model="cssVars.secondary_accent_color" class="h-8 w-10 rounded cursor-pointer border-0" />
                            <input type="text" wire:model="cssVars.secondary_accent_color" class="w-full px-3 py-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs rounded" />
                        </div>
                    </div>

                    <div>
                        <label class="text-2xs font-bold text-slate-500 block mb-1">Header Background Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" wire:model="cssVars.header_background_color" class="h-8 w-10 rounded cursor-pointer border-0" />
                            <input type="text" wire:model="cssVars.header_background_color" class="w-full px-3 py-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs rounded" />
                        </div>
                    </div>

                    <div>
                        <label class="text-2xs font-bold text-slate-500 block mb-1">Top Nav Container Background</label>
                        <div class="flex items-center gap-2">
                            <input type="color" wire:model="cssVars.top_nav_container_background" class="h-8 w-10 rounded cursor-pointer border-0" />
                            <input type="text" wire:model="cssVars.top_nav_container_background" class="w-full px-3 py-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs rounded" />
                        </div>
                    </div>

                    <div>
                        <label class="text-2xs font-bold text-slate-500 block mb-1">Site Max Width (site_max_width)</label>
                        <div class="flex items-center gap-2">
                            <input type="text" wire:model="cssVars.site_max_width" class="w-full px-3 py-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs rounded font-mono" placeholder="1400px or 100%" />
                        </div>
                        <span class="text-[10px] text-slate-400 block mt-0.5">Global maximum container layout width for header, content, and footer. Default: 1400px.</span>
                    </div>

                    <div>
                        <label class="text-2xs font-bold text-slate-500 block mb-1">Header Min Height (Desktop Only)</label>
                        <div class="flex items-center gap-2">
                            <input type="text" wire:model="cssVars.header_min_height" class="w-full px-3 py-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs rounded font-mono" placeholder="201px or auto" />
                        </div>
                        <span class="text-[10px] text-slate-400 block mt-0.5">Applies strictly to desktop mode (&ge; 1024px). Mobile/tablet resets to auto.</span>
                    </div>

                    <div>
                        <label class="text-2xs font-bold text-slate-500 block mb-1">Header Padding Top</label>
                        <div class="flex items-center gap-2">
                            <input type="text" wire:model="cssVars.header_padding_top" class="w-full px-3 py-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs rounded font-mono" placeholder="5px" />
                        </div>
                        <span class="text-[10px] text-slate-400 block mt-0.5">Top padding for main site header area. Default: 5px.</span>
                    </div>

                    <div>
                        <label class="text-2xs font-bold text-slate-500 block mb-1">Header Padding Bottom</label>
                        <div class="flex items-center gap-2">
                            <input type="text" wire:model="cssVars.header_padding_bottom" class="w-full px-3 py-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs rounded font-mono" placeholder="5px" />
                        </div>
                        <span class="text-[10px] text-slate-400 block mt-0.5">Bottom padding for main site header area. Default: 5px.</span>
                    </div>
                </div>

                {{-- Top Navigation & Hover Colors --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Top Nav Hover &amp; Dropdown</h4>

                    <div>
                        <label class="text-2xs font-bold text-slate-500 block mb-1">Menu Link Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" wire:model="cssVars.top_nav_menu_desktop_font_color" class="h-8 w-10 rounded cursor-pointer border-0" />
                            <input type="text" wire:model="cssVars.top_nav_menu_desktop_font_color" class="w-full px-3 py-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs rounded" />
                        </div>
                    </div>

                    <div>
                        <label class="text-2xs font-bold text-slate-500 block mb-1">Menu Link Hover Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" wire:model="cssVars.top_nav_menu_desktop_tab_hover_label_color" class="h-8 w-10 rounded cursor-pointer border-0" />
                            <input type="text" wire:model="cssVars.top_nav_menu_desktop_tab_hover_label_color" class="w-full px-3 py-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs rounded" />
                        </div>
                    </div>

                    <div>
                        <label class="text-2xs font-bold text-slate-500 block mb-1">Dropdown Background Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" wire:model="cssVars.top_nav_menu_desktop_drop_down_background_color" class="h-8 w-10 rounded cursor-pointer border-0" />
                            <input type="text" wire:model="cssVars.top_nav_menu_desktop_drop_down_background_color" class="w-full px-3 py-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs rounded" />
                        </div>
                    </div>

                    <div>
                        <label class="text-2xs font-bold text-slate-500 block mb-1">Dropdown Link Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" wire:model="cssVars.top_nav_menu_desktop_drop_down_list_item_label_color" class="h-8 w-10 rounded cursor-pointer border-0" />
                            <input type="text" wire:model="cssVars.top_nav_menu_desktop_drop_down_list_item_label_color" class="w-full px-3 py-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs rounded" />
                        </div>
                    </div>

                    <div>
                        <label class="text-2xs font-bold text-slate-500 block mb-1">Dropdown Link Hover Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" wire:model="cssVars.top_nav_menu_desktop_drop_down_list_item_hover_label_color" class="h-8 w-10 rounded cursor-pointer border-0" />
                            <input type="text" wire:model="cssVars.top_nav_menu_desktop_drop_down_list_item_hover_label_color" class="w-full px-3 py-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs rounded" />
                        </div>
                    </div>
                </div>

                {{-- Footer Typography & Colors --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Footer Typography &amp; Colors</h4>

                    <div>
                        <label class="text-2xs font-bold text-slate-500 block mb-1">Footer Background Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" wire:model="cssVars.footer_background_color" class="h-8 w-10 rounded cursor-pointer border-0" />
                            <input type="text" wire:model="cssVars.footer_background_color" class="w-full px-3 py-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs rounded" />
                        </div>
                    </div>

                    <div>
                        <label class="text-2xs font-bold text-slate-500 block mb-1">Footer Header Title Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" wire:model="cssVars.footer_header_title_color" class="h-8 w-10 rounded cursor-pointer border-0" />
                            <input type="text" wire:model="cssVars.footer_header_title_color" class="w-full px-3 py-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs rounded" />
                        </div>
                    </div>

                    <div>
                        <label class="text-2xs font-bold text-slate-500 block mb-1">Footer Link Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" wire:model="cssVars.footer_link_color" class="h-8 w-10 rounded cursor-pointer border-0" />
                            <input type="text" wire:model="cssVars.footer_link_color" class="w-full px-3 py-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs rounded" />
                        </div>
                    </div>

                    <div>
                        <label class="text-2xs font-bold text-slate-500 block mb-1">Footer Link Hover Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" wire:model="cssVars.footer_link_hover_color" class="h-8 w-10 rounded cursor-pointer border-0" />
                            <input type="text" wire:model="cssVars.footer_link_hover_color" class="w-full px-3 py-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs rounded" />
                        </div>
                    </div>

                    <div>
                        <label class="text-2xs font-bold text-slate-500 block mb-1">Footer General Text Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" wire:model="cssVars.footer_text_color" class="h-8 w-10 rounded cursor-pointer border-0" />
                            <input type="text" wire:model="cssVars.footer_text_color" class="w-full px-3 py-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs rounded" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <div>
                            <label class="text-2xs font-bold text-slate-500 block mb-1">General Font Size</label>
                            <input type="text" wire:model="cssVars.footer_font_size" class="w-full px-2 py-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs rounded" placeholder="0.9rem" />
                        </div>
                        <div>
                            <label class="text-2xs font-bold text-slate-500 block mb-1">Heading Font Size</label>
                            <input type="text" wire:model="cssVars.footer_heading_font_size" class="w-full px-2 py-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs rounded" placeholder="1.2rem" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Custom Icon SVG Overrides --}}
            <div class="space-y-4 border-b border-slate-100 dark:border-slate-700 pb-6">
                <h4 class="text-sm font-extrabold text-slate-800 dark:text-white uppercase tracking-wider text-indigo-600 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/></svg>
                    <span>Cart &amp; Account Icon Overrides</span>
                </h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 -mt-2">Paste custom SVG code to replace the default cart or account icons in the header. Leave empty to use the built-in defaults. Do not include the outer <code class="bg-slate-100 dark:bg-slate-700 px-1 rounded">&lt;button&gt;</code> or <code class="bg-slate-100 dark:bg-slate-700 px-1 rounded">&lt;a&gt;</code> wrapper — just the <code class="bg-slate-100 dark:bg-slate-700 px-1 rounded">&lt;svg&gt;...&lt;/svg&gt;</code> element itself.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Shopping Cart Icon --}}
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700 space-y-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">Shopping Cart Icon</span>
                        </div>
                        <p class="text-2xs text-slate-400">Default: shopping bag outline SVG. Paste a replacement <code>&lt;svg&gt;</code> here.</p>
                        <textarea wire:model="cssVars.custom_cart_icon_svg"
                                  rows="5"
                                  class="w-full font-mono text-xs p-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 resize-y"
                                  placeholder="<svg class=&quot;w-6 h-6&quot; fill=&quot;none&quot; stroke=&quot;currentColor&quot; viewBox=&quot;0 0 24 24&quot;>...</svg>"></textarea>
                        @if(!empty($cssVars['custom_cart_icon_svg']))
                            <div class="flex items-center gap-2 pt-1">
                                <span class="text-2xs font-bold text-slate-400 uppercase tracking-wider">Preview:</span>
                                <span class="text-slate-700 dark:text-slate-200">{!! $cssVars['custom_cart_icon_svg'] !!}</span>
                            </div>
                        @endif
                    </div>

                    {{-- My Account Icon --}}
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700 space-y-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">My Account Icon</span>
                        </div>
                        <p class="text-2xs text-slate-400">Default: person/user circle outline SVG. Paste a replacement <code>&lt;svg&gt;</code> here.</p>
                        <textarea wire:model="cssVars.custom_account_icon_svg"
                                  rows="5"
                                  class="w-full font-mono text-xs p-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 resize-y"
                                  placeholder="<svg class=&quot;w-6 h-6&quot; fill=&quot;none&quot; stroke=&quot;currentColor&quot; viewBox=&quot;0 0 24 24&quot;>...</svg>"></textarea>
                        @if(!empty($cssVars['custom_account_icon_svg']))
                            <div class="flex items-center gap-2 pt-1">
                                <span class="text-2xs font-bold text-slate-400 uppercase tracking-wider">Preview:</span>
                                <span class="text-slate-700 dark:text-slate-200">{!! $cssVars['custom_account_icon_svg'] !!}</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="flex justify-end">
                    <button wire:click="saveCssVars" class="px-4 py-2 text-xs font-bold bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 shadow-md transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Save Icon Settings
                    </button>
                </div>
            </div>

            {{-- Custom CSS Overrides Textarea --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-slate-100 dark:border-slate-700 pt-6">
                <div>
                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Custom Header CSS Overrides</label>
                    <textarea wire:model="cssVars.header_custom_css" rows="5" class="w-full font-mono text-xs p-3 bg-slate-900 text-slate-100 rounded-xl focus:outline-none" placeholder=".header_container { /* custom css */ }"></textarea>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Custom Footer CSS Overrides</label>
                    <textarea wire:model="cssVars.footer_custom_css" rows="5" class="w-full font-mono text-xs p-3 bg-slate-900 text-slate-100 rounded-xl focus:outline-none" placeholder=".footer_container { /* custom css */ }"></textarea>
                </div>
            </div>
        </div>
    @endif

    {{-- SECTION TAB #4: FULL PAGE SANDBOX PREVIEW --}}
    @if($activeTab === 'full_preview')
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-3">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">🌐 Full Page Storefront Preview Sandbox</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Simulates complete storefront catalog pages, combining header, product grid, and footer into one interactive view.
                    </p>
                </div>
                <button onclick="document.getElementById('fullSandboxPreviewIframe').contentWindow.location.reload()" class="px-3 py-1.5 text-xs font-bold bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    🔄 Refresh Full Sandbox
                </button>
            </div>

            <div class="flex justify-center bg-slate-100 dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-700">
                <div class="transition-all duration-300 w-full overflow-hidden rounded-xl border border-slate-300 dark:border-slate-700 bg-white shadow-xl" style="max-width: {{ $deviceView === 'mobile' ? '375px' : ($deviceView === 'tablet' ? '768px' : '100%') }}; height: 600px;">
                    <iframe id="fullSandboxPreviewIframe" src="{{ route('admin.cms-header-footer.preview', ['device' => $deviceView, 'tab' => 'full_preview']) }}" class="w-full h-full border-0"></iframe>
                </div>
            </div>
        </div>
    @endif

    {{-- CREATE CUSTOM BLOCK MODAL --}}
    @if($isCreating)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 max-w-lg w-full p-6 space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-3">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Create Custom Layout Block</h3>
                    <button wire:click="closeAddModal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">✕</button>
                </div>

                <div class="space-y-4 text-xs">
                    <div>
                        <label class="font-bold text-slate-500 block mb-1 uppercase">Block Title</label>
                        <input type="text" wire:model="newTitle" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white" placeholder="e.g., Announcement Bar" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="font-bold text-slate-500 block mb-1 uppercase">Type</label>
                            <select wire:model="newType" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white">
                                <option value="header">Header Block</option>
                                <option value="footer">Footer Block</option>
                            </select>
                        </div>
                        <div>
                            <label class="font-bold text-slate-500 block mb-1 uppercase">Target Element Key</label>
                            <input type="text" wire:model="newTargetElement" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-white" placeholder="custom_banner_1" />
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-700">
                    <button wire:click="closeAddModal" class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-700">Cancel</button>
                    <button wire:click="createBlock" class="px-4 py-2 text-xs font-bold bg-indigo-600 text-white rounded-xl hover:bg-indigo-700">Create Block</button>
                </div>
            </div>
        </div>
    @endif

    {{-- UNIFIED FLOATING SIDEBAR TAB CONTAINER (MATCHING CMS PAGE & PRODUCT EDITORS) --}}
    <div class="fixed right-0 top-1/2 -translate-y-1/2 z-40 flex flex-col gap-3.5 items-end">
        {{-- Widgets Drawer Button --}}
        <button type="button" 
                x-on:click.stop="showWidgetLibrary = !showWidgetLibrary; showPluginsPanel = false; showShortcodeGenerator = false; showLinkGenerator = false" 
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-2 py-3.5 rounded-l-2xl shadow-xl hover:shadow-2xl transition-all flex flex-col items-center gap-2 border-l border-y border-indigo-500/30 group w-[36px]"
                title="Toggle Widgets Panel">
            <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            <span class="text-[9px] font-extrabold uppercase tracking-widest [writing-mode:vertical-lr] group-hover:scale-105 transition-transform duration-200">Widgets</span>
        </button>

        {{-- Plugins Drawer Button --}}
        <button type="button" 
                x-on:click.stop="showPluginsPanel = !showPluginsPanel; showWidgetLibrary = false; showShortcodeGenerator = false; showLinkGenerator = false" 
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-2 py-3.5 rounded-l-2xl shadow-xl hover:shadow-2xl transition-all flex flex-col items-center gap-2 border-l border-y border-emerald-500/30 group w-[36px]"
                title="Toggle Plugins Panel">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"/>
            </svg>
            <span class="text-[9px] font-extrabold uppercase tracking-widest [writing-mode:vertical-lr] group-hover:scale-105 transition-transform duration-200">Plugins</span>
        </button>

        {{-- Shortcodes Drawer Button --}}
        <button type="button" 
                x-on:click.stop="showShortcodeGenerator = !showShortcodeGenerator; showWidgetLibrary = false; showPluginsPanel = false; showLinkGenerator = false" 
                class="bg-blue-900 hover:bg-blue-950 text-white px-2 py-3.5 rounded-l-2xl shadow-xl hover:shadow-2xl transition-all flex flex-col items-center gap-2 border-l border-y border-blue-800/30 group w-[36px]"
                title="Toggle Shortcode Generator">
            <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
            </svg>
            <span class="text-[9px] font-extrabold uppercase tracking-widest [writing-mode:vertical-lr] group-hover:scale-105 transition-transform duration-200">Shortcodes</span>
        </button>

        {{-- Link Generator Drawer Button --}}
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

    {{-- SLIDEOUT PANEL DRAWER INCLUDES --}}
    @include('partials.html-widgets-drawer')
    @include('partials.display-plugins-drawer')
    @include('partials.shortcodes-generator-drawer')
    @include('partials.link-generator-drawer')

    <script>
        window.insertHtmlWidget = function(content) {
            if (typeof tinymce !== 'undefined') {
                var editor = tinymce.get('block_content_editor') || tinymce.get('cms_page_right_col_editor') || tinymce.activeEditor;
                if (editor && !editor.isDestroyed) {
                    editor.insertContent(content);
                    editor.fire('change');
                }
            }
        };
        window.insertPluginShortcode = function(shortcode) {
            window.insertHtmlWidget(shortcode);
        };
    </script>
</div>
