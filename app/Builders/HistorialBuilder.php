<?php
namespace App\Builders;

use App\Models\Paciente;

final class HistorialBuilder
{
    private array $atributos = [];
    public function __construct(private readonly Paciente $paciente) {}
    public function agregarDiagnostico(string $valor): self { $this->atributos['diagnostico'] = $valor; return $this; }
    public function agregarAntecedentes(array $valores): self { $this->atributos['antecedentes'] = $valores; return $this; }
    public function agregarMedicamentos(array $valores): self { $this->atributos['medicamentos'] = $valores; return $this; }
    public function agregarAlergias(array $valores): self { $this->atributos['alergias'] = $valores; return $this; }
    public function agregarEstudios(array $valores): self { $this->atributos['estudios'] = $valores; return $this; }
    public function construir(): array { return $this->atributos; }
}
