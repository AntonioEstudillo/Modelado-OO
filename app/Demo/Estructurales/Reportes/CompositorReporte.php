<?php

namespace App\Demo\Estructurales\Reportes;

use App\Demo\Estructurales\TrazaPatron;

/** Monta la cadena de decoradores elegida por el usuario. */
final class CompositorReporte
{
    /** @param list<string> $extras */
    public function componer(string $codigoCaso, array $extras, TrazaPatron $traza): IReporteClinico
    {
        $reporte = new ReporteClinicoBasico($codigoCaso, $traza); // BREAKPOINT D1: componente original
        foreach ($extras as $extra) {
            $reporte = match ($extra) { // BREAKPOINT D2: mismo tipo, nuevos envoltorios
                'laboratorio' => new ReporteConLaboratorio($reporte, $traza),
                'imagenologia' => new ReporteConImagenologia($reporte, $traza),
                'farmacia' => new ReporteConFarmacia($reporte, $traza),
                default => throw new \InvalidArgumentException('Decorador desconocido.'),
            };
        }
        $traza->agregar('CompositorReporte::componer() — producto final: '.get_class($reporte));
        return $reporte;
    }
}
