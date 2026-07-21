<?php

namespace App\Mail;

use App\Models\TicketReply;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketReplyReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public TicketReply $reply) {}

    public function envelope(): Envelope
    {
        $ticket = $this->reply->ticket;

        $tpl = \App\Services\EmailTemplateService::getActiveTemplate('ticket_reply');
        if ($tpl) {
            $vars = [
                'customer_name' => $ticket->user->name ?? 'Customer',
                'ticket_title' => $ticket->title,
                'ticket_status' => $ticket->status->label(),
                'ticket_url' => $ticket->publicUrl(),
                'reply_author' => $this->reply->authorLabel(),
                'reply_body' => $this->reply->body,
                'app_name' => config('app.name'),
                'year' => date('Y'),
            ];
            $subject = \App\Services\EmailTemplateService::renderSubject($tpl, $vars);
            return new Envelope(
                subject: $subject,
                replyTo: [$ticket->replyEmailAddress()],
            );
        }

        return new Envelope(
            subject: 'New Reply on Ticket: '.$ticket->title,
            replyTo: [$ticket->replyEmailAddress()],
        );
    }

    public function content(): Content
    {
        $ticket = $this->reply->ticket;

        $tpl = \App\Services\EmailTemplateService::getActiveTemplate('ticket_reply');
        if ($tpl) {
            $vars = [
                'customer_name' => $ticket->user->name ?? 'Customer',
                'ticket_title' => $ticket->title,
                'ticket_status' => $ticket->status->label(),
                'ticket_url' => $ticket->publicUrl(),
                'reply_author' => $this->reply->authorLabel(),
                'reply_body' => $this->reply->body,
                'app_name' => config('app.name'),
                'year' => date('Y'),
            ];
            $body = \App\Services\EmailTemplateService::renderBody($tpl, $vars);
            return new Content(
                htmlString: $body
            );
        }

        return new Content(
            markdown: 'emails.tickets.reply-received',
            with: [
                'reply' => $this->reply,
                'ticket' => $this->reply->ticket,
                'url' => $this->reply->ticket->publicUrl(),
            ],
        );
    }
}
