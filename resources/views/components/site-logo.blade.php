@php
    $logo = \App\Models\CmsSetting::resolveLogoUrl();
    $siteName = \App\Models\CmsSetting::getSiteName();
@endphp

<a href="/" class="inline-flex items-center gap-2 group w-auto max-w-max min-w-0 shrink-0 my-auto py-0.5">
    @if($logo['type'] === 'url')
        <img src="{{ $logo['value'] }}" alt="{{ $siteName }} Logo"
             class="h-8 w-auto object-contain group-hover:opacity-90 transition-opacity">
    @elseif($logo['type'] === 'svg')
        <span class="group-hover:opacity-90 transition-opacity">{!! $logo['value'] !!}</span>
    @else
        {{-- Default: hardcoded gift package SVG fallback using primary accent color --}}
        <span class="p-1.5 rounded-xl text-white shadow-md group-hover:scale-105 transition-all duration-300 flex items-center justify-center shrink-0" style="background-color: var(--primary-accent-color, #026C80);">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V6a2 2 0 10-2 2h2zm-7 8h14M5 8h14a1 1 0 011 1v11a1 1 0 01-1 1H5a1 1 0 01-1-1V9a1 1 0 011-1z"/>
            </svg>
        </span>
    @endif
    <span class="site-logo-title font-extrabold text-lg tracking-tight bg-gradient-to-r from-slate-800 via-indigo-800 to-violet-800 bg-clip-text text-transparent group-hover:from-indigo-600 group-hover:to-violet-600 transition-all duration-300 truncate max-w-[130px] xs:max-w-[180px] sm:max-w-none">
        {{ $siteName }}
    </span>
</a>
