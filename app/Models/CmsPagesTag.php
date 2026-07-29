<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class CmsPagesTag extends Model
{
    use HasTranslations;

    protected $table = 'cms_pages_tags';

    protected array $translatable = ['name'];

    protected function translationForeignKey(): string
    {
        return 'cms_pages_tag_id';
    }

    protected $fillable = ['name', 'slug'];

    public function pages()
    {
        return $this->belongsToMany(CmsPage::class, 'cms_page_tag', 'tag_id', 'cms_page_id')->withTimestamps();
    }
}
