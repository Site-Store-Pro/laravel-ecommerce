<div @if($languages->count() < 2) style="display:none" @endif>
@if($languages->count() > 1)
<div class="relative" x-data="{ open: false }" @click.away="open = false">

    {{-- Trigger button --}}
    <button @click="open = !open"
            type="button"
            class="flex items-center gap-1.5 px-1.5 sm:px-2.5 py-1 sm:py-1.5 max-[500px]:px-1 max-[500px]:py-1 max-[500px]:gap-0.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700/60 transition-colors focus:outline-none"
            aria-label="Switch language">
        <span class="fi fi-{{ strtolower($current->flag_emoji) }} rounded-sm text-base" style="width:1.25em;height:0.95em;"></span>
        <span class="hidden sm:inline text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300">{{ $current->code }}</span>
        <svg class="w-3 h-3 text-slate-400 transition-transform" :class="{ 'rotate-180': open }"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    {{-- Dropdown --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
         class="absolute right-0 mt-2 w-44 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-xl z-50 overflow-hidden py-1"
         style="display:none;">
        @foreach($languages as $lang)
            <button wire:click="switchLanguage('{{ $lang->code }}')"
                    type="button"
                    @click="open = false"
                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-semibold hover:bg-slate-50 dark:hover:bg-slate-700/60 transition-colors
                           {{ $lang->id === $current->id ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/60 dark:bg-indigo-900/20' : 'text-slate-700 dark:text-slate-200' }}">
                <span class="fi fi-{{ strtolower($lang->flag_emoji) }} rounded-sm text-base flex-shrink-0" style="width:1.25em;height:0.95em;"></span>
                <span>{{ $lang->native_name }}</span>
                @if($lang->id === $current->id)
                    <svg class="w-3.5 h-3.5 ml-auto text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                @endif
            </button>
        @endforeach
    </div>
</div>
@endif
</div>
