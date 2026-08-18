<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_details';

    protected $fillable = [
        'order_id',
        'item_name',
        'item_qty',
        'final_price',
        'base_price',
        'discount_price',
        'options_fee',
        'options_list',
        'inventory_id',
        'download_item',
        'item_taxable',
        'download_location',
        'download_expiration',
        'downloads_counter',
        'downloads_max_allowed',
        'download_s3',
        'download_s3_region',
        'download_s3_bucket_name',
        'download_s3_access_key_id',
        'download_s3_secret_access_key',
        'download_cdn_url',
        'subscription',
        'subscription_user_id',
        'subscription_plan_id',
        'subscription_provider',
        'subscription_plan_total',
        'subscription_plan_remaining',
        'subscription_type',
        'subscription_status',
        'active_subscription',
    ];

    protected $casts = [
        'item_qty' => 'decimal:3',
        'final_price' => 'decimal:2',
        'base_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'options_fee' => 'decimal:2',
        'download_expiration' => 'datetime',
        'subscription_plan_total' => 'decimal:2',
        'subscription_plan_remaining' => 'decimal:2',
        'item_taxable' => 'integer',
        'active_subscription' => 'boolean',
    ];

    public function isActiveSubscription(): bool
    {
        return (bool) $this->active_subscription;
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(OrderDownload::class, 'order_details_id');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'inventory_id');
    }
}
