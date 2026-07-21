<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCheckoutOption extends Model
{
    use HasFactory;

    protected $table = 'order_checkout_options';

    protected $fillable = [
        'primary_processor',
        'secondary_processor',
        'tertiary_processor',
        'randomize_processor',
        'paypal_express',
        'retail_minimum',
        'wholesale_minimum',
        'stripe_address_required',
    ];
}
