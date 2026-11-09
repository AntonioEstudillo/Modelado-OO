# SGHI: Builder con historial resumido y completo

Este paquete actualiza la demostración anterior. **No es un proyecto Laravel completo**: copia su contenido sobre la raíz de tu aplicación Laravel 12, respaldando primero los archivos reemplazados.

## Cambios
- `app/Demo/FlujoDemo.php`: el Director ahora contiene `construirResumido()` y `construirCompleto()`; ambos utilizan `HistorialBuilderDemo`.
- `app/Http/Controllers/DemoController.php`: valida `tipo_historial`.
- `resources/views/demo/index.blade.php`: agrega selector de tipo de historial y muestra el tipo generado.
- `routes/web.php`: igual que la versión anterior.

## Rutas y ejecución
Usa `SESSION_DRIVER=file` y `CACHE_STORE=file` en `.env` para evitar conexiones a BD. Ejecuta `php artisan optimize:clear` y `php artisan serve`; accede a `http://127.0.0.1:8000`.

## Para la demostración con Xdebug
1. Pon breakpoint en `FlujoDemo::ejecutar` cerca de `$tipoHistorial`.
2. Selecciona `resumido` y usa F11 sobre `construirResumido`; inspecciona `$builder->datos` (expandir `$this` dentro del Builder).
3. Repite con `completo`, entra a `construirCompleto` y comprueba que se llaman tres métodos adicionales.
4. En `HistorialBuilderDemo::construir`, inspecciona `$resultado` tras su asignación: resumido tiene 3 claves; completo, 6.
5. `Shift+F5` detiene la sesión de VS Code y `Ctrl+C` detiene `php artisan serve` en su terminal.

Los datos son ficticios y nunca se ejecutan consultas SQL desde este módulo.
