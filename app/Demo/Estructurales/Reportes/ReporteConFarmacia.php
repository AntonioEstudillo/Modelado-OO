<?php

namespace App\Demo\Estructurales\Reportes;

/** ConcreteDecorator: añade un módulo sin cambiar el reporte base. */
final class ReporteConFarmacia extends DecoradorReporte
{
    public function generarReporte(): string
    {
        $base = parent::generarReporte(); // BREAKPOINT D3: entrar al componente envuelto
        $this->traza->agregar('ReporteConFarmacia::generarReporte() — agrega sección Farmacia');
        return $base . "\nAnexo de farmacia: solicitud ficticia registrada.";
    }
}
