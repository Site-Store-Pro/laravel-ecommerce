<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderRefund extends Model
{
    use HasFactory;

    protected $table = 'order_refunds';

    protected $fillable = [
        'order_id',
        'amount',
        'refund_date',
        'authorization_code',
        'processor_response'
    ];

    protected $casts = [
        'refund_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
