<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        h2 { font-size: 14px; margin-top: 24px; margin-bottom: 6px; border-bottom: 2px solid #4f46e5; padding-bottom: 4px; }
        p.meta { color: #555; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background-color: #f3f4f6; }
        .total-row { font-weight: bold; background-color: #eef2ff; }
        .orden-box { margin-bottom: 18px; border: 1px solid #ddd; padding: 10px; page-break-inside: avoid; }
        .orden-header { font-weight: bold; margin-bottom: 4px; }
        .orden-meta { color: #555; font-size: 10px; margin-bottom: 6px; }
        .items-table th, .items-table td { font-size: 10px; padding: 4px 6px; }
        .resumen-fila { display: block; }
    </style>
</head>
<body>
    <h1>Reporte de Ventas {{ $mes ? '- Mes ' . $mes : '' }} - {{ $anio }}</h1>
    <p class="meta">Generado el {{ now()->format('d/m/Y H:i') }}</p>

    <h2>Resumen mensual</h2>
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
            @forelse ($resumenPorMes as $r)
                <tr>
                    <td>{{ $meses[$r->mes] ?? $r->mes }}</td>
                    <td>{{ $r->total_ordenes }}</td>
                    <td>&#8353;{{ number_format($r->total_ventas, 0) }}</td>
                </tr>
            @empty
                <tr><td colspan="3">No hay ventas registradas en {{ $anio }}.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2">Total del período</td>
                <td>&#8353;{{ number_format($resumenPorMes->sum('total_ventas'), 0) }}</td>
            </tr>
        </tfoot>
    </table>

    <h2>Detalle de pedidos</h2>

    @forelse ($ordenes as $orden)
        <div class="orden-box">
            <div class="orden-header">
                Pedido {{ $orden->numero_seguimiento }} — {{ $orden->user->name ?? 'N/D' }}
            </div>
            <div class="orden-meta">
                Fecha: {{ $orden->created_at->format('d/m/Y H:i') }} &nbsp;|&nbsp;
                Cliente: {{ $orden->user->email ?? 'N/D' }} &nbsp;|&nbsp;
                Método de pago: {{ ucfirst($orden->payment->metodo ?? 'N/D') }} &nbsp;|&nbsp;
                Estado: {{ ucfirst($orden->estado) }}
            </div>

            <table class="items-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Talla / Color</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orden->items as $item)
                        <tr>
                            <td>{{ $item->variant->product->nombre ?? 'Producto eliminado' }}</td>
                            <td>{{ $item->variant->talla ?? '-' }} / {{ $item->variant->color ?? '-' }}</td>
                            <td>{{ $item->cantidad }}</td>
                            <td>&#8353;{{ number_format($item->precio_unitario, 0) }}</td>
                            <td>&#8353;{{ number_format($item->cantidad * $item->precio_unitario, 0) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table style="margin-top: 6px;">
                <tr>
                    <td style="width: 25%;">Subtotal</td>
                    <td>&#8353;{{ number_format($orden->subtotal, 0) }}</td>
                </tr>
                <tr>
                    <td>Impuestos</td>
                    <td>&#8353;{{ number_format($orden->impuestos, 0) }}</td>
                </tr>
                <tr>
                    <td>Envío</td>
                    <td>&#8353;{{ number_format($orden->envio, 0) }}</td>
                </tr>
                <tr class="total-row">
                    <td>Total</td>
                    <td>&#8353;{{ number_format($orden->total, 0) }}</td>
                </tr>
            </table>
        </div>
    @empty
        <p>No hay pedidos en este período.</p>
    @endforelse
</body>
</html>
