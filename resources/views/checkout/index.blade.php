<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl text-ink">Confirmar Compra</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded-md mb-4">{{ session('error') }}</div>
            @endif

            <div class="card-surface p-6">
                <p class="eyebrow mb-3">Resumen del pedido</p>

                @foreach ($items as $item)
                    <div class="flex justify-between border-b border-ink/10 py-2 text-sm text-ink/80">
                        <span>{{ $item->variant->product->nombre }} ({{ $item->variant->talla }}/{{ $item->variant->color }}) x{{ $item->cantidad }}</span>
                        <span class="font-mono">&#8353;{{ number_format($item->cantidad * $item->variant->product->precio, 0) }}</span>
                    </div>
                @endforeach

                <div class="mt-4 space-y-1.5 text-sm">
                    <div class="flex justify-between text-ink/70"><span>Subtotal</span><span class="font-mono">&#8353;{{ number_format($subtotal, 0) }}</span></div>
                    <div class="flex justify-between text-ink/70"><span>Impuestos (13%)</span><span class="font-mono">&#8353;{{ number_format($impuestos, 0) }}</span></div>
                    <div class="flex justify-between text-ink/70"><span>Envío</span><span class="font-mono">&#8353;{{ number_format($envio, 0) }}</span></div>
                    <div class="flex justify-between font-display text-lg text-ink border-t border-ink/10 pt-2 mt-1"><span>Total</span><span class="font-mono">&#8353;{{ number_format($total, 0) }}</span></div>
                </div>

                <form method="POST" action="{{ route('checkout.procesar') }}" class="mt-6">
                    @csrf
                    <label class="eyebrow block mb-1">Método de pago</label>
                    <select name="metodo_pago" class="field w-full" required>
                        <option value="tarjeta">Tarjeta de crédito</option>
                        <option value="paypal">PayPal</option>
                    </select>

                    <button type="submit" class="btn-primary w-full mt-4">
                        Pagar y confirmar pedido
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
