<?php
namespace App\Factories;

use App\Models\{Historial, Paciente, PacienteOncologico};
use App\Services\Prediccion\{ModeloPrediccion, ModeloPrediccionDemo};

final class FabricaHospitalOncologico implements FabricaHospital
{
    public function crearPaciente(array $datos): Paciente
    {
        $datos['tipo'] = 'oncologico';
        return PacienteOncologico::create($datos);
    }

    public function crearHistorial(Paciente $paciente, array $datos = []): Historial
    {
        return $paciente->historial()->create([...$datos, 'tipo' => 'oncologico']);
    }

    public function crearModeloPrediccion(): ModeloPrediccion
    {
        return new ModeloPrediccionDemo(); // TODO: estrategia validada específica
    }
}
