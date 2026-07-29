<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.public')] class extends Component
{
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard'), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

@section('title', siteLabel('auth.verify_heading', 'Verify Your Email Address'))

<div class="min-h-[70vh] flex items-center justify-center px-6 py-16 bg-gradient-to-br from-slate-50 via-white to-indigo-50/30">
    <div class="max-w-md w-full space-y-6">

        {{-- Header --}}
        <div class="text-center">
            <div class="mx-auto w-16 h-16 bg-teal-500 rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-teal-200">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">@label('auth.check_email', 'Check Your Email')</h1>
            <p class="mt-2 text-sm text-slate-500 leading-relaxed max-w-sm mx-auto">
                @label('auth.verify_sent', 'We sent a verification link to')
                <strong class="text-slate-700">{{ Auth::user()->email }}</strong>.
                @label('auth.verify_click', 'Click the link in that email to activate your account.')
            </p>
        </div>

        {{-- Success flash --}}
        @if (session('status') === 'verification-link-sent')
            <div class="flex items-start gap-3 bg-teal-50 border border-teal-200 rounded-2xl px-4 py-3.5">
                <svg class="w-5 h-5 text-teal-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <p class="text-sm text-teal-800 font-medium">
                    A new verification link has been sent to your email address.
                </p>
            </div>
        @endif

        {{-- Card --}}
        <div class="bg-white border border-slate-100 rounded-3xl shadow-xl shadow-slate-100/50 p-8 space-y-5">

            {{-- Instruction steps --}}
            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">1</span>
                    <p class="text-sm text-slate-600">Open the email we sent to <strong class="text-slate-800">{{ Auth::user()->email }}</strong></p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">2</span>
                    <p class="text-sm text-slate-600">Click the <strong class="text-slate-800">"Verify Email Address"</strong> button in the email</p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">3</span>
                    <p class="text-sm text-slate-600">You will be redirected to your account dashboard automatically</p>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-5">
                <p class="text-xs text-slate-500 mb-3">Didn't receive the email? Check your spam folder, or:</p>
                <button wire:click="sendVerification"
                        wire:loading.attr="disabled"
                        class="w-full bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 disabled:opacity-60 text-white font-bold px-6 py-3 rounded-2xl shadow-md shadow-indigo-200 hover:shadow-lg hover:shadow-indigo-300 transition-all flex items-center justify-center gap-2 text-sm">
                    <svg wire:loading.remove wire:target="sendVerification" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <svg wire:loading wire:target="sendVerification" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="sendVerification">@label('auth.resend_verification', 'Resend Verification Email')</span>
                    <span wire:loading wire:target="sendVerification">@label('auth.resend_sending', 'Sending...')</span>
                </button>
            </div>

        </div>

        {{-- Footer --}}
        <p class="text-center text-xs text-slate-400">
            @label('auth.signed_in_as', 'Signed in as') <strong class="text-slate-600">{{ Auth::user()->email }}</strong> &mdash;
            <button wire:click="logout" class="text-indigo-600 hover:text-indigo-700 font-semibold">@label('auth.sign_out_link', 'Sign out')</button>
        </p>

    </div>
</div>
