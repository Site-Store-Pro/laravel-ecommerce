<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'ticket_id',
    'ticket_reply_id',
    'filename',
    'disk',
    'original_name',
    'mime_type',
    'size',
])]
class TicketAttachment extends Model
{
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function reply(): BelongsTo
    {
        return $this->belongsTo(TicketReply::class, 'ticket_reply_id');
    }

    public function url(): string
    {
        if ($this->disk === 's3') {
            return rtrim(config('app.cdn_url'), '/') . '/' . ltrim($this->filename, '/');
        }

        return Storage::disk($this->disk)->url($this->filename);
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }
}