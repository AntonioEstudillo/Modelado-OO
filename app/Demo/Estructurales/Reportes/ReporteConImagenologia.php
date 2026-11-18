<?php

namespace App\Demo\Estructurales\Reportes;

/** ConcreteDecorator: añade un módulo sin cambiar el reporte base. */
final class ReporteConImagenologia extends DecoradorReporte
{
    public function generarReporte(): string
    {
        $base = parent::generarReporte(); // BREAKPOINT D3: entrar al componente envuelto
        $this->traza->agregar('ReporteConImagenologia::generarReporte() — agrega sección Imagenología');
        return $base . "\nAnexo de imagenología: estudio ficticio solicitado.";
    }
}
