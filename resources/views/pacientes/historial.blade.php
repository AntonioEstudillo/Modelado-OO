@extends('layouts.app')
@section('title', 'Historial de paciente')
@section('breadcrumb', 'Historial clínico')

@section('content')
<div class="page-heading">
    <div><div class="eyebrow"><span class="status-dot"></span> HISTORIAL DEL PACIENTE</div><h1>Historial clínico</h1><p>{{ $paciente->nombre }} · {{ ['general'=>'General','pediatrico'=>'Pediátrico','oncologico'=>'Oncológico'][$paciente->tipo] ?? ucfirst($paciente->tipo) }}</p></div>
    <div class="page-actions"><span class="pill blue">Módulo conectado a BD</span><a href="{{ route('web.pacientes.index') }}" class="btn btn-outline">Volver a pacientes</a></div>
</div>
<div class="grid-2">
    <section class="surface section-pad span-2">
        <div class="section-heading"><div><h2>Editor de historial</h2><p>El patrón Builder organiza los campos según el formato seleccionado.</p></div><span class="pill purple">Builder</span></div>
        <form method="post" action="{{ route('web.pacientes.historial.update', $paciente) }}">@csrf @method('PUT')
            <div class="form-grid">
                <div class="form-group"><label for="tipo_historial">Formato del historial</label><select id="tipo_historial" name="tipo_historial" required><option value="resumido" @selected(old('tipo_historial', $historial?->tipo_historial ?? 'completo') === 'resumido')>Historial resumido</option><option value="completo" @selected(old('tipo_historial', $historial?->tipo_historial ?? 'completo') === 'completo')>Historial completo</option></select><span class="field-hint">El formato resumido conserva diagnóstico y antecedentes.</span></div>
                <div class="form-group"><label>Paciente</label><input value="{{ $paciente->nombre }}" disabled aria-label="Paciente actual"><span class="field-hint">El paciente se seleccionó en la tabla anterior.</span></div>
                <div class="form-group full"><label for="diagnostico">Diagnóstico</label><textarea id="diagnostico" name="diagnostico" maxlength="5000" placeholder="Escribe un diagnóstico...">{{ old('diagnostico', $historial?->diagnostico) }}</textarea></div>
                <div class="form-group full"><label for="antecedentes">Antecedentes <span class="optional">· Uno por línea o separados por comas</span></label><textarea id="antecedentes" name="antecedentes" maxlength="5000" placeholder="Antecedente 1&#10;Antecedente 2">{{ old('antecedentes', implode("\n", $historial?->antecedentes ?? [])) }}</textarea></div>
            </div>
            <div id="campos-completos" class="full-history" hidden>
                <div class="form-section-label"><svg class="icon" style="width:16px;height:16px"><use href="#i-file"/></svg> Campos adicionales · Historial completo</div>
                <div class="form-grid">
                    @foreach(['medicamentos'=>'Medicamentos','alergias'=>'Alergias','estudios'=>'Estudios'] as $campo=>$etiqueta)
                    <div class="form-group {{ $campo === 'estudios' ? 'full' : '' }}"><label for="{{ $campo }}">{{ $etiqueta }} <span class="optional">· Uno por línea o separados por comas</span></label><textarea id="{{ $campo }}" name="{{ $campo }}" maxlength="5000">{{ old($campo, implode("\n", $historial?->{$campo} ?? [])) }}</textarea></div>
                    @endforeach
                </div>
            </div>
            <div class="form-actions"><button class="btn btn-primary" type="submit"><svg class="icon"><use href="#i-check"/></svg> Guardar historial</button><span class="inline-note">Los cambios se almacenarán en la base de datos de Laravel.</span></div>
        </form>
    </section>
</div>
<div class="surface section-pad mt-20"><div class="section-heading"><div><h2>¿Cómo actúa Builder?</h2><p>El director decide qué campos se agregan al historial.</p></div></div><div class="builder-path"><span class="builder-node">DirectorHistorial</span><svg class="icon"><use href="#i-arrow"/></svg><span class="builder-node">HistorialBuilder</span><svg class="icon"><use href="#i-arrow"/></svg><span class="builder-node">Historial</span></div></div>
@endsection
@push('scripts')
<script>
(function(){const formato=document.getElementById('tipo_historial');const extra=document.getElementById('campos-completos');if(!formato||!extra)return;const actualizar=()=>{extra.hidden=formato.value!=='completo'};formato.addEventListener('change',actualizar);actualizar()})();
</script>
@endpush
