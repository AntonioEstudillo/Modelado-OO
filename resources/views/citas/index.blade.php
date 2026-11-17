<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de citas · SGHI</title>
    <style>
        body{font:16px system-ui;background:#eef3f9;color:#18304d;margin:0}
        header{background:#18304d;color:white;padding:22px}
        header a{color:white}
        main{max-width:1100px;margin:25px auto;padding:0 16px}
        .card{background:white;padding:20px;border-radius:12px;margin-bottom:18px;border:1px solid #dce5f0}
        .grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px}
        label{display:block;font-weight:bold;margin-bottom:5px}
        input,select{width:100%;box-sizing:border-box;padding:9px;border:1px solid #aabbd0;border-radius:6px}
        button{background:#1768c1;color:white;border:0;padding:10px 14px;border-radius:6px;cursor:pointer}
        .danger{background:#a83232}
        .actions{display:flex;gap:8px;align-items:end}
        .notice{background:#e5f6e8;padding:12px;border-radius:6px}
        .error{color:#a83232}
        .table-wrap{overflow-x:auto}
        table{width:100%;border-collapse:collapse;min-width:850px}
        th,td{text-align:left;padding:10px;border-bottom:1px solid #dce5f0;vertical-align:top}
        td form{margin:0}
        .row-form{display:flex;gap:8px;align-items:center;margin-bottom:8px}
        .row-form button{white-space:nowrap}
        @media(max-width:750px){.grid,.row-form{grid-template-columns:1fr}}
    </style>
</head>
<body>
<header>
    <h1>Gestión de citas</h1>
    <a href="{{ route('demo.index') }}">Volver al laboratorio</a>
    <a href="{{ route('web.pacientes.index') }}">Gestionar pacientes</a>
</header>
<main>
    @if(session('mensaje'))<p class="notice">{{ session('mensaje') }}</p>@endif
    @if($errors->any())
        <div class="card error">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
    @endif

    <section class="card">
        <h2>Programar cita</h2>
        @if($pacientes->isEmpty())
            <p>No hay pacientes registrados. Registra uno primero en <code>POST /api/pacientes</code>.</p>
        @else
            <form method="post" action="{{ route('web.citas.store') }}">
                @csrf
                <div class="grid">
                    <div>
                        <label for="paciente_id">Paciente</label>
                        <select id="paciente_id" name="paciente_id" required>
                            <option value="">Selecciona un paciente</option>
                            @foreach($pacientes as $paciente)
                                <option value="{{ $paciente->id }}" @selected(old('paciente_id') == $paciente->id)>{{ $paciente->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="fecha">Fecha y hora</label>
                        <input id="fecha" type="datetime-local" name="fecha" value="{{ old('fecha') }}" required>
                    </div>
                    <div>
                        <label for="motivo">Motivo</label>
                        <input id="motivo" name="motivo" maxlength="255" value="{{ old('motivo') }}">
                    </div>
                </div>
                <p><button type="submit">Programar cita</button></p>
            </form>
        @endif
    </section>

    <section class="card">
        <h2>Citas registradas</h2>
        @if($citas->isEmpty())
            <p>No hay citas registradas.</p>
        @else
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Paciente</th><th>Fecha</th><th>Motivo</th><th>Estado</th><th>Acciones</th></tr></thead>
                    <tbody>
                    @foreach($citas as $cita)
                        <tr>
                            <td>
                                <select form="editar-cita-{{ $cita->id }}" name="paciente_id" aria-label="Paciente" required>
                                    @foreach($pacientes as $paciente)
                                        <option value="{{ $paciente->id }}" @selected($cita->paciente_id === $paciente->id)>{{ $paciente->nombre }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input form="editar-cita-{{ $cita->id }}" type="datetime-local" name="fecha" aria-label="Fecha y hora" value="{{ $cita->fecha->format('Y-m-d\TH:i') }}" required></td>
                            <td><input form="editar-cita-{{ $cita->id }}" name="motivo" aria-label="Motivo" maxlength="255" value="{{ $cita->motivo }}"></td>
                            <td>
                                <select form="editar-cita-{{ $cita->id }}" name="estado" aria-label="Estado" required>
                                    @foreach(['programada' => 'Programada', 'completada' => 'Completada', 'cancelada' => 'Cancelada'] as $valor => $etiqueta)
                                        <option value="{{ $valor }}" @selected($cita->estado === $valor)>{{ $etiqueta }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="actions">
                                <form id="editar-cita-{{ $cita->id }}" class="row-form" method="post" action="{{ route('web.citas.update', $cita) }}">
                                    @csrf
                                    @method('PUT')
                                </form>
                                <button form="editar-cita-{{ $cita->id }}" type="submit">Guardar</button>
                                <form method="post" action="{{ route('web.citas.destroy', $cita) }}" onsubmit="return confirm('¿Eliminar esta cita?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="danger" type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $citas->links() }}
        @endif
    </section>
</main>
</body>
</html>
