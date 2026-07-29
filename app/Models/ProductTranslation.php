<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductTranslation extends Model
{
    protected $table = 'product_translations';
    protected $fillable = ['product_id','language_id','title','short_description','long_description','meta_title','meta_description','translation_status','translated_at'];
    protected $casts = ['translated_at' => 'datetime'];
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function language(): BelongsTo { return $this->belongsTo(Language::class); }
}
