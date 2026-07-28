<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-ink/20 rounded-md font-semibold text-xs text-ink uppercase tracking-widest shadow-sm hover:bg-ink/5 focus:outline-none focus:ring-2 focus:ring-forest focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
