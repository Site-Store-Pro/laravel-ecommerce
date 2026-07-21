<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDownload extends Model
{
    use HasFactory;

    protected $table = 'order_downloads';

    protected $fillable = [
        'order_details_id',
        'user_id',
        'download_date'
    ];

    protected $casts = [
        'download_date' => 'datetime',
    ];

    public function detail(): BelongsTo
    {
        return $this->belongsTo(OrderDetail::class, 'order_details_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
