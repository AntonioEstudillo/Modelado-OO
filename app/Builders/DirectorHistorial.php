<?php
namespace App\Builders;

use App\Models\Paciente;

final class DirectorHistorial
{
    public function crearHistorialBasico(Paciente $paciente, string $diagnostico): array
    {
        return (new HistorialBuilder($paciente))->agregarDiagnostico($diagnostico)->construir();
    }
}
