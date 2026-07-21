<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsFormSubmission extends Model
{
    protected $fillable = [
        'form_id',
        'data',
        'ip_address',
        'user_agent',
        'submitted_at',
    ];

    protected $casts = [
        'data'         => 'array',
        'submitted_at' => 'datetime',
    ];

    // ── Relations ────────────────────────────────────────────────────────────

    public function form(): BelongsTo
    {
        return $this->belongsTo(CmsForm::class, 'form_id');
    }
}
