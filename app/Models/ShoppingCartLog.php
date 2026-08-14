<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCartLog extends Model
{
    use HasFactory;

    protected $table = 'shopping_cart_log';

    protected $fillable = [
        'cart_log_session',
        'item_name',
        'item_qty',
        'item_price',
        'item_discount_price',
        'item_attributes',
        'item_shippable',
        'item_weight',
        'item_taxable',
        'item_downloadable',
        'variant_id',
        'order_id',
        'user_id',
        'guest_email',
        'abandoned_reminder_1_sent_at',
        'abandoned_reminder_2_sent_at',
    ];

    protected $casts = [
        'item_qty' => 'decimal:3',
        'item_price' => 'decimal:2',
        'item_discount_price' => 'decimal:2',
        'item_weight' => 'decimal:3',
    ];
}
