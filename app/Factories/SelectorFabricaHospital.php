<?php
namespace App\Factories;

use InvalidArgumentException;

final class SelectorFabricaHospital
{
    public function paraTipo(string $tipo): FabricaHospital
    {
        return match ($tipo) {
            'general' => app(FabricaHospitalGeneral::class),
            'pediatrico' => app(FabricaHospitalPediatrico::class),
            'oncologico' => app(FabricaHospitalOncologico::class),
            default => throw new InvalidArgumentException('Tipo de paciente no válido.'),
        };
    }
}
