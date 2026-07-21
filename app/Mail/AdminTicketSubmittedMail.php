<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminTicketSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Ticket $ticket) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Support Ticket: '.$this->ticket->title,
            replyTo: [$this->ticket->replyEmailAddress()],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.tickets.admin-submitted',
            with: [
                'ticket' => $this->ticket,
                'url' => route('admin.tickets.show', $this->ticket->id),
            ],
        );
    }
}
