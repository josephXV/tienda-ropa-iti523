<?php

namespace Tests\Unit;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Verifica las relaciones Eloquent del dominio de la tienda.
 */
class ModelRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_una_categoria_tiene_muchos_productos(): void
    {
        $categoria = Category::factory()->create();
        Product::factory()->count(3)->create(['category_id' => $categoria->id]);

        $this->assertCount(3, $categoria->products);
        $this->assertInstanceOf(Product::class, $categoria->products->first());
    }

    public function test_un_producto_pertenece_a_una_categoria(): void
    {
        $categoria = Category::factory()->create();
        $producto = Product::factory()->create(['category_id' => $categoria->id]);

        $this->assertInstanceOf(Category::class, $producto->category);
        $this->assertSame($categoria->id, $producto->category->id);
    }

    public function test_un_producto_tiene_muchas_variantes(): void
    {
        $producto = Product::factory()->create();
        ProductVariant::factory()->count(4)->create(['product_id' => $producto->id]);

        $this->assertCount(4, $producto->variants);
        $this->assertSame($producto->id, $producto->variants->first()->product->id);
    }

    public function test_un_usuario_tiene_un_carrito_y_muchas_ordenes(): void
    {
        $user = User::factory()->create();
        Cart::factory()->create(['user_id' => $user->id]);
        Order::factory()->count(2)->create(['user_id' => $user->id]);

        $this->assertInstanceOf(Cart::class, $user->cart);
        $this->assertCount(2, $user->orders);
    }

    public function test_un_carrito_tiene_muchos_items_ligados_a_variantes(): void
    {
        $cart = Cart::factory()->create();
        $variante = ProductVariant::factory()->create();
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_variant_id' => $variante->id,
            'cantidad' => 2,
        ]);

        $this->assertCount(1, $cart->items);

        $item = $cart->items->first();
        $this->assertSame($cart->id, $item->cart->id);
        $this->assertSame($variante->id, $item->variant->id);
        $this->assertInstanceOf(Product::class, $item->variant->product);
    }

    public function test_una_orden_pertenece_a_un_usuario_y_tiene_muchos_items(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);
        OrderItem::factory()->count(3)->create(['order_id' => $order->id]);

        $this->assertInstanceOf(User::class, $order->user);
        $this->assertSame($user->id, $order->user->id);
        $this->assertCount(3, $order->items);
        $this->assertInstanceOf(OrderItem::class, $order->items->first());
    }

    public function test_un_item_de_orden_apunta_a_la_variante_comprada(): void
    {
        $variante = ProductVariant::factory()->create(['talla' => 'M', 'color' => 'Morado']);
        $item = OrderItem::factory()->create(['product_variant_id' => $variante->id]);

        $this->assertSame($variante->id, $item->variant->id);
        $this->assertSame('M', $item->variant->talla);
        $this->assertSame('Morado', $item->variant->color);
    }

    public function test_una_orden_tiene_un_pago(): void
    {
        $order = Order::factory()->create();
        Payment::factory()->create(['order_id' => $order->id, 'metodo' => 'tarjeta']);

        $this->assertInstanceOf(Payment::class, $order->payment);
        $this->assertSame('tarjeta', $order->payment->metodo);
        $this->assertSame($order->id, $order->payment->order->id);
    }

    public function test_borrar_una_orden_borra_sus_items_en_cascada(): void
    {
        $order = Order::factory()->create();
        OrderItem::factory()->count(2)->create(['order_id' => $order->id]);

        $order->delete();

        $this->assertDatabaseCount('order_items', 0);
    }
}
