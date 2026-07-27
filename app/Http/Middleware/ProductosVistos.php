<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

class ProductosVistos
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->route() && $request->route()->getName() === 'productos.show') {
            $slug = $request->route('slug');

            $vistos = json_decode($request->cookie('productos_vistos', '[]'), true);
            if (!is_array($vistos)) {
                $vistos = [];
            }

            // Quitar si ya estaba, para volver a ponerlo primero
            $vistos = array_values(array_diff($vistos, [$slug]));

            // Agregar al inicio
            array_unshift($vistos, $slug);

            // Limitar a los últimos 5
            $vistos = array_slice($vistos, 0, 5);

            $response->headers->setCookie(
                new Cookie('productos_vistos', json_encode($vistos), now()->addDays(30))
            );
        }

        return $response;
    }
}
