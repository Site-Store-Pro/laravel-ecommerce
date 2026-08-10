<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CmsFaq extends Model
{
    use HasTranslations;

    protected $table = 'cms_faqs';

    protected $fillable = [
        'question',
        'answer',
        'is_active',
        'sort_order',
    ];

    /**
     * Fields automatically translated when translations relation is loaded.
     */
    protected array $translatable = [
        'question',
        'answer',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
