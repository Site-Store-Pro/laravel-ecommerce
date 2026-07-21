<?php

namespace App\Models;

use Database\Factories\TicketReplyFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'ticket_id',
    'user_id',
    'body',
    'via',
    'author_name',
    'author_email',
])]
class TicketReply extends Model
{
    /** @use HasFactory<TicketReplyFactory> */
    use HasFactory;

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class);
    }

    public function authorLabel(): string
    {
        if ($this->user) {
            return $this->user->name;
        }

        return $this->author_name ?? 'Guest';
    }
}
