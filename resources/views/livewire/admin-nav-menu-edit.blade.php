<div class="py-8 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto"
     x-data="{ htmlEditorReady: false }">

    {{-- TinyMCE --}}
    <script src="{{ asset('build/node_modules/tinymce/tinymce.min.js') }}"></script>
    {{-- SortableJS --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.nav-builder.index') }}" wire:navigate
           class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-500 hover:text-slate-700 dark:hover:text-slate-200 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-slate-100">{{ $menu->name }}</h1>
            <p class="text-xs text-slate-400 dark:text-slate-500">slug: <code class="font-mono">{{ $menu->slug }}</code></p>
        </div>
        @if($menu->is_primary)
            <span class="ml-2 px-2 py-0.5 text-xs font-bold bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 rounded-full">PRIMARY</span>
        @endif
    </div>

    {{-- Flash messages --}}
    @if($successMessage)
        <div wire:key="success" class="mb-4 p-3 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 rounded-xl text-sm">
            {{ $successMessage }}
        </div>
    @endif
    @if($errorMessage)
        <div wire:key="error" class="mb-4 p-3 bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 rounded-xl text-sm">
            {{ $errorMessage }}
        </div>
    @endif

    {{-- Tabs --}}
    <div class="flex gap-1 mb-6 border-b border-slate-200 dark:border-slate-700">
        <button wire:click="$set('activeTab','items')"
                class="px-4 py-2.5 text-sm font-semibold border-b-2 transition-colors -mb-px {{ $activeTab === 'items' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
            Menu Items
        </button>
        <button wire:click="$set('activeTab','appearance')"
                class="px-4 py-2.5 text-sm font-semibold border-b-2 transition-colors -mb-px {{ $activeTab === 'appearance' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
            Appearance
        </button>
        <button wire:click="$set('activeTab','translations')"
                class="px-4 py-2.5 text-sm font-semibold border-b-2 transition-colors -mb-px {{ $activeTab === 'translations' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
            Translations
        </button>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    {{-- TAB: ITEMS --}}
    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    @if($activeTab === 'items')

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Left: Item list --}}
        <div>
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-300">Navigation Items</h2>
                <button wire:click="openAddItem()"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Add Item
                </button>
            </div>

            @if($items->isEmpty())
                <div class="text-center py-12 bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-slate-300 dark:border-slate-600">
                    <p class="text-sm text-slate-400">No items yet. Click "Add Item" to build your navigation.</p>
                </div>
            @else
                {{-- SortableJS list (top-level) --}}
                <div id="nav-sortable-root"
                     class="space-y-1.5"
                     x-data
                     x-init="
                        new Sortable($el, {
                            animation: 150,
                            handle: '.drag-handle',
                            ghostClass: 'opacity-40',
                            onEnd: (e) => {
                                const ids = [...$el.querySelectorAll('[data-item-id]')].map(el => parseInt(el.dataset.itemId));
                                $wire.reorderItems(ids, null);
                            }
                        });
                     ">
                    @foreach($items as $topItem)
                    <div data-item-id="{{ $topItem->id }}" wire:key="top-{{ $topItem->id }}">
                        {{-- Top-level item row --}}
                        <div class="flex items-center gap-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2.5 shadow-sm group">
                            <span class="drag-handle cursor-grab text-slate-300 dark:text-slate-600 hover:text-slate-500 shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                            </span>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-slate-800 dark:text-slate-100 truncate">{!! strip_tags($topItem->label) ?: '<em class="text-slate-400">No label</em>' !!}</span>
                                    <span class="shrink-0 text-xs px-1.5 py-0.5 bg-slate-100 dark:bg-slate-700 text-slate-500 rounded font-mono">{{ $topItem->item_type }}</span>
                                    @unless($topItem->is_active)
                                        <span class="shrink-0 text-xs text-slate-400 italic">inactive</span>
                                    @endunless
                                </div>
                            </div>
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button wire:click="toggleItemActive({{ $topItem->id }})"
                                        title="{{ $topItem->is_active ? 'Deactivate' : 'Activate' }}"
                                        class="p-1 rounded hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-400 hover:text-slate-600 transition-colors">
                                    @if($topItem->is_active)
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    @else
                                        <svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                                    @endif
                                </button>
                                <button wire:click="editItem({{ $topItem->id }})"
                                        class="p-1 rounded hover:bg-indigo-50 dark:hover:bg-indigo-900/30 text-slate-400 hover:text-indigo-600 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/></svg>
                                </button>
                                @if(in_array($topItem->item_type, ['parent','no_link']))
                                <button wire:click="openAddItem({{ $topItem->id }})"
                                        title="Add child item"
                                        class="p-1 rounded hover:bg-emerald-50 dark:hover:bg-emerald-900/20 text-slate-400 hover:text-emerald-600 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                </button>
                                @endif
                                <button wire:click="deleteItem({{ $topItem->id }})"
                                        wire:confirm="Delete this item{{ $topItem->children->isNotEmpty() ? ' and its '.count($topItem->children).' child item(s)' : '' }}?"
                                        class="p-1 rounded hover:bg-rose-50 dark:hover:bg-rose-900/20 text-slate-400 hover:text-rose-500 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Children --}}
                        @if($topItem->children->isNotEmpty())
                        <div class="ml-6 mt-1 space-y-1"
                             id="nav-sortable-children-{{ $topItem->id }}"
                             x-data
                             x-init="
                                new Sortable($el, {
                                    animation: 150,
                                    handle: '.drag-handle',
                                    ghostClass: 'opacity-40',
                                    onEnd: (e) => {
                                        const ids = [...$el.querySelectorAll('[data-item-id]')].map(el => parseInt(el.dataset.itemId));
                                        $wire.reorderItems(ids, {{ $topItem->id }});
                                    }
                                });
                             ">
                            @foreach($topItem->children as $child)
                            <div data-item-id="{{ $child->id }}" wire:key="child-{{ $child->id }}"
                                 class="flex items-center gap-2 bg-slate-50 dark:bg-slate-750 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 group">
                                <span class="drag-handle cursor-grab text-slate-300 dark:text-slate-600 hover:text-slate-500 shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                                </span>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-medium text-slate-600 dark:text-slate-300 truncate">{!! strip_tags($child->label) ?: '<em class="text-slate-400">No label</em>' !!}</span>
                                        <span class="shrink-0 text-xs px-1.5 py-0.5 bg-slate-100 dark:bg-slate-700 text-slate-500 rounded font-mono">{{ $child->item_type }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button wire:click="editItem({{ $child->id }})"
                                            class="p-1 rounded hover:bg-indigo-50 dark:hover:bg-indigo-900/30 text-slate-400 hover:text-indigo-600 transition-colors">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/></svg>
                                    </button>
                                    <button wire:click="deleteItem({{ $child->id }})"
                                            wire:confirm="Delete this child item?"
                                            class="p-1 rounded hover:bg-rose-50 dark:hover:bg-rose-900/20 text-slate-400 hover:text-rose-500 transition-colors">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                <p class="mt-3 text-xs text-slate-400 dark:text-slate-500 text-center">
                    Drag ☰ to reorder items. Use "+ Add Item" on a parent to add sub-menu items.
                </p>
            @endif
        </div>

        {{-- Right: Item form panel --}}
        <div>
            @if($showItemForm)
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6">
                <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-5">
                    {{ $editingItemId ? 'Edit Item' : ($itemParentId ? 'Add Child Item' : 'Add Top-Level Item') }}
                </h2>

                <div class="space-y-4">

                    {{-- Item Type --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Item Type *</label>
                        <select wire:model.live="itemType"
                                class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <optgroup label="Built-in Types">
                                @foreach($builtInTypes as $typeKey => $typeLabel)
                                    <option value="{{ $typeKey }}">{{ $typeLabel }}</option>
                                @endforeach
                            </optgroup>
                            @if(count($topNavPlugins))
                            <optgroup label="Plugin Types">
                                @foreach($topNavPlugins as $pluginSlug => $plugin)
                                    <option value="plugin" data-plugin="{{ $pluginSlug }}">{{ $plugin->name() }}</option>
                                @endforeach
                            </optgroup>
                            @endif
                        </select>
                    </div>

                    {{-- Label --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">
                            Label *
                            <span class="text-slate-400 font-normal">(HTML + icon markup allowed)</span>
                        </label>
                        <input wire:model="itemLabel" type="text"
                               placeholder='e.g. Shop, <i class="fa fa-cart"></i>, <strong>Sale</strong>'
                               class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-sm font-mono text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        @error('itemLabel')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                    </div>

                    {{-- Conditional: Custom URL --}}
                    @if($itemType === 'link')
                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">URL *</label>
                        <input wire:model="itemUrl" type="text" placeholder="https://example.com or /page-slug"
                               class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    </div>
                    @endif

                    {{-- Conditional: CMS Page --}}
                    @if($itemType === 'cms_page')
                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Search CMS Page</label>
                        <input wire:model.live.debounce.300ms="cmsPageSearch" type="text" placeholder="Type page title..."
                               class="w-full px-3 py-2 mb-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        <select wire:model="itemCmsPageId" size="5"
                                class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">— Select a page —</option>
                            @foreach($cmsPages as $page)
                                <option value="{{ $page->id }}">{{ $page->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- Conditional: Mega Menu / HTML Submenu — TinyMCE --}}
                    @if(in_array($itemType, ['mega_menu','html_submenu']))
                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">
                            {{ $itemType === 'mega_menu' ? 'Mega Menu HTML Content' : 'Custom HTML Sub-Menu Content' }}
                        </label>
                        <div wire:ignore
                             x-data="{
                                content: @entangle('itemHtmlContent'),
                                editorId: 'nav_html_editor_{{ $editingItemId ?? 'new' }}',
                                init() {
                                    const self = this;
                                    tinymce.init({
                                        selector: '#' + self.editorId,
                                        license_key: 'gpl',
                                        promotion: false,
                                        height: 350,
                                        menubar: 'insert format table',
                                        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace wordcount visualblocks fullscreen insertdatetime media table',
                                        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | numlist bullist | link image | fullscreen',
                                        toolbar_mode: 'wrap',
                                        convert_urls: false,
                                        branding: false,
                                        extended_valid_elements: '*[class|style|id|name]',
                                        setup: (editor) => {
                                            editor.on('init', () => { editor.setContent(self.content || ''); });
                                            editor.on('change blur', () => { self.content = editor.getContent(); });
                                        }
                                    });
                                },
                                destroy() { tinymce.remove('#' + this.editorId); }
                             }"
                             x-init="init()"
                             @livewire-navigating.window="destroy()">
                            <textarea :id="editorId" class="w-full"></textarea>
                        </div>
                    </div>
                    @endif

                    {{-- Conditional: Plugin selector --}}
                    @if($itemType === 'plugin')
                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Plugin</label>
                        <select wire:model="itemPluginSlug"
                                class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">— Select plugin —</option>
                            @foreach($topNavPlugins as $pluginSlug => $plugin)
                                <option value="{{ $pluginSlug }}">{{ $plugin->name() }}</option>
                            @endforeach
                        </select>
                        @if(empty($topNavPlugins))
                            <p class="mt-1 text-xs text-amber-600 dark:text-amber-400">No top-navigation plugins registered yet. Drop a plugin folder into <code class="font-mono">plugins/</code> at the project root.</p>
                        @endif
                    </div>
                    @endif

                    {{-- Parent item (only for child items being added) --}}
                    @if($itemParentId)
                    <div class="p-2 bg-slate-50 dark:bg-slate-700/50 rounded-lg text-xs text-slate-500 dark:text-slate-400">
                        ↳ Adding as child of: <strong class="text-slate-700 dark:text-slate-200">
                            {{ optional($parentItems->firstWhere('id', $itemParentId))->label ? strip_tags(optional($parentItems->firstWhere('id', $itemParentId))->label) : 'parent item' }}
                        </strong>
                    </div>
                    @endif

                    {{-- Divider --}}
                    <div class="border-t border-slate-100 dark:border-slate-700 pt-4">
                        <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Options</h3>
                        <div class="space-y-3">

                            {{-- Visibility --}}
                            <div>
                                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Show For</label>
                                <select wire:model="itemVisibility"
                                        class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="all">Everyone</option>
                                    <option value="guests_only">Guests Only (not logged in)</option>
                                    <option value="auth_only">Logged-In Users Only</option>
                                    <option value="wholesale_only">Wholesale Users Only</option>
                                </select>
                            </div>

                            {{-- Toggle options --}}
                            <div class="grid grid-cols-2 gap-3">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input wire:model="itemIsActive" type="checkbox"
                                           class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                                    <span class="text-xs text-slate-600 dark:text-slate-300">Active</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input wire:model="itemNewTab" type="checkbox"
                                           class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                                    <span class="text-xs text-slate-600 dark:text-slate-300">Open in new tab</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input wire:model="itemHideMobile" type="checkbox"
                                           class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                                    <span class="text-xs text-slate-600 dark:text-slate-300">Hide on mobile</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input wire:model="itemHideDesktop" type="checkbox"
                                           class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                                    <span class="text-xs text-slate-600 dark:text-slate-300">Hide on desktop</span>
                                </label>
                            </div>

                            {{-- Advanced --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Extra CSS Class</label>
                                    <input wire:model="itemCssClass" type="text" placeholder="my-class"
                                           class="w-full px-3 py-1.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-xs font-mono text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">ARIA Label</label>
                                    <input wire:model="itemAriaLabel" type="text" placeholder="Accessible label"
                                           class="w-full px-3 py-1.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form actions --}}
                <div class="flex gap-3 mt-6 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <button wire:click="saveItem"
                            class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors">
                        {{ $editingItemId ? 'Save Changes' : 'Add Item' }}
                    </button>
                    <button wire:click="cancelItemForm"
                            class="px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-sm font-semibold rounded-lg transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
            @else
                <div class="text-center py-16 bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-slate-200 dark:border-slate-700">
                    <p class="text-sm text-slate-400 dark:text-slate-500">Select an item to edit, or click "Add Item".</p>
                </div>
            @endif
        </div>
    </div>

    @endif {{-- end items tab --}}

    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    {{-- TAB: APPEARANCE --}}
    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    @if($activeTab === 'appearance')

    <div class="max-w-2xl">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 space-y-6">

            {{-- Menu name --}}
            <div>
                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Menu Name</label>
                <input wire:model="menuName" type="text"
                       class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            </div>

            {{-- Color scheme --}}
            <div>
                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-2">Color Scheme</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach([
                        'default'     => ['White / Light', 'bg-white border border-slate-200 text-slate-600'],
                        'dark'        => ['Dark Slate', 'bg-slate-800 text-slate-200'],
                        'indigo'      => ['Indigo Gradient', 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white'],
                        'slate'       => ['Slate Solid', 'bg-slate-600 text-white'],
                        'transparent' => ['Transparent', 'bg-white/10 backdrop-blur border border-dashed border-slate-300 text-slate-500'],
                        'custom'      => ['Custom CSS Only', 'bg-purple-50 border border-purple-200 text-purple-600'],
                    ] as $key => [$label, $classes])
                    <label class="relative flex flex-col cursor-pointer rounded-xl overflow-hidden border-2 transition-all
                                  {{ $menuColorScheme === $key ? 'border-indigo-500 ring-2 ring-indigo-200 dark:ring-indigo-800' : 'border-transparent hover:border-slate-300' }}">
                        <div class="{{ $classes }} px-3 py-4 text-center text-xs font-semibold rounded-xl">
                            {{ $label }}
                        </div>
                        <input wire:model.live="menuColorScheme" type="radio" value="{{ $key }}" class="sr-only" />
                    </label>
                    @endforeach
                </div>
            {{-- Menu Alignment --}}
            <div>
                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Menu Items Alignment</label>
                <select wire:model="menuAlignment" class="w-full sm:w-72 px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="left">Left aligned</option>
                    <option value="center">Center aligned</option>
                    <option value="right">Right aligned</option>
                    <option value="even">Evenly distributed</option>
                </select>
                <p class="mt-1 text-xs text-slate-400">Controls how desktop menu items are aligned across the navigation bar.</p>
            </div>

            {{-- Options row --}}
            <div class="space-y-4">
                <div class="flex flex-wrap gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input wire:model.live="menuSticky" type="checkbox"
                               class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                        <span class="text-sm text-slate-700 dark:text-slate-300">Sticky nav (fixed on scroll)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input wire:model="menuShowLogo" type="checkbox"
                               class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                        <span class="text-sm text-slate-700 dark:text-slate-300">Show site logo in nav</span>
                    </label>
                </div>

                <div x-show="$wire.menuSticky" class="p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl border border-slate-200 dark:border-slate-600 space-y-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Main Body Top Offset (Sticky Menu Compensation)
                    </label>
                    <input wire:model="stickyBodyOffset" type="text" placeholder="e.g. 70px, 4rem, 0px"
                           class="w-full sm:w-72 px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-mono" />
                    <p class="text-xs text-slate-400">Specify an optional top padding/margin offset for main body content to prevent layout overlap under sticky navigation.</p>
                </div>
            </div>

            {{-- Custom CSS --}}
            <div>
                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">
                    Custom CSS
                    <span class="text-slate-400 font-normal">— scoped to <code class="font-mono">#top-nav-{{ $menu->slug }}</code></span>
                </label>
                <textarea wire:model="menuCustomCss" rows="10"
                          placeholder="#top-nav-{{ $menu->slug }} {&#10;  /* your custom CSS here */&#10;}&#10;&#10;#top-nav-{{ $menu->slug }} a {&#10;  font-weight: 700;&#10;}"
                          class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-xs font-mono text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                <p class="mt-1 text-xs text-slate-400">CSS variables available: <code class="font-mono">--nav-bg</code>, <code class="font-mono">--nav-text</code>, <code class="font-mono">--nav-text-hover</code>, <code class="font-mono">--nav-dropdown-bg</code>, and more. See documentation.</p>
            </div>

            {{-- Default CSS (Read-Only Reference) --}}
            <div>
                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">
                    Default Top Navigation CSS (Read-Only Reference)
                </label>
                <pre class="text-xs bg-slate-900 dark:bg-slate-950 text-slate-300 p-4 rounded-xl overflow-x-auto max-h-56 leading-relaxed font-mono"><code>{{ $this->defaultTopNavCss }}</code></pre>
                <p class="mt-1 text-xs text-slate-400 italic">Reference only — edit the Custom CSS field above to customize.</p>
            </div>

            <div class="flex items-center gap-4">
                <button wire:click="saveAppearance"
                        class="w-full sm:w-auto px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors">
                    Save Appearance
                </button>
                @if($successMessage)
                    <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                        {{ $successMessage }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    @endif {{-- end appearance tab --}}

    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    {{-- TAB: TRANSLATIONS --}}
    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    @if($activeTab === 'translations')

    <div class="max-w-4xl">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 space-y-6">
            @if($activeLanguages->isEmpty())
                <div class="text-center py-12 border border-dashed border-slate-300 dark:border-slate-600 rounded-xl">
                    <p class="text-sm text-slate-500">No active secondary languages configured.</p>
                </div>
            @else
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Select Language</label>
                    <select wire:model.live="translationLanguageId" class="w-full sm:w-72 px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="0">— Select language to translate —</option>
                        @foreach($activeLanguages as $lang)
                            <option value="{{ $lang->id }}">{{ $lang->name }} ({{ $lang->code }})</option>
                        @endforeach
                    </select>
                </div>

                @if($translationLanguageId > 0)
                    <div class="overflow-x-auto border-t border-slate-200 dark:border-slate-700 pt-4">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide border-b border-slate-200 dark:border-slate-700">
                                    <th class="py-3 px-4">Menu Item (Default)</th>
                                    <th class="py-3 px-4">Translation</th>
                                    <th class="py-3 px-4 w-48">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                                @php
                                    // Flatten the items tree for easy translation table rendering
                                    $flatItems = [];
                                    $flatten = function($items, $level = 0) use (&$flatten, &$flatItems) {
                                        foreach($items as $item) {
                                            $item->level = $level;
                                            $flatItems[] = $item;
                                            if($item->children && $item->children->isNotEmpty()) {
                                                $flatten($item->children, $level + 1);
                                            }
                                        }
                                    };
                                    $flatten($items);
                                @endphp
                                @foreach($flatItems as $item)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-750">
                                        <td class="py-3 px-4">
                                            <div class="text-sm font-medium text-slate-700 dark:text-slate-200" style="padding-left: {{ $item->level * 1.5 }}rem;">
                                                {!! strip_tags($item->label) ?: '<em class="text-slate-400">No label</em>' !!}
                                            </div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <input type="text" 
                                                   wire:model.defer="translationBuffer.{{ $item->id }}" 
                                                   placeholder="{{ $navTranslations[$item->id] ?? 'Translate label...' }}"
                                                   class="w-full px-3 py-2 bg-white dark:bg-slate-800 border {{ isset($navTranslations[$item->id]) ? 'border-emerald-300 dark:border-emerald-700' : 'border-slate-200 dark:border-slate-600' }} rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex flex-wrap gap-1.5">
                                                {{-- AI Translate button --}}
                                                <button wire:click="aiTranslateNavItem({{ $item->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="aiTranslateNavItem({{ $item->id }})"
                                                        title="AI translate from default label"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-violet-50 hover:bg-violet-100 text-violet-600 dark:bg-violet-900/30 dark:hover:bg-violet-900/50 dark:text-violet-400 text-xs font-semibold rounded transition-colors disabled:opacity-60 disabled:cursor-wait">
                                                    <span wire:loading.remove wire:target="aiTranslateNavItem({{ $item->id }})">
                                                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L9.09 9.09 2 12l7.09 2.91L12 22l2.91-7.09L22 12l-7.09-2.91L12 2z"/></svg>
                                                    </span>
                                                    <span wire:loading wire:target="aiTranslateNavItem({{ $item->id }})" class="inline-flex">
                                                        <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                                    </span>
                                                    AI
                                                </button>

                                                {{-- Save button --}}
                                                <button wire:click="saveNavTranslation({{ $item->id }})" class="px-2.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:hover:bg-indigo-900/50 dark:text-indigo-400 text-xs font-semibold rounded transition-colors">
                                                    Save
                                                </button>

                                                {{-- Clear button (only when translation exists) --}}
                                                @if(isset($navTranslations[$item->id]))
                                                <button wire:click="clearNavTranslation({{ $item->id }})" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-900/30 dark:hover:bg-rose-900/50 dark:text-rose-400 text-xs font-semibold rounded transition-colors">
                                                    Clear
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endif
        </div>
    </div>

    @endif {{-- end translations tab --}}
</div>
