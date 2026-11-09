# SGHI — Demo de patrones sin base de datos

## Instalación (Laravel 12 existente)
1. Haz una copia de seguridad de tu proyecto.
2. Copia `app/Demo`, `app/Http/Controllers/DemoController.php`, `resources/views/demo/index.blade.php` y `routes/web.php` dentro de tu proyecto Laravel. **Revisa `routes/web.php` antes de sobrescribirlo**.
3. En `.env` configura `SESSION_DRIVER=file` y `CACHE_STORE=file` (no `database`). Si cambiaste `.env`, ejecuta `php artisan config:clear`.
4. Ejecuta `php artisan serve` y abre `http://127.0.0.1:8000`.
5. No necesitas ejecutar migraciones ni iniciar MySQL.

## Evaluación con Xdebug
- Instala y habilita Xdebug en el PHP que ejecuta Laravel y configura VS Code con PHP Debug y escucha en puerto 9003.
- Inicia una sesión de depuración y coloca breakpoints en los comentarios `BREAKPOINT` de `app/Demo/FlujoDemo.php` y `DemoController.php`.
- Envía el formulario. Sigue `$datos` → `$tipo` → `$fabrica` → `$paciente` → `$historial`.
- Prueba los tres tipos para observar tres implementaciones de la interfaz `FabricaHospitalDemo`.
- `DirectorHistorialDemo` construye el historial llamando al Builder en secuencia.

## Aclaraciones
Esta demo añade clases con sufijo `Demo` para no modificar los modelos Eloquent ni las fábricas originales, que sí requieren BD. Las rutas `/api/*` del esqueleto anterior **no están simuladas**: usa únicamente las rutas web de esta demo. Los registros duran lo que dure la sesión; no existe almacenamiento persistente. La predicción de IA es únicamente un mensaje.
