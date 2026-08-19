<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth bg-slate-50 text-slate-800">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="theme-color" content="#f8fafc">
        <title>Tag: {{ $tag->name }} | {{ config('app.name', 'Support Desk') }}</title>
        <meta name="description" content="Browse all articles and posts tagged with {{ $tag->name }}.">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        {{-- Swiper 11 Bundle (Global Slider Engine for Display Plugins) --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        {{-- flag-icons: CSS-based flag rendering for language switcher --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icons@7.2.3/css/flag-icons.min.css">
        <x-site-theme-styles />
        <x-header-footer-styles />
        <x-site-google-analytics-loader />
    </head>
    <body class="antialiased font-sans bg-slate-50 text-slate-800 overflow-x-hidden selection:bg-indigo-500 selection:text-white">
        <!-- Background Glows -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[-20%] left-[-10%] w-[60vw] h-[60vw] rounded-full bg-gradient-to-tr from-indigo-200/30 to-violet-200/20 blur-3xl opacity-60"></div>
            <div class="absolute top-[40%] right-[-15%] w-[50vw] h-[50vw] rounded-full bg-gradient-to-br from-indigo-100/30 to-purple-100/20 blur-3xl opacity-50"></div>
        </div>

        <div class="min-h-[100dvh] flex flex-col justify-between relative">
            <livewire:public-navigation />

            <!-- Main Content Area -->
            <main class="flex-1 py-16 lg:py-24">
                <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
                    
                    <!-- Tag Header Info -->
                    <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-violet-50 text-violet-700 uppercase tracking-widest">
                            @label('cms.tag_archives', 'Tag Archives')
                        </span>
                        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900 leading-snug pb-2 bg-gradient-to-r from-slate-900 to-indigo-950 bg-clip-text text-transparent">
                            #{{ $tag->name }}
                        </h1>
                        <p class="text-base text-slate-500 leading-relaxed font-medium">
                            Browse all content and updates tagged under <span class="text-violet-600 font-semibold">#{{ $tag->name }}</span>.
                        </p>
                    </div>

                    <!-- Tag Records Grid -->
                    @if($pages->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach($pages as $post)
                                <article class="group bg-white/95 backdrop-blur-md border border-slate-100/80 rounded-3xl overflow-hidden shadow-xl shadow-slate-100/40 hover:shadow-2xl hover:shadow-slate-200/50 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                                    <div>
                                        <!-- Header Image Thumbnail or premium gradient fallback -->
                                        <a href="{{ route('page.show', $post->slug) }}" class="block relative aspect-[16/10] overflow-hidden bg-slate-100">
                                            @if($post->header_image)
                                                <img src="{{ $post->headerImageUrl() }}" 
                                                     alt="{{ $post->title }}" 
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center opacity-85">
                                                    <svg class="w-12 h-12 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 012 2v6a2 2 0 01-2 2h-2m-4-3H9m3 3H9m12-6a2 2 0 00-2-2h-2m2 2v4a2 2 0 002 2H5a2 2 0 01-2-2v-6a2 2 0 012-2h2"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            @if(!$post->is_active)
                                                <span class="absolute top-4 right-4 px-3 py-1 bg-amber-500 text-white font-extrabold text-2xs uppercase tracking-wider rounded-lg shadow-sm">Draft</span>
                                            @endif
                                        </a>

                                        <!-- Content Body -->
                                        <div class="p-6 md:p-8 space-y-4">
                                            <div class="flex items-center gap-2.5 flex-wrap">
                                                @foreach($post->tags as $t)
                                                    <span class="text-2xs font-extrabold {{ $t->id === $tag->id ? 'bg-violet-100 text-violet-700' : 'bg-slate-50 text-slate-500' }} px-2 py-0.5 rounded-md uppercase tracking-wider">#{{ $t->name }}</span>
                                                @endforeach
                                            </div>

                                            <h3 class="text-xl font-bold text-slate-900 leading-tight group-hover:text-violet-600 transition-colors">
                                                <a href="{{ route('page.show', $post->slug) }}">{{ $post->title }}</a>
                                            </h3>

                                            <div class="text-sm text-slate-500 line-clamp-3 font-medium">
                                                {!! strip_tags($post->content) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Meta Footer info -->
                                    <div class="px-6 md:px-8 pb-6 md:pb-8 pt-4 border-t border-slate-50/60 flex items-center justify-between text-2xs text-slate-400 font-bold uppercase tracking-wider">
                                        @if($post->show_author)
                                            <div class="flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                <span>{{ $post->custom_author ?: ($post->author?->name ?? 'Site Manager') }}</span>
                                            </div>
                                        @endif

                                        @if($post->show_date)
                                            <div class="flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                                <span>{{ $post->created_at->format('M d, Y') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white border border-slate-100 rounded-3xl p-12 text-center max-w-xl mx-auto shadow-sm space-y-4">
                            <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center mx-auto">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v2m0 0H2"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900">@label('cms.no_content', 'No content available')</h3>
                            <p class="text-sm text-slate-400 font-medium">There are currently no active or public posts tagged with #{{ $tag->name }}.</p>
                        </div>
                    @endif

                </div>
            </main>

            <!-- Footer -->
            <livewire:public-footer />
        </div>
    </body>
</html>
