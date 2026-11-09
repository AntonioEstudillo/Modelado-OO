<?php
namespace App\Services;

use App\Models\Paciente;
use App\Services\Prediccion\ModeloPrediccion;

final class SistemaSGHI
{
    public function __construct(private readonly ModeloPrediccion $prediccion) {}
    public function validarPaciente(Paciente $paciente): bool { return $paciente->exists && filled($paciente->nombre); }
    public function modeloPrediccion(): ModeloPrediccion { return $this->prediccion; }
}
