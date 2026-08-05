@php
    $favicon = \App\Models\CmsSetting::resolveFaviconUrl();
@endphp

@if($favicon['type'] === 'url')
    <link rel="icon" href="{{ $favicon['value'] }}">
    <link rel="apple-touch-icon" href="{{ $favicon['value'] }}">
@elseif($favicon['type'] === 'svg')
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml;base64,{{ base64_encode($favicon['value']) }}">
@else
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
@endif
