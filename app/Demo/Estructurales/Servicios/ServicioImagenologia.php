<?php

namespace App\Demo\Estructurales\Servicios;

use App\Demo\Estructurales\TrazaPatron;

final class ServicioImagenologia
{
    public function solicitarEstudio(string $codigoCaso, TrazaPatron $traza): string
    {
        $mensaje = "Imagenología: solicitud de estudio ficticio para {$codigoCaso}.";
        $traza->agregar('ServicioImagenologia::solicitarEstudio() — estudio simulado'); // BREAKPOINT F5
        return $mensaje;
    }
}
