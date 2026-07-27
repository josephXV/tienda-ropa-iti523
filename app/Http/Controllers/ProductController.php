<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants'])->where('activo', true);

        if ($request->filled('categoria')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->categoria);
            });
        }

        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }

        if ($request->filled('precio_min')) {
            $query->where('precio', '>=', $request->precio_min);
        }

        if ($request->filled('precio_max')) {
            $query->where('precio', '<=', $request->precio_max);
        }

        $productos = $query->paginate(12);
        $categorias = Category::all();

        $vistosSlugs = json_decode($request->cookie('productos_vistos', '[]'), true) ?? [];
        $vistos = Product::whereIn('slug', $vistosSlugs)->get()->sortBy(function ($p) use ($vistosSlugs) {
            return array_search($p->slug, $vistosSlugs);
        });

        return view('productos.index', compact('productos', 'categorias', 'vistos'));
    }

    public function show(string $slug)
    {
        $producto = Product::with(['category', 'variants'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('productos.show', compact('producto'));
    }
}
