<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsPagesCategory extends Model
{
    protected $table = 'cms_pages_categories';

    protected $fillable = ['name', 'slug'];

    public function pages()
    {
        return $this->belongsToMany(CmsPage::class, 'cms_page_category', 'category_id', 'cms_page_id')->withTimestamps();
    }
}
