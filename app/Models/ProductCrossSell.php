<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductCrossSell extends Model
{
    protected $table = 'product_cross_selling';

    protected $fillable = [
        'product_id',
        'cross_sell_product_id',
        'sort_order',
        'display_on_item_view',
        'display_on_post_cart',
    ];

    protected $casts = [
        'sort_order'           => 'float',
        'display_on_item_view' => 'boolean',
        'display_on_post_cart' => 'boolean',
    ];

    /**
     * The parent product that owns this cross-sell entry.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * The product being cross-sold.
     */
    public function crossSellProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'cross_sell_product_id');
    }
}
