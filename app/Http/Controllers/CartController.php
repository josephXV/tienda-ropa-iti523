<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    private function getOrCreateCart(): Cart
    {
        return Cart::firstOrCreate(['user_id' => Auth::id()]);
    }

    public function index()
    {
        $cart = $this->getOrCreateCart();
        $items = $cart->items()->with('variant.product')->get();

        $subtotal = $items->sum(function ($item) {
            return $item->cantidad * $item->variant->product->precio;
        });

        return view('carrito.index', compact('items', 'subtotal'));
    }

    public function agregar(Request $request)
    {
        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $variante = ProductVariant::findOrFail($request->product_variant_id);

        if ($variante->stock < $request->cantidad) {
            return back()->with('error', 'No hay suficiente stock disponible.');
        }

        $cart = $this->getOrCreateCart();

        $item = $cart->items()->where('product_variant_id', $variante->id)->first();

        if ($item) {
            $item->cantidad += $request->cantidad;
            $item->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_variant_id' => $variante->id,
                'cantidad' => $request->cantidad,
            ]);
        }

        return redirect()->route('carrito.index')->with('success', 'Producto agregado al carrito.');
    }

    public function actualizar(Request $request, CartItem $cartItem)
    {
        $request->validate(['cantidad' => 'required|integer|min:1']);

        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cartItem->update(['cantidad' => $request->cantidad]);

        return back()->with('success', 'Carrito actualizado.');
    }

    public function eliminar(CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', 'Producto eliminado del carrito.');
    }
}
