<?php

namespace App\Demo\Estructurales\Reportes;

use App\Demo\Estructurales\TrazaPatron;

/** ConcreteComponent: contenido mínimo, sólo para fines académicos. */
final class ReporteClinicoBasico implements IReporteClinico
{
    public function __construct(
        private readonly string $codigoCaso,
        private readonly TrazaPatron $traza,
    ) {}

    public function generarReporte(): string
    {
        $this->traza->agregar('ReporteClinicoBasico::generarReporte() — contenido base'); // BREAKPOINT D5
        return "REPORTE ACADÉMICO — Caso {$this->codigoCaso}\nEstado: información completamente simulada.";
    }
}
