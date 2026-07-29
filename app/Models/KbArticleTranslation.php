<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KbArticleTranslation extends Model
{
    protected $table = 'kb_article_translations';
    protected $fillable = ['kb_article_id','language_id','title','article_content','meta_description','translation_status','translated_at'];
    protected $casts = ['translated_at' => 'datetime'];
    public function article(): BelongsTo { return $this->belongsTo(KbArticle::class, 'kb_article_id'); }
    public function language(): BelongsTo { return $this->belongsTo(Language::class); }
}
