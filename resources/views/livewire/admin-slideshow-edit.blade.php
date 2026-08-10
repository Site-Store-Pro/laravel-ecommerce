<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- ── Page Header ─────────────────────────────────────────────────── --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.cms-slideshows.index') }}" wire:navigate
                   class="flex items-center gap-1.5 text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    All Slideshows
                </a>
                <span class="text-slate-200 dark:text-slate-700">/</span>
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                        {{ $slideshow->slideshow_name ?? 'Slideshow' }}
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Alignment: <span class="font-semibold">{{ $alignmentOptions[$slideshow->slide_show_alignment] ?? $slideshow->slide_show_alignment }}</span>
                        &nbsp;·&nbsp; ID #{{ $slideshow->slideshow_id }}
                        &nbsp;·&nbsp;
                        <span class="{{ $slideshow->slideshow_active ? 'text-emerald-600' : 'text-slate-400' }} font-semibold">
                            {{ $slideshow->slideshow_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                </div>
            </div>
            <button wire:click="startCreate"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-md flex items-center gap-2 transition duration-150 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Add Slide
            </button>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">

            {{-- ══════════════════════════════════════════════════════════════ --}}
            {{-- ── Slide List (drag-and-drop) ─────────────────────────────── --}}
            {{-- ══════════════════════════════════════════════════════════════ --}}
            <div class="xl:col-span-5 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                        Slides
                        <span class="ml-2 px-2 py-0.5 bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300 rounded-full text-xs font-bold">
                            {{ $slides->count() }}
                        </span>
                    </h2>
                    <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        Drag to reorder
                    </span>
                </div>

                @if($slides->isEmpty())
                    <div class="bg-white dark:bg-slate-800 border border-dashed border-slate-200 dark:border-slate-700 rounded-3xl p-10 text-center">
                        <div class="inline-flex items-center justify-center p-4 rounded-2xl bg-slate-100 dark:bg-slate-700 mb-3">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01"/>
                            </svg>
                        </div>
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400">No slides yet. Click "Add Slide" to get started.</p>
                    </div>
                @else
                    <div
                        id="slide-sortable-list"
                        class="space-y-3"
                        x-data="{
                            sortable: null,
                            init() {
                                this.sortable = Sortable.create(this.$el, {
                                    animation: 200,
                                    handle: '.drag-handle',
                                    ghostClass: 'opacity-30',
                                    onEnd: () => {
                                        const order = [...this.$el.querySelectorAll('[data-slide-id]')]
                                            .map(el => parseInt(el.dataset.slideId));
                                        $wire.updateSlideOrder(order);
                                    }
                                });
                            }
                        }"
                    >
                        @foreach($slides as $slide)
                            <div data-slide-id="{{ $slide->id }}"
                                 class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-4 shadow-sm flex items-center gap-4 transition duration-150
                                        {{ ($isEditing && $slideId === $slide->id) ? 'ring-2 ring-indigo-400 ring-offset-1' : 'hover:border-slate-200 dark:hover:border-slate-600' }}"
                                 wire:key="slide-card-{{ $slide->id }}">

                                {{-- Drag handle --}}
                                <div class="drag-handle cursor-grab active:cursor-grabbing text-slate-300 dark:text-slate-600 hover:text-slate-400 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                    </svg>
                                </div>

                                {{-- Thumbnail --}}
                                <div class="w-16 h-12 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-700 shrink-0 flex items-center justify-center">
                                    @if($slide->thumbnailUrl())
                                        <img src="{{ $slide->thumbnailUrl() }}" alt="{{ $slide->Title }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01"/>
                                        </svg>
                                    @endif
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-slate-800 dark:text-white truncate">
                                        {{ $slide->slide_heading ?: ($slide->Title ?: '(No heading)') }}
                                    </p>
                                    <p class="text-[10px] text-slate-400 truncate mt-0.5">
                                        {{ $slide->slide_sub_heading ?: $slide->Description ?: '—' }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-1.5">
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full {{ $slide->Active ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-slate-100 text-slate-400 dark:bg-slate-700' }}">
                                            {{ $slide->Active ? 'Active' : 'Inactive' }}
                                        </span>
                                        <span class="text-[9px] text-slate-400">#{{ $slide->ImageSort }}</span>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <button wire:click="toggleActive({{ $slide->id }})" title="{{ $slide->Active ? 'Deactivate' : 'Activate' }}"
                                            class="p-1.5 rounded-lg {{ $slide->Active ? 'text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/30' : 'text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700' }} transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $slide->Active ? 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z' : 'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21' }}"/>
                                        </svg>
                                    </button>
                                    <button wire:click="editSlide({{ $slide->id }})" title="Edit Slide"
                                            class="p-1.5 rounded-lg text-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button wire:click="deleteSlide({{ $slide->id }})"
                                            wire:confirm="Delete this slide and its images permanently?"
                                            title="Delete Slide"
                                            class="p-1.5 rounded-lg text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/30 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>{{-- /slide list --}}

            {{-- ══════════════════════════════════════════════════════════════ --}}
            {{-- ── Slide Edit / Create Form ────────────────────────────────── --}}
            {{-- ══════════════════════════════════════════════════════════════ --}}
            <div class="xl:col-span-7">
                @if($isCreating || $isEditing)
                    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-3xl shadow-sm overflow-hidden"
                         x-data x-init="$el.scrollIntoView({behavior:'smooth',block:'nearest'})">

                        {{-- Form header --}}
                        <div class="flex items-center justify-between px-8 py-5 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                            <h2 class="text-sm font-bold text-slate-800 dark:text-white">
                                {{ $isEditing ? 'Edit Slide #' . $slideId : 'New Slide' }}
                            </h2>
                            <button wire:click="cancel" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <div class="p-8 space-y-6">

                            {{-- ── Content Section ──────────────────────────── --}}
                            <div>
                                <h3 class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4">Overlay Content</h3>
                                <div class="space-y-4">

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        {{-- Heading --}}
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Slide Heading</label>
                                            <input type="text" wire:model="slide_heading" placeholder="e.g. Welcome to Our Store"
                                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs font-semibold">
                                            @error('slide_heading') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                                        </div>

                                        {{-- Callout button label --}}
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Callout Button Label</label>
                                            <input type="text" wire:model="slide_callout_button_label" placeholder="e.g. Shop Now"
                                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs font-semibold">
                                            @error('slide_callout_button_label') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    {{-- Sub-heading --}}
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Sub-Heading / Description</label>
                                        <textarea wire:model="slide_sub_heading" rows="2" placeholder="Short supporting description shown below the heading..."
                                                  class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs font-semibold resize-none"></textarea>
                                        @error('slide_sub_heading') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- Slide URL --}}
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Callout Button URL (Link Target)</label>
                                        <input type="text" wire:model="SlideURL" placeholder="e.g. /shop or https://example.com/sale"
                                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs font-semibold">
                                        @error('SlideURL') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- Legacy title / description --}}
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Title (Legacy)</label>
                                            <input type="text" wire:model="Title" placeholder="Alt title field"
                                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs font-semibold">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Description (Legacy)</label>
                                            <input type="text" wire:model="Description" placeholder="Alt description field"
                                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs font-semibold">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ── CSS Overrides ─────────────────────────────── --}}
                            <div class="border-t border-slate-100 dark:border-slate-700 pt-6">
                                <h3 class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4">CSS Customization Overrides</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Content Container CSS</label>
                                        <textarea wire:model="slide_content_css" rows="3" placeholder="e.g. background: rgba(0,0,0,0.4); padding: 2rem;"
                                                  class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs font-mono resize-none"></textarea>
                                        @error('slide_content_css') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Heading CSS Override</label>
                                        <textarea wire:model="slide_heading_css" rows="3" placeholder="e.g. font-size: 3rem; color: #fff; text-shadow: 0 2px 8px rgba(0,0,0,0.5);"
                                                  class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs font-mono resize-none"></textarea>
                                        @error('slide_heading_css') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- ── Settings ──────────────────────────────────── --}}
                            <div class="border-t border-slate-100 dark:border-slate-700 pt-6">
                                <h3 class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4">Settings</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Overlay Alignment</label>
                                        <select wire:model="slide_alignment"
                                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs font-semibold">
                                            <option value="top-left">Top Left</option>
                                            <option value="top-center">Top Center</option>
                                            <option value="top-right">Top Right</option>
                                            <option value="middle-left">Middle Left</option>
                                            <option value="middle-center">Middle Center</option>
                                            <option value="middle-right">Middle Right</option>
                                            <option value="bottom-left">Bottom Left</option>
                                            <option value="bottom-center">Bottom Center</option>
                                            <option value="bottom-right">Bottom Right</option>
                                        </select>
                                        @error('slide_alignment') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Sort Order</label>
                                        <input type="number" wire:model="ImageSort" step="0.5" min="0"
                                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs font-semibold">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Status</label>
                                        <label class="flex items-center gap-2.5 cursor-pointer mt-1">
                                            <div class="relative">
                                                <input type="checkbox" wire:model.number="Active" class="sr-only peer" true-value="1" false-value="0">
                                                <div class="w-10 h-5 bg-slate-200 dark:bg-slate-600 rounded-full peer peer-focus:ring-2 peer-focus:ring-indigo-400 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-500"></div>
                                            </div>
                                            <span class="text-xs font-bold {{ $Active ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }} transition-colors">
                                                {{ $Active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- ── Image Storage ─────────────────────────────── --}}
                            <div class="border-t border-slate-100 dark:border-slate-700 pt-6" x-data="{ s3Mode: @entangle('image_s3').live }">
                                <h3 class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4">Image Storage</h3>

                                {{-- Storage mode selector --}}
                                <div class="space-y-1 mb-5">
                                    <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Storage Location</label>
                                    <select wire:model.live="image_s3" x-model.number="s3Mode"
                                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs font-semibold">
                                        <option value="0">Local Public Storage</option>
                                        <option value="1">Amazon S3 (from .env)</option>
                                        <option value="2">Amazon S3 (Custom Credentials)</option>
                                    </select>
                                </div>

                                {{-- Custom S3 credentials (only when mode = 2) --}}
                                <div x-show="s3Mode == 2" x-cloak class="space-y-4 mb-5 p-5 bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800/50 rounded-2xl">
                                    <p class="text-[10px] font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider">Custom AWS S3 Credentials</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">AWS Region</label>
                                            <input type="text" wire:model="image_s3_region" placeholder="e.g. us-east-1"
                                                   class="w-full px-4 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:border-indigo-500 text-xs font-semibold">
                                            @error('image_s3_region') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">S3 Bucket Name</label>
                                            <input type="text" wire:model="image_s3_bucket" placeholder="my-bucket-name"
                                                   class="w-full px-4 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:border-indigo-500 text-xs font-semibold">
                                            @error('image_s3_bucket') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Access Key ID</label>
                                            <input type="text" wire:model="image_s3_key" placeholder="AKIA..."
                                                   class="w-full px-4 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:border-indigo-500 text-xs font-semibold">
                                            @error('image_s3_key') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Secret Access Key</label>
                                            <input type="password" wire:model="image_s3_secret" placeholder="••••••••••••••••"
                                                   class="w-full px-4 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:border-indigo-500 text-xs font-semibold">
                                            @error('image_s3_secret') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- CDN / CloudFront prefix (shown for any S3 mode) --}}
                                <div x-show="s3Mode > 0" x-cloak class="space-y-1 mb-5">
                                    <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">CDN / CloudFront URL Prefix <span class="normal-case font-normal text-slate-400">(optional)</span></label>
                                    <input type="text" wire:model="cdn_url" placeholder="https://d1abc.cloudfront.net"
                                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs font-semibold">
                                    <p class="text-[10px] text-slate-400 mt-1">If set, image URLs will be prefixed with this value instead of the S3 bucket URL.</p>
                                    @error('cdn_url') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Image dimension overrides --}}
                                <div class="space-y-1 mb-5">
                                    <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">CDN Image Dimensions (for HTML width/height attributes)</p>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Desktop W</label>
                                            <input type="number" wire:model="cdn_image_width" min="1"
                                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:border-indigo-500 text-xs font-semibold">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Desktop H</label>
                                            <input type="number" wire:model="cdn_image_height" min="1"
                                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:border-indigo-500 text-xs font-semibold">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Mobile W</label>
                                            <input type="number" wire:model="cdn_mobile_image_width" min="1"
                                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:border-indigo-500 text-xs font-semibold">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Mobile H</label>
                                            <input type="number" wire:model="cdn_mobile_image_height" min="1"
                                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white rounded-xl focus:outline-none focus:border-indigo-500 text-xs font-semibold">
                                        </div>
                                    </div>
                                </div>

                                {{-- ── Image Uploads ─────────────────────────── --}}
                                <div class="space-y-4">
                                    <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Image Files</p>

                                    {{-- Desktop Image --}}
                                    <div class="p-4 bg-slate-50 dark:bg-slate-700/30 border border-slate-200 dark:border-slate-600 rounded-2xl space-y-3">
                                        <div class="flex items-center justify-between">
                                            <label class="text-[10px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Desktop / Hero Image</label>
                                            @if($existing_large_image)
                                                <button wire:click="removeDesktopImage" wire:confirm="Remove the desktop image?"
                                                        class="text-[10px] text-rose-500 hover:text-rose-700 font-bold transition">Remove</button>
                                            @endif
                                        </div>

                                        {{-- External URL override --}}
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"/></svg>
                                                External URL Override
                                            </label>
                                            <input type="url" wire:model="cdn_image"
                                                   placeholder="https://example.com/hero.jpg — if set, overrides file upload"
                                                   class="w-full px-3 py-2 bg-white dark:bg-slate-800 border {{ $cdn_image ? 'border-amber-400 dark:border-amber-500' : 'border-slate-200 dark:border-slate-600' }} text-slate-800 dark:text-white rounded-xl focus:outline-none focus:border-amber-500 text-xs font-semibold">
                                            @if($cdn_image)
                                                <p class="text-[10px] text-amber-600 dark:text-amber-400 font-semibold mt-0.5">⚡ External URL active — file upload below will be ignored.</p>
                                            @endif
                                            @error('cdn_image') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        @if($existing_large_image)
                                            <div class="flex items-center gap-3 p-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl">
                                                @php
                                                    $slide = $isEditing && $slideId ? \App\Models\CmsSlide::find($slideId) : null;
                                                @endphp
                                                @if($slide)
                                                    <img src="{{ $slide->desktopImageUrl() }}" alt="Desktop" class="w-16 h-10 object-cover rounded-lg">
                                                @endif
                                                <span class="text-[10px] text-slate-500 font-mono truncate">{{ basename($existing_large_image) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <input type="file" wire:model="largeImageFile" accept="image/*"
                                                   class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-600 dark:file:bg-indigo-900/30 dark:file:text-indigo-300 hover:file:bg-indigo-100 cursor-pointer">
                                            @error('largeImageFile') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                                            @if($largeImageFile)
                                                <p class="text-[10px] text-indigo-500 mt-1 font-semibold">New file selected — will upload on save.</p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Mobile Image (Required) --}}
                                    <div class="p-4 bg-slate-50 dark:bg-slate-700/30 border border-slate-200 dark:border-slate-600 rounded-2xl space-y-3">
                                        <div class="flex items-center justify-between">
                                            <label class="text-[10px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Mobile Image <span class="text-rose-500 font-bold">* Required</span></label>
                                            @if($existing_mobile_image)
                                                <button wire:click="removeMobileImage" wire:confirm="Remove the mobile image?"
                                                        class="text-[10px] text-rose-500 hover:text-rose-700 font-bold transition">Remove</button>
                                            @endif
                                        </div>

                                        {{-- External URL override --}}
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"/></svg>
                                                External URL Override
                                            </label>
                                            <input type="url" wire:model="cdn_mobile_image"
                                                   placeholder="https://example.com/mobile.jpg — if set, overrides file upload"
                                                   class="w-full px-3 py-2 bg-white dark:bg-slate-800 border {{ $cdn_mobile_image ? 'border-amber-400 dark:border-amber-500' : 'border-slate-200 dark:border-slate-600' }} text-slate-800 dark:text-white rounded-xl focus:outline-none focus:border-amber-500 text-xs font-semibold">
                                            @if($cdn_mobile_image)
                                                <p class="text-[10px] text-amber-600 dark:text-amber-400 font-semibold mt-0.5">⚡ External URL active — file upload below will be ignored.</p>
                                            @endif
                                            @error('cdn_mobile_image') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        @if($existing_mobile_image && $slide)
                                            <div class="flex items-center gap-3 p-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl">
                                                <img src="{{ $slide->mobileImageUrl() }}" alt="Mobile" class="w-8 h-12 object-cover rounded-lg">
                                                <span class="text-[10px] text-slate-500 font-mono truncate">{{ basename($existing_mobile_image) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <input type="file" wire:model="mobileImageFile" accept="image/*"
                                                   class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-600 dark:file:bg-indigo-900/30 dark:file:text-indigo-300 hover:file:bg-indigo-100 cursor-pointer">
                                            @error('mobileImageFile') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    {{-- Thumbnail (Optional — admin display only) --}}
                                    <div class="p-4 bg-slate-50 dark:bg-slate-700/30 border border-slate-200 dark:border-slate-600 rounded-2xl space-y-3">
                                        <div class="flex items-center justify-between">
                                            <label class="text-[10px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Thumbnail <span class="normal-case font-normal text-slate-400">(Optional — admin display only)</span></label>
                                            @if($existing_thumbnail)
                                                <button wire:click="removeThumbnailImage" wire:confirm="Remove the thumbnail?"
                                                        class="text-[10px] text-rose-500 hover:text-rose-700 font-bold transition">Remove</button>
                                            @endif
                                        </div>
                                        <p class="text-[10px] text-slate-400 font-medium">Used for admin management preview list only. If left empty, desktop/mobile image will be used as preview thumbnail.</p>

                                        {{-- External URL override --}}
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"/></svg>
                                                External URL Override
                                            </label>
                                            <input type="url" wire:model="cdn_thumbnail"
                                                   placeholder="https://example.com/thumb.jpg — if set, overrides file upload"
                                                   class="w-full px-3 py-2 bg-white dark:bg-slate-800 border {{ $cdn_thumbnail ? 'border-amber-400 dark:border-amber-500' : 'border-slate-200 dark:border-slate-600' }} text-slate-800 dark:text-white rounded-xl focus:outline-none focus:border-amber-500 text-xs font-semibold">
                                            @if($cdn_thumbnail)
                                                <p class="text-[10px] text-amber-600 dark:text-amber-400 font-semibold mt-0.5">⚡ External URL active — file upload below will be ignored.</p>
                                            @endif
                                            @error('cdn_thumbnail') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        @if($existing_thumbnail && $slide)
                                            <div class="flex items-center gap-3 p-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl">
                                                <img src="{{ $slide->thumbnailUrl() }}" alt="Thumbnail" class="w-12 h-8 object-cover rounded-lg">
                                                <span class="text-[10px] text-slate-500 font-mono truncate">{{ basename($existing_thumbnail) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <input type="file" wire:model="thumbnailFile" accept="image/*"
                                                   class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-600 dark:file:bg-indigo-900/30 dark:file:text-indigo-300 hover:file:bg-indigo-100 cursor-pointer">
                                            @error('thumbnailFile') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ── Form Actions ──────────────────────────────── --}}
                            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                                <button wire:click="cancel"
                                        class="px-5 py-2.5 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-bold text-xs rounded-xl transition duration-150">
                                    Cancel
                                </button>
                                <button wire:click="saveSlide" wire:loading.attr="disabled" wire:target="saveSlide"
                                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md flex items-center gap-2 transition duration-150">
                                    <svg wire:loading wire:target="saveSlide" class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>{{ $isEditing ? 'Update Slide' : 'Create Slide' }}</span>
                                </button>
                            </div>

                        </div>{{-- /p-8 --}}
                    </div>{{-- /form card --}}

                    {{-- ══════════════════════════════════════════════════════ --}}
                    {{-- ── Translation Panel (editing only) ─────────────────── --}}
                    {{-- ══════════════════════════════════════════════════════ --}}
                    @php
                        $activeLangs = \App\Models\Language::where('is_active', true)->where('is_default', false)->orderBy('sort_order')->orderBy('name')->get();
                    @endphp
                    @if($activeLangs->isNotEmpty())
                        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl shadow-sm overflow-hidden mt-1">

                            {{-- Panel header --}}
                            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="p-1.5 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg">
                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Slide Translations</h3>
                                    @if($trans_status === 'reviewed')
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-[10px] font-bold">Reviewed</span>
                                    @elseif($trans_status === 'ai_translated')
                                        <span class="px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-[10px] font-bold">AI Draft</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-[10px] font-bold">Pending</span>
                                    @endif
                                </div>
                                <button wire:click="translateAllLanguages"
                                        wire:loading.attr="disabled"
                                        wire:target="translateAllLanguages"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 dark:bg-slate-700 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 text-slate-600 dark:text-slate-300 hover:text-indigo-700 dark:hover:text-indigo-300 rounded-xl text-[10px] font-bold transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                                    Translate All Languages
                                </button>
                            </div>

                            {{-- Language tab switcher --}}
                            <div class="flex overflow-x-auto border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                                @foreach($activeLangs as $lang)
                                    <button wire:click="selectTranslationLang('{{ $lang->code }}', {{ $lang->id }})"
                                            class="px-5 py-3 text-xs font-bold border-b-2 whitespace-nowrap transition {{ $activeLangCode === $lang->code ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-white/50 dark:hover:bg-slate-700/50' }}">
                                        {{ $lang->flag_emoji }} {{ $lang->name }}
                                    </button>
                                @endforeach
                            </div>

                            {{-- Translation fields --}}
                            <div class="p-6 space-y-4">

                                @if($trans_translated_at)
                                    <p class="text-[10px] text-slate-400">Last translated: {{ $trans_translated_at }}</p>
                                @endif

                                {{-- Heading --}}
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">
                                        Heading
                                        @if($slide_heading)
                                            <span class="ml-2 font-normal text-slate-400 normal-case tracking-normal">Original: "{{ Str::limit($slide_heading, 60) }}"</span>
                                        @endif
                                    </label>
                                    <input type="text"
                                           wire:model="trans_slide_heading"
                                           placeholder="Translated heading…"
                                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs font-semibold">
                                </div>

                                {{-- Sub-heading --}}
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">
                                        Sub-heading
                                        @if($slide_sub_heading)
                                            <span class="ml-2 font-normal text-slate-400 normal-case tracking-normal">Original: "{{ Str::limit($slide_sub_heading, 60) }}"</span>
                                        @endif
                                    </label>
                                    <textarea wire:model="trans_slide_sub_heading"
                                              rows="2"
                                              placeholder="Translated sub-heading…"
                                              class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs font-semibold resize-none"></textarea>
                                </div>

                                {{-- CTA Button Label --}}
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">
                                        CTA Button Label
                                        @if($slide_callout_button_label)
                                            <span class="ml-2 font-normal text-slate-400 normal-case tracking-normal">Original: "{{ $slide_callout_button_label }}"</span>
                                        @endif
                                    </label>
                                    <input type="text"
                                           wire:model="trans_button_label"
                                           placeholder="Translated button label…"
                                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs font-semibold">
                                </div>

                                {{-- Action buttons --}}
                                <div class="flex flex-wrap items-center gap-3 pt-2 border-t border-slate-100 dark:border-slate-700">
                                    <button wire:click="aiTranslateSlideInline"
                                            wire:loading.attr="disabled"
                                            wire:target="aiTranslateSlideInline"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-violet-50 hover:bg-violet-100 dark:bg-violet-900/20 dark:hover:bg-violet-900/40 text-violet-700 dark:text-violet-300 rounded-xl text-xs font-bold transition">
                                        <svg wire:loading wire:target="aiTranslateSlideInline" class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <svg wire:loading.remove wire:target="aiTranslateSlideInline" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        <span wire:loading.remove wire:target="aiTranslateSlideInline">Translate with AI</span>
                                        <span wire:loading wire:target="aiTranslateSlideInline">Translating…</span>
                                    </button>

                                    <button wire:click="saveTranslation"
                                            wire:loading.attr="disabled"
                                            wire:target="saveTranslation"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition">
                                        <svg wire:loading wire:target="saveTranslation" class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <svg wire:loading.remove wire:target="saveTranslation" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Save Translation
                                    </button>

                                    <button wire:click="autoTranslateSlide"
                                            wire:loading.attr="disabled"
                                            wire:target="autoTranslateSlide"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 rounded-xl text-[10px] font-bold transition">
                                        Queue Background Job
                                    </button>
                                </div>

                            </div>
                        </div>{{-- /translation panel --}}
                    @endif

                @else
                    {{-- Empty state placeholder --}}
                    <div class="bg-white dark:bg-slate-800 border border-dashed border-slate-200 dark:border-slate-700 rounded-3xl p-12 text-center hidden xl:flex flex-col items-center justify-center h-full min-h-[300px]">
                        <div class="inline-flex items-center justify-center p-4 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 mb-4">
                            <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-slate-500 dark:text-slate-400">Select a slide to edit</p>
                        <p class="text-xs text-slate-400 mt-1">or click "Add Slide" to create a new one.</p>
                    </div>
                @endif
            </div>{{-- /form col --}}

        </div>{{-- /grid --}}
    </div>{{-- /container --}}

    {{-- Load SortableJS from CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
</div>
