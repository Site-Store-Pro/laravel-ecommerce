<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-slate-50 text-slate-800">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ !empty($exception->getMessage()) ? $exception->getMessage() : 'Link Expired' }} | {{ config('app.name', 'Store') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 bg-slate-50 selection:bg-indigo-500 selection:text-white">
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-20%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-gradient-to-tr from-rose-200/40 to-indigo-200/30 blur-3xl opacity-60"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[50vw] h-[50vw] rounded-full bg-gradient-to-br from-amber-100/40 to-rose-100/30 blur-3xl opacity-50"></div>
    </div>

    <div class="relative z-10 w-full max-w-lg bg-white border border-slate-100 rounded-3xl p-8 sm:p-10 shadow-xl shadow-slate-200/50 text-center space-y-6">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 shadow-inner">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <div class="space-y-2">
            <span class="inline-block px-3 py-1 bg-rose-100 text-rose-800 text-[11px] font-black uppercase tracking-wider rounded-full">
                410 &bull; Link Expired
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                {{ !empty($exception->getMessage()) ? $exception->getMessage() : 'Content Link Expired' }}
            </h1>
            <p class="text-sm text-slate-500 max-w-sm mx-auto leading-relaxed">
                This content access link is no longer active. If you purchased this digital product, please log in to your customer dashboard or contact customer support.
            </p>
        </div>

        <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ url('/') }}" class="w-full sm:w-auto px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-500/20 transition-all text-center">
                Return to Store
            </a>
            @if(Route::has('dashboard'))
                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition text-center">
                    My Account
                </a>
            @endif
        </div>
    </div>
</body>
</html>