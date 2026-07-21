@php
    $scripts = \App\Models\CmsSetting::get('custom_js_loader', '');
@endphp
@if($scripts)
    {!! $scripts !!}
@endif
