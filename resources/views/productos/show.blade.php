<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $producto->nombre }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white rounded shadow p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    @if ($producto->imagen_path)
                        <img src="{{ asset('storage/' . $producto->imagen_path) }}" alt="{{ $producto->nombre }}" class="w-full rounded">
                    @else
                        <div class="w-full h-64 bg-gray-200 rounded flex items-center justify-center text-gray-400">Sin imagen</div>
                    @endif
                </div>

                <div>
                    <p class="text-sm text-gray-500">{{ $producto->category->nombre }}</p>
                    <p class="text-2xl font-bold text-indigo-600 mt-2">₡{{ number_format($producto->precio, 0) }}</p>
                    <p class="text-gray-700 mt-4">{{ $producto->descripcion }}</p>

                    <form method="POST" action="{{ route('carrito.agregar') }}" class="mt-6">
                        @csrf
                        <label class="block text-sm font-medium text-gray-700">Variante</label>
                        <select name="product_variant_id" class="border rounded px-3 py-2 w-full mt-1" required>
                            @foreach ($producto->variants as $variante)
                                <option value="{{ $variante->id }}" {{ $variante->stock <= 0 ? 'disabled' : '' }}>
                                    Talla {{ $variante->talla }} - {{ $variante->color }}
                                    ({{ $variante->stock > 0 ? $variante->stock . ' disponibles' : 'agotado' }})
                                </option>
                            @endforeach
                        </select>

                        <label class="block text-sm font-medium text-gray-700 mt-3">Cantidad</label>
                        <input type="number" name="cantidad" value="1" min="1" class="border rounded px-3 py-2 w-full mt-1">

                        <button type="submit" class="mt-4 bg-indigo-600 text-white rounded px-4 py-2 w-full">
                            Agregar al carrito
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
