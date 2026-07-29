<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsListMenuItemTranslation extends Model
{
    protected $table = 'cms_list_menu_item_translations';
    protected $fillable = ['cms_list_menu_item_id','language_id','list_item','translation_status','translated_at'];
    protected $casts = ['translated_at' => 'datetime'];
    public function menuItem(): BelongsTo { return $this->belongsTo(CmsListMenuItem::class, 'cms_list_menu_item_id'); }
    public function language(): BelongsTo { return $this->belongsTo(Language::class); }
}
