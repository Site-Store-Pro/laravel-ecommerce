<x-mail::message>
# Ticket Status Updated

Hi {{ $ticket->user->name }},

Your support ticket **"{{ $ticket->title }}"** has been updated.

**Previous status:** {{ $previousStatus->label() }}  
**New status:** {{ $ticket->status->label() }}

Please review the latest updates using your secure ticket link below.

<x-mail::button :url="$url">
View Your Ticket
</x-mail::button>

You can reply to this email or use the link above to respond.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
