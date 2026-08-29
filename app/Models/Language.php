<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Language extends Model
{
    protected $table = 'languages';

    protected $fillable = [
        'code', 'name', 'native_name', 'flag_emoji',
        'is_default', 'is_active', 'show_in_switcher', 'rtl',
        'currency_code', 'currency_symbol', 'currency_position',
        'decimal_separator', 'thousands_separator', 'sort_order',
    ];

    protected $casts = [
        'is_default'       => 'boolean',
        'is_active'        => 'boolean',
        'show_in_switcher' => 'boolean',
        'rtl'              => 'boolean',
        'sort_order'       => 'integer',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function siteLabels(): HasMany
    {
        return $this->hasMany(SiteLabel::class, 'language_id');
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeInSwitcher($query)
    {
        return $query->where('is_active', true)->where('show_in_switcher', true)->orderBy('sort_order');
    }

    // ── Static helpers ───────────────────────────────────────────────────────

    /**
     * Cache ONLY the integer ID to avoid Eloquent __PHP_Incomplete_Class
     * deserialization errors when the class isn't yet autoloaded.
     */
    public static function getDefault(): self
    {
        $id = Cache::remember('language.default_id', 3600, fn () =>
            static::where('is_default', true)->value('id')
                ?? static::orderBy('id')->value('id')
        );

        if ($id && $lang = static::find($id)) {
            return $lang;
        }

        // Cache miss or stale — clear and fall back to a live query
        Cache::forget('language.default_id');
        return static::where('is_default', true)->first()
            ?? static::orderBy('id')->first()
            ?? new self([
                'id' => 1, 'code' => 'en', 'name' => 'English',
                'native_name' => 'English', 'flag_emoji' => '\xF0\x9F\x87\xBA\xF0\x9F\x87\xB8',
                'is_default' => true, 'is_active' => true,
            ]);
    }

    public static function getAllActive(): Collection
    {
        $ids = Cache::remember('language.active_ids', 3600,
            fn () => static::active()->pluck('id')->toArray()
        );
        return static::whereIn('id', $ids)->orderBy('sort_order')->get();
    }

    public static function getSwitcherLanguages(): Collection
    {
        $ids = Cache::remember('language.switcher_ids', 3600,
            fn () => static::inSwitcher()->pluck('id')->toArray()
        );
        return static::whereIn('id', $ids)->orderBy('sort_order')->get();
    }

    public static function findByCode(string $code): ?self
    {
        $code = trim(strtolower($code));
        if ($code === '' || strlen($code) > 10 || !preg_match('/^[a-zA-Z0-9_-]{2,10}$/', $code)) {
            return null;
        }

        $id = Cache::remember('language.code_id.' . $code, 3600,
            fn () => static::where('code', $code)->value('id')
        );
        return $id ? static::find($id) : null;
    }

    public static function clearCache(): void
    {
        Cache::forget('language.default_id');
        Cache::forget('language.active_ids');
        Cache::forget('language.switcher_ids');
        // Per-code ID caches expire naturally after 1 hour
    }

    /** Returns currency override array or null if not set. */
    public function currencyOverride(): ?array
    {
        if (empty($this->currency_symbol) && empty($this->currency_code)) {
            return null;
        }
        return [
            'symbol'    => $this->currency_symbol ?: '$',
            'code'      => $this->currency_code ?: 'USD',
            'position'  => $this->currency_position ?: 'before',
            'decimal'   => $this->decimal_separator ?: '.',
            'thousands' => $this->thousands_separator ?: ',',
        ];
    }

    /** Human-readable label for switcher UI. */
    public function switcherLabel(): string
    {
        return $this->flag_emoji . ' ' . $this->native_name;
    }
}
