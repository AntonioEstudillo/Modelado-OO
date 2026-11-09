<?php
namespace App\Services\Prediccion;

interface ModeloPrediccion
{
    /** Contrato; no hace diagnósticos médicos reales. */
    public function generarPrediccionRiesgos(array $datos): array;
}
