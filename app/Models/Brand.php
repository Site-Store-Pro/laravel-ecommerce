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
        'is_visible_in_menu',
        'brand_icon',
        'brand_url',
        'brand_logo_s3',
        'brand_logo_cdn_url',
        'brand_logo_region',
        'brand_logo_bucket_name',
        'brand_logo_access_key_id',
        'brand_logo_secret_access_key',
        'brand_icon_direct_url',
        'show_image',
    ];

    protected $casts = [
        'is_visible_in_menu' => 'boolean',
        'show_image'         => 'boolean',
        'sort_order'         => 'integer',
        'brand_logo_s3'      => 'integer',
    ];

    /**
     * Scope: Only brands visible in menus & filters.
     */
    public function scopeVisibleInMenu($query)
    {
        return $query->where('is_visible_in_menu', true);
    }

    /**
     * Relationship: Products belonging to this brand.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'brand_id');
    }
}
