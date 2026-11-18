<?php

namespace App\Demo\Estructurales\Servicios;

use App\Demo\Estructurales\TrazaPatron;

/** Subsistema de farmacia. Simulación; no gestiona recetas ni medicamentos reales. */
final class ServicioFarmacia
{
    public function solicitarMedicamento(string $codigoCaso, TrazaPatron $traza): string
    {
        $mensaje = "Farmacia: solicitud administrativa ficticia registrada para {$codigoCaso}.";
        $traza->agregar('ServicioFarmacia::solicitarMedicamento() — solicitud simulada, sin SQL'); // BREAKPOINT F3
        return $mensaje;
    }
}
