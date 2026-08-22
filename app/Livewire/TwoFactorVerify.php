<?php

namespace App\Livewire;

use App\Services\TwoFactorAuthService;
use App\Services\CartSessionService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.public')]
class TwoFactorVerify extends Component
{
    #[Url]
    public string $context = 'login'; // 'login' | 'checkout'

    public string $code = '';
    public string $email = '';
    public int $cooldownSeconds = 0;
    public string $resendMessage = '';
    public string $errorMessage = '';

    public function mount(): void
    {
        $sessionCtx = session('2fa_context');
        if ($sessionCtx) {
            $this->context = $sessionCtx;
        }

        $this->email = session('2fa_email', '');

        // If no active 2FA challenge is present in session, redirect user back to source
        if (empty($this->email) || !session()->has('2fa_code')) {
            if ($this->context === 'checkout') {
                $this->redirect(route('shop.checkout'), navigate: true);
            } else {
                $this->redirect(route('login'), navigate: true);
            }
            return;
        }

        // Calculate remaining cooldown if any
        $lastSent = session('2fa_last_sent_at', 0);
        $elapsed = now()->timestamp - $lastSent;
        if ($elapsed < TwoFactorAuthService::RESEND_COOLDOWN_SECONDS) {
            $this->cooldownSeconds = TwoFactorAuthService::RESEND_COOLDOWN_SECONDS - $elapsed;
        }
    }

    public function verify(): void
    {
        $this->resetErrorBag();
        $this->errorMessage = '';

        $this->validate([
            'code' => 'required|string|size:6',
        ], [
            'code.required' => siteLabel('two_factor.code_required', 'Please enter the 6-digit verification code.'),
            'code.size'     => siteLabel('two_factor.code_size', 'The verification code must be exactly 6 digits.'),
        ]);

        $success = TwoFactorAuthService::verifyCode($this->code, $this->context);

        if (!$success) {
            $this->errorMessage = siteLabel('two_factor.invalid_code', 'The verification code entered is invalid or has expired. Please check your email and try again.');
            $this->addError('code', $this->errorMessage);
            return;
        }

        // Verification successful!
        if ($this->context === 'checkout') {
            $this->redirect(route('shop.checkout-review'), navigate: false);
            return;
        }

        $user = Auth::user();
        $isCustomerRole = $user && in_array((int) ($user->role_id instanceof \App\Enums\UserRole ? $user->role_id->value : $user->role_id), [1, 2], true);

        // Login context: Only customer roles (1 or 2) redirect to checkout if cart has items
        if ($isCustomerRole && CartSessionService::getCartCount() > 0) {
            $this->redirect(route('shop.checkout'), navigate: true);
            return;
        }

        $this->redirectIntended(default: route('dashboard'), navigate: true);
    }

    public function resend(): void
    {
        $this->resetErrorBag();
        $this->errorMessage = '';
        $this->resendMessage = '';

        $result = TwoFactorAuthService::resendCode($this->context);

        if ($result['success']) {
            $this->cooldownSeconds = $result['seconds_remaining'];
            $this->resendMessage = siteLabel('two_factor.code_sent', $result['message']);
        } else {
            $this->cooldownSeconds = $result['seconds_remaining'];
            $this->errorMessage = siteLabel('two_factor.cooldown_message', $result['message']);
            $this->addError('resend', $this->errorMessage);
        }
    }

    public function render(): View
    {
        return view('livewire.two-factor-verify');
    }
}
