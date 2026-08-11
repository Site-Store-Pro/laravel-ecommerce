@extends('layouts.public')

@section('title', __('Profile'))

@section('content')
    @if (isset($header))
        <header class="bg-white dark:bg-slate-800 border-b border-slate-100 dark:border-slate-700 shadow-sm">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
                    {{ __('Profile') }}
                </h2>
            </div>
        </header>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (auth()->user()->provider)
                <div class="p-6 bg-white dark:bg-slate-800 shadow sm:rounded-2xl border-l-4 border-indigo-500 dark:border-indigo-400">
                    <div class="flex items-start gap-4">
                        <div class="p-2 bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 rounded-lg mt-0.5">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Profile Managed Externally</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                You cannot edit your profile details here since your account was registered through a social provider ({{ ucfirst(auth()->user()->provider) }}). Please update your details directly on your provider's platform.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="p-4 sm:p-8 bg-white dark:bg-slate-800 shadow sm:rounded-2xl border border-slate-200 dark:border-slate-700/60">
                    <div class="max-w-xl">
                        <livewire:profile.update-profile-information-form />
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-slate-800 shadow sm:rounded-2xl border border-slate-200 dark:border-slate-700/60">
                    <div class="max-w-xl">
                        <livewire:profile.update-password-form />
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-slate-800 shadow sm:rounded-2xl border border-slate-200 dark:border-slate-700/60">
                    <div class="max-w-xl">
                        <livewire:profile.delete-user-form />
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
