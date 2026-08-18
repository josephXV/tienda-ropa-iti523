<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ImagePathSeeder extends Seeder
{
    public function run(): void
    {
        Product::all()->each(function ($p) {
            $p->imagen_path = 'productos/' . $p->slug . '.jpg';
            $p->save();
            echo $p->slug . '.jpg' . PHP_EOL;
        });
    }
}
