<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketAttachmentController extends Controller
{
    public function download(Ticket $ticket, TicketAttachment $attachment)
    {
        $this->authorizeAttachment($ticket, $attachment);

        abort_unless(
            Storage::disk($attachment->disk)->exists($attachment->filename),
            404
        );

        return redirect()->away($attachment->url());
    }

    private function authorizeAttachment(Ticket $ticket, TicketAttachment $attachment): void
    {
        abort_unless($attachment->ticket_id === $ticket->id, 404);

        $user = request()->user();

        if ($user && ($user->isAdmin() || $user->isTicketManager() || $ticket->user_id === $user->id)) {
            return;
        }

        abort(403);
    }
}