<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Ticket $ticket) {}

    public function envelope(): Envelope
    {
        $tpl = \App\Services\EmailTemplateService::getActiveTemplate('ticket_submitted');
        if ($tpl) {
            $vars = [
                'customer_name' => $this->ticket->user->name ?? 'Customer',
                'ticket_title' => $this->ticket->title,
                'ticket_status' => $this->ticket->status->label(),
                'ticket_url' => $this->ticket->publicUrl(),
                'app_name' => config('app.name'),
                'year' => date('Y'),
            ];
            $subject = \App\Services\EmailTemplateService::renderSubject($tpl, $vars);
            return new Envelope(
                subject: $subject,
                replyTo: [$this->ticket->replyEmailAddress()],
            );
        }

        return new Envelope(
            subject: 'Support Ticket Submitted: '.$this->ticket->title,
            replyTo: [$this->ticket->replyEmailAddress()],
        );
    }

    public function content(): Content
    {
        $tpl = \App\Services\EmailTemplateService::getActiveTemplate('ticket_submitted');
        if ($tpl) {
            $vars = [
                'customer_name' => $this->ticket->user->name ?? 'Customer',
                'ticket_title' => $this->ticket->title,
                'ticket_status' => $this->ticket->status->label(),
                'ticket_url' => $this->ticket->publicUrl(),
                'app_name' => config('app.name'),
                'year' => date('Y'),
            ];
            $body = \App\Services\EmailTemplateService::renderBody($tpl, $vars);
            return new Content(
                htmlString: $body
            );
        }

        return new Content(
            markdown: 'emails.tickets.submitted',
            with: [
                'ticket' => $this->ticket,
                'url' => $this->ticket->publicUrl(),
            ],
        );
    }
}
