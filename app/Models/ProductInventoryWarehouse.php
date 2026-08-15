<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductInventoryWarehouse extends Model
{
    use HasFactory;

    protected $table = 'product_inventory_warehouses';

    protected $fillable = [
        'product_inventory_id',
        'warehouse_location_id',
        'stock_level',
    ];

    protected $casts = [
        'stock_level' => 'integer',
    ];

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(ProductInventory::class, 'product_inventory_id');
    }

    public function warehouseLocation(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class, 'warehouse_location_id');
    }
}
