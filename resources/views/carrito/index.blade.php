<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl text-ink">Mi Carrito</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-forest/10 border border-forest/30 text-forest-dark px-4 py-2 rounded-md mb-4">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded-md mb-4">{{ session('error') }}</div>
            @endif

            <div class="card-surface p-6">
                @forelse ($items as $item)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-ink/10 py-4 gap-4">
                        <div>
                            <p class="font-display text-base text-ink">{{ $item->variant->product->nombre }}</p>
                            <p class="eyebrow mt-1">
                                Talla {{ $item->variant->talla }} · {{ $item->variant->color }}
                            </p>
                            <span class="price-tag mt-2">&#8353;{{ number_format($item->variant->product->precio, 0) }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('carrito.actualizar', $item->id) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="cantidad" value="{{ $item->cantidad }}" min="1" class="field w-16 text-center py-2">
                                <button type="submit" class="btn-secondary">
                                    Actualizar
                                </button>
                            </form>

                            <form method="POST" action="{{ route('carrito.eliminar', $item->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-ink/50">Tu carrito está vacío.</p>
                @endforelse

                @if ($items->count() > 0)
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-6 gap-4">
                        <a href="{{ route('productos.index') }}" class="text-forest text-sm font-medium hover:underline">
                            &larr; Seguir comprando
                        </a>

                        <div class="text-right">
                            <p class="eyebrow mb-1">Subtotal</p>
                            <p class="font-display text-xl text-ink mb-3">&#8353;{{ number_format($subtotal, 0) }}</p>
                            <a href="{{ route('checkout.index') }}" class="btn-primary">
                                Ir a pagar
                            </a>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
