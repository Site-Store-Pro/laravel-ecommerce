<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteLabelTranslation extends Model
{
    protected $table = 'site_label_translations';

    protected $fillable = [
        'site_label_id',
        'language_id',
        'label_value',
        'translation_status',
        'translated_at',
    ];

    protected $casts = [
        'translated_at' => 'datetime',
    ];

    public function siteLabel(): BelongsTo
    {
        return $this->belongsTo(SiteLabel::class, 'site_label_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
