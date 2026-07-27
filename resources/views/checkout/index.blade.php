<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Confirmar Compra</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if (session('error'))
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">{{ session('error') }}</div>
            @endif

            <div class="bg-white rounded shadow p-6">
                <h3 class="font-semibold mb-3">Resumen del pedido</h3>

                @foreach ($items as $item)
                    <div class="flex justify-between border-b py-2 text-sm">
                        <span>{{ $item->variant->product->nombre }} ({{ $item->variant->talla }}/{{ $item->variant->color }}) x{{ $item->cantidad }}</span>
                        <span>₡{{ number_format($item->cantidad * $item->variant->product->precio, 0) }}</span>
                    </div>
                @endforeach

                <div class="mt-4 space-y-1 text-sm">
                    <div class="flex justify-between"><span>Subtotal</span><span>₡{{ number_format($subtotal, 0) }}</span></div>
                    <div class="flex justify-between"><span>Impuestos (13%)</span><span>₡{{ number_format($impuestos, 0) }}</span></div>
                    <div class="flex justify-between"><span>Envío</span><span>₡{{ number_format($envio, 0) }}</span></div>
                    <div class="flex justify-between font-bold text-lg border-t pt-2"><span>Total</span><span>₡{{ number_format($total, 0) }}</span></div>
                </div>

                <form method="POST" action="{{ route('checkout.procesar') }}" class="mt-6">
                    @csrf
                    <label class="block text-sm font-medium text-gray-700">Método de pago</label>
                    <select name="metodo_pago" class="border rounded px-3 py-2 w-full mt-1" required>
                        <option value="tarjeta">Tarjeta de crédito</option>
                        <option value="paypal">PayPal</option>
                    </select>

                    <button type="submit" class="mt-4 bg-green-600 text-white rounded px-4 py-2 w-full">
                        Pagar y confirmar pedido
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
