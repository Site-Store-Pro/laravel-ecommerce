<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'product_brands';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
        'brand_icon',
        'brand_url',
        'brand_logo_s3'
    ];

    /**
     * Relationship: Products belonging to this brand.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'brand_id');
    }
}
