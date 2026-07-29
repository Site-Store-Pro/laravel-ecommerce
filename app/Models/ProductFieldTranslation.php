<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductFieldTranslation extends Model
{
    protected $fillable = [
        'product_field_id',
        'language_id',
        'label',
    ];

    public function field(): BelongsTo
    {
        return $this->belongsTo(ProductField::class, 'product_field_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
