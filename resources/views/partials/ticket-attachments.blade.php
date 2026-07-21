@if ($attachments->isNotEmpty())
    <div class="mt-4 flex flex-wrap gap-3">
        @foreach ($attachments as $attachment)
            <a href="{{ route('tickets.attachments.download', [$attachment->ticket_id, $attachment]) }}"
               class="inline-flex items-center gap-2 rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50"
               target="_blank">
                @if ($attachment->isImage())
                    <img src="{{ $attachment->url() }}" alt="{{ $attachment->original_name }}" class="h-10 w-10 rounded object-cover" />
                @endif
                <span>{{ $attachment->original_name }}</span>
            </a>
        @endforeach
    </div>
@endif
