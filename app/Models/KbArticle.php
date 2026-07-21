<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KbArticle extends Model
{
    use HasFactory;

    protected $table = 'kb_articles';

    protected $fillable = [
        'title',
        'meta_description',
        'seo_link',
        'category_id',
        'article_content',
        'article_active',
        'show_date',
        'date_added',
        'date_modified',
        'sort_order',
        'kb_rating',
    ];

    protected $casts = [
        'article_active' => 'integer',
        'category_id'    => 'integer',
        'show_date'      => 'boolean',
    ];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(KbCategory::class, 'category_id');
    }
}
