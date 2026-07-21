<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductField extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'label',
        'field_type',
        'is_required',
        'charge_tax',
        'sort_order'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'sort_order' => 'integer',
        'charge_tax' => 'integer',
    ];

    /**
     * Relationship: Product that this field belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relationship: Predefined options/choices for this field.
     */
    public function options(): HasMany
    {
        return $this->hasMany(ProductFieldOption::class, 'product_field_id')->orderBy('sort_order')->orderBy('id');
    }
}
