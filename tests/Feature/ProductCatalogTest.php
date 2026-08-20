<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas del catalogo publico: listado, filtros y ficha de producto.
 */
class ProductCatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_el_catalogo_es_publico_y_lista_productos_activos(): void
    {
        $activo = Product::factory()->create(['nombre' => 'Camisa Purple Wave']);
        Product::factory()->inactivo()->create(['nombre' => 'Producto Descontinuado']);

        $response = $this->get(route('productos.index'));

        $response->assertOk();
        $this->assertCount(1, $response->viewData('productos'));
        $this->assertSame($activo->id, $response->viewData('productos')->first()->id);
    }

    public function test_el_catalogo_filtra_por_categoria(): void
    {
        $camisas = Category::factory()->create(['slug' => 'camisas']);
        Product::factory()->create(['category_id' => $camisas->id]);
        Product::factory()->create(); // otra categoria

        $response = $this->get(route('productos.index', ['categoria' => 'camisas']));

        $response->assertOk();
        $this->assertCount(1, $response->viewData('productos'));
    }

    public function test_el_catalogo_filtra_por_nombre_y_rango_de_precio(): void
    {
        Product::factory()->create(['nombre' => 'Camisa Purple Wave', 'precio' => 15000]);
        Product::factory()->create(['nombre' => 'Camisa Cara', 'precio' => 90000]);
        Product::factory()->create(['nombre' => 'Zapatos Dynamite', 'precio' => 20000]);

        $response = $this->get(route('productos.index', [
            'buscar' => 'Camisa',
            'precio_min' => 10000,
            'precio_max' => 30000,
        ]));

        $response->assertOk();
        $productos = $response->viewData('productos');
        $this->assertCount(1, $productos);
        $this->assertSame('Camisa Purple Wave', $productos->first()->nombre);
    }

    public function test_la_ficha_de_producto_se_busca_por_slug(): void
    {
        $producto = Product::factory()->create(['slug' => 'camisa-purple-wave']);

        $this->get(route('productos.show', 'camisa-purple-wave'))
            ->assertOk()
            ->assertSee($producto->nombre);

        $this->get(route('productos.show', 'no-existe'))->assertNotFound();
    }
}
