<?php
namespace App\Builders;

use App\Models\Historial;
use App\Models\Paciente;

final class DirectorHistorial
{
    public function construirResumido(Paciente $paciente, array $datos): Historial
    {
        return (new HistorialBuilder($paciente))
            ->iniciar('resumido')
            ->agregarDiagnostico($datos['diagnostico'] ?? '')
            ->agregarAntecedentes($datos['antecedentes'] ?? [])
            ->construir();
    }

    public function construirCompleto(Paciente $paciente, array $datos): Historial
    {
        return (new HistorialBuilder($paciente))
            ->iniciar('completo')
            ->agregarDiagnostico($datos['diagnostico'] ?? '')
            ->agregarAntecedentes($datos['antecedentes'] ?? [])
            ->agregarMedicamentos($datos['medicamentos'] ?? [])
            ->agregarAlergias($datos['alergias'] ?? [])
            ->agregarEstudios($datos['estudios'] ?? [])
            ->construir();
    }
}
