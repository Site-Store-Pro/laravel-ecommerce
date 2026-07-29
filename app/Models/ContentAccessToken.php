<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ContentAccessToken extends Model
{
    protected $fillable = [
        'token',
        'order_detail_id',
        'product_id',
        'redirect_url',
        'email',
        'accessed_at',
        'expires_at',
    ];

    protected $casts = [
        'accessed_at' => 'datetime',
        'expires_at'  => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────────

    public function orderDetail(): BelongsTo
    {
        return $this->belongsTo(OrderDetail::class, 'order_detail_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    /**
     * Returns true if the token has passed its expiry date.
     */
    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    /**
     * Generate (or regenerate) a ContentAccessToken for a given order detail.
     *
     * If a token already exists for this order_detail_id, it is refreshed with
     * a new UUID and a fresh 90-day expiry (admin resend flow).
     *
     * @param  \App\Models\OrderDetail  $detail
     * @param  string                   $resolvedUrl  Pre-resolved absolute URL
     * @param  string                   $email        Recipient email for audit
     * @param  int                      $expiryDays   Defaults to 90
     */
    public static function generateOrRefresh(
        OrderDetail $detail,
        string $resolvedUrl,
        string $email,
        int $expiryDays = 90
    ): self {
        $record = static::where('order_detail_id', $detail->id)->first();

        $attributes = [
            'token'       => (string) Str::uuid(),
            'product_id'  => $detail->variant->product_id ?? 0,
            'redirect_url'=> $resolvedUrl,
            'email'       => $email,
            'expires_at'  => now()->addDays($expiryDays),
        ];

        if ($record) {
            // Regenerate — reset token and expiry (admin resend)
            $record->update($attributes);
            return $record->fresh();
        }

        return static::create(array_merge(['order_detail_id' => $detail->id], $attributes));
    }
}
