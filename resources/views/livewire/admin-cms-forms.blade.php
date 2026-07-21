<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Forms</h1>
            <p class="mt-1 text-sm text-slate-500">Build unlimited embeddable forms. Use <code class="bg-slate-100 px-1.5 py-0.5 rounded text-xs font-mono">[cms-form id=N]</code> to embed any form.</p>
        </div>
        <a href="{{ route('admin.cms-forms.create') }}" wire:navigate
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl shadow transition duration-150">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Form
        </a>
    </div>

    {{-- Status flash --}}
    @if(session()->has('status'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-800 text-sm font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('status') }}
        </div>
    @endif

    {{-- Search --}}
    <div class="mb-6">
        <input type="text" wire:model.live.debounce.300ms="search"
               placeholder="Search forms…"
               class="w-full max-w-sm px-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400 shadow-sm">
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-left font-bold text-slate-500 uppercase tracking-wider text-xs">Name / Slug</th>
                    <th class="px-6 py-4 text-left font-bold text-slate-500 uppercase tracking-wider text-xs">Shortcode</th>
                    <th class="px-4 py-4 text-center font-bold text-slate-500 uppercase tracking-wider text-xs">Fields</th>
                    <th class="px-4 py-4 text-center font-bold text-slate-500 uppercase tracking-wider text-xs">Submissions</th>
                    <th class="px-4 py-4 text-center font-bold text-slate-500 uppercase tracking-wider text-xs">Active</th>
                    <th class="px-6 py-4 text-right font-bold text-slate-500 uppercase tracking-wider text-xs">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($forms as $form)
                    <tr class="hover:bg-slate-50/50 transition-colors duration-100">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $form->name }}</div>
                            <div class="text-xs text-slate-400 font-mono mt-0.5">{{ $form->slug }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div x-data="{ copied: false }"
                                 @click="navigator.clipboard.writeText('[cms-form id={{ $form->id }}]'); copied = true; setTimeout(() => copied = false, 1500)"
                                 class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-slate-100 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-200 rounded-xl cursor-pointer transition duration-150 group">
                                <code class="text-xs font-mono text-slate-700 group-hover:text-indigo-700">[cms-form id={{ $form->id }}]</code>
                                <svg x-show="!copied" class="w-3 h-3 text-slate-400 group-hover:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <svg x-show="copied" x-cloak class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="text-slate-700 font-semibold">{{ $form->fields_count }}</span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($form->submissions_count > 0)
                                <a href="{{ route('admin.cms-forms.submissions', $form->id) }}" wire:navigate
                                   class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-xl text-xs font-bold hover:bg-indigo-100 transition duration-150">
                                    {{ $form->submissions_count }}
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @else
                                <span class="text-slate-400 text-xs">0</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            <button wire:click="toggleActive({{ $form->id }})"
                                    class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors duration-200 focus:outline-none {{ $form->is_active ? 'bg-indigo-600' : 'bg-slate-200' }}">
                                <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow transition-transform duration-200 {{ $form->is_active ? 'translate-x-4.5' : 'translate-x-0.5' }}"></span>
                            </button>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.cms-forms.edit', $form->id) }}" wire:navigate
                                   class="px-3 py-1.5 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition duration-150">
                                    Edit
                                </a>
                                <button wire:click="duplicateForm({{ $form->id }})"
                                        class="px-3 py-1.5 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition duration-150">
                                    Duplicate
                                </button>
                                <button wire:click="deleteForm({{ $form->id }})"
                                        wire:confirm="Delete '{{ addslashes($form->name) }}' and all its submissions?"
                                        class="px-3 py-1.5 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition duration-150">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="text-slate-300 mb-3">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <p class="text-slate-500 font-semibold text-sm">No forms yet</p>
                            <p class="text-slate-400 text-xs mt-1">Click "New Form" to create your first form.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($forms->hasPages())
        <div class="mt-6">
            {{ $forms->links() }}
        </div>
    @endif

</div>
