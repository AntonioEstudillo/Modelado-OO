<?php
namespace App\Services;

use App\Factories\FabricaHospital;
use App\Models\Paciente;
use App\Services\Prediccion\ModeloPrediccion;
use Illuminate\Support\Facades\DB;

final class SistemaSGHI
{
    public function registrarPaciente(array $datos, FabricaHospital $fabrica): Paciente
    {
        return DB::transaction(function () use ($datos, $fabrica): Paciente {
            $paciente = $fabrica->crearPaciente($datos);
            $fabrica->crearHistorial($paciente);

            return $paciente->load('historial');
        });
    }

    public function validarPaciente(Paciente $paciente): bool { return $paciente->exists && filled($paciente->nombre); }

    public function modeloPrediccion(FabricaHospital $fabrica): ModeloPrediccion
    {
        return $fabrica->crearModeloPrediccion();
    }
}
