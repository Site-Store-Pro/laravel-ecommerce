<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsFormFieldTranslation extends Model
{
    protected $table = 'cms_form_field_translations';

    protected $fillable = [
        'cms_form_field_id',
        'language_id',
        'label',
        'instructions',
        'required_error_message',
        'html_above',
        'options',
        'translation_status',
        'translated_at',
    ];

    protected $casts = [
        'options'       => 'array',
        'translated_at' => 'datetime',
    ];

    public function formField(): BelongsTo
    {
        return $this->belongsTo(CmsFormField::class, 'cms_form_field_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
