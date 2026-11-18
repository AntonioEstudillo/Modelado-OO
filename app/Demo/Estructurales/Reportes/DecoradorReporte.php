<?php

namespace App\Demo\Estructurales\Reportes;

use App\Demo\Estructurales\TrazaPatron;

/** Decorator: conserva la misma interfaz y delega al componente envuelto. */
abstract class DecoradorReporte implements IReporteClinico
{
    public function __construct(
        protected IReporteClinico $componente,
        protected TrazaPatron $traza,
    ) {}

    public function generarReporte(): string
    {
        return $this->componente->generarReporte(); // BREAKPOINT D4: delegación
    }
}
