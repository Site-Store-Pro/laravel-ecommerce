<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsListMenuItem extends Model
{
    use HasFactory;

    protected $table = 'cms_list_menu_items';

    protected $fillable = [
        'cms_list_menu_id',
        'list_item',
        'sort_val',
    ];

    protected $casts = [
        'cms_list_menu_id' => 'integer',
        'sort_val' => 'float',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(CmsListMenu::class, 'cms_list_menu_id');
    }
}
