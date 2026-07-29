<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\ProductVariantEvent;
use App\Traits\HasTranslations;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductVariant extends Model
{
    use HasFactory, HasTranslations;

    /** Fields returned in the active language when translations are eager-loaded. */
    protected array $translatable = [
        'personalization_label',
        'personalization_details_label',
        'personalization_placeholder',
    ];


    protected $table = 'product_variants';

    protected $touches = ['product'];

    protected $fillable = [
        'product_id',
        'sku',
        'public_price',
        'wholesale_price',
        'on_sale',
        'sale_price',
        'shipping',
        'weight',
        'weight_type',
        'attributes',
        'download_item',
        'charge_tax',
        'download_location',
        'direct_download_url',
        'download_label',
        'download_expiration',
        'downloads_max_allowed',
        'download_s3',
        'download_s3_region',
        'download_s3_bucket_name',
        'download_s3_access_key_id',
        'download_s3_secret_access_key',
        'subscription',
        'video_item',
        'video_preview',
        'video_purchase',
        'download_cdn_url',
        'variant_fee',
        'wholesale_variant_fee',
        'personalization_active',
        'personalization_fee',
        'personalization_label',
        'personalization_details_label',
        'personalization_placeholder',
        // Payment processor price IDs
        'paddle_sandbox_price_id',
        'paddle_live_price_id',
        'stripe_sandbox_price_id',
        'stripe_live_price_id',
        'create_new_stripe_product',
        'stripe_billing_interval',
        'stripe_trial_enabled',
        'stripe_trial_days',
        'paddle_price',
        'paddle_interval',
        'paddle_frequency',
        'paddle_currency_code',
        'is_event',
    ];

    protected $casts = [
        'public_price'              => 'decimal:2',
        'wholesale_price'           => 'decimal:2',
        'sale_price'                => 'decimal:2',
        'variant_fee'               => 'decimal:2',
        'wholesale_variant_fee'     => 'decimal:2',
        'personalization_active'    => 'boolean',
        'personalization_fee'       => 'decimal:2',
        'download_expiration'       => 'datetime',
        'downloads_max_allowed'     => 'integer',
        'charge_tax'                => 'integer',
        'create_new_stripe_product' => 'integer',
        'stripe_trial_enabled'      => 'integer',
        'stripe_trial_days'         => 'integer',
        'paddle_price'              => 'decimal:2',
        'paddle_frequency'          => 'integer',
        'is_event'                  => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($variant) {
            $variant->subscription = $variant->isSubscriptionVariant() ? 1 : 0;
        });
    }

    /**
     * Determine whether this variant is a subscription item.
     * A variant is a subscription if it has any Stripe price ID, create_new_stripe_product
     * is enabled, or it has a Paddle price ID configured.
     */
    public function isSubscriptionVariant(): bool
    {
        return !empty($this->stripe_sandbox_price_id)
            || !empty($this->stripe_live_price_id)
            || (int) $this->create_new_stripe_product === 1
            || !empty($this->paddle_sandbox_price_id)
            || !empty($this->paddle_live_price_id)
            || !empty($this->paddle_interval);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function inventories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductInventory::class, 'variant_id');
    }

    public function inventory(): HasOne
    {
        // For backwards compatibility: fallback to primary inventory record
        return $this->hasOne(ProductInventory::class, 'variant_id')->orderBy('location_id', 'asc');
    }

    /**
     * Resolve the available stock level.
     * If a shipping country/state is provided, it checks the location matching the address.
     * Otherwise, it sums up the available stock across all locations.
     */
    public function getStockForFulfillment(?string $countryCode = null, ?string $stateCode = null): int
    {
        if ($this->download_item) {
            return 999999;
        }

        $inventories = $this->inventories()->get();
        if ($inventories->isEmpty()) {
            return 0;
        }

        // Try to match the exact location mapping
        if ($countryCode) {
            $locationIds = \Illuminate\Support\Facades\DB::table('warehouse_locations')
                ->where('country_code', $countryCode)
                ->when($stateCode, function ($q) use ($stateCode) {
                    $q->where('state_code', $stateCode);
                })
                ->pluck('id');

            if ($locationIds->isNotEmpty()) {
                $locationInv = $inventories->whereIn('location_id', $locationIds)->first();
                if ($locationInv) {
                    return $locationInv->available_stock;
                }
            }

            // Fallback to matching country code without state code constraint
            if ($stateCode) {
                $countryLocationIds = \Illuminate\Support\Facades\DB::table('warehouse_locations')
                    ->where('country_code', $countryCode)
                    ->pluck('id');
                
                if ($countryLocationIds->isNotEmpty()) {
                    $locationInv = $inventories->whereIn('location_id', $countryLocationIds)->first();
                    if ($locationInv) {
                        return $locationInv->available_stock;
                    }
                }
            }
        }

        // Default fallback: sum of available stock across all locations
        return (int) $inventories->sum(function ($inv) {
            return $inv->available_stock;
        });
    }

    public function quantityDiscounts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductQuantityDiscount::class, 'product_variant_id')->orderBy('qty_min');
    }

    public function eventDetails(): HasOne
    {
        return $this->hasOne(ProductVariantEvent::class, 'variant_id');
    }

    public function images(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductImage::class, 'variant_id');
    }

    public function getStorageDisk(string $type = 'download')
    {
        $storageType = $this->download_s3;

        if ($storageType == 1) {
            // Global S3
            return Storage::disk('s3');
        } elseif ($storageType == 2) {
            // Custom S3 credentials
            $diskName = 'custom_s3_' . $this->id;
            config([
                "filesystems.disks.{$diskName}" => [
                    'driver' => 's3',
                    'key' => $this->download_s3_access_key_id,
                    'secret' => $this->download_s3_secret_access_key,
                    'region' => $this->download_s3_region,
                    'bucket' => $this->download_s3_bucket_name,
                    'use_path_style_endpoint' => false,
                ]
            ]);
            return Storage::disk($diskName);
        }

        // Local storage (public disk)
        return Storage::disk('public');
    }

    public function imageUrl(?string $path, string $type = 'image'): ?string
    {
        if (!$path) {
            return null;
        }

        if ($type === 'download') {
            $storageType = $this->download_s3;
            if ($storageType == 1 || $storageType == 2) {
                $cdn = $this->download_cdn_url ?: config('app.cdn_url');
                if ($cdn) {
                    return rtrim($cdn, '/') . '/' . ltrim($path, '/');
                }

                if ($storageType == 1) {
                    return Storage::disk('s3')->url($path);
                } else {
                    $disk = $this->getStorageDisk($type);
                    return $disk->url($path);
                }
            }
            return Storage::disk('public')->url($path);
        }

        // For image type, find matching child image record
        $image = $this->images()->where('image_path', $path)->first();
        if ($image) {
            return $image->getUrl();
        }

        return Storage::disk('public')->url($path);
    }

    public function activeImages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductImage::class, 'variant_id')->where('active', 1);
    }

    public function searchActiveImageSet(): ?ProductImage
    {
        // Ensure images relation is loaded/eager loaded
        $set = $this->images->where('active', 1)->where('search_image', 1)->first();
        if ($set) {
            return $set;
        }

        return $this->images->where('active', 1)->first();
    }

    public function thumbnailImageUrl(): ?string
    {
        $img = $this->searchActiveImageSet();
        return $img ? $img->thumbnailUrl() : null;
    }

    public function mainImageUrl(): ?string
    {
        $img = $this->searchActiveImageSet();
        return $img ? $img->mainUrl() : null;
    }

    public function zoomImageUrl(): ?string
    {
        $img = $this->searchActiveImageSet();
        return $img ? $img->zoomUrl() : null;
    }
}
