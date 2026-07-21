@props(['status'])

@php
    $colors = [
        'blue' => 'bg-blue-100 text-blue-800',
        'yellow' => 'bg-yellow-100 text-yellow-800',
        'purple' => 'bg-purple-100 text-purple-800',
        'green' => 'bg-green-100 text-green-800',
        'gray' => 'bg-gray-100 text-gray-800',
    ];
    $colorClass = $colors[$status->color()] ?? $colors['gray'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {$colorClass}"]) }}>
    {{ $status->label() }}
</span>
