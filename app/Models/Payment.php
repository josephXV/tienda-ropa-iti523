<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'metodo', 'estado', 'referencia_transaccion'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
