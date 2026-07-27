<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $camisas = Category::where('slug', 'camisas')->first();
        $pantalones = Category::where('slug', 'pantalones')->first();
        $zapatos = Category::where('slug', 'zapatos')->first();

        $camisa = Product::create([
            'category_id' => $camisas->id,
            'nombre' => 'Camisa Casual Manga Larga',
            'slug' => 'camisa-casual-manga-larga',
            'descripcion' => 'Camisa de algodón, corte regular, ideal para uso diario.',
            'precio' => 15000,
            'activo' => true,
        ]);

        foreach (['S', 'M', 'L', 'XL'] as $talla) {
            foreach (['Blanco', 'Negro', 'Azul'] as $color) {
                ProductVariant::create([
                    'product_id' => $camisa->id,
                    'talla' => $talla,
                    'color' => $color,
                    'stock' => rand(5, 20),
                    'sku' => 'CAM-' . strtoupper($talla) . '-' . strtoupper(substr($color, 0, 3)),
                ]);
            }
        }

        $pantalon = Product::create([
            'category_id' => $pantalones->id,
            'nombre' => 'Pantalón Jeans Slim Fit',
            'slug' => 'pantalon-jeans-slim-fit',
            'descripcion' => 'Jeans corte slim, mezclilla resistente.',
            'precio' => 22000,
            'activo' => true,
        ]);

        foreach (['30', '32', '34', '36'] as $talla) {
            foreach (['Azul', 'Negro'] as $color) {
                ProductVariant::create([
                    'product_id' => $pantalon->id,
                    'talla' => $talla,
                    'color' => $color,
                    'stock' => rand(5, 20),
                    'sku' => 'PAN-' . $talla . '-' . strtoupper(substr($color, 0, 3)),
                ]);
            }
        }

        $zapato = Product::create([
            'category_id' => $zapatos->id,
            'nombre' => 'Tenis Urbano',
            'slug' => 'tenis-urbano',
            'descripcion' => 'Tenis casual, suela de goma antideslizante.',
            'precio' => 28000,
            'activo' => true,
        ]);

        foreach (['38', '39', '40', '41', '42'] as $talla) {
            ProductVariant::create([
                'product_id' => $zapato->id,
                'talla' => $talla,
                'color' => 'Blanco',
                'stock' => rand(5, 20),
                'sku' => 'TEN-' . $talla,
            ]);
        }
    }
}
