<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    public function ventasPorMes(Request $request)
    {
        $anio = $request->input('anio', now()->year);
        $mes = $request->input('mes');

        $query = Order::with(['user', 'items.variant.product', 'payment'])
            ->where('estado', 'pagado')
            ->whereYear('created_at', $anio);

        if ($mes) {
            $query->whereMonth('created_at', $mes);
        }

        $ordenes = $query->orderBy('created_at')->get();

        $resumenPorMes = Order::where('estado', 'pagado')
            ->whereYear('created_at', $anio)
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%m") as mes'),
                DB::raw('COUNT(*) as total_ordenes'),
                DB::raw('SUM(total) as total_ventas')
            )
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $pdf = Pdf::loadView('reportes.ventas_por_mes', compact('ordenes', 'resumenPorMes', 'anio', 'mes'));

        return $pdf->download("reporte-ventas-detallado-{$anio}.pdf");
    }

    public function ventasPorCliente(Request $request)
    {
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');

        $query = Order::with(['user', 'items.variant.product', 'payment'])
            ->where('estado', 'pagado');

        if ($desde) {
            $query->whereDate('created_at', '>=', $desde);
        }
        if ($hasta) {
            $query->whereDate('created_at', '<=', $hasta);
        }

        $ordenes = $query->orderBy('user_id')->orderBy('created_at')->get();
        $ordenesPorCliente = $ordenes->groupBy(fn ($o) => $o->user->name ?? 'N/D');

        $resumenPorCliente = Order::with('user:id,name,email')
            ->where('estado', 'pagado')
            ->when($desde, fn ($q) => $q->whereDate('created_at', '>=', $desde))
            ->when($hasta, fn ($q) => $q->whereDate('created_at', '<=', $hasta))
            ->select('user_id', DB::raw('COUNT(*) as total_ordenes'), DB::raw('SUM(total) as total_gastado'))
            ->groupBy('user_id')
            ->orderByDesc('total_gastado')
            ->get();

        $pdf = Pdf::loadView('reportes.ventas_por_cliente', compact('ordenesPorCliente', 'resumenPorCliente', 'desde', 'hasta'));

        return $pdf->download('reporte-ventas-cliente-detallado.pdf');
    }
}
