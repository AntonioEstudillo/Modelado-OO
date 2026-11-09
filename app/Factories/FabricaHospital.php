<?php
namespace App\Factories;

use App\Models\{Historial, Paciente};
use App\Services\Prediccion\ModeloPrediccion;

interface FabricaHospital
{
    public function crearPaciente(array $datos): Paciente;
    public function crearHistorial(Paciente $paciente, array $datos = []): Historial;
    public function crearModeloPrediccion(): ModeloPrediccion;
}
