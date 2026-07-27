<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mis Pedidos</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white rounded shadow p-6">
            @forelse ($ordenes as $orden)
                <a href="{{ route('ordenes.confirmacion', $orden->id) }}" class="block border-b py-4 hover:bg-gray-50">
                    <div class="flex justify-between">
                        <span class="font-semibold">{{ $orden->numero_seguimiento }}</span>
                        <span class="text-gray-500 text-sm">{{ $orden->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>{{ ucfirst($orden->estado) }}</span>
                        <span>₡{{ number_format($orden->total, 0) }}</span>
                    </div>
                </a>
            @empty
                <p class="text-gray-500">Aún no tienes pedidos.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
