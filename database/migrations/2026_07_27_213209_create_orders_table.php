<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('numero_seguimiento')->unique();
            $table->enum('estado', ['pendiente', 'pagado', 'enviado', 'cancelado'])->default('pendiente');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('impuestos', 10, 2);
            $table->decimal('envio', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
