<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-10">
    <div class="bg-white border border-slate-200/80 rounded-[2rem] p-6 sm:p-10 md:p-14 shadow-sm">
        {{-- Hero/Search Section --}}
        <div class="text-center max-w-3xl mx-auto mb-16 space-y-6">
        <h1 class="text-4xl font-extrabold tracking-tight bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 bg-clip-text text-transparent sm:text-5xl pb-2 leading-snug">
            @label('kb.help_heading', 'How can we help you today?')
        </h1>
        <p class="text-base text-slate-500 max-w-2xl mx-auto leading-relaxed">
            @label('kb.help_message', 'Search our knowledge base for guides, FAQs, and step-by-step instructions to solve support queries quickly.')
        </p>
        
        {{-- Search Input --}}
        <div class="max-w-2xl mx-auto">
            <livewire:kb-search-bar :is-landing="true" :query="$search" />
        </div>
    </div>

    {{-- Categories & Nested Articles Section (Only show if not searching) --}}
    @if(!$search)
        <div class="mb-16">
            <h2 class="text-2xl font-bold text-slate-800 mb-8">@label('kb.browse_by_category', 'Browse by Category')</h2>
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($categories as $category)
                    @if($category->articles->isNotEmpty())
                    <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm hover:shadow-md transition-shadow">
                        <h3 class="text-lg font-bold text-slate-800 mb-4 border-b border-slate-100 pb-3 flex items-center gap-2">
                            <span class="inline-flex items-center justify-center p-1.5 rounded-lg bg-indigo-50 text-indigo-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                            </span>
                            {{ $category->name }}
                        </h3>
                        <ul class="space-y-3">
                            @foreach($category->articles as $article)
                                <li>
                                    <a href="{{ route('kb.show', $article->seo_link) }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors flex items-start gap-2">
                                        <svg class="w-4 h-4 text-slate-300 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        {{ $article->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    {{-- Articles Grid --}}
    <div class="space-y-8">
        <div class="flex items-center justify-between pb-4 border-b border-slate-200/60">
            <h2 class="text-xl font-bold text-slate-800">
                @if($search)
                    @label('kb.search_results', 'Search Results')
                @else
                    @label('kb.popular_articles', 'Top 15 Most Popular Articles')
                @endif
            </h2>
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider bg-slate-100 px-2.5 py-1 rounded-xl">
                {{ $articles->total() }} {{ Str::plural('Article', $articles->total()) }}
            </span>
        </div>

        @if($articles->isEmpty())
            <div class="bg-white border border-slate-200/70 rounded-3xl p-12 text-center max-w-lg mx-auto shadow-sm">
                <div class="h-12 w-12 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="font-bold text-slate-800 text-lg">@label('kb.no_articles', 'No articles found')</h3>
                <p class="text-slate-500 text-sm mt-2 leading-relaxed">
                    @label('kb.no_match_message', 'We couldn\'t find any articles matching') "{{ $search }}". Try using different keywords or categories.
                </p>
                <button wire:click="$set('search', '')" class="mt-6 text-sm font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 mx-auto transition-colors">
                    @label('kb.clear_search', 'Clear Search Filter')
                </button>
            </div>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($articles as $article)
                    <a href="{{ route('kb.show', $article->seo_link) }}"
                       class="group bg-white border border-slate-200/60 rounded-3xl p-6 hover:shadow-md hover:border-slate-300 transition-all flex flex-col justify-between shadow-sm relative overflow-hidden">
                        
                        <div class="space-y-4">
                            {{-- Category Badge --}}
                            @if($article->category)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-600">
                                    {{ $article->category->name }}
                                </span>
                            @endif

                            <div class="space-y-2">
                                <h3 class="text-lg font-bold text-slate-800 group-hover:text-indigo-600 transition-colors leading-snug">
                                    {{ $article->title }}
                                </h3>
                                <p class="text-sm text-slate-500 line-clamp-3 leading-relaxed">
                                    @if($article->meta_description)
                                        {{ $article->meta_description }}
                                    @else
                                        {{ strip_tags($article->article_content) }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="pt-6 mt-6 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                            <span>
                                @if($article->show_date ?? true)
                                    @label('kb.added', 'Added') {{ $article->date_added ?? $article->created_at?->format('M d, Y') }}
                                @endif
                            </span>
                            <span class="font-semibold text-indigo-600 group-hover:translate-x-1.5 transition-transform flex items-center gap-1">
                                @label('kb.read_article', 'Read Article')
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>

            @if ($articles->hasPages())
                <div class="pt-10 border-t border-slate-200/60">
                    {{ $articles->links() }}
                </div>
            @endif
        @endif
        </div>
    </div>
</div>
