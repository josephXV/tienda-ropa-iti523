<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeederExtra extends Seeder
{
    public function run(): void
    {
        $camisas = Category::where('slug', 'camisas')->first();
        $pantalones = Category::where('slug', 'pantalones')->first();
        $chaquetas = Category::where('slug', 'chaquetas')->first();
        $accesorios = Category::where('slug', 'accesorios')->first();
        $zapatos = Category::where('slug', 'zapatos')->first();

        // 1. Chaqueta de cuero
        $chaqueta = Product::create([
            'category_id' => $chaquetas->id,
            'nombre' => 'Chaqueta de Cuero Sintético',
            'slug' => 'chaqueta-cuero-sintetico',
            'descripcion' => 'Chaqueta estilo motociclista, cierre frontal, forro interior.',
            'precio' => 45000,
            'activo' => true,
        ]);
        foreach (['S', 'M', 'L'] as $talla) {
            foreach (['Negro', 'Café'] as $color) {
                ProductVariant::create([
                    'product_id' => $chaqueta->id,
                    'talla' => $talla,
                    'color' => $color,
                    'stock' => rand(3, 15),
                    'sku' => 'CHQ-' . $talla . '-' . strtoupper(substr($color, 0, 3)),
                ]);
            }
        }

        // 2. Suéter de lana
        $sueter = Product::create([
            'category_id' => $camisas->id,
            'nombre' => 'Suéter de Lana Cuello Redondo',
            'slug' => 'sueter-lana-cuello-redondo',
            'descripcion' => 'Suéter tejido, ideal para clima fresco, mezcla de lana y algodón.',
            'precio' => 19500,
            'activo' => true,
        ]);
        foreach (['S', 'M', 'L', 'XL'] as $talla) {
            foreach (['Gris', 'Vino', 'Verde Oliva'] as $color) {
                ProductVariant::create([
                    'product_id' => $sueter->id,
                    'talla' => $talla,
                    'color' => $color,
                    'stock' => rand(3, 15),
                    'sku' => 'SUE-' . $talla . '-' . strtoupper(substr($color, 0, 3)),
                ]);
            }
        }

        // 3. Short deportivo
        $short = Product::create([
            'category_id' => $pantalones->id,
            'nombre' => 'Short Deportivo Running',
            'slug' => 'short-deportivo-running',
            'descripcion' => 'Short liviano de secado rápido, con bolsillo interno para llaves.',
            'precio' => 12000,
            'activo' => true,
        ]);
        foreach (['S', 'M', 'L'] as $talla) {
            foreach (['Negro', 'Azul Marino'] as $color) {
                ProductVariant::create([
                    'product_id' => $short->id,
                    'talla' => $talla,
                    'color' => $color,
                    'stock' => rand(5, 20),
                    'sku' => 'SHO-' . $talla . '-' . strtoupper(substr($color, 0, 3)),
                ]);
            }
        }

        // 4. Cinturón de cuero
        $cinturon = Product::create([
            'category_id' => $accesorios->id,
            'nombre' => 'Cinturón de Cuero Genuino',
            'slug' => 'cinturon-cuero-genuino',
            'descripcion' => 'Cinturón clásico con hebilla metálica, disponible en varias tallas.',
            'precio' => 9500,
            'activo' => true,
        ]);
        foreach (['S', 'M', 'L'] as $talla) {
            ProductVariant::create([
                'product_id' => $cinturon->id,
                'talla' => $talla,
                'color' => 'Café',
                'stock' => rand(5, 20),
                'sku' => 'CIN-' . $talla,
            ]);
        }

        // 5. Gorra ajustable
        $gorra = Product::create([
            'category_id' => $accesorios->id,
            'nombre' => 'Gorra Ajustable Bordada',
            'slug' => 'gorra-ajustable-bordada',
            'descripcion' => 'Gorra unitalla con cierre trasero ajustable, bordado frontal.',
            'precio' => 8000,
            'activo' => true,
        ]);
        foreach (['Negro', 'Blanco', 'Azul'] as $color) {
            ProductVariant::create([
                'product_id' => $gorra->id,
                'talla' => 'Única',
                'color' => $color,
                'stock' => rand(8, 25),
                'sku' => 'GOR-' . strtoupper(substr($color, 0, 3)),
            ]);
        }
    }
}
