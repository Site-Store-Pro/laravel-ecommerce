<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsFormTranslation extends Model
{
    protected $table = 'cms_form_translations';

    protected $fillable = [
        'cms_form_id',
        'language_id',
        'name',
        'submit_button_label',
        'confirmation_message',
        'translation_status',
        'translated_at',
    ];

    protected $casts = [
        'translated_at' => 'datetime',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(CmsForm::class, 'cms_form_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
