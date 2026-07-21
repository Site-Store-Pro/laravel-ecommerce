<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsPageType extends Model
{
    protected $table = 'cms_page_types';

    protected $fillable = ['id', 'title'];

    public $incrementing = false;

    public function pages(): HasMany
    {
        return $this->hasMany(CmsPage::class, 'page_type');
    }
}
