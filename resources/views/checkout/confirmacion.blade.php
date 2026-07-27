<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pedido Confirmado</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-green-50 border border-green-200 rounded p-4 mb-6">
                <p class="text-green-800 font-semibold">¡Gracias por tu compra!</p>
                <p class="text-sm text-green-700">Número de seguimiento: <strong>{{ $order->numero_seguimiento }}</strong></p>
            </div>

            <div class="bg-white rounded shadow p-6">
                <p class="text-sm text-gray-500">Fecha: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p class="text-sm text-gray-500">Estado: {{ ucfirst($order->estado) }}</p>
                <p class="text-sm text-gray-500">Método de pago: {{ ucfirst($order->payment->metodo) }}</p>
                <p class="text-sm text-gray-500">Referencia: {{ $order->payment->referencia_transaccion }}</p>

                <hr class="my-4">

                @foreach ($order->items as $item)
                    <div class="flex justify-between border-b py-2 text-sm">
                        <span>{{ $item->variant->product->nombre }} ({{ $item->variant->talla }}/{{ $item->variant->color }}) x{{ $item->cantidad }}</span>
                        <span>₡{{ number_format($item->cantidad * $item->precio_unitario, 0) }}</span>
                    </div>
                @endforeach

                <div class="text-right mt-4 font-bold text-lg">
                    Total: ₡{{ number_format($order->total, 0) }}
                </div>

                <a href="{{ route('productos.index') }}" class="inline-block mt-4 text-indigo-600 text-sm">Seguir comprando</a>
            </div>

        </div>
    </div>
</x-app-layout>
