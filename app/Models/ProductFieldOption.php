<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductFieldOption extends Model
{
    use HasFactory, HasTranslations;

    /** Fields returned in the active language when translations are eager-loaded. */
    protected array $translatable = ['option_value'];

    protected $fillable = [
        'product_field_id',
        'option_value',
        'option_price_modifier',
        'option_wholesale_price_modifier',
        'sort_order'
    ];

    protected $casts = [
        'option_price_modifier' => 'decimal:2',
        'option_wholesale_price_modifier' => 'decimal:2',
        'sort_order' => 'integer'
    ];

    /**
     * Relationship: The custom field this option belongs to.
     */
    public function field(): BelongsTo
    {
        return $this->belongsTo(ProductField::class, 'product_field_id');
    }
}
