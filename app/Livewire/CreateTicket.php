<?php

namespace App\Livewire;

use App\Models\Ticket;
use App\Services\TicketAttachmentService;
use App\Services\TicketNotificationService;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rules\File;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class CreateTicket extends Component
{
    use WithFileUploads;

    public string $title = '';

    public string $description = '';

    /** @var array<int, \Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public array $attachments = [];

    public function rules(): array
    {
        $maxKb = config('support.max_attachment_size_kb');

        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:10'],
            'attachments.*' => [
                'nullable',
                File::types(['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'txt', 'doc', 'docx'])
                    ->max($maxKb),
            ],
        ];
    }

    public function save(
        TicketAttachmentService $attachmentService,
        TicketNotificationService $notificationService,
    ): void {
        $validated = $this->validate();

        $ticket = Ticket::create([
            'user_id'     => auth()->id(),
            'title'       => strip_tags($validated['title']),
            'description' => strip_tags($validated['description']),
        ]);

        if (! empty($this->attachments)) {
            $attachmentService->storeForTicket($ticket, $this->attachments);
        }

        $notificationService->ticketSubmitted($ticket);

        session()->flash('status', 'Your support ticket has been submitted. Check your email for confirmation.');

        $this->redirectRoute('tickets.show', $ticket, navigate: true);
    }

    public function render(): View
    {
        return view('livewire.create-ticket');
    }
}
