<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductFieldOptionTranslation extends Model
{
    protected $fillable = [
        'product_field_option_id',
        'language_id',
        'option_value',
    ];

    public function option(): BelongsTo
    {
        return $this->belongsTo(ProductFieldOption::class, 'product_field_option_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
