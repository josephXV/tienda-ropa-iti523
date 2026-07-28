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
