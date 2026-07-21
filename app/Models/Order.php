<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'order_external_id',
        'order_user_id',
        'order_status',
        'order_date',
        'order_total',
        'order_subtotal',
        'order_taxes',
        'order_discounts',
        'order_shipping',
        'order_shipping_date',
        'order_shipping_method',
        'order_shipping_tracking',
        'order_download',
        'order_invoice_no',
        'order_handling',
        'order_comments',
        'order_shipping_method_name',
        'custom_field_data',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'order_shipping_date' => 'datetime',
        'order_total' => 'decimal:2',
        'order_subtotal' => 'decimal:2',
        'order_taxes' => 'decimal:2',
        'order_discounts' => 'decimal:2',
        'order_handling' => 'decimal:2',
        'custom_field_data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'order_user_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(OrderPayment::class, 'order_id');
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(OrderRefund::class, 'order_id');
    }

    public function statusList(): BelongsTo
    {
        return $this->belongsTo(OrderStatusList::class, 'order_status', 'orderstatuscode');
    }
}
