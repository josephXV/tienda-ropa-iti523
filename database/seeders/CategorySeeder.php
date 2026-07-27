<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Camisas', 'slug' => 'camisas'],
            ['nombre' => 'Pantalones', 'slug' => 'pantalones'],
            ['nombre' => 'Zapatos', 'slug' => 'zapatos'],
            ['nombre' => 'Chaquetas', 'slug' => 'chaquetas'],
            ['nombre' => 'Accesorios', 'slug' => 'accesorios'],
        ];

        foreach ($categorias as $categoria) {
            Category::create($categoria);
        }
    }
}
