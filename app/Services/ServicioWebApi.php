<?php
namespace App\Services;

use App\Factories\SelectorFabricaHospital;
use App\Models\Paciente;

final class ServicioWebApi
{
    public function __construct(
        private readonly SelectorFabricaHospital $selector,
        private readonly SistemaSGHI $sistema,
    ) {}

    public function registrarPaciente(array $datos): Paciente
    {
        $fabrica = $this->selector->paraTipo($datos['tipo']);

        return $this->sistema->registrarPaciente($datos, $fabrica);
    }
}
