<?php

namespace App\Demo\Estructurales\Reportes;

/** ConcreteDecorator: añade un módulo sin cambiar el reporte base. */
final class ReporteConLaboratorio extends DecoradorReporte
{
    public function generarReporte(): string
    {
        $base = parent::generarReporte(); // BREAKPOINT D3: entrar al componente envuelto
        $this->traza->agregar('ReporteConLaboratorio::generarReporte() — agrega sección Laboratorio');
        return $base . "\nAnexo de laboratorio: orden de análisis ficticia.";
    }
}
