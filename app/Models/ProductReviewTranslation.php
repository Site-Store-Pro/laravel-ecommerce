<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReviewTranslation extends Model
{
    protected $table = 'product_review_translations';

    protected $fillable = [
        'product_review_id',
        'language_id',
        'comments',
        'translation_status',
        'translated_at',
    ];

    protected $casts = [
        'translated_at' => 'datetime',
    ];

    public function review(): BelongsTo
    {
        return $this->belongsTo(ProductReview::class, 'product_review_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
