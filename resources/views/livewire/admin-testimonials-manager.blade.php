<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">Testimonials Management</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Manage customer quotes, ratings, and social proof displays across your site.</p>
            </div>
            <button wire:click="startCreate" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-md flex items-center gap-2 transition duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Add Testimonial
            </button>
        </div>

        <!-- Flash messages -->
        @if(session()->has('status'))
            <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 rounded-2xl border border-emerald-100 dark:border-emerald-800 flex items-center gap-3 text-emerald-800 dark:text-emerald-300 text-sm font-semibold">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Table Section -->
            <div class="{{ ($isCreating || $isEditing) ? 'lg:col-span-7' : 'lg:col-span-12' }} bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl p-6 shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 border-b border-slate-100 dark:border-slate-700">
                    <div class="relative w-full sm:w-64">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input type="text" wire:model.live="search" placeholder="Search testimonials..." class="w-full pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-xl focus:outline-none focus:border-indigo-500">
                    </div>

                    <div class="flex items-center gap-2">
                        <select wire:model.live="statusFilter" class="px-3 py-1.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-300">
                            <option value="all">All Statuses</option>
                            <option value="active">Active Only</option>
                            <option value="inactive">Inactive Only</option>
                        </select>
                    </div>
                </div>

                @if($testimonials->isEmpty())
                    <div class="text-center py-12 text-slate-400 text-sm">
                        No testimonials found matching your criteria.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 dark:border-slate-700 text-slate-400 font-bold uppercase tracking-wider">
                                    <th class="py-3 px-3">Author</th>
                                    <th class="py-3 px-3">Rating</th>
                                    <th class="py-3 px-3">Testimonial Quote</th>
                                    <th class="py-3 px-3">Status</th>
                                    <th class="py-3 px-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @foreach($testimonials as $t)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/40 transition">
                                        <td class="py-3 px-3 font-semibold text-slate-900 dark:text-white">
                                            <div class="flex items-center gap-2.5">
                                                <img src="{{ $t->getAvatarUrl() }}" alt="{{ $t->author_name }}" class="w-8 h-8 rounded-full object-cover border border-slate-200 shrink-0">
                                                <div>
                                                    <div class="font-bold text-slate-800 dark:text-slate-100">{!! $t->author_name !!}</div>
                                                    @if($t->author_title)
                                                        <div class="text-[10px] text-slate-400 font-normal">{!! $t->author_title !!}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-3">
                                            <div class="flex items-center text-amber-400">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-3.5 h-3.5 {{ $i <= $t->rating ? 'fill-current' : 'text-slate-200 dark:text-slate-600' }}" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </td>
                                        <td class="py-3 px-3 max-w-xs truncate text-slate-600 dark:text-slate-300">
                                            {!! strip_tags($t->content) !!}
                                        </td>
                                        <td class="py-3 px-3">
                                            <button wire:click="toggleActive({{ $t->id }})" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border transition {{ $t->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                                                {{ $t->is_active ? 'Active' : 'Hidden' }}
                                            </button>
                                        </td>
                                        <td class="py-3 px-3 text-right">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <button wire:click="editTestimonial({{ $t->id }})" class="px-2.5 py-1 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-200 rounded-lg text-xs font-semibold hover:bg-slate-50 transition">
                                                    Edit
                                                </button>
                                                <button onclick="confirm('Delete this testimonial permanently?') || event.stopImmediatePropagation()" wire:click="deleteTestimonial({{ $t->id }})" class="p-1 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($testimonials->hasPages())
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                            {{ $testimonials->links() }}
                        </div>
                    @endif
                @endif
            </div>

            <!-- Form Panel -->
            @if($isCreating || $isEditing)
                <div class="lg:col-span-5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-3">
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">
                            {{ $isEditing ? 'Edit Testimonial' : 'Add Testimonial' }}
                        </h2>
                        <button wire:click="cancel" class="text-slate-400 hover:text-slate-600 text-xs font-bold px-2 py-1 bg-slate-100 dark:bg-slate-700 rounded-lg">✕ Cancel</button>
                    </div>

                    <form wire:submit.prevent="saveTestimonial" class="space-y-4">
                        <div>
                            <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Author Name *</label>
                            <input type="text" wire:model="author_name" placeholder="e.g. Joan F." class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-xl focus:outline-none focus:border-indigo-500">
                            @error('author_name') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Author Title / Role</label>
                            <input type="text" wire:model="author_title" placeholder="e.g. Verified Buyer / Company Owner" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-xl focus:outline-none focus:border-indigo-500">
                            @error('author_title') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Rating (1 to 5 Stars)</label>
                            <select wire:model="rating" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-xl focus:outline-none focus:border-indigo-500 font-bold">
                                <option value="5">⭐⭐⭐⭐⭐ (5 Stars)</option>
                                <option value="4">⭐⭐⭐⭐ (4 Stars)</option>
                                <option value="3">⭐⭐⭐ (3 Stars)</option>
                                <option value="2">⭐⭐ (2 Stars)</option>
                                <option value="1">⭐ (1 Star)</option>
                            </select>
                            @error('rating') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Testimonial Quote Content *</label>
                            <textarea wire:model="content" rows="4" placeholder="Enter customer testimonial text..." class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-xl focus:outline-none focus:border-indigo-500 resize-none"></textarea>
                            @error('content') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Avatar / Photo Image (Upload or CDN URL)</label>
                            <input type="text" wire:model="avatar_image" placeholder="https://cdn.example.com/avatar.jpg" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-xl focus:outline-none focus:border-indigo-500 mb-2">
                            <input type="file" wire:model="avatar_file" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700">
                            @if($avatar_image)
                                <div class="mt-2 flex items-center gap-2">
                                    <img src="{{ $avatar_image }}" alt="Preview" class="w-10 h-10 rounded-full object-cover border border-slate-200">
                                </div>
                            @endif
                            @error('avatar_image') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                            @error('avatar_file') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Company Name</label>
                                <input type="text" wire:model="company_name" placeholder="e.g. Aspire Properties" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-xl focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Company Link</label>
                                <input type="url" wire:model="company_link" placeholder="https://example.com" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-xl focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Display Sort Order</label>
                                <input type="number" wire:model="sort_order" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-xl focus:outline-none focus:border-indigo-500">
                            </div>
                            <div class="flex items-center pt-5">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model="is_active" class="rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4 bg-slate-50 border-slate-300">
                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Active / Published</span>
                                </label>
                            </div>
                        </div>

                        {{-- ─── Translations Section ──────────────────────────────────────────────── --}}
                        @if($activeLanguages->isNotEmpty() && $testimonialId)
                        <div x-data="{ tlOpen: false }" class="border-t border-slate-100 dark:border-slate-700 pt-4">
                            <button type="button" @click="tlOpen = !tlOpen"
                                    class="flex items-center justify-between w-full text-left">
                                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                                    Translations
                                </span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform" :class="tlOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="tlOpen" x-cloak class="mt-4 space-y-4">
                                {{-- Language selector pills --}}
                                <div class="flex flex-wrap gap-2">
                                    @foreach($activeLanguages as $lang)
                                        <button type="button"
                                                wire:click="selectTlLang({{ $lang->id }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border transition
                                                    {{ $tlLangId === $lang->id
                                                        ? 'bg-indigo-600 text-white border-indigo-600 shadow'
                                                        : 'bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-600 hover:border-indigo-400' }}">
                                            <span class="fi fi-{{ strtolower($lang->flag_emoji) }}" style="width:1em;height:0.75em;font-size:1rem;"></span>
                                            {{ $lang->name }}
                                        </button>
                                    @endforeach
                                </div>

                                @if($tlLangId > 0)
                                    <div class="space-y-3 bg-slate-50 dark:bg-slate-900/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
                                        {{-- per-field inputs --}}
                                        <div>
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Author Name (Default: "{{ $author_name }}")</label>
                                            <input type="text" wire:model="tlBuffer.author_name"
                                                   placeholder="Translation..."
                                                   class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-lg focus:outline-none focus:border-indigo-500">
                                        </div>
                                        <div>
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Author Title (Default: "{{ $author_title }}")</label>
                                            <input type="text" wire:model="tlBuffer.author_title"
                                                   placeholder="Translation..."
                                                   class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-lg focus:outline-none focus:border-indigo-500">
                                        </div>
                                        <div>
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Content (Default: "{{ strip_tags($content) }}")</label>
                                            <textarea wire:model="tlBuffer.content" rows="3"
                                                   placeholder="Translation..."
                                                   class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-lg focus:outline-none focus:border-indigo-500 resize-none"></textarea>
                                        </div>
                                        <div>
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Company Name (Default: "{{ $company_name }}")</label>
                                            <input type="text" wire:model="tlBuffer.company_name"
                                                   placeholder="Translation..."
                                                   class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs rounded-lg focus:outline-none focus:border-indigo-500">
                                        </div>

                                        <div class="flex gap-2 pt-1">
                                            <button type="button" wire:click="aiTlTestimonial({{ $testimonialId }})"
                                                    wire:loading.attr="disabled" wire:target="aiTlTestimonial({{ $testimonialId }})"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-violet-50 hover:bg-violet-100 text-violet-600 dark:bg-violet-900/30 dark:text-violet-400 text-xs font-bold rounded-lg transition disabled:opacity-60">
                                                <span wire:loading.remove wire:target="aiTlTestimonial({{ $testimonialId }})">
                                                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L9.09 9.09 2 12l7.09 2.91L12 22l2.91-7.09L22 12l-7.09-2.91L12 2z"/></svg>
                                                </span>
                                                <span wire:loading wire:target="aiTlTestimonial({{ $testimonialId }})" class="inline-flex">
                                                    <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                                </span>
                                                AI Translate All
                                            </button>
                                            <button type="button" wire:click="saveTlTestimonial({{ $testimonialId }})"
                                                    class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition">
                                                Save Translation
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="flex gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                            <button type="submit" class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md transition duration-150">
                                Save Testimonial
                            </button>
                            <button type="button" wire:click="cancel" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs rounded-xl transition duration-150">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
