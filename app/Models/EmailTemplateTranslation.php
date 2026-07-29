<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailTemplateTranslation extends Model
{
    protected $table = 'email_template_translations';

    protected $fillable = [
        'email_template_id',
        'language_id',
        'subject',
        'header_html',
        'salutation',
        'greeting',
        'body',
        'sign_off',
        'signature',
        'disclaimer',
        'copyright',
        'footer_html',
        'translation_status',
        'translated_at',
    ];

    protected $casts = [
        'translated_at' => 'datetime',
    ];

    public function emailTemplate(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class, 'email_template_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
