<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KbCategory extends Model
{
    protected $table = 'kb_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(KbArticle::class, 'category_id');
    }
}
