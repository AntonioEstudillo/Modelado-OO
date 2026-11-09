<?php
namespace App\Factories;

use App\Models\{Historial, Paciente, PacienteGeneral};
use App\Services\Prediccion\{ModeloPrediccion, ModeloPrediccionDemo};

final class FabricaHospitalGeneral implements FabricaHospital
{
    public function crearPaciente(array $datos): Paciente
    {
        $datos['tipo'] = 'general';
        return PacienteGeneral::create($datos);
    }

    public function crearHistorial(Paciente $paciente, array $datos = []): Historial
    {
        return $paciente->historial()->create([...$datos, 'tipo' => 'general']);
    }

    public function crearModeloPrediccion(): ModeloPrediccion
    {
        return new ModeloPrediccionDemo(); // TODO: estrategia validada específica
    }
}
