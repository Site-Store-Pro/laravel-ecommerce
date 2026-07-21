<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsForm extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'submit_button_label',
        'custom_css',
        'confirmation_message',
        'redirect_url',
        'email_to',
        'email_subject',
        'auto_optin',
        'optin_provider',
        'optin_list_id',
        'is_active',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'auto_optin'  => 'boolean',
    ];

    // ── Relations ────────────────────────────────────────────────────────────

    public function fields(): HasMany
    {
        return $this->hasMany(CmsFormField::class, 'form_id')->orderBy('sort_order');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(CmsFormSubmission::class, 'form_id')->orderByDesc('submitted_at');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Generate a URL-safe slug from a given string.
     */
    public static function generateSlug(string $name): string
    {
        $base = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name));
        $base = trim($base, '-');
        $slug = $base;
        $i    = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
