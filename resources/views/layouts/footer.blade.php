@php
    $isDark = ($theme ?? 'light') === 'dark';
@endphp
<footer class="border-t py-8 text-xs relative z-10 {{ $isDark ? 'border-white/5 bg-transparent text-slate-400' : 'border-slate-200/60 bg-white/40 text-slate-500' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
        <p>&copy; {{ date('Y') }} {{ \App\Models\CmsSetting::getSiteName() }}. All rights reserved.</p>
        <div class="flex items-center gap-6">
            <a href="{{ url('/') }}" class="hover:underline transition-colors {{ $isDark ? 'hover:text-white' : 'hover:text-slate-800' }}">Home</a>
            <a href="{{ route('kb.index') }}" wire:navigate class="hover:underline transition-colors font-medium {{ $isDark ? 'hover:text-white' : 'hover:text-slate-800' }}">Knowledge Base</a>
            @auth
                <a href="{{ route('dashboard') }}" wire:navigate class="hover:underline transition-colors {{ $isDark ? 'hover:text-white' : 'hover:text-slate-800' }}">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="hover:underline transition-colors {{ $isDark ? 'hover:text-white' : 'hover:text-slate-800' }}">Sign In</a>
            @endauth
        </div>
    </div>
</footer>
