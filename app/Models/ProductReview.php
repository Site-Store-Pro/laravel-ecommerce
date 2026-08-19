<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReview extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'product_reviews';

    protected array $translatable = ['comments'];

    protected $fillable = [
        'product_id',
        'name',
        'location',
        'rating',
        'comments',
        'approved'
    ];

    protected $casts = [
        'product_id' => 'integer',
        'rating' => 'integer',
        'approved' => 'boolean'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
