<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/productos', [ProductController::class, 'index'])->name('productos.index');
Route::get('/productos/{slug}', [ProductController::class, 'show'])->name('productos.show');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/carrito', [CartController::class, 'index'])->name('carrito.index');
    Route::post('/carrito/agregar', [CartController::class, 'agregar'])->name('carrito.agregar');
    Route::patch('/carrito/{cartItem}', [CartController::class, 'actualizar'])->name('carrito.actualizar');
    Route::delete('/carrito/{cartItem}', [CartController::class, 'eliminar'])->name('carrito.eliminar');

    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout/procesar', [OrderController::class, 'procesarPago'])->name('checkout.procesar');
    Route::get('/ordenes/{order}/confirmacion', [OrderController::class, 'confirmacion'])->name('ordenes.confirmacion');
    Route::get('/ordenes', [OrderController::class, 'historial'])->name('ordenes.historial');

    Route::get('/reportes', [ReportController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/ventas-por-mes', [ReportController::class, 'ventasPorMes'])->name('reportes.ventas_mes');
    Route::get('/reportes/ventas-por-cliente', [ReportController::class, 'ventasPorCliente'])->name('reportes.ventas_cliente');
});

require __DIR__.'/auth.php';
