<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsModalTranslation extends Model
{
    protected $table = 'cms_modal_translations';

    protected $fillable = [
        'cms_modal_id',
        'language_id',
        'title',
        'body',
        'translation_status',
        'translated_at',
    ];

    protected $casts = [
        'translated_at' => 'datetime',
    ];

    public function modal(): BelongsTo
    {
        return $this->belongsTo(CmsModal::class, 'cms_modal_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
