@extends('layouts.app')
@section('title', 'Vista general')
@section('breadcrumb', 'Vista general')

@section('content')
<div class="page-heading">
    <div>
        <div class="eyebrow"><span class="status-dot"></span> CENTRO DE CONTROL</div>
        <h1>Vista general</h1>
        <p>Un vistazo a la actividad de tus patrones de diseño.</p>
    </div>
    <div class="page-actions"><span class="pill"><span class="status-dot"></span> Entorno de demostración</span></div>
</div>

<section class="intro-banner" aria-label="Bienvenida">
    <div>
        <div class="hero-label"><svg class="icon" style="width:15px;height:15px"><use href="#i-spark"/></svg> SOFTWARE DE GESTIÓN HOSPITALARIA</div>
        <h2>Bienvenido al centro de control SGHI</h2>
        <p>Explora la arquitectura del sistema, ejecuta patrones de diseño y sigue cada operación con Xdebug.</p>
        <div class="hero-buttons">
            <a class="btn btn-hero" href="{{ route('demo.index') }}"><svg class="icon"><use href="#i-lab"/></svg> Abrir laboratorio <svg class="icon"><use href="#i-arrow"/></svg></a>
            <a class="btn btn-hero-alt" href="{{ route('web.pacientes.index') }}">Gestionar pacientes</a>
        </div>
    </div>
    <div class="hero-illustration" aria-hidden="true"><svg class="icon"><use href="#i-activity"/></svg></div>
</section>

<div class="grid-4 mb-20">
    <div class="surface stat-card"><div class="stat-top"><span class="stat-name">Simulaciones ejecutadas</span><span class="stat-icon"><svg class="icon"><use href="#i-activity"/></svg></span></div><strong class="stat-value">{{ $totalEjecuciones }}</strong><div class="stat-foot">Operaciones de esta sesión</div></div>
    <div class="surface stat-card"><div class="stat-top"><span class="stat-name">Pacientes de prueba</span><span class="stat-icon blue"><svg class="icon"><use href="#i-users"/></svg></span></div><strong class="stat-value">{{ $totalPacientes }}</strong><div class="stat-foot">Sólo registros simulados</div></div>
    <div class="surface stat-card"><div class="stat-top"><span class="stat-name">Historiales resumidos</span><span class="stat-icon orange"><svg class="icon"><use href="#i-file"/></svg></span></div><strong class="stat-value">{{ $formatos['resumido'] }}</strong><div class="stat-foot">Construidos con Builder</div></div>
    <div class="surface stat-card"><div class="stat-top"><span class="stat-name">Historiales completos</span><span class="stat-icon purple"><svg class="icon"><use href="#i-file"/></svg></span></div><strong class="stat-value">{{ $formatos['completo'] }}</strong><div class="stat-foot">Construidos con Builder</div></div>
</div>

<section class="surface section-pad mb-20">
    <div class="section-heading"><div><h2>Patrones estructurales</h2><p>Actividad de Facade, Decorator y Proxy sin conexiones SQL</p></div><a class="btn btn-primary btn-sm" href="{{ route('estructurales.index') }}"><svg class="icon"><use href="#i-arrow"/></svg> Abrir laboratorio</a></div>
    <div class="grid-3">
        <a class="struct-dash-tile" href="{{ route('estructurales.index') }}#facade"><span class="struct-dash-icon emerald"><svg class="icon"><use href="#i-git"/></svg></span><span><strong>Facade</strong><small>Coordinar subsistemas</small></span><b>{{ $estadisticasEstructurales['facade'] }}</b></a>
        <a class="struct-dash-tile" href="{{ route('estructurales.index') }}#decorator"><span class="struct-dash-icon blue"><svg class="icon"><use href="#i-layers"/></svg></span><span><strong>Decorator</strong><small>Ampliar reportes</small></span><b>{{ $estadisticasEstructurales['decorator'] }}</b></a>
        <a class="struct-dash-tile" href="{{ route('estructurales.index') }}#proxy"><span class="struct-dash-icon violet"><svg class="icon"><use href="#i-shield"/></svg></span><span><strong>Proxy</strong><small>Acceso y caché simulados</small></span><b>{{ $estadisticasEstructurales['proxy'] }}</b></a>
    </div>
    <p class="struct-muted-note">{{ $totalEstructurales }} ejecuciones estructurales registradas en esta sesión.</p>
</section>

<div class="grid-2 mb-20">
    <section class="surface section-pad">
        <div class="section-heading"><div><h2>Distribución por categoría</h2><p>Pacientes creados mediante Abstract Factory</p></div><span class="pill gray">Sesión actual</span></div>
        <div class="chart-list">
            @foreach (['general' => 'General', 'pediatrico' => 'Pediátrico', 'oncologico' => 'Oncológico'] as $codigo => $etiqueta)
                <div class="chart-row">
                    <span class="chart-label"><span class="chart-swatch {{ $codigo === 'pediatrico' ? 'pediatric' : ($codigo === 'oncologico' ? 'oncology' : '') }}"></span>{{ $etiqueta }}</span>
                    <div class="chart-track" role="img" aria-label="{{ $etiqueta }}: {{ $categorias[$codigo] }} de {{ $totalEjecuciones }}"><div class="chart-fill {{ $codigo === 'pediatrico' ? 'pediatric' : ($codigo === 'oncologico' ? 'oncology' : '') }}" style="--bar: {{ $totalEjecuciones ? round(100 * $categorias[$codigo] / $totalEjecuciones) : 0 }}%"></div></div>
                    <span class="chart-count">{{ $categorias[$codigo] }}</span>
                </div>
            @endforeach
        </div>
        <p class="chart-note">Los indicadores se calculan con datos temporales de tu navegador, no con información clínica.</p>
    </section>
    <section class="surface section-pad">
        <div class="section-heading"><div><h2>Últimas ejecuciones</h2><p>Registro de operaciones del laboratorio</p></div><a class="icon-link" href="{{ route('demo.index') }}">Abrir laboratorio <svg class="icon"><use href="#i-arrow"/></svg></a></div>
        @if (count($recientes))
            <div class="table-wrap"><table class="table"><thead><tr><th>Paciente de prueba</th><th>Tipo</th><th>Historial</th></tr></thead><tbody>
            @foreach ($recientes as $ejecucion)
                <tr><td><div class="person"><span class="person-avatar {{ ($ejecucion['tipo'] ?? '') === 'pediatrico' ? 'blue' : (($ejecucion['tipo'] ?? '') === 'oncologico' ? 'purple' : '') }}">{{ strtoupper(substr($ejecucion['nombre'] ?? 'P', 0, 1)) }}</span><div class="person-meta"><strong>{{ $ejecucion['nombre'] }}</strong><small>{{ $ejecucion['fecha'] ?? 'Sesión' }}</small></div></div></td><td>{{ ['general'=>'General','pediatrico'=>'Pediátrico','oncologico'=>'Oncológico'][$ejecucion['tipo'] ?? ''] ?? 'Otro' }}</td><td><span class="pill {{ ($ejecucion['tipo_historial'] ?? '') === 'completo' ? 'purple' : 'blue' }}">{{ ucfirst($ejecucion['tipo_historial'] ?? '') }}</span></td></tr>
            @endforeach
            </tbody></table></div>
        @else
            <div class="empty-state"><span class="empty-icon"><svg class="icon"><use href="#i-activity"/></svg></span><h3>Aún no hay actividad</h3><p>Ejecuta una demostración de patrones para comenzar a llenar las estadísticas.</p><a class="btn btn-soft btn-sm" href="{{ route('demo.index') }}">Hacer primera simulación</a></div>
        @endif
    </section>
</div>

<div class="section-heading"><div><h2>Accesos rápidos</h2><p>Continúa tu trabajo desde cualquier módulo</p></div></div>
<div class="grid-3 mb-20">
    <a class="surface quick-action" href="{{ route('demo.index') }}"><span class="quick-icon"><svg class="icon"><use href="#i-lab"/></svg></span><div class="quick-body"><strong>Laboratorio de patrones</strong><p>Demuestra Abstract Factory y Builder en Xdebug.</p><span>Explorar módulo →</span></div></a>
    <a class="surface quick-action" href="{{ route('web.pacientes.index') }}"><span class="quick-icon blue"><svg class="icon"><use href="#i-users"/></svg></span><div class="quick-body"><strong>Gestión de pacientes</strong><p>Administra pacientes e historiales persistentes.</p><span>Ir a pacientes →</span></div></a>
    <a class="surface quick-action" href="{{ route('web.citas.index') }}"><span class="quick-icon orange"><svg class="icon"><use href="#i-calendar"/></svg></span><div class="quick-body"><strong>Agenda de citas</strong><p>Consulta y programa citas en la base de datos.</p><span>Ir a citas →</span></div></a>
</div>
<div class="info-strip"><svg class="icon"><use href="#i-spark"/></svg><span><strong>Nota sobre los datos:</strong> este panel muestra exclusivamente simulaciones guardadas en sesión. Las secciones <strong>Pacientes</strong> y <strong>Citas</strong> utilizan la base de datos configurada en Laravel.</span></div>
@endsection
