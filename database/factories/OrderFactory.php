<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->numberBetween(10000, 100000);
        $impuestos = round($subtotal * 0.13, 2);
        $envio = 2500;

        return [
            'user_id' => User::factory(),
            'numero_seguimiento' => 'ORD-'.strtoupper(Str::random(10)),
            'estado' => 'pagado',
            'subtotal' => $subtotal,
            'impuestos' => $impuestos,
            'envio' => $envio,
            'total' => $subtotal + $impuestos + $envio,
        ];
    }

    public function pendiente(): static
    {
        return $this->state(fn () => ['estado' => 'pendiente']);
    }

    public function enFecha(string $fecha): static
    {
        return $this->state(fn () => [
            'created_at' => $fecha,
            'updated_at' => $fecha,
        ]);
    }
}
