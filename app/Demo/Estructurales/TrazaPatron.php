<?php

namespace App\Demo\Estructurales;

/** Bitácora en memoria para mostrar el recorrido real en Xdebug. */
final class TrazaPatron
{
    private array $pasos = [];

    public function agregar(string $paso): void
    {
        $this->pasos[] = $paso;
    }

    public function pasos(): array
    {
        return $this->pasos;
    }
}
