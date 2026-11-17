<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CitaController extends Controller
{
    public function index(): View
    {
        return view('citas.index', [
            'citas' => Cita::with('paciente')->orderBy('fecha')->paginate(10),
            'pacientes' => Paciente::orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'fecha' => ['required', 'date', 'after:now'],
            'motivo' => ['nullable', 'string', 'max:255'],
        ]);

        Cita::create($datos);

        return redirect()->route('web.citas.index')->with('mensaje', 'Cita programada correctamente.');
    }

    public function update(Request $request, Cita $cita): RedirectResponse
    {
        $reglasFecha = ['required', 'date'];
        if ($request->input('fecha') !== $cita->fecha->format('Y-m-d\TH:i')) {
            $reglasFecha[] = 'after:now';
        }

        $datos = $request->validate([
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'fecha' => $reglasFecha,
            'motivo' => ['nullable', 'string', 'max:255'],
            'estado' => ['required', 'in:programada,completada,cancelada'],
        ]);

        $cita->update($datos);

        return redirect()->route('web.citas.index')->with('mensaje', 'Cita actualizada correctamente.');
    }

    public function destroy(Cita $cita): RedirectResponse
    {
        $cita->delete();

        return redirect()->route('web.citas.index')->with('mensaje', 'Cita eliminada correctamente.');
    }
}
