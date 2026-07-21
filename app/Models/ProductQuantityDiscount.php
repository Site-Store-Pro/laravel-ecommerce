<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductQuantityDiscount extends Model
{
    protected $table = 'product_quantity_discounts';

    protected $fillable = [
        'product_variant_id',
        'qty_min',
        'qty_max',
        'discount_value',
        'value_type'
    ];

    protected $casts = [
        'qty_min' => 'integer',
        'qty_max' => 'integer',
        'discount_value' => 'decimal:2',
        'value_type' => 'integer'
    ];

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
