<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\View\View;

/** Panel académico: únicamente lee la sesión, sin realizar consultas SQL. */
final class DashboardController extends Controller
{
    public function index(): View
    {
        $ejecuciones = session('demo_ejecuciones', []);
        $registros = session('registros', []);
        $categorias = ['general' => 0, 'pediatrico' => 0, 'oncologico' => 0];
        $formatos = ['resumido' => 0, 'completo' => 0];
        $estructurales = session('estructurales_operaciones', []);
        $estadisticasEstructurales = ['facade' => 0, 'decorator' => 0, 'proxy' => 0];
        foreach ($estructurales as $operacion) {
            $clave = $operacion['patron'] ?? '';
            if (array_key_exists($clave, $estadisticasEstructurales)) {
                $estadisticasEstructurales[$clave]++;
            }
        }

        foreach ($ejecuciones as $ejecucion) {
            $tipo = $ejecucion['tipo'] ?? '';
            $formato = $ejecucion['tipo_historial'] ?? '';
            if (array_key_exists($tipo, $categorias)) {
                $categorias[$tipo]++;
            }
            if (array_key_exists($formato, $formatos)) {
                $formatos[$formato]++;
            }
        }

        return view('dashboard.index', [
            'totalEjecuciones' => count($ejecuciones),
            'totalPacientes' => count($registros),
            'categorias' => $categorias,
            'formatos' => $formatos,
            'recientes' => array_slice(array_reverse($ejecuciones), 0, 5),
            'estadisticasEstructurales' => $estadisticasEstructurales,
            'totalEstructurales' => count($estructurales),
        ]);
    }
}
