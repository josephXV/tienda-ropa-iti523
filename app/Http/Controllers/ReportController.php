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

        $ventas = Order::where('estado', 'pagado')
            ->whereYear('created_at', $anio)
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%m") as mes'),
                DB::raw('COUNT(*) as total_ordenes'),
                DB::raw('SUM(total) as total_ventas')
            )
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $pdf = Pdf::loadView('reportes.ventas_por_mes', compact('ventas', 'anio'));

        return $pdf->download("reporte-ventas-mes-{$anio}.pdf");
    }

    public function ventasPorCliente(Request $request)
    {
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');

        $query = Order::with('user')
            ->where('estado', 'pagado');

        if ($desde) {
            $query->whereDate('created_at', '>=', $desde);
        }
        if ($hasta) {
            $query->whereDate('created_at', '<=', $hasta);
        }

        $ventas = $query->select(
                'user_id',
                DB::raw('COUNT(*) as total_ordenes'),
                DB::raw('SUM(total) as total_gastado')
            )
            ->groupBy('user_id')
            ->with('user:id,name,email')
            ->orderByDesc('total_gastado')
            ->get();

        $pdf = Pdf::loadView('reportes.ventas_por_cliente', compact('ventas', 'desde', 'hasta'));

        return $pdf->download('reporte-ventas-cliente.pdf');
    }
}
