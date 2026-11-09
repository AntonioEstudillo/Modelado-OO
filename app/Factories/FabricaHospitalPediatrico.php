<?php
namespace App\Factories;

use App\Models\{Historial, Paciente, PacientePediatrico};
use App\Services\Prediccion\{ModeloPrediccion, ModeloPrediccionDemo};

final class FabricaHospitalPediatrico implements FabricaHospital
{
    public function crearPaciente(array $datos): Paciente
    {
        $datos['tipo'] = 'pediatrico';
        return PacientePediatrico::create($datos);
    }

    public function crearHistorial(Paciente $paciente, array $datos = []): Historial
    {
        return $paciente->historial()->create([...$datos, 'tipo' => 'pediatrico']);
    }

    public function crearModeloPrediccion(): ModeloPrediccion
    {
        return new ModeloPrediccionDemo(); // TODO: estrategia validada específica
    }
}
