<button {{ $attributes->merge(['type' => 'submit', 'class' => 'primary-btn btn-theme-primary inline-flex items-center justify-center px-4 py-2.5 font-bold text-sm text-white focus:outline-none transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
