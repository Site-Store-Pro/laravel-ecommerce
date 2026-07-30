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
        {{-- Default: hardcoded SVG fallback --}}
        <span class="p-1.5 rounded-xl bg-gradient-to-tr from-indigo-500 to-violet-600 text-white shadow-md shadow-indigo-200 group-hover:shadow-indigo-300 group-hover:scale-105 transition-all duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </span>
    @endif
    <span class="font-extrabold text-lg tracking-tight bg-gradient-to-r from-slate-800 via-indigo-800 to-violet-800 bg-clip-text text-transparent group-hover:from-indigo-600 group-hover:to-violet-600 transition-all duration-300 truncate max-w-[130px] xs:max-w-[180px] sm:max-w-none">
        {{ $siteName }}
    </span>
</a>
