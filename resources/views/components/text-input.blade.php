@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-ink/20 focus:border-forest focus:ring-forest rounded-md shadow-sm']) }}>
