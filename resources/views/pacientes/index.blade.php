<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de pacientes · SGHI</title>
    <style>
        body{font:16px system-ui;background:#eef3f9;color:#18304d;margin:0}
        header{background:#18304d;color:white;padding:22px}
        header a{color:white;margin-right:16px}
        main{max-width:1050px;margin:25px auto;padding:0 16px}
        .card{background:white;padding:20px;border-radius:12px;margin-bottom:18px;border:1px solid #dce5f0}
        .grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;align-items:end}
        label{display:block;font-weight:bold;margin-bottom:5px}
        input,select{width:100%;box-sizing:border-box;padding:9px;border:1px solid #aabbd0;border-radius:6px}
        button{background:#1768c1;color:white;border:0;padding:10px 14px;border-radius:6px;cursor:pointer}
        .danger{background:#a83232}
        .notice{background:#e5f6e8;padding:12px;border-radius:6px}
        .error{color:#a83232}
        .table-wrap{overflow-x:auto}
        table{width:100%;border-collapse:collapse;min-width:800px}
        th,td{text-align:left;padding:10px;border-bottom:1px solid #dce5f0;vertical-align:middle}
        .actions{display:flex;gap:8px;align-items:center}
        .actions form{margin:0}
        .actions button{white-space:nowrap}
        @media(max-width:750px){.grid{grid-template-columns:1fr}}
    </style>
</head>
<body>
<header>
    <h1>Gestión de pacientes · {{ $hospitalNombre }}</h1>
    <a href="{{ route('demo.index') }}">Volver al laboratorio</a>
    <a href="{{ route('web.citas.index') }}">Ir a citas</a>
</header>
<main>
    @if(session('mensaje'))<p class="notice">{{ session('mensaje') }}</p>@endif
    @if($errors->any())
        <div class="card error">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
    @endif

    <section class="card">
        <h2>Registrar paciente</h2>
        <form method="post" action="{{ route('web.pacientes.store') }}">
            @csrf
            <div class="grid">
                <div>
                    <label for="nombre">Nombre</label>
                    <input id="nombre" name="nombre" maxlength="150" value="{{ old('nombre') }}" required>
                </div>
                <div>
                    <label for="tipo">Tipo de paciente</label>
                    <select id="tipo" name="tipo" required>
                        <option value="">Selecciona un tipo</option>
                        @foreach(['general' => 'General', 'pediatrico' => 'Pediátrico', 'oncologico' => 'Oncológico'] as $valor => $etiqueta)
                            <option value="{{ $valor }}" @selected(old('tipo') === $valor)>{{ $etiqueta }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="fecha_nacimiento">Fecha de nacimiento</label>
                    <input id="fecha_nacimiento" type="date" name="fecha_nacimiento" max="{{ now()->toDateString() }}" value="{{ old('fecha_nacimiento') }}">
                </div>
            </div>
            <p><button type="submit">Registrar paciente</button></p>
        </form>
        <p>Al registrarlo se crea también su historial inicial.</p>
    </section>

    <section class="card">
        <h2>Pacientes registrados</h2>
        @if($pacientes->isEmpty())
            <p>No hay pacientes registrados.</p>
        @else
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Nombre</th><th>Tipo</th><th>Fecha de nacimiento</th><th>Citas</th><th>Historial</th><th>Acciones</th></tr></thead>
                    <tbody>
                    @foreach($pacientes as $paciente)
                        <tr>
                            <td>
                                <form id="editar-paciente-{{ $paciente->id }}" method="post" action="{{ route('web.pacientes.update', $paciente) }}">
                                    @csrf
                                    @method('PUT')
                                </form>
                                <input form="editar-paciente-{{ $paciente->id }}" name="nombre" aria-label="Nombre" maxlength="150" value="{{ $paciente->nombre }}" required>
                            </td>
                            <td>{{ ['general' => 'General', 'pediatrico' => 'Pediátrico', 'oncologico' => 'Oncológico'][$paciente->tipo] }}</td>
                            <td><input form="editar-paciente-{{ $paciente->id }}" type="date" name="fecha_nacimiento" aria-label="Fecha de nacimiento" max="{{ now()->toDateString() }}" value="{{ $paciente->fecha_nacimiento?->format('Y-m-d') }}"></td>
                            <td>{{ $paciente->citas_count }}</td>
                            <td><a href="{{ route('web.pacientes.historial.edit', $paciente) }}">Editar historial</a></td>
                            <td class="actions">
                                <button form="editar-paciente-{{ $paciente->id }}" type="submit">Guardar</button>
                                <form method="post" action="{{ route('web.pacientes.destroy', $paciente) }}" onsubmit="return confirm('¿Eliminar este paciente? También se eliminarán su historial y sus citas.')">
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
            {{ $pacientes->links() }}
        @endif
    </section>
</main>
</body>
</html>
