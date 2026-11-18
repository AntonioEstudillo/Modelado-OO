# SGHI · Rediseño visual y dashboard

Esta actualización parte **del repositorio adjunto por el usuario** `Modelado-OO-feature-sghi-creational-patterns` y conserva el flujo existente de **Abstract Factory + Builder** y las rutas de los módulos Eloquent.

## Novedades

- Nuevo **dashboard** en `/` con totales de ejecuciones, pacientes de prueba, historiales resumidos y completos, distribución por tipo y últimas cinco ejecuciones.
- El dashboard utiliza **datos de `session('demo_ejecuciones')` y `session('registros')`**, sin consultar la BD.
- Laboratorio de patrones en `/laboratorio`, con formulario reorganizado, selector resumido/completo, ruta de breakpoints y resultados más legibles.
- Nuevo layout Blade compartido: menú lateral responsive, barra superior, tarjetas, tablas y avisos.
- Vistas mejoradas de pacientes, citas y edición de historial.
- Hoja de estilos local en `public/assets/sghi.css`; **no se necesita Vite ni `npm run dev`** para el nuevo diseño.
- Pruebas del dashboard en `tests/Feature/DashboardDemoTest.php`.

## Instalación limpia en Ubuntu WSL

Requisitos: PHP >= 8.2, Composer y extensiones que Laravel solicite.

```bash
cd ~/ModeladoOO
# Descomprime este ZIP y entra a la carpeta del proyecto
cd SGHI_Dashboard_Laravel12
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

Abre `http://127.0.0.1:8000` para el dashboard y `http://127.0.0.1:8000/laboratorio` para la simulación.

Si ya tienes un proyecto SGHI funcionando y quieres mantener su configuración, **no sobrescribas tu `.env` ni `vendor/`**. Copia únicamente los archivos cambiados del ZIP de actualización, manteniendo su estructura de carpetas:

- `app/Http/Controllers/DashboardController.php`
- `app/Http/Controllers/DemoController.php`
- `routes/web.php` (fusiona si tienes rutas propias)
- `resources/views/layouts/app.blade.php`
- `resources/views/dashboard/index.blade.php`
- `resources/views/demo/index.blade.php`
- `resources/views/pacientes/index.blade.php`
- `resources/views/pacientes/historial.blade.php`
- `resources/views/citas/index.blade.php`
- `resources/views/partials/pagination.blade.php`
- `public/assets/sghi.css`

Después, en el proyecto existente:

```bash
php artisan optimize:clear
php artisan serve
```

## Configurar sesión para NO necesitar base de datos

En **tu `.env` real** (si ya existe), indica:

```ini
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

> No es necesario ejecutar migraciones para navegar por `/` y `/laboratorio`, siempre que el proyecto Laravel esté instalado y la sesión esté configurada en archivos. Se requiere BD para `/pacientes`, `/citas` y los historiales persistentes, porque ya usaban Eloquent en el repositorio original.

Cuando desees utilizar los módulos persistentes, configura `DB_CONNECTION` y los demás parámetros de BD de tu proyecto y ejecuta las migraciones correspondientes.

## Guía del debugger (Xdebug)

1. Abre el proyecto desde VS Code conectado a WSL.
2. Arranca Laravel con `php artisan serve` y activa **Escuchar Xdebug** (F5).
3. Abre `/laboratorio`, elige `tipo` y `tipo_historial`, y ejecuta.
4. Los breakpoints permanecen en `app/Demo/FlujoDemo.php` (no se cambió ese archivo).
5. Observa `$tipo` → `$fabrica` → `$paciente` y luego `$tipoHistorial` → `$director` → `$builder` → `$historial`.
6. Para detener el debugger: **Shift + F5**. Para detener Laravel en la terminal: **Ctrl + C**.

## Comprobaciones

- Sintaxis PHP comprobada con `php -l` para los nuevos controladores y rutas.
- Las dependencias `vendor/` no se incluyen en el ZIP, por lo cual no se ejecutaron las pruebas completas de PHPUnit / Laravel en el entorno de preparación. Cuando lo instales, ejecuta:

```bash
php artisan route:list
php artisan test
```

**Advertencia:** Esta aplicación es una demostración académica. No ingreses información clínica real en los formularios del laboratorio. Las métricas del dashboard no reflejan datos reales de pacientes.
