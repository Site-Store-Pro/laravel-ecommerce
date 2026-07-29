<div class="bg-white dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700 rounded-3xl p-6 shadow-sm space-y-2">
    <div class="text-3xs font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-3 mb-3">
        CMS Sections
    </div>

    <a href="{{ route('admin.cms-pages.index') }}" wire:navigate
       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.cms-pages.*') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 shadow-sm shadow-indigo-100/50 dark:shadow-none' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/40 hover:text-slate-900 dark:hover:text-slate-200' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <span>Pages</span>
    </a>

    <a href="{{ route('admin.cms-categories.index') }}" wire:navigate
       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.cms-categories.*') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 shadow-sm shadow-indigo-100/50 dark:shadow-none' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/40 hover:text-slate-900 dark:hover:text-slate-200' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
        </svg>
        <span>Categories</span>
    </a>

    <a href="{{ route('admin.cms-tags.index') }}" wire:navigate
       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.cms-tags.*') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 shadow-sm shadow-indigo-100/50 dark:shadow-none' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/40 hover:text-slate-900 dark:hover:text-slate-200' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <span>Tags</span>
    </a>

    <a href="{{ route('admin.cms-slideshows.index') }}" wire:navigate
       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.cms-slideshows.*') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 shadow-sm shadow-indigo-100/50 dark:shadow-none' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/40 hover:text-slate-900 dark:hover:text-slate-200' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <span>Slideshows</span>
    </a>

    <a href="{{ route('admin.cms-list-menus.index') }}" wire:navigate
       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.cms-list-menus.*') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 shadow-sm shadow-indigo-100/50 dark:shadow-none' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/40 hover:text-slate-900 dark:hover:text-slate-200' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <span>List Menus</span>
    </a>

    <a href="{{ route('admin.cms-downloads.index') }}" wire:navigate
       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.cms-downloads.*') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 shadow-sm shadow-indigo-100/50 dark:shadow-none' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/40 hover:text-slate-900 dark:hover:text-slate-200' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        <span>Downloads</span>
    </a>

    <a href="{{ route('admin.cms-embeds.index') }}" wire:navigate
       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.cms-embeds.*') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 shadow-sm shadow-indigo-100/50 dark:shadow-none' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/40 hover:text-slate-900 dark:hover:text-slate-200' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
        </svg>
        <span>Code Embeds</span>
    </a>

    <a href="{{ route('admin.cms-forms.index') }}" wire:navigate
       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.cms-forms.*') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 shadow-sm shadow-indigo-100/50 dark:shadow-none' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/40 hover:text-slate-900 dark:hover:text-slate-200' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
        </svg>
        <span>Forms</span>
    </a>

    <a href="{{ route('admin.kb.index') }}" wire:navigate
       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.kb.*') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 shadow-sm shadow-indigo-100/50 dark:shadow-none' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/40 hover:text-slate-900 dark:hover:text-slate-200' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
        <span>KB Docs</span>
    </a>

    <a href="{{ route('admin.nav-builder.index') }}" wire:navigate
       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.nav-builder.*') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 shadow-sm shadow-indigo-100/50 dark:shadow-none' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/40 hover:text-slate-900 dark:hover:text-slate-200' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
        </svg>
        <span>Navigation Builder</span>
    </a>

    <a href="{{ route('admin.cms-header-footer.index') }}" wire:navigate
       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.cms-header-footer.*') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 shadow-sm shadow-indigo-100/50 dark:shadow-none' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/40 hover:text-slate-900 dark:hover:text-slate-200' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
        </svg>
        <span>Header &amp; Footer Builder</span>
    </a>

    <a href="{{ route('admin.testimonials.index') }}" wire:navigate
       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.testimonials.*') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 shadow-sm shadow-indigo-100/50 dark:shadow-none' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/40 hover:text-slate-900 dark:hover:text-slate-200' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
        </svg>
        <span>Testimonials</span>
    </a>

    {{-- ── Page Labels — dynamic text override manager ─────────────────────── --}}
    <a href="{{ route('admin.site-labels.index') }}" wire:navigate
       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.site-labels.*') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 shadow-sm shadow-indigo-100/50 dark:shadow-none' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/40 hover:text-slate-900 dark:hover:text-slate-200' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
        </svg>
        <span>Page Labels</span>
    </a>

    <a href="{{ route('admin.languages.index') }}" wire:navigate
       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('admin.languages.*') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 shadow-sm shadow-indigo-100/50 dark:shadow-none' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/40 hover:text-slate-900 dark:hover:text-slate-200' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>Languages</span>
    </a>
</div>
