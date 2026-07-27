<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Catálogo de Ropa
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($vistos->count() > 0)
                <div class="bg-white p-4 rounded shadow mb-6">
                    <h3 class="font-semibold text-gray-700 mb-3">Vistos recientemente</h3>
                    <div class="flex gap-4 overflow-x-auto">
                        @foreach ($vistos as $producto)
                            <a href="{{ route('productos.show', $producto->slug) }}" class="flex-shrink-0 w-40 border rounded p-2 hover:shadow">
                                @if ($producto->imagen_path)
                                    <img src="{{ asset('storage/' . $producto->imagen_path) }}" class="w-full h-24 object-cover rounded mb-1">
                                @else
                                    <div class="w-full h-24 bg-gray-200 rounded mb-1 flex items-center justify-center text-gray-400 text-xs">Sin imagen</div>
                                @endif
                                <p class="text-xs font-medium text-gray-700 truncate">{{ $producto->nombre }}</p>
                                <p class="text-xs text-indigo-600">₡{{ number_format($producto->precio, 0) }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="GET" action="{{ route('productos.index') }}" class="bg-white p-4 rounded shadow mb-6 grid grid-cols-1 md:grid-cols-5 gap-3">
                <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar producto..." class="border rounded px-3 py-2 col-span-2">

                <select name="categoria" class="border rounded px-3 py-2">
                    <option value="">Todas las categorías</option>
                    @foreach ($categorias as $cat)
                        <option value="{{ $cat->slug }}" {{ request('categoria') == $cat->slug ? 'selected' : '' }}>
                            {{ $cat->nombre }}
                        </option>
                    @endforeach
                </select>

                <input type="number" name="precio_min" value="{{ request('precio_min') }}" placeholder="Precio mín" class="border rounded px-3 py-2">
                <input type="number" name="precio_max" value="{{ request('precio_max') }}" placeholder="Precio máx" class="border rounded px-3 py-2">

                <button type="submit" class="md:col-span-5 bg-indigo-600 text-white rounded px-4 py-2">Filtrar</button>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse ($productos as $producto)
                    <a href="{{ route('productos.show', $producto->slug) }}" class="bg-white rounded shadow hover:shadow-lg transition p-4 block">
                        @if ($producto->imagen_path)
                            <img src="{{ asset('storage/' . $producto->imagen_path) }}" alt="{{ $producto->nombre }}" class="w-full h-40 object-cover rounded mb-3">
                        @else
                            <div class="w-full h-40 bg-gray-200 rounded mb-3 flex items-center justify-center text-gray-400">Sin imagen</div>
                        @endif
                        <h3 class="font-semibold text-gray-800">{{ $producto->nombre }}</h3>
                        <p class="text-sm text-gray-500">{{ $producto->category->nombre }}</p>
                        <p class="text-indigo-600 font-bold mt-1">₡{{ number_format($producto->precio, 0) }}</p>
                    </a>
                @empty
                    <p class="col-span-full text-gray-500">No se encontraron productos.</p>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $productos->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
