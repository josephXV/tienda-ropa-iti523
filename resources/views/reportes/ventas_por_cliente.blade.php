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
    <h1>Reporte de Ventas por Cliente</h1>
    <p>
        Periodo: {{ $desde ?? 'Inicio' }} — {{ $hasta ?? 'Hoy' }}<br>
        Generado el {{ now()->format('d/m/Y H:i') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Correo</th>
                <th>Cantidad de Órdenes</th>
                <th>Total Gastado</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ventas as $venta)
                <tr>
                    <td>{{ $venta->user->name ?? 'N/D' }}</td>
                    <td>{{ $venta->user->email ?? 'N/D' }}</td>
                    <td>{{ $venta->total_ordenes }}</td>
                    <td>&#8353;{{ number_format($venta->total_gastado, 0) }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No hay ventas registradas en este periodo.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3">Total general</td>
                <td>&#8353;{{ number_format($ventas->sum('total_gastado'), 0) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
