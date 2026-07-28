<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl text-ink">Mis Pedidos</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 card-surface p-6">
            @forelse ($ordenes as $orden)
                <a href="{{ route('ordenes.confirmacion', $orden->id) }}" class="block border-b border-ink/10 py-4 hover:bg-cream transition-colors -mx-6 px-6">
                    <div class="flex justify-between items-center">
                        <span class="font-mono text-sm text-ink">{{ $orden->numero_seguimiento }}</span>
                        <span class="text-ink/50 text-sm">{{ $orden->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center mt-1">
                        <span class="eyebrow">{{ ucfirst($orden->estado) }}</span>
                        <span class="price-tag">&#8353;{{ number_format($orden->total, 0) }}</span>
                    </div>
                </a>
            @empty
                <p class="text-ink/50">Aún no tienes pedidos.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
