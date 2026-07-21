<?php

namespace App\Livewire;

use App\Models\Ticket;
use App\Models\TicketReply;
use App\Services\TicketAttachmentService;
use App\Services\TicketNotificationService;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rules\File;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class ShowTicket extends Component
{
    use WithFileUploads;

    public Ticket $ticket;

    public string $body = '';

    /** @var array<int, \Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public array $attachments = [];

    public function mount(Ticket $ticket): void
    {
        abort_unless(
            auth()->user()->isAdmin() || $ticket->user_id === auth()->id(),
            403
        );

        $this->ticket = $ticket->load(['user', 'assignee', 'attachments', 'replies.user', 'replies.attachments']);
    }

    public function rules(): array
    {
        $maxKb = config('support.max_attachment_size_kb');

        return [
            'body' => ['required', 'string', 'min:2'],
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
    ): void {
        abort_if($this->ticket->status->value === 'closed', 403, 'This ticket is closed.');

        $this->validate();

        $reply = TicketReply::create([
            'ticket_id' => $this->ticket->id,
            'user_id'   => auth()->id(),
            'body'      => strip_tags($this->body),
            'via'       => 'web',
        ]);

        if (! empty($this->attachments)) {
            $attachmentService->storeForTicket($this->ticket, $this->attachments, $reply);
        }

        $notificationService->replyAdded($reply);

        $this->reset(['body', 'attachments']);
        $this->ticket->refresh()->load(['replies.user', 'replies.attachments', 'attachments']);

        session()->flash('status', 'Your reply has been posted.');
    }

    public function render(): View
    {
        return view('livewire.show-ticket');
    }
}
