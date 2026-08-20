<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'metodo' => fake()->randomElement(['tarjeta', 'paypal']),
            'estado' => 'aprobado',
            'referencia_transaccion' => 'TXN-'.strtoupper(Str::random(12)),
        ];
    }
}
