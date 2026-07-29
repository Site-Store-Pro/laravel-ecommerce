<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-10">
    {{-- Scoped Styles moved to app.css --}}

    {{-- Breadcrumb Navigation --}}
    <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-8">
        <a href="{{ route('kb.index') }}" wire:navigate class="hover:text-indigo-600 transition-colors flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            @label('kb.breadcrumb', 'Knowledge Base')
        </a>
        <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-600 truncate max-w-xs">{{ $article->title }}</span>
    </nav>

    {{-- Main Article Card --}}
    <article class="bg-white border border-slate-200/80 rounded-3xl p-8 md:p-12 shadow-sm space-y-8">
        {{-- Header metadata --}}
        <div class="space-y-4 pb-6 border-b border-slate-100">
            @if($article->category)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                    {{ $article->category->name }}
                </span>
            @endif

            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight">
                {{ $article->title }}
            </h1>

            @if($article->show_date ?? true)
            <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-xs text-slate-400 font-medium">
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>@label('kb.published', 'Published') {{ $article->date_added ?? $article->created_at?->format('M d, Y') }}</span>
                </div>
                @if($article->date_modified && $article->date_modified !== $article->date_added)
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.22"/></svg>
                        <span>@label('kb.updated', 'Updated') {{ $article->date_modified }}</span>
                    </div>
                @endif
            </div>
            @endif
        </div>

        {{-- Markdown Rendered Content --}}
        <div class="kb-article-content text-slate-700 text-base leading-relaxed">
            {!! $article->article_content !!}
        </div>

        {{-- Helpful Rating UI (Wow component) --}}
        <div class="pt-8 mt-8 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h4 class="font-bold text-slate-800 text-sm">@label('kb.helpful_heading', 'Was this article helpful?')</h4>
                <p class="text-xs text-slate-400 mt-1">@label('kb.helpful_message', 'Let us know how we can improve our documentation.')</p>
            </div>
            <div class="flex items-center gap-2" x-data="{ rated: @entangle('hasVoted'), helpful: true }">
                <div x-show="!rated" class="flex gap-2">
                    <button wire:click="rateArticle(1)" @click="helpful = true"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-all">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>
                        @label('kb.helpful_yes', 'Yes, thanks!')
                    </button>
                    <button wire:click="rateArticle(-1)" @click="helpful = false"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-all">
                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.737 3h4.018c.163 0 .326.02.485.06L17 4m-7 10V9a2 2 0 002-2h.095c.5 0 .905-.405.905-.905 0-.714.211-1.412.608-2.006L17 13v-9m-7 10h2M17 4h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5"/></svg>
                        @label('kb.helpful_no', 'No, it didn\'t help')
                    </button>
                </div>
                <div x-show="rated" x-cloak class="text-xs font-semibold text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-xl px-4 py-2.5 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span x-text="helpful ? '{{ siteLabel('kb.thanks_positive', 'Thank you for your positive feedback!') }}' : '{{ siteLabel('kb.thanks_improve', 'Thank you! We will update this guide soon.') }}'"></span>
                </div>
            </div>
        </div>
    </article>

    {{-- Back to Knowledge Base Link --}}
    <div class="mt-8 text-center">
        <a href="{{ route('kb.index') }}" wire:navigate class="inline-flex items-center gap-1 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            @label('kb.back_to_all', 'Back to All Articles')
        </a>
    </div>
</div>
