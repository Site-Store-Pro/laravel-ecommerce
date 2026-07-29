<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasTranslations;

class KbCategory extends Model
{
    use HasTranslations;

    protected $table = 'kb_categories';

    protected array $translatable = ['name', 'description'];

    protected function translationForeignKey(): string
    {
        return 'kb_category_id';
    }

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
