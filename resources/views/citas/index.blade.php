@extends('layouts.app')
@section('title', 'Gestión de citas')
@section('breadcrumb', 'Citas médicas')

@section('content')
<div class="page-heading">
    <div><div class="eyebrow"><span class="status-dot"></span> GESTIÓN HOSPITALARIA</div><h1>Agenda de citas</h1><p>Programa y administra las citas de tus pacientes.</p></div>
    <div class="page-actions"><span class="pill blue">Módulo conectado a BD</span><a href="#nueva-cita" class="btn btn-primary"><svg class="icon"><use href="#i-plus"/></svg> Nueva cita</a></div>
</div>
<div class="info-strip mb-20"><svg class="icon"><use href="#i-calendar"/></svg><span>Las citas de esta sección utilizan <strong>la base de datos configurada</strong>. El dashboard sólo refleja la actividad del laboratorio de patrones.</span></div>
<section class="surface section-pad mb-20" id="nueva-cita">
    <div class="section-heading"><div><h2>Programar cita</h2><p>Selecciona un paciente y define la fecha de atención.</p></div><span class="pill gray">Nuevo registro</span></div>
    @if($pacientes->isEmpty())
        <div class="empty-state"><span class="empty-icon"><svg class="icon"><use href="#i-users"/></svg></span><h3>Primero registra un paciente</h3><p>Para programar citas necesitas crear al menos un paciente en el módulo de pacientes.</p><a class="btn btn-soft btn-sm" href="{{ route('web.pacientes.index') }}">Ir a pacientes</a></div>
    @else
        <form method="post" action="{{ route('web.citas.store') }}">@csrf
            <div class="form-grid three">
                <div class="form-group"><label for="paciente_id">Paciente</label><select id="paciente_id" name="paciente_id" required><option value="">Selecciona un paciente</option>@foreach($pacientes as $paciente)<option value="{{ $paciente->id }}" @selected(old('paciente_id') == $paciente->id)>{{ $paciente->nombre }}</option>@endforeach</select></div>
                <div class="form-group"><label for="fecha">Fecha y hora</label><input id="fecha" type="datetime-local" name="fecha" value="{{ old('fecha') }}" required></div>
                <div class="form-group"><label for="motivo">Motivo <span class="optional">· Opcional</span></label><input id="motivo" name="motivo" maxlength="255" value="{{ old('motivo') }}" placeholder="Ej. Consulta de seguimiento"></div>
            </div><div class="form-actions"><button class="btn btn-primary" type="submit"><svg class="icon"><use href="#i-calendar"/></svg> Programar cita</button><span class="inline-note">La fecha debe ser posterior a la actual.</span></div>
        </form>
    @endif
</section>
<section class="surface section-pad">
    <div class="section-heading"><div><h2>Citas registradas</h2><p>{{ $citas->total() }} citas en la base de datos</p></div><span class="pill gray">Agenda</span></div>
    @if ($citas->isEmpty())
        <div class="empty-state"><span class="empty-icon"><svg class="icon"><use href="#i-calendar"/></svg></span><h3>No hay citas programadas</h3><p>La agenda aparecerá aquí cuando registres una cita nueva.</p></div>
    @else
        <div class="table-wrap"><table class="table" style="min-width:850px"><thead><tr><th>Paciente</th><th>Fecha / hora</th><th>Motivo</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>
        @foreach($citas as $cita)
            <tr>
                <td><form id="editar-cita-{{ $cita->id }}" method="post" action="{{ route('web.citas.update', $cita) }}">@csrf @method('PUT')</form><select form="editar-cita-{{ $cita->id }}" name="paciente_id" aria-label="Paciente de la cita" required>@foreach($pacientes as $paciente)<option value="{{ $paciente->id }}" @selected($cita->paciente_id === $paciente->id)>{{ $paciente->nombre }}</option>@endforeach</select></td>
                <td><input form="editar-cita-{{ $cita->id }}" type="datetime-local" name="fecha" aria-label="Fecha y hora de cita" value="{{ $cita->fecha->format('Y-m-d\TH:i') }}" required></td>
                <td><input form="editar-cita-{{ $cita->id }}" name="motivo" aria-label="Motivo de cita" maxlength="255" value="{{ $cita->motivo }}"></td>
                <td><select form="editar-cita-{{ $cita->id }}" name="estado" aria-label="Estado de cita" required>@foreach(['programada'=>'Programada','completada'=>'Completada','cancelada'=>'Cancelada'] as $valor=>$etiqueta)<option value="{{ $valor }}" @selected($cita->estado === $valor)>{{ $etiqueta }}</option>@endforeach</select></td>
                <td><div class="table-actions"><button form="editar-cita-{{ $cita->id }}" class="btn btn-outline btn-sm" type="submit">Guardar</button><form method="post" action="{{ route('web.citas.destroy', $cita) }}" onsubmit="return confirm('¿Eliminar esta cita?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" type="submit">Eliminar</button></form></div></td>
            </tr>
        @endforeach
        </tbody></table></div>
        @include('partials.pagination', ['paginator' => $citas])
    @endif
</section>
@endsection
