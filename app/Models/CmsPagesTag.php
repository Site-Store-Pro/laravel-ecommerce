<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsPagesTag extends Model
{
    protected $table = 'cms_pages_tags';

    protected $fillable = ['name', 'slug'];

    public function pages()
    {
        return $this->belongsToMany(CmsPage::class, 'cms_page_tag', 'tag_id', 'cms_page_id')->withTimestamps();
    }
}
