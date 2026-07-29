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

        if ($code) {
            $lang = Language::findByCode($code);
            if ($lang && $lang->is_active) {
                $this->currentLanguage = $lang;
                return $lang;
            }
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
        $lang = Language::findByCode($code);
        if (!$lang || !$lang->is_active) {
            return false;
        }

        Session::put(self::SESSION_KEY, $code);
        Cookie::queue(self::COOKIE_KEY, $code, 60 * 24 * 365); // 1 year
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
