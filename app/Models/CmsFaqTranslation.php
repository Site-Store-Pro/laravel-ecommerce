<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsFaqTranslation extends Model
{
    protected $table = 'cms_faq_translations';

    protected $fillable = [
        'cms_faq_id',
        'language_id',
        'question',
        'answer',
        'translation_status',
        'translated_at',
    ];

    protected $casts = [
        'translated_at' => 'datetime',
    ];

    public function faq(): BelongsTo
    {
        return $this->belongsTo(CmsFaq::class, 'cms_faq_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
