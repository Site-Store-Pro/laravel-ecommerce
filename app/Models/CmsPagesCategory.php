<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class CmsPagesCategory extends Model
{
    use HasTranslations;

    protected $table = 'cms_pages_categories';

    protected array $translatable = ['name'];

    protected function translationForeignKey(): string
    {
        return 'cms_pages_category_id';
    }

    protected $fillable = ['name', 'slug'];

    public function pages()
    {
        return $this->belongsToMany(CmsPage::class, 'cms_page_category', 'category_id', 'cms_page_id')->withTimestamps();
    }
}
