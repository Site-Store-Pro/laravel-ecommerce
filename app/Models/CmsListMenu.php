<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsListMenu extends Model
{
    use HasFactory;

    protected $table = 'cms_list_menus';

    protected $fillable = [
        'name',
        'custom_css',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(CmsListMenuItem::class, 'cms_list_menu_id')->orderBy('sort_val');
    }
}
