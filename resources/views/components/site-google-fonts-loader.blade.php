@php
    $fontsHtml = \App\Models\CmsSetting::get('google_fonts_url', '');
@endphp
@if($fontsHtml)
    @if(str_contains($fontsHtml, '<'))
        {!! $fontsHtml !!}
    @else
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="{{ $fontsHtml }}" rel="stylesheet">
    @endif
@endif
