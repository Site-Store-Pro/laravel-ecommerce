<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use App\Services\EmailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DynamicTemplateMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $renderedBody;
    public string $renderedSubject;

    public function __construct(public EmailTemplate $tpl, public array $vars, ?int $languageId = null)
    {
        $this->renderedSubject = EmailTemplateService::renderSubject($tpl, $vars, $languageId);
        $this->renderedBody = EmailTemplateService::renderBody($tpl, $vars, $languageId);
    }

    public function envelope(): Envelope
    {
        $envelope = new Envelope(
            subject: $this->renderedSubject,
        );

        if (!empty($this->tpl->from_address)) {
            $envelope->from = new Address(
                $this->tpl->from_address,
                $this->tpl->from_name ?: config('mail.from.name')
            );
        }

        if (!empty($this->tpl->bcc_address)) {
            $envelope->bcc = [new Address($this->tpl->bcc_address)];
        }

        return $envelope;
    }

    public function content(): Content
    {
        return new Content(
            htmlString: $this->renderedBody
        );
    }
}
