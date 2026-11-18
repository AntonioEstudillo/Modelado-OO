<?php

namespace App\Http\Controllers;

use App\Demo\Estructurales\HospitalFacade;
use App\Demo\Estructurales\Prediccion\ProxyModelo;
use App\Demo\Estructurales\Reportes\CompositorReporte;
use App\Demo\Estructurales\Servicios\ServicioFarmacia;
use App\Demo\Estructurales\Servicios\ServicioImagenologia;
use App\Demo\Estructurales\Servicios\ServicioLaboratorio;
use App\Demo\Estructurales\TrazaPatron;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

/** Orquestación HTTP: el código del patrón vive fuera del controlador. */
final class PatronesEstructuralesController extends Controller
{
    public function index(): View
    {
        return view('estructurales.index', [
            'resultado' => session('estructural_resultado'),
            'operaciones' => array_reverse(session('estructurales_operaciones', [])),
            'cacheCount' => count(session('estructurales_cache', [])),
        ]);
    }

    public function facade(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'codigo_caso' => ['required', 'string', 'regex:/^CASO-[A-Za-z0-9]{2,12}$/', 'max:17'],
        ]);
        $traza = new TrazaPatron();
        $fachada = new HospitalFacade(
            new ServicioFarmacia(),
            new ServicioLaboratorio(),
            new ServicioImagenologia(),
            new CompositorReporte(),
        );
        $salida = $fachada->atenderCaso(strtoupper($datos['codigo_caso']), $traza); // BREAKPOINT F0
        return $this->registrar('facade', 'Atención coordinada', $traza, $salida);
    }

    public function decorator(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'codigo_caso' => ['required', 'string', 'regex:/^CASO-[A-Za-z0-9]{2,12}$/', 'max:17'],
            'extras' => ['sometimes', 'array', 'max:3'],
            'extras.*' => ['required', 'in:laboratorio,imagenologia,farmacia', 'distinct'],
        ]);
        $extras = $datos['extras'] ?? [];
        $traza = new TrazaPatron();
        $reporte = (new CompositorReporte())->componer(strtoupper($datos['codigo_caso']), $extras, $traza); // BREAKPOINT D0
        $contenido = $reporte->generarReporte();
        return $this->registrar('decorator', 'Reporte con '.count($extras).' decoradores', $traza, [
            'tipo_concreto' => get_class($reporte),
            'envoltorios' => $extras,
            'reporte' => $contenido,
        ]);
    }

    public function proxy(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'codigo_caso' => ['required', 'string', 'regex:/^CASO-[A-Za-z0-9]{2,12}$/', 'max:17'],
            'rol' => ['required', 'in:medico,visitante'],
        ]);
        $codigo = strtoupper($datos['codigo_caso']);
        $traza = new TrazaPatron();
        $cache = session('estructurales_cache', []);
        $proxy = new ProxyModelo($datos['rol'], $cache, $traza); // BREAKPOINT P0
        $respuesta = $proxy->generarPrediccionRiesgos($codigo);
        $nuevoCache = $proxy->exportarCache();
        session(['estructurales_cache' => array_slice($nuevoCache, -30, null, true)]);
        return $this->registrar('proxy', $datos['rol'] === 'medico' ? 'Acceso de prueba permitido' : 'Acceso de prueba denegado', $traza, [
            'rol_demostrativo' => $datos['rol'],
            'codigo' => $codigo,
            'respuesta' => $respuesta,
            'cache_entradas' => count($nuevoCache),
        ]);
    }

    public function limpiar(): RedirectResponse
    {
        session()->forget(['estructurales_operaciones', 'estructurales_cache', 'estructural_resultado']);
        return redirect()->route('estructurales.index')->with('mensaje', 'Simulaciones estructurales eliminadas de la sesión.');
    }

    private function registrar(string $patron, string $operacion, TrazaPatron $traza, array $salida): RedirectResponse
    {
        $historial = session('estructurales_operaciones', []);
        $historial[] = [
            'patron' => $patron,
            'operacion' => $operacion,
            'fecha' => now()->format('d/m/Y H:i:s'),
        ];
        session(['estructurales_operaciones' => array_slice($historial, -25)]);
        return redirect()->route('estructurales.index')->with('estructural_resultado', [
            'patron' => $patron,
            'operacion' => $operacion,
            'pasos' => $traza->pasos(),
            'salida' => $salida,
        ]);
    }
}
