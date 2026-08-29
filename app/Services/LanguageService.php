<?php

namespace App\Services;

use App\Models\Language;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class LanguageService
{
    private const SESSION_KEY = 'language_code';
    private const COOKIE_KEY  = 'app_language';
    private const CACHE_TTL   = 3600;

    private ?Language $currentLanguage = null;
    private ?Language $defaultLanguage = null;

    // ── Current Language ─────────────────────────────────────────────────────

    public function current(): Language
    {
        if ($this->currentLanguage) {
            return $this->currentLanguage;
        }

        $code = Session::get(self::SESSION_KEY)
            ?: request()->cookie(self::COOKIE_KEY);

        // Sanitize code: must be a short 2-10 char alphanumeric/hyphen string
        if (is_string($code) && strlen($code) <= 10 && preg_match('/^[a-zA-Z0-9_-]{2,10}$/', $code)) {
            $lang = Language::findByCode(strtolower($code));
            if ($lang && $lang->is_active) {
                $this->currentLanguage = $lang;
                return $lang;
            }
        } elseif (!empty($code)) {
            // Invalid, encrypted, or corrupted legacy cookie — purge it to prevent DB cache key overflow
            Cookie::queue(Cookie::forget(self::COOKIE_KEY));
            Session::forget(self::SESSION_KEY);
        }

        $this->currentLanguage = $this->getDefault();
        return $this->currentLanguage;
    }

    public function currentCode(): string
    {
        return $this->current()->code;
    }

    public function currentId(): int
    {
        return (int) ($this->current()->id ?? 1);
    }

    // ── Default Language ─────────────────────────────────────────────────────

    public function getDefault(): Language
    {
        if ($this->defaultLanguage) {
            return $this->defaultLanguage;
        }
        $this->defaultLanguage = Language::getDefault();
        return $this->defaultLanguage;
    }

    public function defaultId(): int
    {
        return (int) ($this->getDefault()->id ?? 1);
    }

    public function isDefault(): bool
    {
        return $this->currentId() === $this->defaultId();
    }

    // ── Language Switching ───────────────────────────────────────────────────

    public function setLanguage(string $code): bool
    {
        $code = trim(strtolower($code));
        if ($code === '' || strlen($code) > 10 || !preg_match('/^[a-zA-Z0-9_-]{2,10}$/', $code)) {
            return false;
        }

        $lang = Language::findByCode($code);
        if (!$lang || !$lang->is_active) {
            return false;
        }

        Session::put(self::SESSION_KEY, $code);
        Cookie::queue(Cookie::make(self::COOKIE_KEY, $code, 60 * 24 * 365, '/', null, false, false)); // 1 year unencrypted
        $this->currentLanguage = $lang;
        return true;
    }

    // ── All Languages ────────────────────────────────────────────────────────

    public function all(): Collection
    {
        return Language::getAllActive();
    }

    public function switcherLanguages(): Collection
    {
        return Language::getSwitcherLanguages();
    }

    // ── Currency Override ────────────────────────────────────────────────────

    public function currencyOverride(): ?array
    {
        return $this->current()->currencyOverride();
    }

    // ── RTL ─────────────────────────────────────────────────────────────────

    public function isRtl(): bool
    {
        return (bool) $this->current()->rtl;
    }
}
