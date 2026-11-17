<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Historial clínico · SGHI</title>
    <style>
        body{font:16px system-ui;background:#eef3f9;color:#18304d;margin:0}
        header{background:#18304d;color:white;padding:22px}
        header a{color:white}
        main{max-width:900px;margin:25px auto;padding:0 16px}
        .card{background:white;padding:20px;border-radius:12px;margin-bottom:18px;border:1px solid #dce5f0}
        label{display:block;font-weight:bold;margin:14px 0 5px}
        input,select,textarea{width:100%;box-sizing:border-box;padding:10px;border:1px solid #aabbd0;border-radius:6px;font:inherit}
        textarea{min-height:90px}
        button{margin-top:16px;background:#1768c1;color:white;border:0;padding:11px 15px;border-radius:6px;cursor:pointer}
        .notice{background:#e5f6e8;padding:12px;border-radius:6px}
        .error{color:#a83232}
    </style>
</head>
<body>
<header>
    <h1>Historial clínico</h1>
    <p>{{ $paciente->nombre }} · {{ ucfirst($paciente->tipo) }}</p>
    <a href="{{ route('web.pacientes.index') }}">Volver a pacientes</a>
</header>
<main>
    @if(session('mensaje'))<p class="notice">{{ session('mensaje') }}</p>@endif
    @if($errors->any())
        <div class="card error">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
    @endif
    <section class="card">
        <form method="post" action="{{ route('web.pacientes.historial.update', $paciente) }}">
            @csrf
            @method('PUT')
            <label for="tipo_historial">Formato del historial</label>
            <select id="tipo_historial" name="tipo_historial" required>
                <option value="resumido" @selected(old('tipo_historial', $historial?->tipo_historial ?? 'completo') === 'resumido')>Resumido</option>
                <option value="completo" @selected(old('tipo_historial', $historial?->tipo_historial ?? 'completo') === 'completo')>Completo</option>
            </select>

            <label for="diagnostico">Diagnóstico</label>
            <textarea id="diagnostico" name="diagnostico" maxlength="5000">{{ old('diagnostico', $historial?->diagnostico) }}</textarea>

            <label for="antecedentes">Antecedentes (uno por línea o separados por comas)</label>
            <textarea id="antecedentes" name="antecedentes" maxlength="5000">{{ old('antecedentes', implode("\n", $historial?->antecedentes ?? [])) }}</textarea>

            <div id="campos-completos">
                @foreach(['medicamentos' => 'Medicamentos', 'alergias' => 'Alergias', 'estudios' => 'Estudios'] as $campo => $etiqueta)
                    <label for="{{ $campo }}">{{ $etiqueta }} (uno por línea o separados por comas)</label>
                    <textarea id="{{ $campo }}" name="{{ $campo }}" maxlength="5000">{{ old($campo, implode("\n", $historial?->{$campo} ?? [])) }}</textarea>
                @endforeach
            </div>

            <button type="submit">Guardar historial</button>
        </form>
    </section>
</main>
<script>
    const formato = document.getElementById('tipo_historial');
    const camposCompletos = document.getElementById('campos-completos');
    const actualizarCampos = () => {
        camposCompletos.hidden = formato.value === 'resumido';
    };
    formato.addEventListener('change', actualizarCampos);
    actualizarCampos();
</script>
</body>
</html>
