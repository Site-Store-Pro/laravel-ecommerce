<?php

namespace App\Livewire;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\TicketReply;
use App\Models\User;
use App\Services\TicketAttachmentService;
use App\Services\TicketNotificationService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class AdminTicketShow extends Component
{
    use WithFileUploads;

    public Ticket $ticket;

    public string $status = '';

    public ?int $assigned_to = null;

    public string $body = '';

    public string $aiResponse = '';

    /** @var array<int, \Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public array $attachments = [];

    public function mount(Ticket $ticket): void
    {
        $user = auth()->user();
        if ($user->isAdmin() || $user->isTicketManager()) {
            // Allow all admins and ticket managers
        } else {
            abort(403, 'Unauthorized access to this ticket.');
        }

        $this->ticket = $ticket->load(['user', 'assignee', 'attachments', 'replies.user', 'replies.attachments']);
        $this->status = $ticket->status->value;
        $this->assigned_to = $ticket->assigned_to;
    }

    public function rules(): array
    {
        $maxKb = config('support.max_attachment_size_kb');

        return [
            'status' => ['required', 'in:'.implode(',', array_column(TicketStatus::cases(), 'value'))],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'body' => ['nullable', 'string', 'min:2'],
            'attachments.*' => [
                'nullable',
                File::types(['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'txt', 'doc', 'docx'])
                    ->max($maxKb),
            ],
        ];
    }

    public function updateStatus(TicketNotificationService $notificationService): void
    {
        $this->validateOnly('status');
        $this->validateOnly('assigned_to');

        $previousStatus = $this->ticket->status;

        $this->ticket->update([
            'status' => TicketStatus::from($this->status),
            'assigned_to' => $this->assigned_to,
        ]);

        if ($previousStatus !== $this->ticket->status) {
            $notificationService->statusUpdated($this->ticket->fresh(), $previousStatus);
        }

        $this->ticket->refresh()->load(['user', 'assignee']);

        session()->flash('status', 'Ticket status updated.');

        if (!auth()->user()->isAdmin() && (int)$this->assigned_to !== auth()->id()) {
            session()->flash('status', 'Ticket reassigned successfully.');
            $this->redirectRoute('admin.assigned-tickets', navigate: true);
        }
    }

    public function reply(
        TicketAttachmentService $attachmentService,
        TicketNotificationService $notificationService,
    ): void {
        $this->validateOnly('body');

        if (blank($this->body)) {
            return;
        }

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
        $this->ticket->refresh()->load(['replies.user', 'replies.attachments']);

        session()->flash('status', 'Admin reply posted.');
    }

    public function deleteTicket(): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        // Delete all attached files from storage
        $this->ticket->attachments->each(function (TicketAttachment $attachment) {
            Storage::disk($attachment->disk ?? 'local')->delete($attachment->filename);
        });

        $ticketId = $this->ticket->id;
        $this->ticket->delete();

        session()->flash('status', "Ticket #{$ticketId} has been deleted.");

        $this->redirectRoute('admin.tickets', navigate: true);
    }

    public function generateAiResponse(): void
    {
        if (empty(config('ai.ai_provider')) || !function_exists('ai_ticket_response')) {
            return;
        }

        $previousEntry = $this->ticket->replies->isNotEmpty()
            ? $this->ticket->replies->last()->body
            : $this->ticket->description;

        $this->aiResponse = ai_ticket_response($previousEntry);
    }

    public function copyAiResponse(): void
    {
        $this->body = $this->aiResponse;
    }

    public function render(): View
    {
        return view('livewire.admin-ticket-show', [
            'admins'   => User::query()->whereIn('role_id', [\App\Enums\UserRole::TicketManager->value, \App\Enums\UserRole::Admin->value])->orderBy('name')->get(),
            'statuses' => TicketStatus::cases(),
            'showAiButton' => !empty(config('ai.ai_provider')) && function_exists('ai_ticket_response'),
        ]);
    }
}
