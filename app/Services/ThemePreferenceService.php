<?php

namespace App\Services;

use App\Models\CmsSetting;
use Illuminate\Support\Facades\Cookie;

class ThemePreferenceService
{
    /**
     * Resolve whether frontend dark mode is active for the current visitor/user.
     * Order of precedence:
     * 1. Authenticated user preference (users.theme_preference)
     * 2. Session preference ('frontend_theme')
     * 3. Visitor cookie ('frontend_theme' / 'theme_mode' / 'visperity_theme' / 'theme')
     * 4. Store default setting (CmsSetting::isEnabled('frontend_dark_mode'))
     */
    public static function isFrontendDarkMode(): bool
    {
        if (auth()->check() && !empty(auth()->user()->theme_preference)) {
            $userPref = auth()->user()->theme_preference;
            if ($userPref === 'dark') {
                return true;
            }
            if ($userPref === 'light') {
                return false;
            }
        }

        $sessionTheme = session('frontend_theme') ?: session('theme_mode');
        if ($sessionTheme === 'dark') {
            return true;
        }
        if ($sessionTheme === 'light') {
            return false;
        }

        $cookie = request()->cookie('frontend_theme')
            ?? request()->cookie('theme_mode')
            ?? request()->cookie('visperity_theme')
            ?? request()->cookie('theme')
            ?? ($_COOKIE['frontend_theme'] ?? $_COOKIE['theme_mode'] ?? $_COOKIE['visperity_theme'] ?? $_COOKIE['theme'] ?? null);

        if ($cookie === 'dark') {
            return true;
        }
        if ($cookie === 'light') {
            return false;
        }

        try {
            return CmsSetting::isEnabled('frontend_dark_mode');
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Set the theme preference for the current visitor/user.
     * - Authenticated users save to their account record (users.theme_preference);
     * - Session stores the preference;
     * - All visitors get 1-year cookies queued (frontend_theme, theme_mode, visperity_theme, theme);
     * - Global CmsSetting is NOT modified.
     */
    public static function setFrontendTheme(?string $theme = null): string
    {
        if ($theme !== 'dark' && $theme !== 'light') {
            $theme = static::isFrontendDarkMode() ? 'light' : 'dark';
        }

        if (auth()->check()) {
            auth()->user()->update(['theme_preference' => $theme]);
        }

        session(['frontend_theme' => $theme, 'theme_mode' => $theme]);

        // Queue unencrypted cookies for 1 year (525600 mins) across all standard cookie names
        Cookie::queue(Cookie::make('frontend_theme', $theme, 525600, '/', null, false, false));
        Cookie::queue(Cookie::make('theme_mode', $theme, 525600, '/', null, false, false));
        Cookie::queue(Cookie::make('visperity_theme', $theme, 525600, '/', null, false, false));
        Cookie::queue(Cookie::make('theme', $theme, 525600, '/', null, false, false));

        return $theme;
    }
}
