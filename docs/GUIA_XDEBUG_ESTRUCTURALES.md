# Guía de exposición con Xdebug — Facade, Decorator y Proxy

**Preparación:** iniciar `php artisan serve`, conectar VS Code a WSL, seleccionar la sesión Xdebug (puerto 9003) y presionar `F5`. Abrir `http://127.0.0.1:8000/patrones-estructurales`. Xdebug debe estar configurado con `xdebug.mode=debug`, `xdebug.start_with_request=yes`.

## 1. Facade

- Selecciona **CASO-001** y pulsa **Ejecutar atención simulada**.
- Pon un breakpoint en `app/Http/Controllers/PatronesEstructuralesController.php` en `F0`. Comprueba que `$fachada` es `HospitalFacade`.
- Usa `F11` en `HospitalFacade::atenderCaso()`, detente en `F1` y observa `$codigoCaso`.
- Sigue `registrarPaciente()`, `programarCita()`, `solicitarMedicamento()`, `solicitarAnalisis()`, `generarImagen()`; ve a los servicios concretos. Observa CALL STACK.
- Inspecciona `$farmacia`, `$laboratorio`, `$imagenologia`, `$reporte`, `$proxy`. La misma fachada llamará a Decorator y Proxy.
- **Conclusión para exposición:** el controlador utiliza un método de alto nivel; no necesita saber qué servicios internos deben coordinarse.

## 2. Decorator

- Selecciona el mismo código `CASO-001` y marca **Laboratorio** + **Imagenología**.
- Breakpoint `D0`: observa `$extras`. Entra con `F11` a `CompositorReporte::componer()` en `D1`.
- En `D2`, compara `get_class($reporte)` antes y después de cada iteración. Primero `ReporteClinicoBasico`, después `ReporteConLaboratorio`, finalmente `ReporteConImagenologia`.
- En `generarReporte()` del decorador exterior (`D3`), entra al método padre (`D4`), que delega al componente envuelto. Llegarás al reporte base (`D5`).
- Observa cómo la ejecución regresa de adentro hacia afuera agregando anexos. Repite sin casillas: no se agregan extras.
- **Conclusión para exposición:** el objeto sigue implementando `IReporteClinico`, pero su comportamiento se amplía mediante composición sin modificar el reporte base.

## 3. Proxy

- Coloca breakpoints `P0` a `P6` y borra la caché mediante **Limpiar demostración**.
- Selecciona `CASO-001`, rol **Médico**, envía. La variable `$rolActual` permite entrar; `$cache` está vacía (`MISS`) y se crea `$modeloReal` (`P4b`). La pila de llamadas alcanza `P5`/`P6`.
- Envía la misma petición: `$cache['CASO-001']` ya tiene el resultado (`HIT`); el RealSubject no se instancia ni ejecuta.
- Selecciona **Visitante**: `autorizar()` devuelve `false`; no se consulta la caché ni se invoca el sujeto real. **Es sólo una demostración, no un esquema de seguridad real.**
- **Conclusión para exposición:** el Proxy implementa la misma interfaz que el objeto real, pero decide cuándo delegar, cuándo negar y cuándo recuperar datos de caché.

## 4. Comandos útiles

- `php artisan route:list --path=patrones-estructurales`
- `php artisan test --filter=StructuralPatternsDemoTest`
- `Shift + F5`: detiene Xdebug.
- `Ctrl + C` en la terminal de `php artisan serve`: detiene Laravel.

**Restricciones:** No se ejecutan consultas SQL en esta demostración. Los roles son seleccionables para mostrar la lógica y no autentican a usuarios reales. No se realizan predicciones médicas; sólo mensajes ficticios.
