<?php

namespace App\Demo\Estructurales\Prediccion;

/** Subject común a RealSubject y Proxy. */
interface ModeloPrediccionContrato
{
    public function generarPrediccionRiesgos(string $codigoCaso): string;
    public function ejecutarModelo(string $codigoCaso): string;
}
