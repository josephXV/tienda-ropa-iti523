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
        $camisas    = Category::where('slug', 'camisas')->first();
        $pantalones = Category::where('slug', 'pantalones')->first();
        $zapatos    = Category::where('slug', 'zapatos')->first();

        // 1. Camisa "Purple Wave" — inspirada en el color morado de BTS
        $camisa = Product::create([
            'category_id' => $camisas->id,
            'nombre'      => 'Camisa Purple Wave',
            'slug'        => 'camisa-purple-wave',
            'descripcion' => 'Camisa de algodon en tono morado suave, corte regular. Inspirada en el color que BTS dedica a los ARMYs: "I purple you."',
            'precio'      => 15000,
            'activo'      => true,
        ]);
        $i = 1;
        foreach (['S', 'M', 'L', 'XL'] as $talla) {
            foreach (['Morado', 'Lavanda', 'Negro'] as $color) {
                ProductVariant::create([
                    'product_id' => $camisa->id,
                    'talla'      => $talla,
                    'color'      => $color,
                    'stock'      => rand(5, 20),
                    'sku'        => 'PWV-' . str_pad($i++, 3, '0', STR_PAD_LEFT),
                ]);
            }
        }

        // 2. Pantalon "DNA Slim" — inspirado en el hit "DNA"
        $pantalon = Product::create([
            'category_id' => $pantalones->id,
            'nombre'      => 'Pantalon DNA Slim',
            'slug'        => 'pantalon-dna-slim',
            'descripcion' => 'Jeans corte slim de mezclilla resistente. Diseno atemporal inspirado en el videoclip de DNA, iconico por sus patrones vibrantes.',
            'precio'      => 22000,
            'activo'      => true,
        ]);
        $i = 1;
        foreach (['30', '32', '34', '36'] as $talla) {
            foreach (['Azul Indigo', 'Negro'] as $color) {
                ProductVariant::create([
                    'product_id' => $pantalon->id,
                    'talla'      => $talla,
                    'color'      => $color,
                    'stock'      => rand(5, 20),
                    'sku'        => 'DNA-' . str_pad($i++, 3, '0', STR_PAD_LEFT),
                ]);
            }
        }

        // 3. Tenis "Dynamite White" — inspirados en el video de Dynamite
        $zapato = Product::create([
            'category_id' => $zapatos->id,
            'nombre'      => 'Tenis Dynamite White',
            'slug'        => 'tenis-dynamite-white',
            'descripcion' => 'Tenis urbanos en blanco brillante, suela de goma antideslizante. Inspirados en el look retro y colorido del MV "Dynamite".',
            'precio'      => 28000,
            'activo'      => true,
        ]);
        $i = 1;
        foreach (['38', '39', '40', '41', '42'] as $talla) {
            foreach (['Blanco', 'Blanco Dorado'] as $color) {
                ProductVariant::create([
                    'product_id' => $zapato->id,
                    'talla'      => $talla,
                    'color'      => $color,
                    'stock'      => rand(5, 20),
                    'sku'        => 'DYN-' . str_pad($i++, 3, '0', STR_PAD_LEFT),
                ]);
            }
        }
    }
}
