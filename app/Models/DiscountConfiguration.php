<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountConfiguration extends Model
{
    protected $table = 'discount_configuration';

    protected $fillable = [
        'store_id',
        'coupon_codes',
        'preferred_customers',
        'category_discounts',
        'quantity_based',
        'value_based',
        'new_customer_discount',
        'item_specific',
        'allow_multiple_order_discounts'
    ];

    protected $casts = [
        'coupon_codes' => 'integer',
        'preferred_customers' => 'integer',
        'category_discounts' => 'integer',
        'quantity_based' => 'integer',
        'value_based' => 'integer',
        'new_customer_discount' => 'integer',
        'item_specific' => 'integer',
        'allow_multiple_order_discounts' => 'integer'
    ];
}
