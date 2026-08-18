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
        $camisas    = Category::where('slug', 'camisas')->first();
        $pantalones = Category::where('slug', 'pantalones')->first();
        $chaquetas  = Category::where('slug', 'chaquetas')->first();
        $accesorios = Category::where('slug', 'accesorios')->first();
        $zapatos    = Category::where('slug', 'zapatos')->first();

        // ── CHAQUETAS ──────────────────────────────────────────────────────

        // Inspirada en "Black Swan" — Suga/Yoongi
        $chaqueta = Product::create([
            'category_id' => $chaquetas->id,
            'nombre'      => 'Chaqueta Black Swan',
            'slug'        => 'chaqueta-black-swan',
            'descripcion' => 'Chaqueta de cuero sintetico negro, estilo motociclista. Inspirada en la oscuridad elegante de "Black Swan", la oda de BTS al arte y la perdida.',
            'precio'      => 45000,
            'activo'      => true,
        ]);
        $i = 1;
        foreach (['S', 'M', 'L'] as $t) {
            foreach (['Negro', 'Gris Carbon'] as $c) {
                ProductVariant::create([
                    'product_id' => $chaqueta->id, 'talla' => $t, 'color' => $c,
                    'stock' => rand(3, 15), 'sku' => 'BSW-' . str_pad($i++, 3, '0', STR_PAD_LEFT),
                ]);
            }
        }

        // Inspirada en "Yet To Come" — album Proof
        $denim = Product::create([
            'category_id' => $chaquetas->id,
            'nombre'      => 'Chaqueta Yet To Come Denim',
            'slug'        => 'chaqueta-yet-to-come-denim',
            'descripcion' => 'Chaqueta de mezclilla lavada, corte recto. Inspirada en "Yet To Come", el himno que celebra que lo mejor siempre esta por llegar.',
            'precio'      => 35000,
            'activo'      => true,
        ]);
        $i = 1;
        foreach (['S', 'M', 'L', 'XL'] as $t) {
            foreach (['Azul Medio', 'Azul Vintage'] as $c) {
                ProductVariant::create([
                    'product_id' => $denim->id, 'talla' => $t, 'color' => $c,
                    'stock' => rand(4, 12), 'sku' => 'YTC-' . str_pad($i++, 3, '0', STR_PAD_LEFT),
                ]);
            }
        }

        // Inspirada en "Idol" — J-Hope
        $windbreaker = Product::create([
            'category_id' => $chaquetas->id,
            'nombre'      => 'Rompevientos Idol Sport',
            'slug'        => 'rompevientos-idol-sport',
            'descripcion' => 'Rompevientos ligero e impermeable con detalles coloridos. El espiritu vibrante y orgulloso de "Idol" en cada movimiento.',
            'precio'      => 28000,
            'activo'      => true,
        ]);
        $i = 1;
        foreach (['S', 'M', 'L', 'XL'] as $t) {
            foreach (['Negro Dorado', 'Rojo Negro', 'Azul Blanco'] as $c) {
                ProductVariant::create([
                    'product_id' => $windbreaker->id, 'talla' => $t, 'color' => $c,
                    'stock' => rand(5, 18), 'sku' => 'IDL-' . str_pad($i++, 3, '0', STR_PAD_LEFT),
                ]);
            }
        }

        // ── CAMISAS / TOPS ─────────────────────────────────────────────────

        // Inspirada en "Winter Bear" — V (Taehyung)
        $sueter = Product::create([
            'category_id' => $camisas->id,
            'nombre'      => 'Sueter Winter Bear',
            'slug'        => 'sueter-winter-bear',
            'descripcion' => 'Sueter tejido de lana suave y acogedora. Homenaje a "Winter Bear", la cancion solista de V grabada en un momento de calma y ternura.',
            'precio'      => 19500,
            'activo'      => true,
        ]);
        $i = 1;
        foreach (['S', 'M', 'L', 'XL'] as $t) {
            foreach (['Crema', 'Gris Perla', 'Azul Hielo'] as $c) {
                ProductVariant::create([
                    'product_id' => $sueter->id, 'talla' => $t, 'color' => $c,
                    'stock' => rand(3, 15), 'sku' => 'WBR-' . str_pad($i++, 3, '0', STR_PAD_LEFT),
                ]);
            }
        }

        // Inspirada en "Butter" — era playera
        $polo = Product::create([
            'category_id' => $camisas->id,
            'nombre'      => 'Polo Butter Edition',
            'slug'        => 'polo-butter-edition',
            'descripcion' => 'Polo de algodon pique, corte slim. Fluye tan suave como Butter, la cancion que conquisto los charts mundiales con un groove irresistible.',
            'precio'      => 16500,
            'activo'      => true,
        ]);
        $i = 1;
        foreach (['S', 'M', 'L', 'XL'] as $t) {
            foreach (['Amarillo Mantequilla', 'Blanco', 'Negro', 'Caramelo'] as $c) {
                ProductVariant::create([
                    'product_id' => $polo->id, 'talla' => $t, 'color' => $c,
                    'stock' => rand(6, 20), 'sku' => 'BUT-' . str_pad($i++, 3, '0', STR_PAD_LEFT),
                ]);
            }
        }

        // Inspirada en "Spring Day" — Jimin
        $lino = Product::create([
            'category_id' => $camisas->id,
            'nombre'      => 'Camisa Spring Day Linen',
            'slug'        => 'camisa-spring-day-linen',
            'descripcion' => 'Camisa de lino manga corta, ligera y fresca. Como "Spring Day", una cancion que espera con paciencia el reencuentro y la calidez de la primavera.',
            'precio'      => 18000,
            'activo'      => true,
        ]);
        $i = 1;
        foreach (['S', 'M', 'L', 'XL'] as $t) {
            foreach (['Rosa Palido', 'Celeste', 'Blanco Hueso'] as $c) {
                ProductVariant::create([
                    'product_id' => $lino->id, 'talla' => $t, 'color' => $c,
                    'stock' => rand(4, 14), 'sku' => 'SPD-' . str_pad($i++, 3, '0', STR_PAD_LEFT),
                ]);
            }
        }

        // Inspirada en "Boy With Luv" — Jungkook/Jin
        $hoodie = Product::create([
            'category_id' => $camisas->id,
            'nombre'      => 'Hoodie Boy With Luv',
            'slug'        => 'hoodie-boy-with-luv',
            'descripcion' => 'Sudadera con capucha de algodon, bolsillo canguro. El abrazo calido de "Boy With Luv", la carta de amor de BTS a los ARMYs.',
            'precio'      => 22000,
            'activo'      => true,
        ]);
        $i = 1;
        foreach (['S', 'M', 'L', 'XL', 'XXL'] as $t) {
            foreach (['Rosa Chicle', 'Blanco', 'Negro', 'Morado'] as $c) {
                ProductVariant::create([
                    'product_id' => $hoodie->id, 'talla' => $t, 'color' => $c,
                    'stock' => rand(8, 25), 'sku' => 'BWL-' . str_pad($i++, 3, '0', STR_PAD_LEFT),
                ]);
            }
        }

        // ── PANTALONES ─────────────────────────────────────────────────────

        // Inspirado en "Run BTS"
        $short = Product::create([
            'category_id' => $pantalones->id,
            'nombre'      => 'Short Run BTS Sport',
            'slug'        => 'short-run-bts-sport',
            'descripcion' => 'Short deportivo de secado rapido con bolsillo lateral. Para correr, saltar y brillar como en cada episodio de Run BTS.',
            'precio'      => 12000,
            'activo'      => true,
        ]);
        $i = 1;
        foreach (['S', 'M', 'L'] as $t) {
            foreach (['Negro', 'Azul Navy', 'Morado'] as $c) {
                ProductVariant::create([
                    'product_id' => $short->id, 'talla' => $t, 'color' => $c,
                    'stock' => rand(5, 20), 'sku' => 'RBS-' . str_pad($i++, 3, '0', STR_PAD_LEFT),
                ]);
            }
        }

        // Inspirado en "Mikrokosmos"
        $cargo = Product::create([
            'category_id' => $pantalones->id,
            'nombre'      => 'Cargo Mikrokosmos',
            'slug'        => 'cargo-mikrokosmos',
            'descripcion' => 'Pantalon cargo con 6 bolsillos y corte relajado. Cada bolsillo guarda un universo pequeno, como cada ARMY en el Mikrokosmos de BTS.',
            'precio'      => 25000,
            'activo'      => true,
        ]);
        $i = 1;
        foreach (['30', '32', '34', '36'] as $t) {
            foreach (['Negro Galaxia', 'Verde Oliva', 'Beige'] as $c) {
                ProductVariant::create([
                    'product_id' => $cargo->id, 'talla' => $t, 'color' => $c,
                    'stock' => rand(4, 16), 'sku' => 'MKS-' . str_pad($i++, 3, '0', STR_PAD_LEFT),
                ]);
            }
        }

        // Inspirado en "Fire" — RM
        $jogger = Product::create([
            'category_id' => $pantalones->id,
            'nombre'      => 'Jogger Fire Comfort',
            'slug'        => 'jogger-fire-comfort',
            'descripcion' => 'Pantalon jogger de tela suave con puno en tobillo. La comodidad explosiva de "Fire", porque vivir con energia no significa sacrificar el confort.',
            'precio'      => 17000,
            'activo'      => true,
        ]);
        $i = 1;
        foreach (['S', 'M', 'L', 'XL'] as $t) {
            foreach (['Rojo Fuego', 'Negro', 'Gris'] as $c) {
                ProductVariant::create([
                    'product_id' => $jogger->id, 'talla' => $t, 'color' => $c,
                    'stock' => rand(6, 18), 'sku' => 'FIR-' . str_pad($i++, 3, '0', STR_PAD_LEFT),
                ]);
            }
        }

        // ── ZAPATOS ────────────────────────────────────────────────────────

        // Inspirado en "Shadow" — Suga
        $bota = Product::create([
            'category_id' => $zapatos->id,
            'nombre'      => 'Bota Shadow Leather',
            'slug'        => 'bota-shadow-leather',
            'descripcion' => 'Bota tobillera de cuero sintetico, suela robusta. La intensidad introspectiva de "Shadow" de Suga, una de las piezas mas poderosas del album MAP OF THE SOUL: 7.',
            'precio'      => 38000,
            'activo'      => true,
        ]);
        $i = 1;
        foreach (['38', '39', '40', '41', '42', '43'] as $t) {
            foreach (['Negro Mate', 'Cafe Oscuro'] as $c) {
                ProductVariant::create([
                    'product_id' => $bota->id, 'talla' => $t, 'color' => $c,
                    'stock' => rand(3, 10), 'sku' => 'SHA-' . str_pad($i++, 3, '0', STR_PAD_LEFT),
                ]);
            }
        }

        // Inspirado en "Serendipity" — Jimin
        $sandalia = Product::create([
            'category_id' => $zapatos->id,
            'nombre'      => 'Sandalia Serendipity',
            'slug'        => 'sandalia-serendipity',
            'descripcion' => 'Sandalia deportiva con correa ajustable y plantilla acolchada. Ligera como "Serendipity", la cancion solista de Jimin que habla de encuentros del destino.',
            'precio'      => 16000,
            'activo'      => true,
        ]);
        $i = 1;
        foreach (['37', '38', '39', '40', '41'] as $t) {
            foreach (['Blanco Perla', 'Beige', 'Rosa Palo'] as $c) {
                ProductVariant::create([
                    'product_id' => $sandalia->id, 'talla' => $t, 'color' => $c,
                    'stock' => rand(5, 15), 'sku' => 'SER-' . str_pad($i++, 3, '0', STR_PAD_LEFT),
                ]);
            }
        }

        // ── ACCESORIOS ─────────────────────────────────────────────────────

        // Inspirado en "Map of the Soul"
        $cinturon = Product::create([
            'category_id' => $accesorios->id,
            'nombre'      => 'Cinturon Map of the Soul',
            'slug'        => 'cinturon-map-of-the-soul',
            'descripcion' => 'Cinturon de cuero genuino con hebilla dorada grabada. Un accesorio que marca el camino, como el album que exploro la identidad y el alma de BTS.',
            'precio'      => 9500,
            'activo'      => true,
        ]);
        $i = 1;
        foreach (['S', 'M', 'L'] as $t) {
            foreach (['Negro Dorado', 'Cafe Plateado'] as $c) {
                ProductVariant::create([
                    'product_id' => $cinturon->id, 'talla' => $t, 'color' => $c,
                    'stock' => rand(5, 20), 'sku' => 'MTS-' . str_pad($i++, 3, '0', STR_PAD_LEFT),
                ]);
            }
        }

        // Inspirada en la fandom color purple — ARMY
        $gorra = Product::create([
            'category_id' => $accesorios->id,
            'nombre'      => 'Gorra ARMY Purple',
            'slug'        => 'gorra-army-purple',
            'descripcion' => 'Gorra unitalla con bordado "ARMY" en el frente y cierre trasero ajustable. El morado que BTS reserva para quienes los acompanan hasta el final.',
            'precio'      => 8000,
            'activo'      => true,
        ]);
        $i = 1;
        foreach (['Morado', 'Negro', 'Blanco'] as $c) {
            ProductVariant::create([
                'product_id' => $gorra->id, 'talla' => 'Unica', 'color' => $c,
                'stock' => rand(8, 25), 'sku' => 'GRP-' . str_pad($i++, 3, '0', STR_PAD_LEFT),
            ]);
        }

        // Inspirada en "Winter Bear" — V
        $bufanda = Product::create([
            'category_id' => $accesorios->id,
            'nombre'      => 'Bufanda Winter Bear Knit',
            'slug'        => 'bufanda-winter-bear-knit',
            'descripcion' => 'Bufanda tejida de acrilico suave, larga y abrigadora. Como la melodia tranquila de "Winter Bear", la cancion en ingles que V compuso pensando en los fans.',
            'precio'      => 7500,
            'activo'      => true,
        ]);
        $i = 1;
        foreach (['Crema', 'Gris', 'Azul Pizarra', 'Morado Lavanda'] as $c) {
            ProductVariant::create([
                'product_id' => $bufanda->id, 'talla' => 'Unica', 'color' => $c,
                'stock' => rand(10, 30), 'sku' => 'WBK-' . str_pad($i++, 3, '0', STR_PAD_LEFT),
            ]);
        }

        // Inspirada en "Serendipity" — Jimin (version accesorio)
        $cartera = Product::create([
            'category_id' => $accesorios->id,
            'nombre'      => 'Cartera Serendipity Slim',
            'slug'        => 'cartera-serendipity-slim',
            'descripcion' => 'Billetera delgada de cuero con ranuras para tarjetas. Pequena como un destino, significativa como una serendipia, igual que la cancion de Jimin.',
            'precio'      => 11000,
            'activo'      => true,
        ]);
        $i = 1;
        foreach (['Negro', 'Blanco Perla'] as $c) {
            ProductVariant::create([
                'product_id' => $cartera->id, 'talla' => 'Unica', 'color' => $c,
                'stock' => rand(10, 25), 'sku' => 'SRS-' . str_pad($i++, 3, '0', STR_PAD_LEFT),
            ]);
        }
    }
}
