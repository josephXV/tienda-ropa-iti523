<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        $items = $cart ? $cart->items()->with('variant.product')->get() : collect();

        if ($items->isEmpty()) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío.');
        }

        $subtotal = $items->sum(fn ($item) => $item->cantidad * $item->variant->product->precio);
        $impuestos = round($subtotal * 0.13, 2); // IVA 13% Costa Rica
        $envio = 2500; // costo fijo de envío, ajusta si quieres
        $total = $subtotal + $impuestos + $envio;

        return view('checkout.index', compact('items', 'subtotal', 'impuestos', 'envio', 'total'));
    }

    public function procesarPago(Request $request)
    {
        $request->validate([
            'metodo_pago' => 'required|in:tarjeta,paypal',
        ]);

        $cart = Cart::where('user_id', Auth::id())->first();
        $items = $cart ? $cart->items()->with('variant.product')->get() : collect();

        if ($items->isEmpty()) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío.');
        }

        // Verificar stock antes de procesar
        foreach ($items as $item) {
            if ($item->variant->stock < $item->cantidad) {
                return back()->with('error', "No hay suficiente stock de {$item->variant->product->nombre}.");
            }
        }

        $subtotal = $items->sum(fn ($item) => $item->cantidad * $item->variant->product->precio);
        $impuestos = round($subtotal * 0.13, 2);
        $envio = 2500;
        $total = $subtotal + $impuestos + $envio;

        $order = DB::transaction(function () use ($items, $subtotal, $impuestos, $envio, $total, $request, $cart) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'numero_seguimiento' => 'ORD-' . strtoupper(Str::random(10)),
                'estado' => 'pagado',
                'subtotal' => $subtotal,
                'impuestos' => $impuestos,
                'envio' => $envio,
                'total' => $total,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $item->variant->id,
                    'cantidad' => $item->cantidad,
                    'precio_unitario' => $item->variant->product->precio,
                ]);

                // Descontar stock
                $item->variant->decrement('stock', $item->cantidad);
            }

            Payment::create([
                'order_id' => $order->id,
                'metodo' => $request->metodo_pago,
                'estado' => 'aprobado',
                'referencia_transaccion' => 'TXN-' . strtoupper(Str::random(12)),
            ]);

            // Vaciar el carrito
            $cart->items()->delete();

            return $order;
        });

        return redirect()->route('ordenes.confirmacion', $order->id);
    }

    public function confirmacion(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items.variant.product', 'payment');

        return view('checkout.confirmacion', compact('order'));
    }

    public function historial()
    {
        $ordenes = Order::where('user_id', Auth::id())
            ->with('items.variant.product')
            ->orderByDesc('created_at')
            ->get();

        return view('checkout.historial', compact('ordenes'));
    }
}
