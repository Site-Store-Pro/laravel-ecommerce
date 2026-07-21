<div class="pt-8 mt-8 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h4 class="font-bold text-slate-800 text-sm">Was this page helpful?</h4>
        <p class="text-xs text-slate-400 mt-1">Let us know how we can improve our content.</p>
    </div>
    <div class="flex items-center gap-2" x-data="{ rated: @entangle('hasVoted'), helpful: true }">
        <div x-show="!rated" class="flex gap-2">
            <button wire:click="ratePage(1)" @click="helpful = true"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all font-semibold text-xs">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>
                Yes, thanks!
            </button>
            <button wire:click="ratePage(-1)" @click="helpful = false"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all font-semibold text-xs">
                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.737 3h4.018c.163 0 .326.02.485.06L17 4m-7 10V9a2 2 0 002-2h.095c.5 0 .905-.405.905-.905 0-.714.211-1.412.608-2.006L17 13v-9m-7 10h2M17 4h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5"/></svg>
                No, it didn't help
            </button>
        </div>
        <div x-show="rated" x-cloak class="text-xs font-semibold text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-xl px-4 py-2.5 flex items-center gap-1.5">
            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span x-text="helpful ? 'Thank you for your positive feedback!' : 'Thank you for your feedback!'"></span>
        </div>
    </div>
</div>
