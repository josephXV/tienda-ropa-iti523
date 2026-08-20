<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas del carrito: agregar, actualizar, eliminar y calculo del subtotal.
 */
class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function variante(int $precio = 15000, int $stock = 10): ProductVariant
    {
        $producto = Product::factory()->create(['precio' => $precio]);

        return ProductVariant::factory()->conStock($stock)->create(['product_id' => $producto->id]);
    }

    public function test_el_carrito_requiere_autenticacion(): void
    {
        $this->get(route('carrito.index'))->assertRedirect(route('login'));
    }

    public function test_agregar_producto_crea_el_carrito_y_el_item(): void
    {
        $user = User::factory()->create();
        $variante = $this->variante();

        $response = $this->actingAs($user)->post(route('carrito.agregar'), [
            'product_variant_id' => $variante->id,
            'cantidad' => 2,
        ]);

        $response->assertRedirect(route('carrito.index'));
        $response->assertSessionHas('success');

        $cart = Cart::where('user_id', $user->id)->firstOrFail();
        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->id,
            'product_variant_id' => $variante->id,
            'cantidad' => 2,
        ]);
    }

    public function test_agregar_la_misma_variante_suma_la_cantidad_en_un_solo_item(): void
    {
        $user = User::factory()->create();
        $variante = $this->variante();

        $this->actingAs($user)->post(route('carrito.agregar'), [
            'product_variant_id' => $variante->id,
            'cantidad' => 2,
        ]);
        $this->actingAs($user)->post(route('carrito.agregar'), [
            'product_variant_id' => $variante->id,
            'cantidad' => 3,
        ]);

        $this->assertDatabaseCount('cart_items', 1);
        $this->assertSame(5, CartItem::first()->cantidad);
    }

    public function test_no_se_puede_agregar_mas_cantidad_que_el_stock(): void
    {
        $user = User::factory()->create();
        $variante = $this->variante(stock: 3);

        $response = $this->actingAs($user)
            ->from(route('productos.index'))
            ->post(route('carrito.agregar'), [
                'product_variant_id' => $variante->id,
                'cantidad' => 5,
            ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_actualizar_cambia_la_cantidad_del_item(): void
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $item = CartItem::factory()->create(['cart_id' => $cart->id, 'cantidad' => 1]);

        $this->actingAs($user)
            ->from(route('carrito.index'))
            ->patch(route('carrito.actualizar', $item), ['cantidad' => 4])
            ->assertRedirect(route('carrito.index'));

        $this->assertSame(4, $item->fresh()->cantidad);
    }

    public function test_un_usuario_no_puede_actualizar_el_carrito_de_otro(): void
    {
        $otro = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $otro->id]);
        $item = CartItem::factory()->create(['cart_id' => $cart->id, 'cantidad' => 1]);

        $this->actingAs(User::factory()->create())
            ->patch(route('carrito.actualizar', $item), ['cantidad' => 9])
            ->assertForbidden();

        $this->assertSame(1, $item->fresh()->cantidad);
    }

    public function test_eliminar_quita_el_item_del_carrito(): void
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $item = CartItem::factory()->create(['cart_id' => $cart->id]);

        $this->actingAs($user)
            ->from(route('carrito.index'))
            ->delete(route('carrito.eliminar', $item))
            ->assertRedirect(route('carrito.index'));

        $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
    }

    public function test_un_usuario_no_puede_eliminar_items_de_otro_carrito(): void
    {
        $cart = Cart::factory()->create();
        $item = CartItem::factory()->create(['cart_id' => $cart->id]);

        $this->actingAs(User::factory()->create())
            ->delete(route('carrito.eliminar', $item))
            ->assertForbidden();

        $this->assertDatabaseHas('cart_items', ['id' => $item->id]);
    }

    public function test_el_subtotal_del_carrito_se_calcula_con_precio_por_cantidad(): void
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);

        $varianteA = $this->variante(precio: 15000);
        $varianteB = $this->variante(precio: 22500);

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_variant_id' => $varianteA->id,
            'cantidad' => 2,
        ]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_variant_id' => $varianteB->id,
            'cantidad' => 3,
        ]);

        $response = $this->actingAs($user)->get(route('carrito.index'));

        $response->assertOk();
        // 15000 * 2 + 22500 * 3 = 97500
        $this->assertEquals(97500, $response->viewData('subtotal'));
        $this->assertCount(2, $response->viewData('items'));
    }
}
