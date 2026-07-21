<x-mail::message>
# Ticket Submitted Successfully

Hi {{ $ticket->user->name }},

Thank you for contacting support. We received your ticket **"{{ $ticket->title }}"** and our team will review it shortly.

**Current status:** {{ $ticket->status->label() }}

<x-mail::button :url="$url">
View Your Ticket
</x-mail::button>

You can also reply to this email to add updates to your ticket.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
