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

    public function warehouseInventories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductInventoryWarehouse::class, 'product_inventory_id');
    }

    public function primaryWarehouse(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class, 'location_id');
    }

    /**
     * Get the dynamic stock level based on the configuration.
     * When use_warehouse_stock is enabled:
     * Available Stock = quantity_available + warehouse_stock_level + SUM(child warehouse stock_levels) - reserved_stock.
     * Otherwise:
     * Available Stock = quantity_available - reserved_stock.
     */
    public function getAvailableStockAttribute(): int
    {
        if ($this->use_warehouse_stock) {
            $childStock = $this->relationLoaded('warehouseInventories')
                ? (int) $this->warehouseInventories->sum('stock_level')
                : (int) $this->warehouseInventories()->sum('stock_level');

            return $this->quantity_available + $this->warehouse_stock_level + $childStock - $this->reserved_stock;
        }
        return $this->quantity_available - $this->reserved_stock;
    }
}
