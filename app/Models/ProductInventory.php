<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductInventory extends Model
{
    use HasFactory;

    protected $table = 'products_inventory';

    protected $fillable = [
        'variant_id',
        'quantity_available',
        'warehouse_stock_level',
        'use_warehouse_stock',
        'reserved_stock',
        'location_id'
    ];

    protected $casts = [
        'use_warehouse_stock' => 'boolean',
        'quantity_available' => 'integer',
        'warehouse_stock_level' => 'integer',
        'reserved_stock' => 'integer',
        'location_id' => 'integer',
    ];

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    /**
     * Get the dynamic stock level based on the configuration.
     * Available Stock + Warehouse Stock - Reserved Stock (if enabled), otherwise Available Stock - Reserved Stock.
     */
    public function getAvailableStockAttribute(): int
    {
        if ($this->use_warehouse_stock) {
            return $this->quantity_available + $this->warehouse_stock_level - $this->reserved_stock;
        }
        return $this->quantity_available - $this->reserved_stock;
    }
}
