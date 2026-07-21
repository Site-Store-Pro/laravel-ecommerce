<x-mail::message>
# New Reply on Your Ticket

There is a new reply on ticket **"{{ $ticket->title }}"** from **{{ $reply->authorLabel() }}**.

@component('mail::panel')
{{ \Illuminate\Support\Str::limit($reply->body, 500) }}
@endcomponent

<x-mail::button :url="$url">
View Conversation
</x-mail::button>

Reply to this email to continue the conversation.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
