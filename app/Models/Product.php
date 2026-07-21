<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\ProductCrossSell;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $fillable = [
        'title',
        'short_description',
        'long_description',
        'meta_title',
        'meta_description',
        'seo_slug',
        'download_item',
        'shipping',
        'brand_id',
        'max_qty',
        'checkout_redirect',
        'standalone_purchase',
        'dependent_variants',
        'hide_inventory_levels',
        'layout_type',
        'reviews_enabled',
        'reviews_rating',
        'featured_item',
    ];

    protected $casts = [
        'max_qty' => 'integer',
        'checkout_redirect' => 'integer',
        'standalone_purchase' => 'integer',
        'dependent_variants' => 'integer',
        'hide_inventory_levels' => 'integer',
        'layout_type' => 'integer',
        'reviews_enabled' => 'integer',
        'reviews_rating' => 'float',
        'featured_item' => 'integer',
    ];

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class, 'product_id');
    }
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function primaryThumbnailUrl(): ?string
    {
        foreach ($this->variants as $variant) {
            $img = $variant->images->where('active', 1)->where('search_image', 1)->first();
            if ($img) {
                return $img->thumbnailUrl();
            }
        }

        foreach ($this->variants as $variant) {
            $url = $variant->thumbnailImageUrl();
            if ($url) {
                return $url;
            }
        }
        return null;
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories_assignments', 'product_id', 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function fields(): HasMany
    {
        return $this->hasMany(ProductField::class, 'product_id')->orderBy('sort_order')->orderBy('id');
    }

    public function crossSells(): HasMany
    {
        return $this->hasMany(ProductCrossSell::class, 'product_id')->orderBy('sort_order')->orderBy('id');
    }

    public function getParsedShortDescriptionAttribute(): string
    {
        return \App\Services\ContentParserService::parse($this->short_description);
    }

    public function getParsedLongDescriptionAttribute(): string
    {
        return \App\Services\ContentParserService::parse($this->long_description);
    }

    public function getPriceRangeAttribute(): string
    {
        $prices = $this->variants->pluck('public_price')->filter()->unique();
        if ($prices->isEmpty()) {
            return 'N/A';
        }
        if ($prices->count() === 1) {
            return '$' . number_format($prices->first(), 2);
        }
        return '$' . number_format($prices->min(), 2) . ' - $' . number_format($prices->max(), 2);
    }
}
