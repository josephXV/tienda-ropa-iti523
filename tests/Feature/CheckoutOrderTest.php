<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas del checkout: totales, creacion de la orden, descuento de stock,
 * registro del pago y vaciado del carrito.
 */
class CheckoutOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    /**
     * Deja un carrito con una sola linea: 2 unidades de un producto de 15000.
     */
    private function carritoCon(User $user, int $precio = 15000, int $stock = 10, int $cantidad = 2): ProductVariant
    {
        $producto = Product::factory()->create(['precio' => $precio]);
        $variante = ProductVariant::factory()->conStock($stock)->create(['product_id' => $producto->id]);
        $cart = Cart::factory()->create(['user_id' => $user->id]);

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_variant_id' => $variante->id,
            'cantidad' => $cantidad,
        ]);

        return $variante;
    }

    public function test_el_checkout_calcula_subtotal_impuestos_envio_y_total(): void
    {
        $user = User::factory()->create();
        $this->carritoCon($user, precio: 15000, cantidad: 2);

        $response = $this->actingAs($user)->get(route('checkout.index'));

        $response->assertOk();
        $this->assertEquals(30000, $response->viewData('subtotal'));
        $this->assertEquals(3900, $response->viewData('impuestos')); // 13% IVA
        $this->assertEquals(2500, $response->viewData('envio'));
        $this->assertEquals(36400, $response->viewData('total'));
    }

    public function test_el_checkout_con_carrito_vacio_redirige_al_carrito(): void
    {
        $user = User::factory()->create();
        Cart::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('checkout.index'))
            ->assertRedirect(route('carrito.index'))
            ->assertSessionHas('error');
    }

    public function test_procesar_pago_crea_la_orden_en_estado_pagado_con_sus_items(): void
    {
        $user = User::factory()->create();
        $variante = $this->carritoCon($user, precio: 15000, cantidad: 2);

        $response = $this->actingAs($user)->post(route('checkout.procesar'), [
            'metodo_pago' => 'tarjeta',
        ]);

        $order = Order::where('user_id', $user->id)->firstOrFail();

        $response->assertRedirect(route('ordenes.confirmacion', $order->id));

        $this->assertSame('pagado', $order->estado);
        $this->assertStringStartsWith('ORD-', $order->numero_seguimiento);
        $this->assertEquals(30000, $order->subtotal);
        $this->assertEquals(3900, $order->impuestos);
        $this->assertEquals(2500, $order->envio);
        $this->assertEquals(36400, $order->total);

        $this->assertCount(1, $order->items);
        $item = $order->items->first();
        $this->assertSame($variante->id, $item->product_variant_id);
        $this->assertSame(2, $item->cantidad);
        $this->assertEquals(15000, $item->precio_unitario);
    }

    public function test_procesar_pago_descuenta_el_stock_de_la_variante(): void
    {
        $user = User::factory()->create();
        $variante = $this->carritoCon($user, stock: 10, cantidad: 3);

        $this->actingAs($user)->post(route('checkout.procesar'), ['metodo_pago' => 'tarjeta']);

        $this->assertSame(7, $variante->fresh()->stock);
    }

    public function test_procesar_pago_registra_el_pago_aprobado(): void
    {
        $user = User::factory()->create();
        $this->carritoCon($user);

        $this->actingAs($user)->post(route('checkout.procesar'), ['metodo_pago' => 'paypal']);

        $order = Order::where('user_id', $user->id)->firstOrFail();

        $this->assertNotNull($order->payment);
        $this->assertSame('paypal', $order->payment->metodo);
        $this->assertSame('aprobado', $order->payment->estado);
        $this->assertStringStartsWith('TXN-', $order->payment->referencia_transaccion);
    }

    public function test_procesar_pago_vacia_el_carrito(): void
    {
        $user = User::factory()->create();
        $this->carritoCon($user);

        $this->actingAs($user)->post(route('checkout.procesar'), ['metodo_pago' => 'tarjeta']);

        $this->assertDatabaseCount('cart_items', 0);
        $this->assertDatabaseHas('carts', ['user_id' => $user->id]);
    }

    public function test_procesar_pago_falla_si_no_hay_stock_suficiente(): void
    {
        $user = User::factory()->create();
        $variante = $this->carritoCon($user, stock: 1, cantidad: 5);

        $this->actingAs($user)
            ->from(route('checkout.index'))
            ->post(route('checkout.procesar'), ['metodo_pago' => 'tarjeta'])
            ->assertRedirect(route('checkout.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);
        $this->assertSame(1, $variante->fresh()->stock);
        $this->assertDatabaseCount('cart_items', 1);
    }

    public function test_procesar_pago_valida_el_metodo_de_pago(): void
    {
        $user = User::factory()->create();
        $this->carritoCon($user);

        $this->actingAs($user)
            ->from(route('checkout.index'))
            ->post(route('checkout.procesar'), ['metodo_pago' => 'efectivo'])
            ->assertSessionHasErrors('metodo_pago');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_la_confirmacion_muestra_la_orden_a_su_dueno(): void
    {
        $user = User::factory()->create();
        $this->carritoCon($user);
        $this->actingAs($user)->post(route('checkout.procesar'), ['metodo_pago' => 'tarjeta']);

        $order = Order::where('user_id', $user->id)->firstOrFail();

        $this->actingAs($user)
            ->get(route('ordenes.confirmacion', $order))
            ->assertOk()
            ->assertSee($order->numero_seguimiento);
    }

    public function test_la_confirmacion_de_otro_usuario_esta_prohibida(): void
    {
        $order = Order::factory()->create();

        $this->actingAs(User::factory()->create())
            ->get(route('ordenes.confirmacion', $order))
            ->assertForbidden();
    }

    public function test_el_historial_lista_solo_las_ordenes_del_usuario(): void
    {
        $user = User::factory()->create();
        Order::factory()->count(2)->create(['user_id' => $user->id]);
        Order::factory()->create(); // de otro usuario

        $response = $this->actingAs($user)->get(route('ordenes.historial'));

        $response->assertOk();
        $this->assertCount(2, $response->viewData('ordenes'));
    }
}
