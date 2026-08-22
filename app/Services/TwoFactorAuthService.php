<?php

namespace App\Services;

use App\Models\CmsSetting;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class TwoFactorAuthService
{
    const CODE_EXPIRY_MINUTES = 15;
    const RESEND_COOLDOWN_SECONDS = 45;

    /**
     * Check if 2FA is globally enabled for checkout.
     */
    public static function isCheckout2FaEnabled(): bool
    {
        return (bool) CmsSetting::get('enable_checkout_2fa', false);
    }

    /**
     * Check if 2FA is globally enabled for login.
     */
    public static function isLogin2FaEnabled(): bool
    {
        return (bool) CmsSetting::get('enable_login_2fa', false);
    }

    /**
     * Determine if a user logging in is exempt from 2FA.
     *
     * Exemptions:
     * 1. Social users (Google, Facebook, GitHub, etc.)
     * 2. Returning users who logged in / verified within the last 30 days.
     */
    public static function isUserExemptFromLogin2Fa(User $user): bool
    {
        // 1. Social login accounts are always exempt
        if (!empty($user->provider)) {
            return true;
        }

        // 2. Returning users who have logged in within the last 30 days are exempt
        if ($user->last_login_at && $user->last_login_at > now()->subDays(30)) {
            return true;
        }

        return false;
    }

    /**
     * Determine if a customer at checkout is exempt from 2FA.
     *
     * Exemptions:
     * 1. Social users (Google, Facebook, GitHub, etc.)
     * 2. Returning users who placed an order within the last 30 days.
     */
    public static function isCustomerExemptFromCheckout2Fa(?User $user = null, ?string $email = null): bool
    {
        $targetUser = $user;

        if (!$targetUser && !empty($email)) {
            $targetUser = User::where('email', $email)->first();
        }

        if (!$targetUser) {
            // New guest customer with no existing account history -> must verify
            return false;
        }

        // 1. Social login accounts are always exempt
        if (!empty($targetUser->provider)) {
            return true;
        }

        // 2. Check if user placed an order in the last 30 days
        $hasRecentOrder = Order::where('order_user_id', $targetUser->id)
            ->where('order_date', '>', now()->subDays(30))
            ->exists();

        if ($hasRecentOrder) {
            return true;
        }

        return false;
    }

    /**
     * Generate a cryptographically secure 6-digit numeric verification code.
     */
    public static function generateCode(): string
    {
        return sprintf('%06d', random_int(100000, 999999));
    }

    /**
     * Send the verification code email via the translatable template system.
     */
    public static function sendVerificationEmail(string $email, string $name, string $code, ?int $languageId = null): bool
    {
        $vars = [
            'verification_code'  => $code,
            'customer_name'      => $name ?: 'Customer',
            'expires_in_minutes' => self::CODE_EXPIRY_MINUTES,
            'site_name'          => CmsSetting::get('site_name', config('app.name', 'Site Store Pro')),
        ];

        return EmailTemplateService::sendEmail('two_factor_verification', $email, $name, $vars, $languageId);
    }

    /**
     * Start a 2FA challenge for a user during login.
     */
    public static function startLoginChallenge(User $user, bool $remember = false): void
    {
        $code = self::generateCode();

        session([
            '2fa_context'      => 'login',
            '2fa_user_id'      => $user->id,
            '2fa_email'        => $user->email,
            '2fa_name'         => $user->name,
            '2fa_code'         => $code,
            '2fa_expires_at'   => now()->addMinutes(self::CODE_EXPIRY_MINUTES)->timestamp,
            '2fa_remember'     => $remember,
            '2fa_last_sent_at' => now()->timestamp,
        ]);

        self::sendVerificationEmail($user->email, $user->name, $code);
    }

    /**
     * Start a 2FA challenge for a customer during checkout.
     */
    public static function startCheckoutChallenge(string $email, string $name, ?int $userId = null): void
    {
        $code = self::generateCode();

        session([
            '2fa_context'      => 'checkout',
            '2fa_user_id'      => $userId,
            '2fa_email'        => $email,
            '2fa_name'         => $name,
            '2fa_code'         => $code,
            '2fa_expires_at'   => now()->addMinutes(self::CODE_EXPIRY_MINUTES)->timestamp,
            '2fa_last_sent_at' => now()->timestamp,
        ]);

        self::sendVerificationEmail($email, $name, $code);
    }

    /**
     * Verify the entered code against session state.
     */
    public static function verifyCode(string $enteredCode, string $context = 'login'): bool
    {
        $storedCode = session('2fa_code');
        $expiresAt  = session('2fa_expires_at');
        $sessionCtx = session('2fa_context');

        if (empty($storedCode) || empty($expiresAt)) {
            return false;
        }

        if ($sessionCtx !== $context) {
            return false;
        }

        if (now()->timestamp > $expiresAt) {
            return false;
        }

        if (trim($enteredCode) !== (string) $storedCode) {
            return false;
        }

        // Code is valid! Finalize action based on context
        if ($context === 'login') {
            $userId = session('2fa_user_id');
            $remember = session('2fa_remember', false);
            $user = User::find($userId);

            if ($user) {
                $user->update(['last_login_at' => now()]);
                Auth::login($user, $remember);
                CartSessionService::associateCartOnLogin($user->id);
            }
        } elseif ($context === 'checkout') {
            session([
                'checkout_2fa_verified'    => true,
                'checkout_2fa_verified_at' => now()->timestamp,
            ]);

            // If user is logged in, also update their last_login_at
            if (Auth::check()) {
                Auth::user()->update(['last_login_at' => now()]);
            }
        }

        // Clear 2FA pending state
        self::clearChallengeSession();

        return true;
    }

    /**
     * Resend verification code with cooldown protection.
     *
     * @return array{success: bool, message: string, seconds_remaining: int}
     */
    public static function resendCode(string $context = 'login'): array
    {
        $lastSent = session('2fa_last_sent_at', 0);
        $elapsed  = now()->timestamp - $lastSent;

        if ($elapsed < self::RESEND_COOLDOWN_SECONDS) {
            $remaining = self::RESEND_COOLDOWN_SECONDS - $elapsed;
            return [
                'success'           => false,
                'message'           => "Please wait {$remaining} seconds before requesting a new code.",
                'seconds_remaining' => $remaining,
            ];
        }

        $email = session('2fa_email');
        $name  = session('2fa_name', 'Customer');

        if (empty($email)) {
            return [
                'success'           => false,
                'message'           => 'Verification session has expired. Please restart the process.',
                'seconds_remaining' => 0,
            ];
        }

        $newCode = self::generateCode();

        session([
            '2fa_code'         => $newCode,
            '2fa_expires_at'   => now()->addMinutes(self::CODE_EXPIRY_MINUTES)->timestamp,
            '2fa_last_sent_at' => now()->timestamp,
        ]);

        self::sendVerificationEmail($email, $name, $newCode);

        return [
            'success'           => true,
            'message'           => 'A new verification code has been sent to your email.',
            'seconds_remaining' => self::RESEND_COOLDOWN_SECONDS,
        ];
    }

    /**
     * Check if checkout 2FA has been successfully verified for current session.
     */
    public static function isCheckout2FaVerified(): bool
    {
        return session('checkout_2fa_verified', false) === true;
    }

    /**
     * Get the verification URL for redirection.
     */
    public static function getVerifyUrl(string $context = 'login'): string
    {
        if (\Illuminate\Support\Facades\Route::has('auth.verify-code')) {
            return route('auth.verify-code', ['context' => $context]);
        }

        return url('/verify-code?context=' . urlencode($context));
    }

    /**
     * Clear the pending 2FA challenge from session.
     */
    public static function clearChallengeSession(): void
    {
        session()->forget([
            '2fa_context',
            '2fa_user_id',
            '2fa_email',
            '2fa_name',
            '2fa_code',
            '2fa_expires_at',
            '2fa_remember',
            '2fa_last_sent_at',
        ]);
    }
}
