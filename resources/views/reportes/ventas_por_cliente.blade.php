<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        h2 { font-size: 14px; margin-top: 24px; margin-bottom: 6px; border-bottom: 2px solid #4f46e5; padding-bottom: 4px; }
        h3 { font-size: 12px; margin-top: 16px; margin-bottom: 4px; background-color: #f3f4f6; padding: 4px 6px; }
        p.meta { color: #555; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background-color: #f3f4f6; }
        .total-row { font-weight: bold; background-color: #eef2ff; }
        .orden-box { margin-bottom: 14px; border: 1px solid #ddd; padding: 8px; page-break-inside: avoid; }
        .orden-header { font-weight: bold; margin-bottom: 4px; font-size: 10px; }
        .items-table th, .items-table td { font-size: 10px; padding: 4px 6px; }
    </style>
</head>
<body>
    <h1>Reporte de Ventas por Cliente</h1>
    <p class="meta">
        Periodo: {{ $desde ?? 'Inicio' }} — {{ $hasta ?? 'Hoy' }} &nbsp;|&nbsp;
        Generado el {{ now()->format('d/m/Y H:i') }}
    </p>

    <h2>Resumen por cliente</h2>
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
            @forelse ($resumenPorCliente as $r)
                <tr>
                    <td>{{ $r->user->name ?? 'N/D' }}</td>
                    <td>{{ $r->user->email ?? 'N/D' }}</td>
                    <td>{{ $r->total_ordenes }}</td>
                    <td>&#8353;{{ number_format($r->total_gastado, 0) }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No hay ventas registradas en este periodo.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3">Total general</td>
                <td>&#8353;{{ number_format($resumenPorCliente->sum('total_gastado'), 0) }}</td>
            </tr>
        </tfoot>
    </table>

    <h2>Detalle por cliente</h2>

    @forelse ($ordenesPorCliente as $clienteNombre => $ordenesCliente)
        <h3>{{ $clienteNombre }} ({{ $ordenesCliente->count() }} pedido{{ $ordenesCliente->count() == 1 ? '' : 's' }})</h3>

        @foreach ($ordenesCliente as $orden)
            <div class="orden-box">
                <div class="orden-header">
                    Pedido {{ $orden->numero_seguimiento }} — {{ $orden->created_at->format('d/m/Y H:i') }} — Total: &#8353;{{ number_format($orden->total, 0) }}
                </div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Talla / Color</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orden->items as $item)
                            <tr>
                                <td>{{ $item->variant->product->nombre ?? 'Producto eliminado' }}</td>
                                <td>{{ $item->variant->talla ?? '-' }} / {{ $item->variant->color ?? '-' }}</td>
                                <td>{{ $item->cantidad }}</td>
                                <td>&#8353;{{ number_format($item->cantidad * $item->precio_unitario, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @empty
        <p>No hay pedidos en este período.</p>
    @endforelse
</body>
</html>
