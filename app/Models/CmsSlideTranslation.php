<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsSlideTranslation extends Model
{
    protected $table = 'cms_slide_translations';

    protected $fillable = [
        'cms_slide_id',
        'language_id',
        'slide_heading',
        'slide_sub_heading',
        'slide_callout_button_label',
        'translation_status',
        'translated_at',
    ];

    protected $casts = [
        'translated_at' => 'datetime',
    ];

    public function slide(): BelongsTo
    {
        return $this->belongsTo(CmsSlide::class, 'cms_slide_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
