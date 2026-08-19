<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderPayment extends Model
{
    use HasFactory;

    protected $table = 'order_payments';

    protected $fillable = [
        'order_id',
        'payment_date',
        'payment_amount',
        'payment_method',
        'payment_status',
        'authorization_code',
        'processor_response',
        'subscription_next_billing_date',
        'subscription_next_billing_amount'
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'payment_amount' => 'decimal:2',
        'subscription_next_billing_date' => 'date',
        'subscription_next_billing_amount' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(OrderRefund::class, 'order_payment_id');
    }

    public function getRefundedAmountAttribute(): float
    {
        return (float) $this->refunds->sum('amount');
    }

    public function getRemainingRefundableAttribute(): float
    {
        return max(0.0, (float) $this->payment_amount - $this->refunded_amount);
    }
}
