<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsListMenuItem extends Model
{
    use HasFactory;
    use HasTranslations;

    protected function translationForeignKey(): string
    {
        return 'cms_list_menu_item_id';
    }

    protected $table = 'cms_list_menu_items';

    protected $fillable = [
        'cms_list_menu_id',
        'list_item',
        'sort_val',
    ];

    /** Fields automatically translated when translations relation is loaded. */
    protected array $translatable = ['list_item', 'description'];

    protected $casts = [
        'cms_list_menu_id' => 'integer',
        'sort_val' => 'float',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(CmsListMenu::class, 'cms_list_menu_id');
    }
}
