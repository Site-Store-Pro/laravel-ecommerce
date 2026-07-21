<?php

namespace App\Livewire;

use App\Models\Ticket;
use App\Models\TicketReply;
use App\Services\RecaptchaService;
use App\Services\TicketAttachmentService;
use App\Services\TicketNotificationService;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rules\File;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.public')]
class PublicTicketView extends Component
{
    use WithFileUploads;

    public Ticket $ticket;

    public string $body = '';

    public string $author_name = '';

    public string $author_email = '';

    public string $recaptchaToken = '';

    /** @var array<int, \Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public array $attachments = [];

    public function mount(string $token): void
    {
        $this->ticket = Ticket::query()
            ->where('token', $token)
            ->with(['user', 'attachments', 'replies.user', 'replies.attachments'])
            ->firstOrFail();

        $this->author_name = $this->ticket->user->name;
        $this->author_email = $this->ticket->user->email;
    }

    public function rules(): array
    {
        $maxKb = config('support.max_attachment_size_kb');

        return [
            'body' => ['required', 'string', 'min:2'],
            'author_name' => ['required', 'string', 'max:255'],
            'author_email' => ['required', 'email', 'max:255'],
            'attachments.*' => [
                'nullable',
                File::types(['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'txt', 'doc', 'docx'])
                    ->max($maxKb),
            ],
        ];
    }

    public function reply(
        TicketAttachmentService $attachmentService,
        TicketNotificationService $notificationService,
        RecaptchaService $recaptcha,
    ): void {
        abort_if($this->ticket->status->value === 'closed', 403, 'This ticket is closed.');

        $this->validate();

        abort_unless(
            strtolower($this->author_email) === strtolower($this->ticket->user->email),
            403,
            'Email must match the ticket owner.'
        );

        if (! $recaptcha->verify($this->recaptchaToken, 'ticket_reply')) {
            $this->addError('body', 'Security check failed. Please try again.');
            return;
        }

        $reply = TicketReply::create([
            'ticket_id'    => $this->ticket->id,
            'user_id'      => $this->ticket->user_id,
            'body'         => strip_tags($this->body),
            'via'          => 'web',
            'author_name'  => strip_tags($this->author_name),
            'author_email' => $this->author_email,
        ]);

        if (! empty($this->attachments)) {
            $attachmentService->storeForTicket($this->ticket, $this->attachments, $reply);
        }

        $notificationService->replyAdded($reply);

        $this->reset(['body', 'attachments']);
        $this->ticket->refresh()->load(['replies.user', 'replies.attachments']);

        session()->flash('status', 'Your reply has been posted.');
    }

    public function render(): View
    {
        return view('livewire.public-ticket-view');
    }
}
