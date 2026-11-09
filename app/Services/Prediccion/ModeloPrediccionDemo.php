<?php
namespace App\Services\Prediccion;

use LogicException;

final class ModeloPrediccionDemo implements ModeloPrediccion
{
    public function generarPrediccionRiesgos(array $datos): array
    {
        throw new LogicException('Integrar modelo validado antes de habilitar predicciones clínicas.');
    }
}
