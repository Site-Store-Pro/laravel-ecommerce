<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsPageTranslation extends Model
{
    protected $table = 'cms_page_translations';
    protected $fillable = ['cms_page_id','language_id','title','content','meta_title','meta_description','alternate_page_title','translation_status','translated_at'];
    protected $casts = ['translated_at' => 'datetime'];
    public function page(): BelongsTo { return $this->belongsTo(CmsPage::class, 'cms_page_id'); }
    public function language(): BelongsTo { return $this->belongsTo(Language::class); }
}
