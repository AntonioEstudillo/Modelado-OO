<?php

namespace App\Demo\Estructurales\Prediccion;

use App\Demo\Estructurales\TrazaPatron;

/** RealSubject ficticio: no aplica modelos ni infiere riesgos de salud. */
final class ModeloPrediccionRealDemo implements ModeloPrediccionContrato
{
    public function __construct(private readonly TrazaPatron $traza) {}

    public function generarPrediccionRiesgos(string $codigoCaso): string
    {
        $this->traza->agregar('ModeloPrediccionRealDemo::generarPrediccionRiesgos() — sujeto real invocado'); // BREAKPOINT P5
        return $this->ejecutarModelo($codigoCaso);
    }

    public function ejecutarModelo(string $codigoCaso): string
    {
        $this->traza->agregar('ModeloPrediccionRealDemo::ejecutarModelo() — procesamiento ficticio'); // BREAKPOINT P6
        return "Caso {$codigoCaso}: ejecución simulada. No existe una predicción ni evaluación clínica.";
    }
}
