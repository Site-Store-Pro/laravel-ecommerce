<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 bg-gradient-to-r from-slate-900 to-indigo-950 bg-clip-text text-transparent">
                    Modal Manager
                </h1>
                <p class="text-sm text-slate-500 mt-1">Manage popup and slide-in modal windows embedded via <code class="text-xs bg-slate-100 px-1.5 py-0.5 rounded font-mono">[plugin:modal id=N]</code> shortcode.</p>
            </div>
            <a href="{{ route('admin.modals.create') }}" wire:navigate
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl shadow-md shadow-indigo-100 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Modal
            </a>
        </div>

        <x-toast-alert />

        {{-- Search --}}
        <div class="mb-6">
            <div class="relative max-w-sm">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" wire:model.live.debounce.300ms="search"
                       placeholder="Search by title..."
                       class="w-full pl-9 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm text-slate-700 focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 shadow-sm">
            </div>
        </div>

        {{-- Delete Confirmation Modal --}}
        @if($confirmingDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
            <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-sm w-full space-y-4">
                <h3 class="text-lg font-bold text-slate-900">Delete Modal?</h3>
                <p class="text-sm text-slate-500">This action cannot be undone. All translations for this modal will also be deleted.</p>
                <div class="flex justify-end gap-3 pt-2">
                    <button wire:click="cancelDelete" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-700 text-sm font-semibold rounded-xl transition">
                        Cancel
                    </button>
                    <button wire:click="deleteModal" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-xl transition">
                        Delete
                    </button>
                </div>
            </div>
        </div>
        @endif

        {{-- Table --}}
        <div class="bg-white border border-slate-200/60 rounded-3xl shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                        <th class="py-3 px-5">Title</th>
                        <th class="py-3 px-4 hidden md:table-cell">Position</th>
                        <th class="py-3 px-4 hidden lg:table-cell">Auto-Open</th>
                        <th class="py-3 px-4 hidden lg:table-cell">Cookie Lifetime</th>
                        <th class="py-3 px-4">Shortcode</th>
                        <th class="py-3 px-4">Active</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($modals as $modal)
                    <tr class="hover:bg-slate-50/40 transition">
                        <td class="py-3.5 px-5">
                            <span class="font-semibold text-slate-800">{{ $modal->title }}</span>
                        </td>
                        <td class="py-3.5 px-4 hidden md:table-cell">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                {{ match($modal->position) {
                                    'center' => 'bg-indigo-50 text-indigo-700',
                                    'left'   => 'bg-violet-50 text-violet-700',
                                    'right'  => 'bg-emerald-50 text-emerald-700',
                                    'bottom' => 'bg-amber-50 text-amber-700',
                                    default  => 'bg-slate-100 text-slate-600',
                                } }}">
                                {{ ucfirst($modal->position) }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 hidden lg:table-cell">
                            @if($modal->auto_open)
                                <span class="text-emerald-600 font-bold text-xs">✓ Yes
                                    @if($modal->open_delay > 0)
                                        <span class="text-slate-400 font-normal">({{ $modal->open_delay }}ms)</span>
                                    @endif
                                </span>
                            @else
                                <span class="text-slate-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 hidden lg:table-cell text-slate-500 text-xs">
                            {{ $modal->cookie_lifetime === 0 ? 'Session only' : $modal->cookie_lifetime . ' days' }}
                        </td>
                        <td class="py-3.5 px-4">
                            <div x-data="{ copied: false }" class="flex items-center gap-2">
                                <code class="text-xs bg-slate-100 text-indigo-700 px-2 py-1 rounded-lg font-mono select-all hidden sm:inline">
                                    [plugin:modal id={{ $modal->id }}]
                                </code>
                                <button
                                    x-on:click="navigator.clipboard.writeText('[plugin:modal id={{ $modal->id }}]').then(()=>{copied=true;setTimeout(()=>copied=false,2000);})"
                                    class="inline-flex items-center gap-1 px-2 py-1 text-xs font-bold rounded-lg transition"
                                    :class="copied ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500 hover:bg-indigo-50 hover:text-indigo-600'"
                                    title="Copy shortcode">
                                    <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    <svg x-show="copied" x-cloak class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                                </button>
                            </div>
                        </td>
                        <td class="py-3.5 px-4">
                            <button wire:click="toggleActive({{ $modal->id }})"
                                    class="relative inline-flex h-5 w-9 items-center rounded-full transition
                                           {{ $modal->is_active ? 'bg-indigo-600' : 'bg-slate-300' }}">
                                <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition
                                             {{ $modal->is_active ? 'translate-x-4' : 'translate-x-1' }}"></span>
                            </button>
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.modals.edit', $modal->id) }}" wire:navigate
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition border border-indigo-100">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>
                                <button wire:click="confirmDelete({{ $modal->id }})"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-xl transition border border-rose-100">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <p class="text-slate-400 text-sm font-medium">No modals found.</p>
                                <a href="{{ route('admin.modals.create') }}" wire:navigate
                                   class="text-indigo-600 text-sm font-bold hover:underline">Create your first modal →</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($modals->hasPages())
        <div class="mt-6">
            {{ $modals->links() }}
        </div>
        @endif

    </div>
</div>
