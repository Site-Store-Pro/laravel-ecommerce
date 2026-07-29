<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasTranslations;

class EmailTemplate extends Model
{
    use HasTranslations;

    protected $fillable = [
        'email_type_id',
        'profile_name',
        'from_address',
        'from_name',
        'bcc_address',
        'subject',
        'header_html',
        'banner_image_url',
        'banner_image_link',
        'show_banner',
        'salutation',
        'include_salutation',
        'greeting',
        'body',
        'sign_off',
        'signature',
        'disclaimer',
        'copyright',
        'footer_image_url',
        'footer_image_link',
        'show_footer_image',
        'footer_html',
        'is_active',
    ];

    protected $casts = [
        'show_banner' => 'boolean',
        'include_salutation' => 'boolean',
        'show_footer_image' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(EmailTemplateType::class, 'email_type_id');
    }
}
