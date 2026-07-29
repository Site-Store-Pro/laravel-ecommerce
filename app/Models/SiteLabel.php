<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SiteLabel extends Model
{
    protected $table = 'site_labels';

    protected $fillable = [
        'label_key',
        'section_id',
        'file_name',
        'label_description',
        'label_default',
        'label_custom',
        'last_updated',
    ];

    protected $casts = [
        'last_updated' => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function section(): BelongsTo
    {
        return $this->belongsTo(SiteLabelSection::class, 'section_id', 'id');
    }

    /** All language translations for this label. */
    public function translations(): HasMany
    {
        return $this->hasMany(SiteLabelTranslation::class, 'site_label_id');
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    /**
     * Returns the effective display value for the DEFAULT language:
     * custom override if set, else the seeded default.
     *
     * This is also used as the SOURCE text for AI translation jobs via
     * TranslationService::translateRecord() (which reads $model->label_value).
     */
    public function getLabelValueAttribute(): string
    {
        return $this->resolve();
    }

    /**
     * Returns the effective display value: custom override if set, else default.
     */
    public function resolve(): string
    {
        return ($this->label_custom !== null && $this->label_custom !== '')
            ? $this->label_custom
            : (string) $this->label_default;
    }
}
