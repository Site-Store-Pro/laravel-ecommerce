@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-500 text-sm font-semibold leading-5 text-slate-900 dark:text-sky-300 dark:border-sky-400 focus:outline-none h-full transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-semibold leading-5 text-slate-700 hover:text-slate-900 hover:border-slate-300 dark:text-sky-400 dark:hover:text-sky-300 focus:outline-none h-full transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
