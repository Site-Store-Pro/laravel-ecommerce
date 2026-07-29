<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NavItemTranslation extends Model
{
    protected $table = 'nav_item_translations';
    protected $fillable = ['nav_item_id','language_id','label','html_content','translation_status','translated_at'];
    protected $casts = ['translated_at' => 'datetime'];
    public function navItem(): BelongsTo { return $this->belongsTo(NavItem::class, 'nav_item_id'); }
    public function language(): BelongsTo { return $this->belongsTo(Language::class); }
}
