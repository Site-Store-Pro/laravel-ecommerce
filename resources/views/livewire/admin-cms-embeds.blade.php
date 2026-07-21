<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Code &amp; Video Embeds</h1>
                <p class="text-sm text-slate-500 mt-0.5">
                    Reusable HTML &amp; video embed snippets inserted into pages via shortcode —
                    never edited by TinyMCE.
                </p>
            </div>
            <a href="{{ route('admin.cms-embeds.create') }}" wire:navigate
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-2xl shadow-md hover:bg-indigo-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Embed
            </a>
        </div>

        {{-- Flash --}}
        @if(session()->has('status'))
            <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center gap-3 text-emerald-800 text-sm font-semibold">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        {{-- Filters --}}
        <div class="bg-white border border-slate-100 rounded-3xl p-5 shadow-sm">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                    </svg>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by name…"
                           class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl text-sm focus:outline-none focus:border-indigo-400">
                </div>
                <select wire:model.live="filterType"
                        class="px-4 py-2 bg-slate-50 border border-slate-200 text-slate-700 rounded-xl text-sm focus:outline-none focus:border-indigo-400">
                    <option value="">All Types</option>
                    <option value="0">YouTube</option>
                    <option value="1">Vimeo</option>
                    <option value="2">Other HTML</option>
                </select>
                <select wire:model.live="filterActive"
                        class="px-4 py-2 bg-slate-50 border border-slate-200 text-slate-700 rounded-xl text-sm focus:outline-none focus:border-indigo-400">
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white border border-slate-100 rounded-3xl shadow-sm overflow-hidden">
            @if($embeds->count() > 0)
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Type</th>
                            <th class="px-4 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Shortcode</th>
                            <th class="px-4 py-4 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Active</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($embeds as $embed)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                {{-- Name --}}
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.cms-embeds.edit', $embed->id) }}" wire:navigate
                                       class="font-semibold text-slate-800 hover:text-indigo-600 transition">
                                        {{ $embed->name }}
                                    </a>
                                </td>

                                {{-- Type badge --}}
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $embed->typeBadgeColor() }}">
                                        {{ $embed->typeLabel() }}
                                    </span>
                                </td>

                                {{-- Shortcode --}}
                                <td class="px-4 py-4">
                                    <code class="text-xs bg-slate-100 text-slate-700 px-2 py-1 rounded-lg font-mono select-all">
                                        {{ $embed->shortcode() }}
                                    </code>
                                </td>

                                {{-- Active toggle --}}
                                <td class="px-4 py-4 text-center">
                                    <button wire:click="toggleActive({{ $embed->id }})"
                                            class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors
                                                   {{ $embed->is_active ? 'bg-emerald-500' : 'bg-slate-200' }}">
                                        <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow transition-transform
                                                     {{ $embed->is_active ? 'translate-x-4.5' : 'translate-x-0.5' }}"></span>
                                    </button>
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('admin.cms-embeds.edit', $embed->id) }}" wire:navigate
                                           class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition"
                                           title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <button wire:click="deleteEmbed({{ $embed->id }})"
                                                wire:confirm="Delete '{{ $embed->name }}'? This cannot be undone."
                                                class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition"
                                                title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($embeds->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $embeds->links() }}
                    </div>
                @endif

            @else
                {{-- Empty state --}}
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-16 h-16 bg-indigo-50 rounded-3xl flex items-center justify-center mb-5">
                        <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">No code embeds yet</h3>
                    <p class="text-sm text-slate-500 mb-6 max-w-sm">
                        Add your first reusable HTML or video embed snippet. Each embed gets a shortcode you can
                        place in any CMS page, product description, or list menu item.
                    </p>
                    <a href="{{ route('admin.cms-embeds.create') }}" wire:navigate
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-2xl shadow-md hover:bg-indigo-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add First Embed
                    </a>
                </div>
            @endif
        </div>

        {{-- Help callout --}}
        <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-5 flex gap-4">
            <svg class="w-5 h-5 text-indigo-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-sm text-indigo-800">
                <p class="font-bold mb-1">How code embeds work</p>
                <p>Place <code class="bg-indigo-100 px-1.5 py-0.5 rounded font-mono text-xs">[code-embed:{id}]</code>
                   anywhere in a CMS page, product description, or list menu item. YouTube and Vimeo embeds are
                   automatically wrapped in a responsive 16:9 container. Other HTML is output verbatim.
                   The embed code is never exposed to TinyMCE — update the embed once here and every page
                   that references it updates automatically.</p>
            </div>
        </div>

    </div>
</div>
