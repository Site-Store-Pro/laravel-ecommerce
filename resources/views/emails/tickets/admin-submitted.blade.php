<x-mail::message>
# New Support Ticket

A new support ticket **"{{ $ticket->title }}"** has been submitted by **{{ $ticket->user->name ?? 'a user' }}**.

@if($ticket->body)
@component('mail::panel')
{{ \Illuminate\Support\Str::limit(strip_tags($ticket->body), 500) }}
@endcomponent
@endif

<x-mail::button :url="$url">
View Ticket in Admin Console
</x-mail::button>

Reply to this email to respond directly to the customer.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
