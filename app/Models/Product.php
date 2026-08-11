<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\ProductCrossSell;

class Product extends Model
{
    use HasFactory;
    use HasTranslations;

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
        'completion_redirect',
        'completion_redirect_label',
        'standalone_purchase',
        'dependent_variants',
        'hide_inventory_levels',
        'layout_type',
        'reviews_enabled',
        'reviews_rating',
        'featured_item',
        'product_search_index',
        'product_search_index_locked',
        'show_item_total',
        'variant_label',
        'product_video_embed',
        'is_donation_or_bill_pay',
        'allow_custom_amount',
        'custom_amount_min',
        'custom_amount_max',
        'custom_amount_options',
        'inventory_alert_id',
        'show_variant_selector_thumbnail',
    ];

    /** Fields automatically translated when translations relation is loaded. */
    protected array $translatable = [
        'title',
        'short_description',
        'long_description',
        'meta_title',
        'meta_description',
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
        'product_search_index_locked' => 'boolean',
        'show_item_total' => 'integer',
        'variant_label' => 'string',
        'product_video_embed' => 'string',
        'is_donation_or_bill_pay' => 'boolean',
        'allow_custom_amount' => 'boolean',
        'custom_amount_min' => 'float',
        'custom_amount_max' => 'float',
        'custom_amount_options' => 'string',
        'show_variant_selector_thumbnail' => 'integer',
        'inventory_alert_id' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (Product $product) {
            $product->rebuildSearchIndex();
        });
    }

    /**
     * Resolve a raw completion_redirect value (URL or [page:ID] shortcode) to
     * an absolute URL string, or null if blank / unresolvable.
     *
     * This is the single source of truth used by email builders and OrderReview.
     */
    public static function resolveCompletionUrl(?string $raw): ?string
    {
        $raw = trim((string) $raw);
        if ($raw === '') {
            return null;
        }

        // [page:ID] or [page:ID label="..."] shortcode
        if (preg_match('/^\[page:(\d+)(?:\s[^\]]*)?\]$/i', $raw, $m)) {
            $page = \App\Models\CmsPage::find((int) $m[1]);
            if ($page && !empty($page->slug)) {
                return url('/' . ltrim($page->slug, '/'));
            }
            return null; // shortcode references a missing page
        }

        // Absolute URL or relative path — return as-is
        return $raw;
    }

    /**
     * Returns the button label for the completion redirect.
     * Falls back to 'View Content' if the DB value is empty.
     */
    public function completionRedirectLabel(): string
    {
        return trim((string) $this->completion_redirect_label) ?: 'View Content';
    }

    public static function stripShortcodesAndHtml(?string $text): string
    {
        if (empty($text)) {
            return '';
        }

        // Strip bracketed shortcodes e.g. [code-embed:12], [plugin:brands-2026], [plugin:live-search-2026]
        $text = preg_replace('/\[[^\]]+\]/', ' ', $text);

        // Strip HTML tags
        $text = strip_tags($text);

        // Normalize whitespace
        return trim(preg_replace('/\s+/', ' ', $text));
    }

    public function rebuildSearchIndex(bool $force = false): string
    {
        if ($this->product_search_index_locked && !$force) {
            return (string) $this->product_search_index;
        }

        $parts = [];

        if (!empty($this->title)) {
            $parts[] = $this->title;
        }
        if (!empty($this->seo_slug)) {
            $parts[] = str_replace(['-', '_'], ' ', $this->seo_slug);
        }
        if (!empty($this->meta_title)) {
            $parts[] = $this->meta_title;
        }
        if (!empty($this->meta_description)) {
            $cleaned = static::stripShortcodesAndHtml($this->meta_description);
            if ($cleaned !== '') $parts[] = $cleaned;
        }
        if (!empty($this->short_description)) {
            $cleaned = static::stripShortcodesAndHtml($this->short_description);
            if ($cleaned !== '') $parts[] = $cleaned;
        }
        if (!empty($this->long_description)) {
            $cleaned = static::stripShortcodesAndHtml($this->long_description);
            if ($cleaned !== '') $parts[] = $cleaned;
        }

        // Keywords for Download & Event items
        if (!empty($this->download_item) || (int)$this->download_item === 1) {
            $parts[] = 'download downloads digital downloadable file files pdf ebook software';
        }
        $fullTextCheck = strtolower($this->title . ' ' . $this->short_description . ' ' . $this->long_description);
        if (str_contains($fullTextCheck, 'event') || str_contains($fullTextCheck, 'ticket') || str_contains($fullTextCheck, 'seminar') || str_contains($fullTextCheck, 'workshop')) {
            $parts[] = 'event events ticket tickets experience seminar workshop admission registration';
        }

        // Brand
        if ($this->brand_id) {
            $brandName = $this->relationLoaded('brand') && $this->brand ? $this->brand->name : Brand::where('id', $this->brand_id)->value('name');
            if ($brandName) {
                $parts[] = $brandName;
            }
        }

        // Categories
        if ($this->relationLoaded('categories') && $this->categories) {
            foreach ($this->categories as $cat) {
                $parts[] = $cat->name;
            }
        }

        // Variants (SKUs, title/name, attributes)
        if ($this->relationLoaded('variants') && $this->variants) {
            foreach ($this->variants as $variant) {
                if (!empty($variant->sku)) {
                    $parts[] = $variant->sku;
                }
                if (!empty($variant->variant_title)) {
                    $parts[] = $variant->variant_title;
                }
                if (!empty($variant->attributes) && is_array($variant->attributes)) {
                    foreach ($variant->attributes as $k => $v) {
                        $parts[] = "{$k}: {$v}";
                    }
                }
            }
        }

        $indexContent = implode(' ', array_filter(array_map('trim', $parts)));
        $this->product_search_index = $indexContent;

        return $indexContent;
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class, 'product_id');
    }

    /**
     * Recalculate and save reviews_rating in the products table if there are approved
     * reviews and the current reviews_rating is 0.0 / uncalculated.
     */
    public function recalculateRatingIfZero(): float
    {
        $approvedQuery = $this->reviews()->where('approved', true);
        $approvedCount = $approvedQuery->count();

        if ($approvedCount >= 1 && ($this->reviews_rating <= 0.0 || $this->reviews_rating == 0)) {
            $avgRating = round((float) $approvedQuery->avg('rating'), 1);
            $this->reviews_rating = $avgRating;
            $this->saveQuietly();
        }

        return (float) $this->reviews_rating;
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

    /**
     * The out-of-stock alert message assigned to this product.
     * Returns null when no alert is assigned OR when the assigned alert has been deleted
     * (nullOnDelete FK constraint ensures no orphan references).
     */
    public function inventoryAlert(): BelongsTo
    {
        return $this->belongsTo(ProductInventoryAlert::class, 'inventory_alert_id');
    }

    public function getParsedShortDescriptionAttribute(): string
    {
        return \App\Services\ContentParserService::parse($this->short_description);
    }

    public function getParsedLongDescriptionAttribute(): string
    {
        return \App\Services\ContentParserService::parse($this->long_description);
    }

    /**
     * Parse the product video embed field through ContentParserService so that
     * CMS shortcodes (e.g. [code-embed:N]) are expanded AND raw <iframe> HTML
     * is passed through unchanged. Returns an empty string when not set.
     */
    public function getParsedVideoEmbedAttribute(): string
    {
        if (empty($this->product_video_embed)) {
            return '';
        }
        return \App\Services\ContentParserService::parse($this->product_video_embed);
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

    /**
     * Check if a product requires selecting options or filling required customization fields
     * before adding to cart (e.g. multiple variants, required custom fields, or donation product).
     */
    public function requiresOptions(): bool
    {
        if ($this->is_donation_or_bill_pay) {
            return true;
        }

        if ($this->variants->count() > 1) {
            return true;
        }

        if ($this->relationLoaded('fields')) {
            if ($this->fields->where('is_required', 1)->isNotEmpty()) {
                return true;
            }
        } else {
            if ($this->fields()->where('is_required', 1)->exists()) {
                return true;
            }
        }

        return false;
    }
}
