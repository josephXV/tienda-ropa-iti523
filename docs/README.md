# Documentación — Tienda de Ropa ITI-523

Índice de la documentación del proyecto.

| Documento | Contenido |
|---|---|
| [`../README.md`](../README.md) | Descripción del proyecto, tecnologías, estructura de carpetas, instalación paso a paso, credenciales de prueba, rutas y guía de pruebas. |
| [`diagrama-caso-uso-compra.md`](diagrama-caso-uso-compra.md) | Diagrama de casos de uso del proceso de compra, con la tabla ruta ↔ controlador y la justificación de cada `<<include>>` / `<<extend>>`. |
| [`diagrama-caso-uso-compra.puml`](diagrama-caso-uso-compra.puml) | Fuente PlantUML del diagrama. |
| [`diagrama-caso-uso-compra.svg`](diagrama-caso-uso-compra.svg) / [`.png`](diagrama-caso-uso-compra.png) | Diagrama renderizado, listo para pegar en el informe. |
| `Documentacion_Proyecto_Final.docx` | Documento del proyecto final entregado al curso. |

## Resumen rápido

- **Qué es:** tienda de ropa en línea (catálogo, carrito, checkout, órdenes y reportes PDF).
- **Stack:** Laravel 13 + Blade + Tailwind CSS + Vite, sobre SQLite (o MySQL).
- **Arrancar:** `composer install && npm install && cp .env.example .env && php artisan key:generate && php artisan storage:link && php artisan migrate --seed`, luego `npm run dev` y `php artisan serve`.
- **Usuario de prueba:** `test@example.com` / `password`.
- **Pruebas:** `php artisan test` (PHPUnit sobre SQLite en memoria).

## Regenerar el diagrama

El diagrama se renderiza desde `diagrama-caso-uso-compra.puml` con PlantUML. Sin Graphviz
instalado hay que usar el motor interno *smetana*:

```bash
java -jar plantuml.jar -Playout=smetana -tsvg docs/diagrama-caso-uso-compra.puml
java -jar plantuml.jar -Playout=smetana -tpng docs/diagrama-caso-uso-compra.puml
```

Con Graphviz (`dot`) disponible, basta con `java -jar plantuml.jar -tsvg docs/diagrama-caso-uso-compra.puml`.
El archivo `.md` también incluye una versión en Mermaid que GitHub renderiza sin herramientas externas.
