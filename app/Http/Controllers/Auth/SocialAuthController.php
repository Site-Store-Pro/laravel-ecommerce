<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;

class SocialAuthController extends Controller
{
    /**
     * @return RedirectResponse|SymfonyRedirectResponse
     */
    public function redirect(string $provider): RedirectResponse|SymfonyRedirectResponse
    {
        $this->validateProvider($provider);

        $driver = Socialite::driver($provider);

        if ($provider === 'github') {
            $driver->scopes(['user:email']);
        }

        return $driver->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['form.email' => 'Social login failed. Please try again.']);
        }

        $email = $socialUser->getEmail();

        if (! $email) {
            session([
                'social_registration' => [
                    'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                ]
            ]);

            return redirect()->route('auth.collect-email');
        }

        $user = User::query()
            ->where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if (! $user) {
            $user = User::query()->where('email', $email)->first();

            if ($user) {
                $user->update([
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                ]);
                if (is_null($user->email_verified_at)) {
                    $user->email_verified_at = now();
                    $user->save();
                }
            } else {
                $user = new User([
                    'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
                    'email' => $email,
                    'password' => Hash::make(Str::random(32)),
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                ]);
                $user->email_verified_at = now();
                $user->save();

                event(new Registered($user));
            }
        }

        Auth::login($user, remember: true);

        return redirect()->intended(route('dashboard'));
    }

    private function validateProvider(string $provider): void
    {
        abort_unless(in_array($provider, ['google', 'facebook', 'github'], true), 404);
    }
}
