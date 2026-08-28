@php
    $roleId = auth()->check() ? (auth()->user()->role_id?->value ?? (int)auth()->user()->role_id) : 1;
    $isAllowedUser = !auth()->check() || in_array($roleId, [1, 2]);
    $isAdminPath = request()->is('admin*') || request()->routeIs('admin.*');

    $scripts = ($isAllowedUser && !$isAdminPath) ? \App\Models\CmsSetting::get('custom_js_loader', '') : '';
@endphp
@if(!empty($scripts))
    {!! $scripts !!}
@endif
