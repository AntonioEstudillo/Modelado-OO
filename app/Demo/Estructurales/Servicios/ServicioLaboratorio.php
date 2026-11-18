<?php

namespace App\Demo\Estructurales\Servicios;

use App\Demo\Estructurales\TrazaPatron;

final class ServicioLaboratorio
{
    public function solicitarAnalisis(string $codigoCaso, TrazaPatron $traza): string
    {
        $mensaje = "Laboratorio: orden de prueba ficticia emitida para {$codigoCaso}.";
        $traza->agregar('ServicioLaboratorio::solicitarAnalisis() — orden simulada'); // BREAKPOINT F4
        return $mensaje;
    }
}
