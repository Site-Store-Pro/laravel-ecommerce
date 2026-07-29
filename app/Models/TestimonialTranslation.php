<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestimonialTranslation extends Model
{
    protected $table = 'testimonial_translations';
    protected $fillable = ['testimonial_id','language_id','content','author_title','translation_status','translated_at'];
    protected $casts = ['translated_at' => 'datetime'];
    public function testimonial(): BelongsTo { return $this->belongsTo(CmsTestimonial::class, 'testimonial_id'); }
    public function language(): BelongsTo { return $this->belongsTo(Language::class); }
}
