<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.cms-forms.index') }}" wire:navigate
               class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition duration-150">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $form->name }}</h1>
                <p class="text-xs text-slate-400 mt-0.5">{{ $submissions->total() }} submission{{ $submissions->total() !== 1 ? 's' : '' }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.cms-forms.edit', $formId) }}" wire:navigate
               class="px-4 py-2 text-sm font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-2xl transition duration-150">
                Edit Form
            </a>
            <button wire:click="exportCsv"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-2xl shadow transition duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export CSV
            </button>
        </div>
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
               placeholder="Search submissions…"
               class="w-full max-w-sm px-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400 shadow-sm">
    </div>

    {{-- Submissions --}}
    <div class="space-y-4">
        @forelse($submissions as $sub)
            @php $data = $sub->data ?? []; @endphp
            <div x-data="{ open: false }" class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">

                {{-- Summary row --}}
                <div class="flex items-center gap-4 px-6 py-4 cursor-pointer select-none hover:bg-slate-50/50 transition-colors"
                     @click="open = !open">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="text-xs font-bold text-slate-500">
                                {{ $sub->submitted_at?->format('M j, Y \a\t g:i A') ?? '—' }}
                            </span>
                            @if($sub->ip_address)
                                <span class="text-[10px] font-mono text-slate-400 bg-slate-100 px-2 py-0.5 rounded-lg">{{ $sub->ip_address }}</span>
                            @endif
                        </div>
                        {{-- Preview of first 3 fields --}}
                        <div class="mt-1.5 flex flex-wrap gap-3">
                            @foreach($fields->take(3) as $field)
                                @php
                                    $val = $data[$field->id] ?? null;
                                    if (is_array($val)) $val = implode(', ', array_filter($val));
                                @endphp
                                @if($val)
                                    <span class="text-xs text-slate-600">
                                        <span class="font-bold text-slate-400">{{ $field->label }}:</span>
                                        {{ Str::limit($val, 60) }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <button type="button"
                                wire:click.stop="deleteSubmission({{ $sub->id }})"
                                wire:confirm="Delete this submission?"
                                class="px-3 py-1.5 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition duration-150">
                            Delete
                        </button>
                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                {{-- Expanded detail --}}
                <div x-show="open" x-collapse class="border-t border-slate-100 px-6 py-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($fields as $field)
                            @php
                                $val = $data[$field->id] ?? null;
                                if (is_array($val)) $val = implode(', ', array_filter($val));
                            @endphp
                            <div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">{{ $field->label }}</div>
                                @if($val !== null && $val !== '')
                                    <div class="text-sm text-slate-700 whitespace-pre-wrap">{{ $val }}</div>
                                @else
                                    <div class="text-sm text-slate-300 italic">No response</div>
                                @endif
                            </div>
                        @endforeach
                        <div class="sm:col-span-2 pt-3 border-t border-slate-100 flex gap-6 text-xs text-slate-400">
                            <span><span class="font-bold">Submitted:</span> {{ $sub->submitted_at?->format('Y-m-d H:i:s') }}</span>
                            @if($sub->ip_address)<span><span class="font-bold">IP:</span> {{ $sub->ip_address }}</span>@endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl border border-dashed border-slate-200 p-16 text-center">
                <div class="text-slate-300 mb-3">
                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <p class="text-slate-500 font-semibold">No submissions yet</p>
                <p class="text-slate-400 text-sm mt-1">Submissions will appear here once visitors complete the form.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($submissions->hasPages())
        <div class="mt-6">
            {{ $submissions->links() }}
        </div>
    @endif

</div>
