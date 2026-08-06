<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Translation record for a CmsTestimonial.
 *
 * Maps to the existing `testimonial_translations` table (created by
 * 2026_07_27_000016_create_testimonial_translations_table.php).
 *
 * Used by AdminTestimonialsManager for per-language text overrides.
 */
class CmsTestimonialTranslation extends Model
{
    protected $table = 'testimonial_translations';

    protected $fillable = [
        'testimonial_id',
        'language_id',
        'author_name',
        'author_title',
        'content',
        'company_name',
        'translation_status',
        'translated_at',
    ];

    protected $casts = [
        'translated_at' => 'datetime',
    ];

    public function testimonial(): BelongsTo
    {
        return $this->belongsTo(CmsTestimonial::class, 'testimonial_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
