<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mi Carrito
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">{{ session('error') }}</div>
            @endif

            <div class="bg-white rounded shadow p-6">
                @forelse ($items as $item)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b py-4 gap-4">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $item->variant->product->nombre }}</p>
                            <p class="text-sm text-gray-500">
                                Talla {{ $item->variant->talla }} - {{ $item->variant->color }}
                            </p>
                            <p class="text-indigo-600 font-bold">
                                ₡{{ number_format($item->variant->product->precio, 0) }}
                            </p>
                        </div>

                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('carrito.actualizar', $item->id) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="cantidad" value="{{ $item->cantidad }}" min="1" class="border rounded w-16 px-2 py-2 text-center">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded px-4 py-2">
                                    Actualizar
                                </button>
                            </form>

                            <form method="POST" action="{{ route('carrito.eliminar', $item->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded px-4 py-2">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">Tu carrito está vacío.</p>
                @endforelse

                @if ($items->count() > 0)
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-6 gap-4">
                        <a href="{{ route('productos.index') }}" class="text-indigo-600 text-sm font-medium hover:underline">
                            &larr; Seguir comprando
                        </a>

                        <div class="text-right">
                            <p class="text-lg font-bold text-gray-800 mb-2">Subtotal: ₡{{ number_format($subtotal, 0) }}</p>
                            <a href="{{ route('checkout.index') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold rounded px-6 py-3">
                                Ir a pagar
                            </a>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
