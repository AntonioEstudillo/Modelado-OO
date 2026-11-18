@extends('layouts.app')
@section('title', 'Laboratorio de patrones')
@section('breadcrumb', 'Laboratorio')

@section('content')
<div class="page-heading">
    <div><div class="eyebrow"><span class="status-dot"></span> PATRONES CREACIONALES</div><h1>Laboratorio de patrones</h1><p>Ejecuta Abstract Factory y Builder; observa el flujo en Xdebug paso a paso.</p></div>
    <div class="page-actions"><span class="pill"><svg class="icon" style="width:14px;height:14px"><use href="#i-check"/></svg> Sin consultas SQL</span><a class="btn btn-outline" href="{{ route('estructurales.index') }}">Facade, Decorator y Proxy</a><a class="btn btn-outline" href="{{ route('dashboard.index') }}">Ver dashboard</a></div>
</div>
<div class="info-strip mb-20"><svg class="icon"><use href="#i-spark"/></svg><span><strong>Práctica académica:</strong> los nombres e historiales de este formulario son ficticios. No se ejecutan consultas SQL ni predicciones clínicas y los datos se guardan temporalmente en la sesión.</span></div>

<div class="lab-layout">
    <section class="surface section-pad">
        <div class="section-heading"><div><h2>Configurar una simulación</h2><p>Completa la información para ejecutar el recorrido de patrones.</p></div><span class="pill blue">Paso 01</span></div>
        <form method="post" action="{{ route('demo.ejecutar') }}">@csrf
            <div class="form-section-label"><svg class="icon" style="width:16px;height:16px"><use href="#i-users"/></svg> Datos del paciente</div>
            <div class="form-grid">
                <div class="form-group full"><label for="demo-nombre">Nombre ficticio</label><input id="demo-nombre" name="nombre" maxlength="100" value="{{ old('nombre','Paciente de prueba') }}" placeholder="Ej. Paciente de prueba" required></div>
                <div class="form-group"><label for="demo-tipo">Tipo de paciente <span class="optional">· Abstract Factory</span></label><select id="demo-tipo" name="tipo" required>@foreach(['general'=>'General','pediatrico'=>'Pediátrico','oncologico'=>'Oncológico'] as $valor=>$texto)<option value="{{ $valor }}" @selected(old('tipo','general') === $valor)>{{ $texto }}</option>@endforeach</select></div>
                <div class="form-group"><label for="demo-formato">Tipo de historial <span class="optional">· Builder</span></label><select id="demo-formato" name="tipo_historial" required><option value="resumido" @selected(old('tipo_historial','resumido') === 'resumido')>Historial resumido</option><option value="completo" @selected(old('tipo_historial') === 'completo')>Historial completo</option></select></div>
            </div>
            <div class="form-section-label"><svg class="icon" style="width:16px;height:16px"><use href="#i-file"/></svg> Construcción del historial</div>
            <div class="form-grid">
                <div class="form-group full"><label for="demo-diagnostico">Diagnóstico ficticio</label><input id="demo-diagnostico" name="diagnostico" maxlength="300" value="{{ old('diagnostico','Caso de prueba') }}" required placeholder="Ej. Caso de prueba"></div>
                <div class="form-group full"><label for="demo-antecedentes">Antecedentes <span class="optional">· Separados por comas</span></label><input id="demo-antecedentes" name="antecedentes" maxlength="300" value="{{ old('antecedentes') }}" placeholder="Antecedente A, Antecedente B"></div>
            </div>
            <div id="lab-full-fields" class="full-history" hidden>
                <div class="form-grid mt-20">
                    @foreach(['medicamentos'=>'Medicamentos','alergias'=>'Alergias','estudios'=>'Estudios'] as $key=>$texto)
                        <div class="form-group {{ $key === 'estudios' ? 'full' : '' }}"><label for="demo-{{ $key }}">{{ $texto }} <span class="optional">· Separados por comas</span></label><input id="demo-{{ $key }}" name="{{ $key }}" maxlength="300" value="{{ old($key) }}" placeholder="Dato ficticio"></div>
                    @endforeach
                </div>
            </div>
            <div class="form-actions"><button class="btn btn-primary" type="submit"><svg class="icon"><use href="#i-lab"/></svg> Ejecutar patrones <svg class="icon"><use href="#i-arrow"/></svg></button><span class="inline-note">Usa F11 en VS Code para entrar a los métodos.</span></div>
        </form>
    </section>
    <div class="stack">
        <section class="surface section-pad">
            <div class="section-heading"><div><h2>Ruta de ejecución</h2><p>Coloca breakpoints en cada etapa</p></div><span class="pill purple">Xdebug</span></div>
            <ol class="steps">
                <li class="step"><span class="step-num">01</span><div><strong>Recibir datos</strong><code>DemoController::ejecutar()</code><p>Variable <code>$datos</code></p></div></li>
                <li class="step"><span class="step-num">02</span><div><strong>Seleccionar fábrica</strong><code>SelectorFabrica::seleccionar()</code><p>Variable <code>$tipo</code> y objeto <code>$fabrica</code></p></div></li>
                <li class="step"><span class="step-num">03</span><div><strong>Crear paciente</strong><code>$fabrica-&gt;crearPaciente()</code><p>Inspecciona <code>$paciente</code></p></div></li>
                <li class="step"><span class="step-num">04</span><div><strong>Construir historial</strong><code>DirectorHistorialDemo</code><p>Recorre <code>$builder</code> y <code>$this-&gt;datos</code></p></div></li>
                <li class="step"><span class="step-num">05</span><div><strong>Devolver resultado</strong><code>HistorialBuilderDemo::construir()</code><p>Inspecciona <code>$historial</code></p></div></li>
            </ol>
            <hr class="divider"><div class="builder-path"><span class="builder-node">Controller</span><svg class="icon"><use href="#i-arrow"/></svg><span class="builder-node">Factory</span><svg class="icon"><use href="#i-arrow"/></svg><span class="builder-node">Builder</span></div>
        </section>
        <section class="surface section-pad"><div class="section-heading"><div><h2>Formatos del Builder</h2><p>Dos productos, un mismo constructor</p></div></div><div class="stack" style="gap:10px"><div class="type-card"><svg class="icon"><use href="#i-file"/></svg><div><strong>Historial resumido</strong><small>Tipo, diagnóstico y antecedentes</small></div></div><div class="type-card"><svg class="icon"><use href="#i-file"/></svg><div><strong>Historial completo</strong><small>Incluye medicamentos, alergias y estudios</small></div></div></div></section>
    </div>
</div>

@if ($resultado)
<section class="surface section-pad mt-20" id="resultados">
    <div class="section-heading"><div><h2>Resultado de la última simulación</h2><p>Datos recibidos de la ejecución de los patrones</p></div><span class="pill"><svg class="icon" style="width:14px;height:14px"><use href="#i-check"/></svg> Ejecución completada</span></div>
    <div class="grid-2">
        <div><h3 class="result-label">Traza de ejecución</h3><ol class="trace-list">@foreach($resultado['mensajes'] as $mensaje)<li>{{ $mensaje }}</li>@endforeach</ol></div>
        <div class="stack" style="gap:12px"><div><h3 class="result-label">Paciente creado en memoria</h3><pre class="console">{{ json_encode($resultado['paciente'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre></div><div><h3 class="result-label">Historial {{ ucfirst($resultado['historial']['tipo_historial']) }} generado</h3><pre class="console">{{ json_encode($resultado['historial'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre></div></div>
    </div>
</section>
@endif
<section class="surface section-pad mt-20">
    <div class="section-heading"><div><h2>Pacientes simulados</h2><p>Registros en memoria de sesión · {{ count($registros) }} en total</p></div><form method="post" action="{{ route('demo.limpiar') }}" onsubmit="return confirm('¿Borrar todos los registros de simulación de la sesión?')">@csrf<button type="submit" class="btn btn-danger btn-sm"><svg class="icon"><use href="#i-trash"/></svg> Vaciar sesión</button></form></div>
    @if (count($registros))
    <div class="table-wrap"><table class="table"><thead><tr><th>Paciente ficticio</th><th>Especialidad</th><th>Almacenamiento</th></tr></thead><tbody>
    @foreach (array_reverse($registros) as $registro)<tr><td><div class="person"><span class="person-avatar">{{ strtoupper(substr($registro['nombre'], 0, 1)) }}</span><strong>{{ $registro['nombre'] }}</strong></div></td><td><span class="pill {{ $registro['tipo'] === 'pediatrico' ? 'blue' : ($registro['tipo'] === 'oncologico' ? 'purple' : '') }}">{{ ['general'=>'General','pediatrico'=>'Pediátrico','oncologico'=>'Oncológico'][$registro['tipo']] ?? $registro['tipo'] }}</span></td><td class="muted">Sesión (sin BD)</td></tr>@endforeach
    </tbody></table></div>
    @else<div class="empty-state"><span class="empty-icon"><svg class="icon"><use href="#i-users"/></svg></span><h3>Sin registros simulados</h3><p>Ejecuta una prueba para visualizar aquí los pacientes generados mediante Abstract Factory.</p></div>@endif
</section>
@endsection
@push('scripts')
<script>
(function(){
const select=document.getElementById('demo-formato');const extra=document.getElementById('lab-full-fields');
if(select&&extra){const update=()=>{extra.hidden=select.value!=='completo'};select.addEventListener('change',update);update()}
@if($resultado)
const result=document.getElementById('resultados');if(result){result.scrollIntoView({block:'start',behavior:'smooth'})}
@endif
})();
</script>
@endpush
