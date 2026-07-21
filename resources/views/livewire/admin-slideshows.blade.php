<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            {{-- ── Sidebar ────────────────────────────────────────────────── --}}
            <div class="lg:col-span-3 space-y-2">
                @include('layouts.cms-sidebar')
            </div>

            {{-- ── Main content ────────────────────────────────────────────── --}}
            <div class="lg:col-span-9 space-y-6">

                {{-- Page header --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">Slideshows</h1>
                        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Create and manage dynamic hero slideshows for CMS pages.</p>
                    </div>
                    <button wire:click="startCreate"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-md flex items-center gap-2 transition duration-150">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        New Slideshow
                    </button>
                </div>

                {{-- ── Create / Edit Panel ─────────────────────────────────── --}}
                @if($isCreating || $isEditing)
                    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-3xl p-8 shadow-sm space-y-5"
                         x-data x-init="$el.scrollIntoView({behavior:'smooth',block:'nearest'})">

                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-700">
                            <h2 class="text-base font-bold text-slate-800 dark:text-white">
                                {{ $isEditing ? 'Edit Slideshow' : 'New Slideshow' }}
                            </h2>
                            <button wire:click="cancel" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {{-- Name --}}
                            <div class="md:col-span-2 space-y-1">
                                <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Slideshow Name</label>
                                <input type="text" wire:model="slideshow_name" placeholder="e.g. Homepage Hero"
                                       class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-850 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs font-semibold">
                                @error('slideshow_name') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Sort order --}}
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Sort Order</label>
                                <input type="number" wire:model="sort_order" min="0"
                                       class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-850 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs font-semibold">
                                @error('sort_order') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Alignment --}}
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Default Overlay Alignment</label>
                                <select wire:model="slide_show_alignment"
                                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-850 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs font-semibold">
                                    @foreach($alignmentOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('slide_show_alignment') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Active toggle --}}
                            <div class="md:col-span-2 flex items-center gap-3">
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <div class="relative">
                                        <input type="checkbox" wire:model.number="slideshow_active" class="sr-only peer" true-value="1" false-value="0">
                                        <div class="w-10 h-5 bg-slate-200 dark:bg-slate-600 rounded-full peer peer-focus:ring-2 peer-focus:ring-indigo-400 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-500"></div>
                                    </div>
                                    <span class="text-xs font-bold {{ $slideshow_active ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }} transition-colors">
                                        {{ $slideshow_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-2 border-t border-slate-100 dark:border-slate-700">
                            <button wire:click="cancel"
                                    class="px-5 py-2.5 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-bold text-xs rounded-xl transition duration-150">
                                Cancel
                            </button>
                            <button wire:click="saveSlideshow" wire:loading.attr="disabled" wire:target="saveSlideshow"
                                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md flex items-center gap-2 transition duration-150">
                                <svg wire:loading wire:target="saveSlideshow" class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>{{ $isEditing ? 'Update Slideshow' : 'Create Slideshow' }}</span>
                            </button>
                        </div>
                    </div>
                @endif

                {{-- ── Slideshows Table ────────────────────────────────────── --}}
                <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-3xl shadow-sm overflow-hidden">
                    @if($slideshows->isEmpty())
                        <div class="p-12 text-center">
                            <div class="inline-flex items-center justify-center p-4 rounded-2xl bg-slate-100 dark:bg-slate-700 mb-4">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-700 dark:text-white">No slideshows yet</h3>
                            <p class="text-xs text-slate-400 mt-1">Click "New Slideshow" to create your first one.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700">
                                <thead class="bg-slate-50/60 dark:bg-slate-800/60">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Name</th>
                                        <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">Slides</th>
                                        <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">Sort</th>
                                        <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">Alignment</th>
                                        <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">Status</th>
                                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                    @foreach($slideshows as $show)
                                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition duration-150" wire:key="slideshow-{{ $show->slideshow_id }}">
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-slate-800 dark:text-white text-sm">{{ $show->slideshow_name ?? '(Unnamed)' }}</div>
                                                <div class="text-xs text-slate-400 mt-0.5">ID #{{ $show->slideshow_id }}</div>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300">
                                                    {{ $show->slides_count }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-center text-xs font-semibold text-slate-500 dark:text-slate-400">
                                                {{ $show->sort_order }}
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                                                    {{ $alignmentOptions[$show->slide_show_alignment] ?? $show->slide_show_alignment }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <button wire:click="toggleActive({{ $show->slideshow_id }})"
                                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold transition duration-150
                                                               {{ $show->slideshow_active ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100' : 'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 hover:bg-slate-200' }}">
                                                    {{ $show->slideshow_active ? 'Active' : 'Inactive' }}
                                                </button>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="{{ route('admin.cms-slideshows.edit', $show->slideshow_id) }}" wire:navigate
                                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300 hover:bg-indigo-100 font-bold text-xs transition duration-150">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/>
                                                        </svg>
                                                        Slides
                                                    </a>
                                                    <button wire:click="editSlideshow({{ $show->slideshow_id }})"
                                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600 font-bold text-xs transition duration-150">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                        Edit
                                                    </button>
                                                    <button wire:click="deleteSlideshow({{ $show->slideshow_id }})"
                                                            wire:confirm="Delete '{{ $show->slideshow_name }}'? This will permanently remove the slideshow and ALL its slides."
                                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 hover:bg-rose-100 font-bold text-xs transition duration-150">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

            </div>{{-- /main content --}}
        </div>{{-- /grid --}}
    </div>{{-- /container --}}
</div>
