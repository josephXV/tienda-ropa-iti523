<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; }
        h1 { font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f3f4f6; }
        .total-row { font-weight: bold; }
    </style>
</head>
<body>
    <h1>Reporte de Ventas por Mes - {{ $anio }}</h1>
    <p>Generado el {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>Mes</th>
                <th>Cantidad de Órdenes</th>
                <th>Total Ventas</th>
            </tr>
        </thead>
        <tbody>
            @php $meses = ['01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto','09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre']; @endphp
            @forelse ($ventas as $venta)
                <tr>
                    <td>{{ $meses[$venta->mes] ?? $venta->mes }}</td>
                    <td>{{ $venta->total_ordenes }}</td>
                    <td>&#8353;{{ number_format($venta->total_ventas, 0) }}</td>
                </tr>
            @empty
                <tr><td colspan="3">No hay ventas registradas en {{ $anio }}.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2">Total del año</td>
                <td>&#8353;{{ number_format($ventas->sum('total_ventas'), 0) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
