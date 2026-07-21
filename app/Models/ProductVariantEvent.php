<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariantEvent extends Model
{
    protected $table = 'product_variant_events';

    protected $fillable = [
        'variant_id',
        'event_start_date',
        'event_end_date',
        'event_label',
        'alternate_label',
        'label_background',
        'show_date',
        'event_location',
        'event_description',
        'event_sort',
    ];

    protected $casts = [
        'event_start_date' => 'datetime',
        'event_end_date'   => 'datetime',
        'show_date'        => 'boolean',
        'event_sort'       => 'float',
    ];

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
