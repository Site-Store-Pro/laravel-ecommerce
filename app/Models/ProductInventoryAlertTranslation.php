<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductInventoryAlertTranslation extends Model
{
    protected $table = 'product_inventory_alert_translations';

    protected $fillable = [
        'product_inventory_alert_id',
        'language_id',
        'message',
        'translation_status',
        'translated_at',
    ];

    protected $casts = [
        'translated_at' => 'datetime',
    ];

    public function alert(): BelongsTo
    {
        return $this->belongsTo(ProductInventoryAlert::class, 'product_inventory_alert_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
