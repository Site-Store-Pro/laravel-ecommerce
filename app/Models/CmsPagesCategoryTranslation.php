<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsPagesCategoryTranslation extends Model
{
    protected $table = 'cms_pages_category_translations';
    protected $fillable = ['cms_pages_category_id', 'language_id', 'name', 'translation_status', 'translated_at'];
    protected $casts = ['translated_at' => 'datetime'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CmsPagesCategory::class, 'cms_pages_category_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
