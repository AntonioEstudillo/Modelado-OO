<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Services\ConfiguracionHospital;
use App\Services\ServicioWebApi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PacienteController extends Controller
{
    public function index(ConfiguracionHospital $configuracion): View
    {
        return view('pacientes.index', [
            'pacientes' => Paciente::with('historial')->withCount('citas')->orderBy('nombre')->paginate(10),
            'hospitalNombre' => $configuracion->nombre(),
        ]);
    }

    public function store(Request $request, ServicioWebApi $servicio): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'tipo' => ['required', 'in:general,pediatrico,oncologico'],
            'fecha_nacimiento' => ['nullable', 'date', 'before_or_equal:today'],
        ]);

        $servicio->registrarPaciente($datos);

        return redirect()->route('web.pacientes.index')->with('mensaje', 'Paciente registrado correctamente.');
    }

    public function update(Request $request, Paciente $paciente): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'fecha_nacimiento' => ['nullable', 'date', 'before_or_equal:today'],
        ]);

        $paciente->update($datos);

        return redirect()->route('web.pacientes.index')->with('mensaje', 'Paciente actualizado correctamente.');
    }

    public function destroy(Paciente $paciente): RedirectResponse
    {
        $paciente->delete();

        return redirect()->route('web.pacientes.index')->with('mensaje', 'Paciente y sus registros relacionados eliminados.');
    }
}
