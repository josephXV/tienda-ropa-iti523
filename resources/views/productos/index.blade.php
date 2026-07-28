<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl text-ink">Colección</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($vistos->count() > 0)
                <div class="mb-8">
                    <p class="eyebrow mb-3">Vistos recientemente</p>
                    <div class="flex gap-4 overflow-x-auto pb-2">
                        @foreach ($vistos as $producto)
                            <a href="{{ route('productos.show', $producto->slug) }}" class="flex-shrink-0 w-36 card-product">
                                @if ($producto->imagen_path)
                                    <img src="{{ asset('storage/' . $producto->imagen_path) }}" class="w-full h-24 object-cover">
                                @else
                                    <div class="w-full h-24 bg-cream flex items-center justify-center text-ink/30 text-xs">Sin imagen</div>
                                @endif
                                <div class="p-2">
                                    <p class="text-xs font-medium text-ink truncate mb-1.5">{{ $producto->nombre }}</p>
                                    <span class="price-tag-sm">&#8353;{{ number_format($producto->precio, 0) }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="GET" action="{{ route('productos.index') }}" class="card-surface p-5 mb-8 grid grid-cols-1 md:grid-cols-5 gap-3">
                <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar producto..." class="field col-span-2">

                <select name="categoria" class="field">
                    <option value="">Todas las categorías</option>
                    @foreach ($categorias as $cat)
                        <option value="{{ $cat->slug }}" {{ request('categoria') == $cat->slug ? 'selected' : '' }}>
                            {{ $cat->nombre }}
                        </option>
                    @endforeach
                </select>

                <input type="number" name="precio_min" value="{{ request('precio_min') }}" placeholder="Precio mín" class="field">
                <input type="number" name="precio_max" value="{{ request('precio_max') }}" placeholder="Precio máx" class="field">

                <button type="submit" class="md:col-span-5 btn-primary">Filtrar</button>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse ($productos as $producto)
                    <a href="{{ route('productos.show', $producto->slug) }}" class="card-product block">
                        @if ($producto->imagen_path)
                            <img src="{{ asset('storage/' . $producto->imagen_path) }}" alt="{{ $producto->nombre }}" class="w-full h-40 object-cover">
                        @else
                            <div class="w-full h-40 bg-cream flex items-center justify-center text-ink/30">Sin imagen</div>
                        @endif
                        <div class="p-4">
                            <p class="eyebrow mb-1">{{ $producto->category->nombre }}</p>
                            <h3 class="font-display text-base text-ink leading-snug">{{ $producto->nombre }}</h3>
                            <div class="mt-3">
                                <span class="price-tag">&#8353;{{ number_format($producto->precio, 0) }}</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="col-span-full text-ink/50">No se encontraron productos.</p>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $productos->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
