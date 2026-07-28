<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl text-ink">{{ $producto->nombre }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 card-surface p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    @if ($producto->imagen_path)
                        <img src="{{ asset('storage/' . $producto->imagen_path) }}" alt="{{ $producto->nombre }}" class="w-full rounded-lg">
                    @else
                        <div class="w-full h-72 bg-cream rounded-lg flex items-center justify-center text-ink/30">Sin imagen</div>
                    @endif
                </div>

                <div>
                    <p class="eyebrow mb-2">{{ $producto->category->nombre }}</p>
                    <h1 class="font-display text-2xl text-ink mb-3">{{ $producto->nombre }}</h1>
                    <span class="price-tag">&#8353;{{ number_format($producto->precio, 0) }}</span>
                    <p class="text-ink/70 mt-4 leading-relaxed">{{ $producto->descripcion }}</p>

                    <form method="POST" action="{{ route('carrito.agregar') }}" class="mt-6">
                        @csrf
                        <label class="eyebrow block mb-1">Variante</label>
                        <select name="product_variant_id" class="field w-full" required>
                            @foreach ($producto->variants as $variante)
                                <option value="{{ $variante->id }}" {{ $variante->stock <= 0 ? 'disabled' : '' }}>
                                    Talla {{ $variante->talla }} - {{ $variante->color }}
                                    ({{ $variante->stock > 0 ? $variante->stock . ' disponibles' : 'agotado' }})
                                </option>
                            @endforeach
                        </select>

                        <label class="eyebrow block mb-1 mt-4">Cantidad</label>
                        <input type="number" name="cantidad" value="1" min="1" class="field w-full">

                        <button type="submit" class="btn-primary w-full mt-6">
                            Agregar al carrito
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
