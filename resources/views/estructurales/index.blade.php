@extends('layouts.app')
@section('title', 'Patrones estructurales')
@section('breadcrumb', 'Patrones estructurales')

@section('content')
<div class="page-heading">
    <div>
        <div class="eyebrow"><span class="status-dot"></span> LABORATORIO · PATRONES ESTRUCTURALES</div>
        <h1>Arquitectura en acción</h1>
        <p>Comprueba cómo Facade, Decorator y Proxy colaboran con el SGHI. Cada operación incluye una traza para Xdebug.</p>
    </div>
    <div class="page-actions"><span class="pill"><span class="status-dot"></span> Datos simulados</span></div>
</div>

<section class="struct-hero">
    <div class="struct-hero-copy">
        <span class="struct-hero-eyebrow">SGHI · DESIGN PATTERNS LAB</span>
        <h2>Tres patrones. Un mismo sistema.</h2>
        <p>Facade simplifica las operaciones del hospital. Decorator amplía el reporte clínico ficticio. Proxy controla el acceso a un modelo simulado y reutiliza respuestas.</p>
        <div class="struct-hero-actions"><a class="btn btn-hero" href="#facade">Iniciar demostración <svg class="icon"><use href="#i-arrow"/></svg></a><a class="struct-hero-link" href="{{ route('demo.index') }}">Ver Abstract Factory + Builder</a></div>
    </div>
    <div class="struct-hero-graphic" aria-hidden="true"><span>FACADE</span><span>DECORATOR</span><span>PROXY</span></div>
</section>

<div class="grid-3 mb-20">
    <a href="#facade" class="struct-intro-card"><span class="struct-kicker">01 / SIMPLIFICACIÓN</span><span class="struct-intro-icon emerald"><svg class="icon"><use href="#i-git"/></svg></span><strong>Facade</strong><p>Una llamada coordina farmacia, laboratorio, imagenología y reportes.</p><span class="struct-card-link">Probar patrón <svg class="icon"><use href="#i-arrow"/></svg></span></a>
    <a href="#decorator" class="struct-intro-card"><span class="struct-kicker">02 / COMPOSICIÓN</span><span class="struct-intro-icon blue"><svg class="icon"><use href="#i-layers"/></svg></span><strong>Decorator</strong><p>Un reporte básico recibe secciones opcionales mediante objetos envolventes.</p><span class="struct-card-link">Probar patrón <svg class="icon"><use href="#i-arrow"/></svg></span></a>
    <a href="#proxy" class="struct-intro-card"><span class="struct-kicker">03 / CONTROL DE ACCESO</span><span class="struct-intro-icon violet"><svg class="icon"><use href="#i-shield"/></svg></span><strong>Proxy</strong><p>Una interfaz común protege el objeto real y gestiona una caché por sesión.</p><span class="struct-card-link">Probar patrón <svg class="icon"><use href="#i-arrow"/></svg></span></a>
</div>

<section class="surface section-pad struct-section" id="facade">
    <div class="struct-section-head"><div><span class="struct-index">01</span><h2>Facade <small>· Fachada hospitalaria</small></h2><p>El controlador sólo invoca <code>HospitalFacade::atenderCaso()</code>. La fachada coordina los subsistemas.</p></div><span class="pill">4 subsistemas + Proxy</span></div>
    <div class="struct-lab-grid">
        <form class="struct-form" method="post" action="{{ route('estructurales.facade') }}">
            @csrf
            <div class="form-group"><label for="caso-facade">Código de caso ficticio</label><input id="caso-facade" name="codigo_caso" required maxlength="17" pattern="CASO-[A-Za-z0-9]{2,12}" value="{{ old('codigo_caso', 'CASO-001') }}" placeholder="CASO-001"/><small class="field-hint">No utilices datos reales de pacientes.</small></div>
            <div class="struct-preview"><div class="struct-mini-flow"><span>Controlador</span><b>→</b><span>HospitalFacade</span><b>→</b><span>Servicios</span></div><ul><li>Registrar solicitudes ficticias de farmacia y laboratorio</li><li>Solicitar estudio de imagenología</li><li>Construir reporte con Decorator y consultar Proxy</li></ul></div>
            <button class="btn btn-primary" type="submit"><svg class="icon"><use href="#i-play"/></svg> Ejecutar atención simulada</button>
        </form>
        <div class="struct-debug-panel"><span class="struct-debug-title">PUNTOS DE INTERRUPCIÓN</span><p><code>PatronesEstructuralesController.php</code> · <b>F0</b></p><p><code>HospitalFacade.php</code> · <b>F1, F2, F7</b></p><p><code>ServicioFarmacia.php</code> · <b>F3</b></p><p><code>ServicioLaboratorio.php</code> · <b>F4</b></p><p><code>ServicioImagenologia.php</code> · <b>F5</b></p><div class="struct-debug-tip">Inspecciona <code>$fachada</code>, entra con F11 en <code>atenderCaso()</code> y sigue la pila de llamadas hasta los servicios.</div></div>
    </div>
</section>

<section class="surface section-pad struct-section" id="decorator">
    <div class="struct-section-head"><div><span class="struct-index">02</span><h2>Decorator <small>· Reportes configurables</small></h2><p>Todos los objetos implementan <code>IReporteClinico</code>. Cada envoltorio añade información simulada sin modificar el componente original.</p></div><span class="pill blue">Composición dinámica</span></div>
    <div class="struct-lab-grid">
        <form class="struct-form" method="post" action="{{ route('estructurales.decorator') }}">
            @csrf
            <div class="form-group"><label for="caso-decorator">Código de caso ficticio</label><input id="caso-decorator" name="codigo_caso" required maxlength="17" pattern="CASO-[A-Za-z0-9]{2,12}" value="{{ old('codigo_caso', 'CASO-001') }}" placeholder="CASO-001"/></div>
            <fieldset class="struct-options"><legend>Secciones adicionales del reporte</legend>
                <label class="struct-choice"><input type="checkbox" name="extras[]" value="laboratorio" checked> <span><strong>Laboratorio</strong><small>Añadir orden ficticia</small></span></label>
                <label class="struct-choice"><input type="checkbox" name="extras[]" value="imagenologia" checked> <span><strong>Imagenología</strong><small>Añadir estudio ficticio</small></span></label>
                <label class="struct-choice"><input type="checkbox" name="extras[]" value="farmacia"> <span><strong>Farmacia</strong><small>Añadir solicitud ficticia</small></span></label>
            </fieldset>
            <div class="struct-preview"><div class="struct-mini-flow"><span>Reporte base</span><b>→</b><span>Decoradores</span><b>→</b><span>Documento</span></div><small>Desmarca todas las secciones para comprobar que se devuelve sólo el reporte básico.</small></div>
            <button class="btn btn-primary" type="submit"><svg class="icon"><use href="#i-file"/></svg> Construir reporte</button>
        </form>
        <div class="struct-debug-panel"><span class="struct-debug-title">PUNTOS DE INTERRUPCIÓN</span><p><code>PatronesEstructuralesController.php</code> · <b>D0</b></p><p><code>CompositorReporte.php</code> · <b>D1, D2</b></p><p><code>ReporteConLaboratorio.php</code> · <b>D3</b></p><p><code>DecoradorReporte.php</code> · <b>D4</b></p><p><code>ReporteClinicoBasico.php</code> · <b>D5</b></p><div class="struct-debug-tip">Observa cómo <code>$reporte</code> sigue siendo <code>IReporteClinico</code> mientras su clase concreta cambia en cada envoltorio.</div></div>
    </div>
</section>

<section class="surface section-pad struct-section" id="proxy">
    <div class="struct-section-head"><div><span class="struct-index">03</span><h2>Proxy <small>· Acceso y caché</small></h2><p>El Proxy implementa la misma interfaz del modelo ficticio, pero verifica el rol de prueba y consulta la caché antes de ejecutarlo.</p></div><span class="pill purple">{{ $cacheCount }} elementos en caché</span></div>
    <div class="struct-lab-grid">
        <form class="struct-form" method="post" action="{{ route('estructurales.proxy') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group"><label for="caso-proxy">Código de caso</label><input id="caso-proxy" name="codigo_caso" maxlength="17" pattern="CASO-[A-Za-z0-9]{2,12}" value="{{ old('codigo_caso', 'CASO-001') }}" required></div>
                <div class="form-group"><label for="rol-proxy">Rol de demostración</label><select id="rol-proxy" name="rol"><option value="medico">Médico (permite)</option><option value="visitante">Visitante (deniega)</option></select></div>
            </div>
            <div class="struct-preview"><strong>Prueba recomendada</strong><ol><li>Ejecuta como Médico: se crea el modelo ficticio (MISS).</li><li>Repite con el mismo código: se reutiliza caché (HIT).</li><li>Elige Visitante: se deniega antes de consultar caché.</li></ol></div>
            <button class="btn btn-primary" type="submit"><svg class="icon"><use href="#i-shield"/></svg> Consultar mediante Proxy</button>
            <p class="field-hint">Los roles de este formulario son didácticos: NO representan autenticación ni autorización reales.</p>
        </form>
        <div class="struct-debug-panel"><span class="struct-debug-title">PUNTOS DE INTERRUPCIÓN</span><p><code>PatronesEstructuralesController.php</code> · <b>P0</b></p><p><code>ProxyModelo.php</code> · <b>P1, P2, P3, P4a, P4b</b></p><p><code>ModeloPrediccionRealDemo.php</code> · <b>P5, P6</b></p><div class="struct-debug-tip">Inspecciona <code>$rolActual</code>, <code>$cache</code>, <code>$modeloReal</code> y la pila de llamadas. En HIT o denegación no se ejecuta el sujeto real.</div></div>
    </div>
</section>

@if($resultado)
<section class="surface section-pad struct-result" id="resultado-estructural">
    <div class="section-heading"><div><div class="eyebrow">ÚLTIMA EJECUCIÓN</div><h2>Resultado · {{ ucfirst($resultado['patron']) }}</h2><p>{{ $resultado['operacion'] }}</p></div><span class="pill"><span class="status-dot"></span> Sin consultas SQL</span></div>
    <div class="struct-result-grid"><div><h3 class="result-label">Recorrido real del patrón</h3><ol class="trace-list">@foreach($resultado['pasos'] as $paso)<li>{{ $paso }}</li>@endforeach</ol></div><div><h3 class="result-label">Valores devueltos</h3><pre class="console">{{ json_encode($resultado['salida'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre><p class="struct-muted-note">Todos los resultados son académicos, ficticios y temporales.</p></div></div>
</section>
@endif

<section class="surface section-pad mt-20">
    <div class="section-heading"><div><h2>Historial de ejecuciones</h2><p>Últimas {{ count($operaciones) }} operaciones estructurales de esta sesión</p></div><form method="post" action="{{ route('estructurales.limpiar') }}" onsubmit="return confirm('¿Limpiar historial y caché del laboratorio estructural?')">@csrf<button type="submit" class="btn btn-danger btn-sm"><svg class="icon"><use href="#i-trash"/></svg> Limpiar demostración</button></form></div>
    @if(count($operaciones))
    <div class="table-wrap"><table class="table"><thead><tr><th>Patrón</th><th>Operación</th><th>Fecha</th></tr></thead><tbody>@foreach($operaciones as $operacion)<tr><td><span class="pill">{{ ucfirst($operacion['patron']) }}</span></td><td>{{ $operacion['operacion'] }}</td><td>{{ $operacion['fecha'] }}</td></tr>@endforeach</tbody></table></div>
    @else<div class="empty-state"><span class="empty-icon"><svg class="icon"><use href="#i-layers"/></svg></span><h3>Listo para depurar</h3><p>Ejecuta uno de los tres formularios para ver la primera traza aquí.</p></div>@endif
</section>
@endsection
@push('scripts')
<script>
(function(){
    @if($resultado)
    const target=document.getElementById('resultado-estructural');
    if(target && !window.matchMedia('(prefers-reduced-motion: reduce)').matches){target.scrollIntoView({block:'start',behavior:'smooth'});}
    @endif
})();
</script>
@endpush
