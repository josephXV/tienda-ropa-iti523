#!/bin/bash
# Aplica un rediseño visual coherente a toda la tienda: paleta verde botella + dorado
# sobre fondo crema, tipografia editorial (Fraunces + Work Sans), y precios en
# formato "etiqueta de ropa". Ejecutar DENTRO de la carpeta del proyecto Laravel.
# Uso: bash rediseno-tienda.sh

set -e

if [ ! -f "artisan" ]; then
    echo "ERROR: no se encontro 'artisan' en este directorio."
    echo "Ejecuta este script DENTRO de la carpeta del proyecto Laravel."
    exit 1
fi

echo ">>> Configurando Tailwind con la nueva paleta y tipografia"
cat > tailwind.config.js << 'EOF'
import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Work Sans"', ...defaultTheme.fontFamily.sans],
                display: ['"Fraunces"', ...defaultTheme.fontFamily.serif],
                mono: ['"IBM Plex Mono"', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                ink: '#182430',
                forest: {
                    DEFAULT: '#1F5C4F',
                    dark: '#153F37',
                    light: '#2E7566',
                },
                gold: {
                    DEFAULT: '#C9A227',
                    light: '#E4C662',
                },
                cream: '#F8F4EC',
                card: '#FFFFFF',
            },
        },
    },

    plugins: [forms],
};
EOF

echo ">>> Escribiendo app.css con fuentes y componentes base"
cat > resources/css/app.css << 'EOF'
@import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,600;9..144,700&family=Work+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@500&display=swap');

@tailwind base;
@tailwind components;
@tailwind utilities;

@layer base {
    body {
        @apply bg-cream text-ink font-sans;
    }
    h1, h2, h3, h4 {
        @apply font-display;
    }
}

@layer components {
    .eyebrow {
        @apply font-mono text-[11px] uppercase tracking-[0.18em] text-forest;
    }

    .price-tag {
        @apply relative inline-flex items-center bg-ink text-cream font-mono text-sm pl-4 pr-3 py-1 rounded-sm;
    }
    .price-tag::before {
        content: '';
        @apply absolute left-1.5 top-1/2 -translate-y-1/2 w-1.5 h-1.5 bg-white rounded-full border border-ink;
    }

    .btn-primary {
        @apply inline-flex items-center justify-center bg-forest hover:bg-forest-dark text-white font-medium rounded-md px-5 py-2.5 transition-colors disabled:opacity-50 disabled:pointer-events-none;
    }
    .btn-secondary {
        @apply inline-flex items-center justify-center border border-forest text-forest hover:bg-forest hover:text-white font-medium rounded-md px-4 py-2 transition-colors;
    }
    .btn-danger {
        @apply inline-flex items-center justify-center bg-red-700 hover:bg-red-800 text-white font-medium rounded-md px-4 py-2 transition-colors;
    }
    .btn-gold {
        @apply inline-flex items-center justify-center bg-gold hover:bg-gold-light text-ink font-semibold rounded-md px-5 py-2.5 transition-colors;
    }

    .card-surface {
        @apply bg-card border border-ink/10 rounded-lg shadow-sm;
    }
    .card-product {
        @apply bg-card border border-ink/10 rounded-lg overflow-hidden shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all;
    }
    .field {
        @apply border-ink/15 rounded-md focus:border-forest focus:ring-forest;
    }
}
EOF

echo ">>> Actualizando componentes compartidos (nav, botones, inputs)"

cat > resources/views/components/nav-link.blade.php << 'EOF'
@props(['active' => false])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-forest text-xs font-medium uppercase tracking-widest leading-5 text-ink focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-xs font-medium uppercase tracking-widest leading-5 text-ink/50 hover:text-ink hover:border-ink/20 focus:outline-none focus:text-ink transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
EOF

cat > resources/views/components/responsive-nav-link.blade.php << 'EOF'
@props(['active' => false])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-forest text-start text-base font-medium text-forest bg-forest/5 focus:outline-none focus:text-forest focus:bg-forest/10 focus:border-forest transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-ink/60 hover:text-ink hover:bg-ink/5 hover:border-ink/20 focus:outline-none focus:text-ink focus:bg-ink/5 focus:border-ink/20 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
EOF

cat > resources/views/components/primary-button.blade.php << 'EOF'
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-forest border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-forest-dark focus:bg-forest-dark active:bg-forest-dark focus:outline-none focus:ring-2 focus:ring-forest focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
EOF

cat > resources/views/components/secondary-button.blade.php << 'EOF'
<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-ink/20 rounded-md font-semibold text-xs text-ink uppercase tracking-widest shadow-sm hover:bg-ink/5 focus:outline-none focus:ring-2 focus:ring-forest focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
EOF

cat > resources/views/components/danger-button.blade.php << 'EOF'
<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-800 focus:bg-red-800 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
EOF

cat > resources/views/components/text-input.blade.php << 'EOF'
@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-ink/20 focus:border-forest focus:ring-forest rounded-md shadow-sm']) }}>
EOF

echo ">>> Rediseñando el menú de navegación"
cat > resources/views/layouts/navigation.blade.php << 'EOF'
<nav x-data="{ open: false }" class="bg-cream/95 backdrop-blur border-b border-ink/10 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-gold"></span>
                        <span class="font-display font-bold text-lg text-ink tracking-tight">Proyecto Web</span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('productos.index')" :active="request()->routeIs('productos.*')">
                        {{ __('Productos') }}
                    </x-nav-link>

                    <x-nav-link :href="route('carrito.index')" :active="request()->routeIs('carrito.*')">
                        {{ __('Carrito') }}
                    </x-nav-link>

                    <x-nav-link :href="route('reportes.index')" :active="request()->routeIs('reportes.*')">
                        {{ __('Reportes') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-ink/60 hover:text-ink focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-ink/50 hover:text-ink hover:bg-ink/5 focus:outline-none focus:bg-ink/5 focus:text-ink transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-cream border-t border-ink/10">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('productos.index')" :active="request()->routeIs('productos.*')">
                {{ __('Productos') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('carrito.index')" :active="request()->routeIs('carrito.*')">
                {{ __('Carrito') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('reportes.index')" :active="request()->routeIs('reportes.*')">
                {{ __('Reportes') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-ink/10">
            <div class="px-4">
                <div class="font-medium text-base text-ink">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-ink/50">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
EOF

echo ">>> Rediseñando el catálogo de productos"
cat > resources/views/productos/index.blade.php << 'EOF'
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
                                    <p class="text-xs font-medium text-ink truncate">{{ $producto->nombre }}</p>
                                    <p class="text-xs font-mono text-forest mt-0.5">&#8353;{{ number_format($producto->precio, 0) }}</p>
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
EOF

echo ">>> Rediseñando el detalle de producto"
cat > resources/views/productos/show.blade.php << 'EOF'
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
EOF

echo ">>> Rediseñando el carrito"
cat > resources/views/carrito/index.blade.php << 'EOF'
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
EOF

echo ">>> Rediseñando el checkout"
cat > resources/views/checkout/index.blade.php << 'EOF'
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
EOF

echo ">>> Rediseñando la confirmación de pedido"
cat > resources/views/checkout/confirmacion.blade.php << 'EOF'
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
EOF

echo ">>> Rediseñando el historial de pedidos"
cat > resources/views/checkout/historial.blade.php << 'EOF'
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl text-ink">Mis Pedidos</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 card-surface p-6">
            @forelse ($ordenes as $orden)
                <a href="{{ route('ordenes.confirmacion', $orden->id) }}" class="block border-b border-ink/10 py-4 hover:bg-cream transition-colors -mx-6 px-6">
                    <div class="flex justify-between items-center">
                        <span class="font-mono text-sm text-ink">{{ $orden->numero_seguimiento }}</span>
                        <span class="text-ink/50 text-sm">{{ $orden->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center mt-1">
                        <span class="eyebrow">{{ ucfirst($orden->estado) }}</span>
                        <span class="price-tag">&#8353;{{ number_format($orden->total, 0) }}</span>
                    </div>
                </a>
            @empty
                <p class="text-ink/50">Aún no tienes pedidos.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
EOF

echo ">>> Rediseñando la sección de reportes"
cat > resources/views/reportes/index.blade.php << 'EOF'
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl text-ink">Reportes de Ventas</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="card-surface p-6">
                <p class="eyebrow mb-1">Reporte mensual</p>
                <h3 class="font-display text-lg text-ink mb-4">Ventas por mes</h3>
                <form method="GET" action="{{ route('reportes.ventas_mes') }}" class="flex flex-wrap items-end gap-3">
                    <div>
                        <label class="eyebrow block mb-1">Año</label>
                        <input type="number" name="anio" value="{{ now()->year }}" class="field">
                    </div>
                    <div>
                        <label class="eyebrow block mb-1">Mes (opcional)</label>
                        <select name="mes" class="field">
                            <option value="">Todos</option>
                            @foreach (['01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto','09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'] as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-primary">
                        Descargar PDF
                    </button>
                </form>
            </div>

            <div class="card-surface p-6">
                <p class="eyebrow mb-1">Reporte por cliente</p>
                <h3 class="font-display text-lg text-ink mb-4">Ventas por cliente</h3>
                <form method="GET" action="{{ route('reportes.ventas_cliente') }}" class="flex flex-wrap items-end gap-3">
                    <div>
                        <label class="eyebrow block mb-1">Desde</label>
                        <input type="date" name="desde" class="field">
                    </div>
                    <div>
                        <label class="eyebrow block mb-1">Hasta</label>
                        <input type="date" name="hasta" class="field">
                    </div>
                    <button type="submit" class="btn-primary">
                        Descargar PDF
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
EOF

echo ">>> Compilando assets"
npm run build

echo ""
echo "=================================================="
echo "Rediseño aplicado. Reinicia el servidor si estaba corriendo:"
echo "  php artisan serve"
echo "Recarga el navegador con Ctrl+Shift+R para ver los cambios."
echo "=================================================="
