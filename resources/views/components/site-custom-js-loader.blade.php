@php
    // Suppress custom JS strictly on admin dashboard paths (/admin/*)
    // Allows admins to test custom scripts on the frontend while logged in
    $isAdminPath = request()->is('admin*') || request()->routeIs('admin.*');
    $scripts = !$isAdminPath ? \App\Models\CmsSetting::get('custom_js_loader', '') : '';
@endphp
@if(!empty($scripts))
    {!! $scripts !!}
@endif
