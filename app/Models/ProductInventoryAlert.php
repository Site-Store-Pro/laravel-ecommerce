<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ProductInventoryAlert extends Model
{
    protected $table = 'product_inventory_alerts';

    protected $fillable = [
        'message',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    // ── Scopes ────────────────────────────────────────────────────────────────

    /**
     * Only active alerts, ordered by sort_order.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    /**
     * All alerts ordered by sort_order.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    // ── Relations ─────────────────────────────────────────────────────────────

    /**
     * Products that have this alert assigned.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'inventory_alert_id');
    }
}
