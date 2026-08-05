@php
    $logo = \App\Models\CmsSetting::resolveLogoUrl();
    $siteName = \App\Models\CmsSetting::getSiteName();
@endphp

<a href="/" class="inline-flex items-center gap-1 group w-auto max-w-max min-w-0 shrink-0 my-auto py-0.5">
    @if($logo['type'] === 'url')
        <img src="{{ $logo['value'] }}" alt="{{ $siteName }} Logo"
             class="h-8 w-auto object-contain group-hover:opacity-90 transition-opacity">
    @elseif($logo['type'] === 'svg')
        <span class="text-slate-800 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{!! $logo['value'] !!}</span>
    @else
        {{-- Default fallback SVG icon (snazzy ribbon emblem without box) using menu label color --}}
        <span class="p-1 rounded-xl text-slate-800 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors flex items-center justify-center shrink-0" style="color: var(--nav-text, currentColor);">
            <svg class="w-6 h-6 shrink-0 transition-transform duration-300 group-hover:scale-105" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14a5 5 0 100-10 5 5 0 000 10zM8.5 13.5L6.5 21 12 18.5 17.5 21l-2-7.5M12 7.5v3m-1.5-1.5h3" />
            </svg>
        </span>
    @endif
    <span class="site-logo-title font-extrabold text-lg tracking-tight text-slate-900 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-300 truncate max-w-[130px] xs:max-w-[180px] sm:max-w-none">
        {{ $siteName }}
    </span>
</a>
