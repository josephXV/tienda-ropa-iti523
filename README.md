# Tienda de Ropa — ITI-523

Tienda de ropa en línea desarrollada con **Laravel 13**. Incluye catálogo con filtros,
carrito de compras, checkout con cálculo de impuestos y envío, órdenes con historial y
reportes de ventas en PDF. El contenido de ejemplo tiene temática BTS.

> Proyecto académico del curso ITI-523.

---

## Índice

1. [Tecnologías](#tecnologías)
2. [Funcionalidades](#funcionalidades)
3. [Estructura de carpetas](#estructura-de-carpetas)
4. [Instalación paso a paso](#instalación-paso-a-paso)
5. [Credenciales de prueba](#credenciales-de-prueba)
6. [Rutas principales](#rutas-principales)
7. [Modelo de datos](#modelo-de-datos)
8. [Pruebas automatizadas](#pruebas-automatizadas)
9. [Documentación adicional](#documentación-adicional)

---

## Tecnologías

| Capa | Herramienta |
|---|---|
| Backend | PHP 8.3+, Laravel 13 |
| Vistas | Blade (componentes de Laravel Breeze) |
| Estilos | Tailwind CSS 3 + `@tailwindcss/forms` |
| Build de assets | Vite 8 (`laravel-vite-plugin`), Alpine.js |
| Base de datos | SQLite por defecto; compatible con MySQL/MariaDB, PostgreSQL y SQL Server |
| Autenticación | Laravel Breeze (login, registro, recuperación de contraseña, perfil) |
| Reportes PDF | `barryvdh/laravel-dompdf` |
| Pruebas | PHPUnit 12 (`php artisan test`) |

## Funcionalidades

- **Catálogo** con filtros por categoría, búsqueda por nombre y rango de precios, paginado de 12 en 12.
- **Productos vistos recientemente**: middleware `ProductosVistos` guarda los últimos 5 slugs en una cookie (30 días).
- **Variantes de producto** (talla, color, stock, SKU): el stock se controla por variante, no por producto.
- **Carrito** por usuario autenticado: agregar, actualizar cantidad, eliminar y subtotal en vivo.
- **Checkout** con desglose: subtotal + IVA 13 % + envío fijo de ₡2 500.
- **Órdenes**: número de seguimiento `ORD-XXXXXXXXXX`, estado `pagado`, items con precio unitario histórico, pago simulado (`tarjeta` / `paypal`) y descuento automático de stock, todo dentro de una transacción.
- **Historial de órdenes** y pantalla de confirmación protegida (solo el dueño de la orden).
- **Reportes PDF**: ventas por mes (con filtro de año/mes) y ventas por cliente (con rango de fechas).

## Estructura de carpetas

```
tienda-ropa-iti523/
├── app/
│   ├── Http/
│   │   ├── Controllers/       ProductController, CartController, OrderController,
│   │   │                      ReportController, ProfileController y Auth/ (Breeze)
│   │   ├── Middleware/        ProductosVistos (cookie de productos vistos)
│   │   └── Requests/          Form requests de perfil
│   ├── Models/                Category, Product, ProductVariant, Cart, CartItem,
│   │                          Order, OrderItem, Payment, User
│   ├── Providers/
│   └── View/
├── bootstrap/app.php          Registro de rutas y middleware
├── config/                    Configuración de Laravel
├── database/
│   ├── factories/             Factories para pruebas (Product, Order, Cart, ...)
│   ├── migrations/            Esquema: categories, products, product_variants,
│   │                          carts, cart_items, orders, order_items, payments
│   ├── seeders/               CategorySeeder, ProductSeeder, ProductSeederExtra,
│   │                          ImagePathSeeder, DatabaseSeeder
│   └── database.sqlite        Base de datos local (SQLite)
├── docs/                      Documentación y diagramas del proyecto
├── public/
│   └── storage/productos/     Imágenes de los productos (vía storage:link)
├── resources/
│   ├── css/  js/              Fuentes de Tailwind y Alpine
│   └── views/
│       ├── productos/         Catálogo y ficha de producto
│       ├── carrito/           Carrito de compras
│       ├── checkout/          Checkout, confirmación e historial
│       ├── reportes/          Vistas Blade que DomPDF convierte a PDF
│       ├── auth/  profile/    Pantallas de Breeze
│       ├── components/        Componentes Blade reutilizables
│       └── layouts/           Layouts app y guest
├── routes/
│   ├── web.php                Rutas de la tienda
│   └── auth.php               Rutas de autenticación (Breeze)
├── tests/
│   ├── Unit/                  Relaciones de modelos
│   └── Feature/               Catálogo, carrito, checkout, reportes y auth
├── phpunit.xml                Suites de PHPUnit (SQLite en memoria)
├── tailwind.config.js  vite.config.js
└── Procfile                   Despliegue en Railway
```

## Instalación paso a paso

Requisitos: **PHP 8.3+**, **Composer**, **Node.js 20+** y la extensión `pdo_sqlite`
(o `pdo_mysql` si vas a usar MySQL).

```bash
# 1. Clonar el repositorio
git clone https://github.com/josephXV/tienda-ropa-iti523.git
cd tienda-ropa-iti523

# 2. Dependencias de PHP
composer install

# 3. Dependencias de JavaScript
npm install

# 4. Archivo de entorno
cp .env.example .env

# 5. Llave de la aplicación
php artisan key:generate

# 6. Enlace simbólico para las imágenes de productos
php artisan storage:link

# 7. Base de datos SQLite (si el archivo no existe)
touch database/database.sqlite

# 8. Migraciones + datos de ejemplo (categorías, productos, variantes e imágenes)
php artisan migrate --seed

# 9. Compilar assets (deja esta terminal abierta)
npm run dev

# 10. Levantar el servidor (en otra terminal)
php artisan serve
```

La aplicación queda en **http://localhost:8000**.

> Para producción usá `npm run build` en lugar de `npm run dev`.
> Con `composer dev` se levantan a la vez el servidor, la cola, los logs y Vite.

### Usar MySQL en lugar de SQLite

Editá el `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tienda_ropa
DB_USERNAME=root
DB_PASSWORD=
```

Luego `php artisan migrate:fresh --seed`. Los reportes detectan el driver activo, así que
funcionan igual en SQLite, MySQL, PostgreSQL y SQL Server.

## Credenciales de prueba

`DatabaseSeeder` crea un usuario de prueba:

| Campo | Valor |
|---|---|
| Email | `test@example.com` |
| Contraseña | `password` |

También podés registrarte desde `/register`. El seeder carga además 5 categorías y varios
productos con sus variantes (talla, color, stock y SKU) e imágenes.

## Rutas principales

### Públicas

| Método | Ruta | Nombre | Descripción |
|---|---|---|---|
| GET | `/` | — | Página de bienvenida. |
| GET | `/productos` | `productos.index` | Catálogo paginado. Acepta `?categoria=`, `?buscar=`, `?precio_min=`, `?precio_max=`. Muestra también los productos vistos recientemente. |
| GET | `/productos/{slug}` | `productos.show` | Ficha del producto con sus variantes; registra la visita en la cookie `productos_vistos`. |
| GET/POST | `/login` | `login` | Inicio de sesión (Breeze). |
| GET/POST | `/register` | `register` | Registro de clientes (Breeze). |
| GET/POST | `/forgot-password`, `/reset-password/{token}` | `password.*` | Recuperación de contraseña. |

### Autenticadas (middleware `auth`)

| Método | Ruta | Nombre | Descripción |
|---|---|---|---|
| GET | `/dashboard` | `dashboard` | Panel del usuario. |
| GET/PATCH/DELETE | `/profile` | `profile.*` | Ver, actualizar o eliminar la cuenta. |
| GET | `/carrito` | `carrito.index` | Carrito del usuario con subtotal calculado. |
| POST | `/carrito/agregar` | `carrito.agregar` | Agrega una variante al carrito (valida stock; si ya existe, suma la cantidad). |
| PATCH | `/carrito/{cartItem}` | `carrito.actualizar` | Cambia la cantidad de una línea (solo el dueño; si no, `403`). |
| DELETE | `/carrito/{cartItem}` | `carrito.eliminar` | Elimina una línea del carrito (solo el dueño). |
| GET | `/checkout` | `checkout.index` | Resumen de compra: subtotal, IVA 13 %, envío ₡2 500 y total. |
| POST | `/checkout/procesar` | `checkout.procesar` | Procesa el pago (`metodo_pago`: `tarjeta` o `paypal`), crea la orden, descuenta stock y vacía el carrito. |
| GET | `/ordenes/{order}/confirmacion` | `ordenes.confirmacion` | Confirmación con número de seguimiento (solo el dueño de la orden). |
| GET | `/ordenes` | `ordenes.historial` | Historial de órdenes del usuario. |
| GET | `/reportes` | `reportes.index` | Pantalla para generar reportes. |
| GET | `/reportes/ventas-por-mes` | `reportes.ventas_mes` | PDF de ventas por mes. Parámetros: `anio`, `mes` (opcional). |
| GET | `/reportes/ventas-por-cliente` | `reportes.ventas_cliente` | PDF de ventas por cliente. Parámetros: `desde`, `hasta` (opcionales). |
| POST | `/logout` | `logout` | Cierra la sesión. |

## Modelo de datos

```
User 1───1 Cart 1───* CartItem *───1 ProductVariant *───1 Product *───1 Category
 │                                        │
 └───* Order 1───* OrderItem *────────────┘
        │
        └───1 Payment
```

- `Product` guarda el precio; `ProductVariant` guarda talla, color, **stock** y SKU.
- `OrderItem` conserva el `precio_unitario` del momento de la compra (histórico).
- `Order.estado`: `pendiente`, `pagado`, `enviado`, `cancelado`. El checkout crea la orden en `pagado`.

## Pruebas automatizadas

Las pruebas corren sobre SQLite en memoria (configurado en `phpunit.xml`), así que no tocan
tu base de datos local.

```bash
php artisan test                                   # toda la suite
php artisan test --filter=CartControllerTest       # una clase
php artisan test tests/Unit                        # solo unitarias
```

| Archivo | Qué cubre |
|---|---|
| `tests/Unit/ModelRelationshipsTest.php` | Relaciones Eloquent: categoría→productos, producto→variantes, usuario→carrito/órdenes, carrito→items, orden→usuario/items/pago y borrado en cascada. |
| `tests/Feature/ProductCatalogTest.php` | Catálogo público, filtros por categoría/nombre/precio y ficha por slug. |
| `tests/Feature/CartControllerTest.php` | Agregar (incluida la suma de cantidades y el tope de stock), actualizar, eliminar, permisos entre usuarios y cálculo del subtotal. |
| `tests/Feature/CheckoutOrderTest.php` | Totales del checkout, creación de la orden en estado `pagado` con sus items, descuento de stock, registro del pago, vaciado del carrito, validaciones y permisos. |
| `tests/Feature/ReportControllerTest.php` | Generación de los PDF y **regresión del bug de `DATE_FORMAT`**: esa función es exclusiva de MySQL y rompía el reporte mensual en SQLite; hoy `ReportController::expresionMes()` elige la expresión según el driver. |
| `tests/Feature/Auth/*`, `ProfileTest.php` | Autenticación y perfil (Breeze). |

Las factories de `database/factories/` (`ProductFactory`, `CategoryFactory`,
`ProductVariantFactory`, `CartFactory`, `CartItemFactory`, `OrderFactory`, `OrderItemFactory`,
`PaymentFactory`) permiten construir datos de prueba sin depender de los seeders.

## Documentación adicional

- [`docs/README.md`](docs/README.md) — índice de la documentación.
- [`docs/diagrama-caso-uso-compra.md`](docs/diagrama-caso-uso-compra.md) — diagrama de casos de uso del proceso de compra (PlantUML, Mermaid, SVG y PNG).

## Despliegue

El `Procfile` incluido sirve para Railway u otro PaaS compatible:

```
web: php artisan serve --host=0.0.0.0 --port=$PORT
```

Antes de desplegar: `composer install --no-dev`, `npm run build`, `php artisan key:generate`,
`php artisan migrate --force` y `php artisan storage:link`.
