<?php
namespace App\Services;

use App\Factories\SelectorFabricaHospital;
use App\Models\Paciente;
use Illuminate\Support\Facades\DB;

final class ServicioWebApi
{
    public function __construct(private readonly SelectorFabricaHospital $selector) {}

    public function registrarPaciente(array $datos): Paciente
    {
        return DB::transaction(function () use ($datos) {
            $fabrica = $this->selector->paraTipo($datos['tipo']);
            $paciente = $fabrica->crearPaciente($datos);
            $fabrica->crearHistorial($paciente);
            return $paciente->load('historial');
        });
    }
}
