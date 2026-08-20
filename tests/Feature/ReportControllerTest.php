<?php

namespace Tests\Feature;

use App\Http\Controllers\ReportController;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use ReflectionMethod;
use Tests\TestCase;

/**
 * Pruebas de los reportes PDF.
 *
 * Incluye la regresion del bug de DATE_FORMAT(): esa funcion es exclusiva de
 * MySQL y reventaba el reporte de ventas por mes sobre SQLite. Ahora la
 * expresion se elige segun el driver de la conexion.
 */
class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    /**
     * Crea una orden pagada, con item y pago, en la fecha indicada.
     */
    private function ordenPagada(User $user, string $fecha, int $total = 36400): Order
    {
        $producto = Product::factory()->create(['precio' => 15000]);
        $variante = ProductVariant::factory()->create(['product_id' => $producto->id]);

        $order = Order::factory()->enFecha($fecha)->create([
            'user_id' => $user->id,
            'subtotal' => 30000,
            'impuestos' => 3900,
            'envio' => 2500,
            'total' => $total,
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_variant_id' => $variante->id,
            'cantidad' => 2,
            'precio_unitario' => 15000,
        ]);

        Payment::factory()->create(['order_id' => $order->id]);

        return $order;
    }

    public function test_la_pantalla_de_reportes_carga(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('reportes.index'))
            ->assertOk();
    }

    public function test_los_reportes_requieren_autenticacion(): void
    {
        $this->get(route('reportes.ventas_mes'))->assertRedirect(route('login'));
        $this->get(route('reportes.ventas_cliente'))->assertRedirect(route('login'));
    }

    /**
     * Regresion: antes lanzaba "no such function: DATE_FORMAT" en SQLite.
     */
    public function test_el_reporte_de_ventas_por_mes_genera_pdf_sin_romper(): void
    {
        $user = User::factory()->create();
        $this->ordenPagada($user, '2026-03-15 10:00:00');
        $this->ordenPagada($user, '2026-08-02 10:00:00');

        $response = $this->actingAs($user)->get(route('reportes.ventas_mes', ['anio' => 2026]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }

    public function test_el_reporte_de_ventas_por_mes_filtra_por_mes(): void
    {
        $user = User::factory()->create();
        $this->ordenPagada($user, '2026-03-15 10:00:00');

        $response = $this->actingAs($user)
            ->get(route('reportes.ventas_mes', ['anio' => 2026, 'mes' => 3]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_el_reporte_de_ventas_por_mes_funciona_sin_ordenes(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('reportes.ventas_mes', ['anio' => 2026]));

        $response->assertOk();
    }

    public function test_el_reporte_de_ventas_por_cliente_genera_pdf(): void
    {
        $user = User::factory()->create();
        $this->ordenPagada($user, '2026-08-02 10:00:00');

        $response = $this->actingAs($user)->get(route('reportes.ventas_cliente', [
            'desde' => '2026-01-01',
            'hasta' => '2026-12-31',
        ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_la_expresion_de_mes_usa_strftime_en_sqlite(): void
    {
        $metodo = new ReflectionMethod(ReportController::class, 'expresionMes');
        $expresion = $metodo->invoke(new ReportController, 'created_at');

        $this->assertSame('sqlite', DB::connection()->getDriverName());
        $this->assertSame("strftime('%m', created_at)", $expresion);
        $this->assertStringNotContainsString('DATE_FORMAT', $expresion);
    }

    /**
     * El agrupado por mes debe devolver etiquetas '01'..'12' y sumar los totales
     * de cada mes por separado.
     */
    public function test_el_resumen_agrupa_las_ventas_por_mes(): void
    {
        $user = User::factory()->create();
        $this->ordenPagada($user, '2026-03-15 10:00:00', total: 10000);
        $this->ordenPagada($user, '2026-03-20 10:00:00', total: 5000);
        $this->ordenPagada($user, '2026-08-02 10:00:00', total: 7000);

        $metodo = new ReflectionMethod(ReportController::class, 'expresionMes');
        $expresion = $metodo->invoke(new ReportController, 'created_at');

        $resumen = Order::where('estado', 'pagado')
            ->whereYear('created_at', 2026)
            ->select(
                DB::raw($expresion.' as mes'),
                DB::raw('COUNT(*) as total_ordenes'),
                DB::raw('SUM(total) as total_ventas')
            )
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->keyBy('mes');

        $this->assertSame(['03', '08'], $resumen->keys()->all());
        $this->assertEquals(2, $resumen['03']->total_ordenes);
        $this->assertEquals(15000, $resumen['03']->total_ventas);
        $this->assertEquals(1, $resumen['08']->total_ordenes);
        $this->assertEquals(7000, $resumen['08']->total_ventas);
    }

    public function test_el_reporte_solo_considera_ordenes_pagadas(): void
    {
        $user = User::factory()->create();
        Order::factory()->pendiente()->enFecha('2026-05-05 10:00:00')->create(['user_id' => $user->id]);

        $ordenes = Order::where('estado', 'pagado')->whereYear('created_at', 2026)->count();

        $this->assertSame(0, $ordenes);
    }
}
