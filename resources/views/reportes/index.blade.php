<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reportes de Ventas</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded shadow p-6">
                <h3 class="font-semibold mb-3">Ventas por mes (detallado)</h3>
                <form method="GET" action="{{ route('reportes.ventas_mes') }}" class="flex items-end gap-3">
                    <div>
                        <label class="block text-sm text-gray-700">Año</label>
                        <input type="number" name="anio" value="{{ now()->year }}" class="border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700">Mes (opcional)</label>
                        <select name="mes" class="border rounded px-3 py-2">
                            <option value="">Todos</option>
                            @foreach (['01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto','09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'] as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white rounded px-4 py-2">
                        Descargar PDF
                    </button>
                </form>
            </div>

            <div class="bg-white rounded shadow p-6">
                <h3 class="font-semibold mb-3">Ventas por cliente (detallado)</h3>
                <form method="GET" action="{{ route('reportes.ventas_cliente') }}" class="flex items-end gap-3">
                    <div>
                        <label class="block text-sm text-gray-700">Desde</label>
                        <input type="date" name="desde" class="border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700">Hasta</label>
                        <input type="date" name="hasta" class="border rounded px-3 py-2">
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white rounded px-4 py-2">
                        Descargar PDF
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
