<?php

namespace App\Http\Controllers;

use App\Builders\DirectorHistorial;
use App\Models\Paciente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HistorialWebController extends Controller
{
    public function edit(Paciente $paciente): View
    {
        return view('pacientes.historial', [
            'paciente' => $paciente,
            'historial' => $paciente->historial,
        ]);
    }

    public function update(Request $request, Paciente $paciente, DirectorHistorial $director): RedirectResponse
    {
        $datos = $request->validate([
            'tipo_historial' => ['required', 'in:resumido,completo'],
            'diagnostico' => ['nullable', 'string', 'max:5000'],
            'antecedentes' => ['nullable', 'string', 'max:5000'],
            'medicamentos' => ['nullable', 'string', 'max:5000'],
            'alergias' => ['nullable', 'string', 'max:5000'],
            'estudios' => ['nullable', 'string', 'max:5000'],
        ]);

        foreach (['antecedentes', 'medicamentos', 'alergias', 'estudios'] as $campo) {
            $datos[$campo] = $this->separarLista($datos[$campo] ?? '');
        }

        if ($datos['tipo_historial'] === 'resumido') {
            $director->construirResumido($paciente, $datos);
        } else {
            $director->construirCompleto($paciente, $datos);
        }

        return redirect()->route('web.pacientes.historial.edit', $paciente)
            ->with('mensaje', 'Historial guardado correctamente.');
    }

    private function separarLista(string $valores): array
    {
        return array_values(array_filter(
            array_map('trim', preg_split('/[\r\n,]+/', $valores) ?: []),
            fn (string $valor): bool => $valor !== '',
        ));
    }
}
