<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryTranslation extends Model
{
    protected $table = 'category_translations';
    protected $fillable = ['category_id', 'language_id', 'name', 'description', 'translation_status', 'translated_at'];
    protected $casts = ['translated_at' => 'datetime'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
