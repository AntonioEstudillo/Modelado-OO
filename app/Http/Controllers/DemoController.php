<?php

namespace App\Http\Controllers;

use App\Demo\FlujoDemo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class DemoController extends Controller
{
    public function index()
    {
        return view('demo.index', [
            'registros' => session('registros', []),
            'resultado' => session('resultado'),
            'ejecuciones' => session('demo_ejecuciones', []),
        ]);
    }

    public function ejecutar(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:100',
            'tipo' => 'required|in:general,pediatrico,oncologico',
            'tipo_historial' => 'required|in:resumido,completo',
            'diagnostico' => 'required|string|max:300',
            'antecedentes' => 'nullable|string|max:300',
            'medicamentos' => 'nullable|string|max:300',
            'alergias' => 'nullable|string|max:300',
            'estudios' => 'nullable|string|max:300',
        ]);

        // Se preserva el flujo con sus breakpoints para Xdebug.
        $resultado = (new FlujoDemo())->ejecutar($datos); // BREAKPOINT 0

        $registros = session('registros', []);
        $registros[] = $resultado['paciente'];
        session(['registros' => $registros]);

        // Resumen estadístico de la simulación, exclusivamente en sesión.
        $ejecuciones = session('demo_ejecuciones', []);
        $ejecuciones[] = [
            'nombre' => $resultado['paciente']['nombre'],
            'tipo' => $resultado['paciente']['tipo'],
            'tipo_historial' => $datos['tipo_historial'],
            'fecha' => now()->format('d/m/Y H:i'),
        ];
        session(['demo_ejecuciones' => $ejecuciones]);

        return redirect()->route('demo.index')->with('resultado', $resultado);
    }

    public function limpiar()
    {
        session()->forget(['registros', 'demo_ejecuciones']);
        return redirect()->route('demo.index')->with('mensaje', 'Datos simulados de la sesión eliminados.');
    }
}
