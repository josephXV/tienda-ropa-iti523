<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-forest border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-forest-dark focus:bg-forest-dark active:bg-forest-dark focus:outline-none focus:ring-2 focus:ring-forest focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
