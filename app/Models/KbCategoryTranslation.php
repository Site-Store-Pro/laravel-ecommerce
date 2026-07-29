<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KbCategoryTranslation extends Model
{
    protected $table = 'kb_category_translations';
    protected $fillable = ['kb_category_id', 'language_id', 'name', 'description', 'translation_status', 'translated_at'];
    protected $casts = ['translated_at' => 'datetime'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(KbCategory::class, 'kb_category_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
