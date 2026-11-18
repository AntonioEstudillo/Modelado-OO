# SGHI — Facade, Decorator y Proxy (demo con Xdebug)

Esta actualización se basa en **SGHI_Dashboard_Laravel12** y no modifica `app/Demo/FlujoDemo.php`: Abstract Factory y Builder conservan sus breakpoints.

## Instalación en el proyecto de Ubuntu WSL

1. Haz una copia de seguridad o un commit: `git add . && git commit -m "Antes de patrones estructurales"`.
2. Desde Windows, abre `\\wsl.localhost\Ubuntu\home\tono\ModeladoOO\sghi` en el Explorador (o desde Ubuntu ejecuta `cd ~/ModeladoOO/sghi && explorer.exe .`).
3. Descomprime **SGHI_Actualizacion_Patrones_Estructurales.zip** en Windows y copia las carpetas **app**, **resources**, **routes**, **public**, **tests** y **docs** al proyecto, combinando directorios. **No sobrescribas `.env` ni `vendor`**. Si tus rutas o vistas han cambiado desde la versión del dashboard, integra manualmente `routes/web.php` y `layouts/app.blade.php`.
4. Como la demo usa sesiones, configura en el `.env` existente: `SESSION_DRIVER=file` y `CACHE_STORE=file`.
5. En Ubuntu: `php artisan optimize:clear` y después `php artisan serve`. Entra a **http://127.0.0.1:8000/patrones-estructurales**.
6. Comprueba las rutas: `php artisan route:list --path=patrones-estructurales`. Ejecuta las pruebas: `php artisan test --filter=StructuralPatternsDemoTest`.

El ZIP completo contiene **el proyecto fuente**, no `vendor`, `.env` ni `node_modules`. Para partir de cero se requiere `composer install` y preparar `.env`/`APP_KEY`; para aplicar una actualización se recomienda el ZIP de archivos modificados.

## Facade

**Objetivo:** ofrecer una operación de alto nivel que coordine subsistemas sin exponer al controlador los detalles de cada uno. `PatronesEstructuralesController::facade()` construye `HospitalFacade` y llama a `atenderCaso()`. Se registran **sólo mensajes ficticios** de registro de paciente, programación de cita, farmacia, laboratorio e imagenología. Además, el Facade crea un reporte usando Decorator y llama a Proxy (con rol académico permitido) para demostrar colaboración entre patrones.

**Recorrido Xdebug:** `PatronesEstructuralesController::facade()` (F0) → `HospitalFacade::atenderCaso()` (F1) → `HospitalFacade::solicitarMedicamento()` (F2) → `ServicioFarmacia::solicitarMedicamento()` (F3) → `ServicioLaboratorio` (F4) → `ServicioImagenologia` (F5) → Decorator → `HospitalFacade::consultarRiesgo()` (F7) → Proxy → final (F6).

**Variables:** `$fachada`, `$codigoCaso`, `$farmacia`, `$laboratorio`, `$imagenologia`, `$reporte`, `$proxy` y `$traza`. Pulsa **F11** para entrar; **F10** para pasar sobre la línea; **F5** para continuar; **Shift+F5** para detener el debugger.

## Decorator

**Objetivo:** añadir responsabilidades a un reporte sin añadir subclases para cada combinación posible. `IReporteClinico` declara `generarReporte()`, `ReporteClinicoBasico` es el componente y `DecoradorReporte` la base abstracta que conserva una referencia `IReporteClinico`. Tres decoradores concretos añaden secciones ficticias: laboratorio, imagenología y farmacia.

**Recorrido Xdebug:** controlador `decorator()` (D0) → `CompositorReporte::componer()` (D1/D2) → `ReporteCon...::generarReporte()` (D3) → `DecoradorReporte::generarReporte()` (D4) → `ReporteClinicoBasico::generarReporte()` (D5) → vuelve por cada envoltorio. **El orden de ejecución de `generarReporte()` al entrar es exterior → interior; las secciones se añaden al regresar interior → exterior.**

**Variables:** `$reporte` (clase concreta cambia, pero implementa la misma interfaz), `$componente`, `$extras`, `$base` y `$contenido`. Prueba sin marcar casillas y después con las tres.

## Proxy

**Objetivo:** interceptar operaciones sobre un objeto real para aplicar controles previos y reutilizar resultados. `ProxyModelo` y `ModeloPrediccionRealDemo` implementan `ModeloPrediccionContrato`. El Proxy verifica un rol **elegido en el formulario sólo para demostración**, busca el código en un arreglo de caché de sesión y crea el sujeto real únicamente cuando existe permiso y no hay entrada previa.

**Recorrido Xdebug:** controlador `proxy()` (P0) → `ProxyModelo::generarPrediccionRiesgos()` (P1) → `autorizar()` (P2) → `buscarEnCache()` (P3) → rama HIT (P4a) o MISS (P4b) → `ModeloPrediccionRealDemo` (P5/P6).

**Tres ensayos:** (1) rol `medico`, código `CASO-001`: `MISS`, se crea RealSubject. (2) repite el mismo rol y código: `HIT`, RealSubject no se ejecuta. (3) rol `visitante`: acceso denegado **antes** de leer caché. Al pulsar **Limpiar demostración** se borra tanto el historial estructural como la caché.

**Variables:** `$rolActual`, `$cache`, `$resultado`, `$modeloReal`, `$traza`.

## Cambios y correcciones del UML proporcionado

- `ModeloPrediccion` no aparece dos veces como Subject y RealSubject; ahora existe una **interfaz** `ModeloPrediccionContrato` y dos implementaciones (`ModeloPrediccionRealDemo` / `ProxyModelo`).
- `rolActual` es `string`, no `ServicioLaboratorio`; los controles de acceso reales **no** se implementan en este laboratorio.
- `DecoradorReporte` es **abstracto** y **compone** a `IReporteClinico`; `ReporteConLaboratorio`, `ReporteConImagenologia`, `ReporteConFarmacia` son decoradores concretos. Se corrigen las cajas duplicadas `ServicioImagenologia` en el diagrama.
- `HospitalFacade` coordina subsistemas y también usa Decorator y Proxy (no reemplaza los servicios ni es una fachada estática de Laravel).
- Los casos usan identificadores inventados `CASO-001`. No se guardan pacientes reales ni se calcula información clínica. Las trazas y la caché permanecen en la sesión; **no hay INSERT/SELECT SQL en estas rutas**.
- Se conservan el dashboard, Abstract Factory, Builder y las vistas anteriores. Las métricas nuevas provienen exclusivamente de `estructurales_operaciones` en sesión.

### Advertencia importante

**La autenticación, permisos, caché persistente, registros de auditoría y predicción reales necesitarían un diseño y controles adicionales antes de cualquier uso hospitalario.** No debe utilizarse esta demo para gestionar pacientes reales.

Diagrama UML editable: `docs/patrones-estructurales.puml`.
