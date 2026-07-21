<div class="relative w-full {{ $isLanding ? 'max-w-xl mx-auto' : '' }}">
    <form wire:submit="submit">
        <span class="absolute inset-y-0 left-0 flex items-center ps-4 text-slate-400 pointer-events-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </span>
        <input wire:model.live.debounce.300ms="query" id="kb-search" type="search"
               class="block w-full ps-11 pe-4 {{ $isLanding ? 'py-3.5 border-slate-200/80 bg-white text-slate-800' : 'py-2 border-white/10 bg-white/5 text-slate-100 placeholder-slate-400 focus:bg-white/10 focus:border-indigo-500' }} rounded-2xl border focus:ring-indigo-500 text-sm shadow-sm transition-colors"
               placeholder="{{ $isLanding ? 'Type keywords (e.g. password, billing, attachment)...' : 'Search knowledge base...' }}" />
    </form>
</div>
