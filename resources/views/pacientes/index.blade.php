@extends('layouts.app')
@section('title', 'Gestión de pacientes')
@section('breadcrumb', 'Pacientes')

@section('content')
<div class="page-heading">
    <div><div class="eyebrow"><span class="status-dot"></span> GESTIÓN HOSPITALARIA</div><h1>Gestión de pacientes</h1><p>{{ $hospitalNombre }} · Registro, edición e historiales clínicos.</p></div>
    <div class="page-actions"><span class="pill blue">Módulo conectado a BD</span><a href="#registrar-paciente" class="btn btn-primary"><svg class="icon"><use href="#i-plus"/></svg> Nuevo paciente</a></div>
</div>
<div class="info-strip mb-20"><svg class="icon"><use href="#i-users"/></svg><span>Este módulo utiliza <strong>Eloquent y una base de datos real</strong>. Para practicar Abstract Factory y Builder sin conectarte a MySQL, utiliza el <a class="icon-link" href="{{ route('demo.index') }}">Laboratorio</a>.</span></div>
<section class="surface section-pad mb-20" id="registrar-paciente">
    <div class="section-heading"><div><h2>Registrar paciente</h2><p>Completa los datos básicos para crear un registro y su historial inicial.</p></div><span class="pill gray">Nuevo registro</span></div>
    <form method="post" action="{{ route('web.pacientes.store') }}">@csrf
        <div class="form-grid three">
            <div class="form-group"><label for="nombre">Nombre completo</label><input id="nombre" name="nombre" maxlength="150" value="{{ old('nombre') }}" required placeholder="Nombre del paciente"></div>
            <div class="form-group"><label for="tipo">Tipo de paciente</label><select id="tipo" name="tipo" required><option value="">Selecciona un tipo</option>@foreach(['general'=>'General','pediatrico'=>'Pediátrico','oncologico'=>'Oncológico'] as $valor=>$etiqueta)<option value="{{ $valor }}" @selected(old('tipo') === $valor)>{{ $etiqueta }}</option>@endforeach</select></div>
            <div class="form-group"><label for="fecha_nacimiento">Fecha de nacimiento <span class="optional">· Opcional</span></label><input id="fecha_nacimiento" type="date" name="fecha_nacimiento" max="{{ now()->toDateString() }}" value="{{ old('fecha_nacimiento') }}"></div>
        </div>
        <div class="form-actions"><button class="btn btn-primary" type="submit"><svg class="icon"><use href="#i-plus"/></svg> Registrar paciente</button><span class="inline-note">El historial inicial se crea automáticamente.</span></div>
    </form>
</section>
<section class="surface section-pad">
    <div class="section-heading"><div><h2>Pacientes registrados</h2><p>{{ $pacientes->total() }} registro(s) en la base de datos</p></div><span class="pill gray">Listado</span></div>
    @if ($pacientes->isEmpty())
        <div class="empty-state"><span class="empty-icon"><svg class="icon"><use href="#i-users"/></svg></span><h3>No hay pacientes registrados</h3><p>Utiliza el formulario superior para crear el primer paciente.</p><a href="#registrar-paciente" class="btn btn-soft btn-sm">Registrar primero</a></div>
    @else
    <div class="table-wrap"><table class="table" style="min-width:920px"><thead><tr><th>Paciente / Nombre editable</th><th>Tipo</th><th>Fecha de nacimiento</th><th>Citas</th><th>Historial</th><th>Acciones</th></tr></thead><tbody>
    @foreach($pacientes as $paciente)
        <tr>
            <td><form id="editar-paciente-{{ $paciente->id }}" method="post" action="{{ route('web.pacientes.update', $paciente) }}">@csrf @method('PUT')</form><div class="person"><span class="person-avatar {{ $paciente->tipo === 'pediatrico' ? 'blue' : ($paciente->tipo === 'oncologico' ? 'purple' : '') }}">{{ strtoupper(substr($paciente->nombre,0,1)) }}</span><input class="table-input" form="editar-paciente-{{ $paciente->id }}" name="nombre" aria-label="Editar nombre de {{ $paciente->nombre }}" maxlength="150" value="{{ $paciente->nombre }}" required></div></td>
            <td><span class="pill {{ $paciente->tipo === 'pediatrico' ? 'blue' : ($paciente->tipo === 'oncologico' ? 'purple' : '') }}">{{ ['general'=>'General','pediatrico'=>'Pediátrico','oncologico'=>'Oncológico'][$paciente->tipo] ?? $paciente->tipo }}</span></td>
            <td><input form="editar-paciente-{{ $paciente->id }}" type="date" name="fecha_nacimiento" aria-label="Fecha de nacimiento de {{ $paciente->nombre }}" max="{{ now()->toDateString() }}" value="{{ $paciente->fecha_nacimiento?->format('Y-m-d') }}"></td>
            <td>{{ $paciente->citas_count }}</td>
            <td><a class="icon-link" href="{{ route('web.pacientes.historial.edit', $paciente) }}">Ver historial <svg class="icon"><use href="#i-arrow"/></svg></a></td>
            <td><div class="table-actions"><button form="editar-paciente-{{ $paciente->id }}" class="btn btn-outline btn-sm" type="submit">Guardar</button><form method="post" action="{{ route('web.pacientes.destroy', $paciente) }}" onsubmit="return confirm('¿Eliminar este paciente? Se borrarán también el historial y las citas relacionadas.')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" type="submit">Eliminar</button></form></div></td>
        </tr>
    @endforeach
    </tbody></table></div>
    @include('partials.pagination', ['paginator' => $pacientes])
    @endif
</section>
@endsection
