@section('title', 'Set Your Password')

<div class="min-h-[70vh] flex items-center justify-center px-6 py-16 bg-gradient-to-br from-slate-50 via-white to-indigo-50/30">
    <div class="max-w-md w-full space-y-6">

        {{-- Header --}}
        <div class="text-center">
            <div class="mx-auto w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-indigo-200">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Set Your Password</h1>
            <p class="mt-2 text-sm text-slate-500 leading-relaxed max-w-sm mx-auto">
                Your email address has been verified. Choose a password to complete your account activation.
            </p>
        </div>

        {{-- Verified notice --}}
        <div class="flex items-start gap-3 bg-teal-50 border border-teal-200 rounded-2xl px-4 py-3.5 text-left">
            <svg class="w-5 h-5 text-teal-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <div class="text-sm text-teal-800 leading-snug">
                <strong class="block mb-0.5">Email verified — you're almost done.</strong>
                Set a password below and you'll have full access to your account dashboard, orders, and downloads.
            </div>
        </div>

        {{-- Form card --}}
        <div class="bg-white border border-slate-100 rounded-3xl shadow-xl shadow-slate-100/50 p-8">
            <form wire:submit="save" class="space-y-5">

                {{-- Password --}}
                <div>
                    <label for="gsp-password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                        New Password
                    </label>
                    <input wire:model="password"
                           id="gsp-password"
                           type="password"
                           autocomplete="new-password"
                           placeholder="At least 8 characters"
                           class="w-full px-4 py-3 border @error('password') border-rose-400 bg-rose-50 @else border-slate-200 bg-slate-50 @enderror rounded-2xl text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" />
                    @error('password')
                        <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="gsp-confirm" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                        Confirm Password
                    </label>
                    <input wire:model="password_confirmation"
                           id="gsp-confirm"
                           type="password"
                           autocomplete="new-password"
                           placeholder="Re-enter your password"
                           class="w-full px-4 py-3 border @error('password_confirmation') border-rose-400 bg-rose-50 @else border-slate-200 bg-slate-50 @enderror rounded-2xl text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" />
                    @error('password_confirmation')
                        <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="w-full bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 disabled:opacity-60 text-white font-bold px-6 py-3.5 rounded-2xl shadow-md shadow-indigo-200 hover:shadow-lg hover:shadow-indigo-300 transition-all flex items-center justify-center gap-2 text-sm">
                    <svg wire:loading.remove wire:target="save" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <svg wire:loading wire:target="save" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="save">Activate My Account</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </button>

            </form>
        </div>

        {{-- Signed in as --}}
        <p class="text-center text-xs text-slate-400">
            Signed in as <strong class="text-slate-600">{{ auth()->user()?->email }}</strong> &mdash;
            <button wire:click="logout"
                    class="text-indigo-600 hover:text-indigo-700 font-semibold bg-transparent border-0 cursor-pointer p-0 text-xs">
                Sign out
            </button>
        </p>


    </div>
</div>
