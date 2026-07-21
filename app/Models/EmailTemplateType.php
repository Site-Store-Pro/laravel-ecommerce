<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailTemplateType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'ordering',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'ordering' => 'double',
    ];

    public function templates(): HasMany
    {
        return $this->hasMany(EmailTemplate::class, 'email_type_id');
    }

    public function activeTemplate(): ?EmailTemplate
    {
        return $this->templates()->where('is_active', true)->first();
    }
}
