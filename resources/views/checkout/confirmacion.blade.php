<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl text-ink">Pedido Confirmado</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-forest/10 border border-forest/30 rounded-lg p-5 mb-6">
                <p class="font-display text-lg text-forest-dark">¡Gracias por tu compra!</p>
                <p class="text-sm text-ink/70 mt-1">Número de seguimiento</p>
                <span class="price-tag mt-2">{{ $order->numero_seguimiento }}</span>
            </div>

            <div class="card-surface p-6">
                <p class="text-sm text-ink/60">Fecha: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p class="text-sm text-ink/60">Estado: {{ ucfirst($order->estado) }}</p>
                <p class="text-sm text-ink/60">Método de pago: {{ ucfirst($order->payment->metodo) }}</p>
                <p class="text-sm text-ink/60">Referencia: {{ $order->payment->referencia_transaccion }}</p>

                <hr class="my-4 border-ink/10">

                @foreach ($order->items as $item)
                    <div class="flex justify-between border-b border-ink/10 py-2 text-sm text-ink/80">
                        <span>{{ $item->variant->product->nombre }} ({{ $item->variant->talla }}/{{ $item->variant->color }}) x{{ $item->cantidad }}</span>
                        <span class="font-mono">&#8353;{{ number_format($item->cantidad * $item->precio_unitario, 0) }}</span>
                    </div>
                @endforeach

                <div class="text-right mt-4 font-display text-lg text-ink">
                    Total: <span class="font-mono">&#8353;{{ number_format($order->total, 0) }}</span>
                </div>

                <a href="{{ route('productos.index') }}" class="inline-block mt-4 text-forest text-sm font-medium hover:underline">Seguir comprando</a>
            </div>

        </div>
    </div>
</x-app-layout>
