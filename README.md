# SGHI — Esqueleto para Laravel 12

**Este ZIP es una capa de código para integrar en una instalación existente de Laravel 12, no un proyecto Laravel autocontenido.** No incluye `vendor/`, `artisan`, `composer.json`, `routes/web.php` ni todo el framework. Los archivos incluidos se colocan en las mismas rutas de un proyecto nuevo. Si ya tienes `bootstrap/app.php`, **no lo sobrescribas**: agrega `api: __DIR__.'/../routes/api.php'` dentro de `withRouting(...)`. Si ya existe `routes/api.php`, integra las rutas sin eliminar las actuales.

## Preparación

```bash
composer create-project laravel/laravel sghi
cd sghi
# Extraer y copiar las carpetas app/, database/, config/, routes/ de este ZIP.
# Integrar bootstrap/app.php solamente en proyecto nuevo o adaptar la sección withRouting.
# Configurar DB_* de .env
php artisan migrate
php artisan route:list --path=api
php artisan serve
```

Si se instaló usando el instalador de Laravel, `php artisan install:api` también puede configurar las rutas API y Sanctum, pero revisa los archivos antes de sobrescribirlos.

Ejemplo de POST a `/api/pacientes`:

```json
{"nombre":"Paciente de prueba","tipo":"general","fecha_nacimiento":"2000-01-01"}
```

Se admite `general`, `pediatrico` y `oncologico`. La creación genera automáticamente un historial vacío mediante Abstract Factory. También están disponibles `GET /api/pacientes`, `GET /api/pacientes/{id}`, `PUT /api/pacientes/{id}`, `DELETE /api/pacientes/{id}`, el CRUD de `/api/citas` y `GET /api/pacientes/{id}/historial`.

## Correspondencia UML y decisiones

| UML | Implementación |
|---|---|
| SistemaSGHI | Servicio de negocio `app/Services/SistemaSGHI.php`, inyectable |
| ServicioWebApi | Servicio de aplicación `app/Services/ServicioWebApi.php` + controladores REST |
| ConfiguracionHospital / PoolConexionDB (Singleton) | `config/hospital.php` y conexión de BD que Laravel administra mediante su contenedor y Database Manager, sin Singleton manual |
| FabricaHospital / fábricas concretas | Interface y 3 fábricas con selector; devuelve modelos Eloquent y una estrategia de predicción |
| ModeloPredicción | Contrato de estrategia y demo que **lanza una excepción** si se pide una predicción (no finge resultados médicos) |
| HistorialBuilder + DirectorHistorial | Builder que reúne atributos; Director de creación básica, persistencia a través de fábrica |
| HistorialCompleto / HistorialResumido | Un solo `Historial` persistente con JSON; el resumen puede ser una representación/API Resource posterior |
| PacienteGeneral/Pediatrico/Oncologico | Subclases con ámbito global según columna discriminadora `tipo` en la tabla `pacientes` |
| Paciente ↔ Cita / Tratamiento | `hasMany` / `belongsTo` |
| Paciente ↔ Historial | `hasOne` / `belongsTo` con índice UNIQUE |
| Reportes ↔ Historial | `Reporte` belongsTo `Historial` (se pueden generar varios reportes por historial) |

## Cambios/correcciones importantes

1. Se eliminaron singletons manuales de configuración/conexión. En Laravel son redundantes y pueden interferir con el ciclo de vida de las conexiones.
2. Se separó dominio y HTTP: los métodos `enviarPeticion` y `recibirRespuesta` no representan un servidor REST Laravel; los reemplazan rutas, controladores y respuestas JSON.
3. Las fábricas concretas no heredan de una clase instanciable: **implementan una interfaz**, y se resuelve el tipo con un selector.
4. Se normalizó un único historial por paciente. `HistorialResumido` es mejor como Resource/DTO de lectura que como tabla de diagnóstico duplicada.
5. Se agregó `paciente_id` a citas, tratamientos e historiales, FK reales y restricciones de esquema.
6. Se corrigieron nombres en español (`fecha`, `Reportes` → `Reporte`), métodos de CRUD gestionados desde controladores y persistencia Eloquent, no desde el propio modelo.
7. Las predicciones clínicas están intencionalmente deshabilitadas hasta conectar un modelo comprobado.
8. Las operaciones de creación del paciente y su historial comparten transacción.

## Límites del esqueleto / antes de producción

- API **sin autenticación/autorización** para permitir probarla en local. No desplegar accesible al público: integrar Sanctum, Policies, roles y auditoría de datos sensibles antes de datos reales.
- Los endpoints no incluyen aún CRUD de tratamientos, reportes, actualización del historial ni generación real de resúmenes.
- El builder existe como ejemplo de patrón pero todavía no está conectado a un endpoint. El director devuelve atributos, no guarda en DB.
- Los tipos de paciente usan herencia Eloquent de tabla única (`tipo`); evitar actualizar `tipo` después de crear para mantener consistencia con el ámbito de cada subclase.
- El uso de `cascadeOnDelete` es solamente demostrativo; para expedientes reales definir retención y borrado conforme requisitos legales.
- Añadir pruebas automatizadas de API, control de concurrencia de citas y validación de datos médicos.
