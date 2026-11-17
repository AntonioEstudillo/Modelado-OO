<?php

namespace Tests\Feature;

use App\Models\Historial;
use App\Models\Paciente;
use App\Services\ConfiguracionHospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArchitecturePatternsTest extends TestCase
{
    use RefreshDatabase;

    public function test_hospital_configuration_is_a_container_singleton(): void
    {
        $primera = app(ConfiguracionHospital::class);
        $segunda = app(ConfiguracionHospital::class);

        $this->assertSame($primera, $segunda);
        $this->assertSame(config('hospital.nombre'), $primera->nombre());
        $this->assertFalse($primera->prediccionHabilitada());
    }

    public function test_history_director_persists_summary_and_complete_formats(): void
    {
        $this->post('/pacientes', [
            'nombre' => 'Paciente Builder',
            'tipo' => 'general',
        ])->assertRedirect('/pacientes');

        $paciente = Paciente::firstOrFail();
        $this->get(route('web.pacientes.historial.edit', $paciente))->assertOk();

        $this->put(route('web.pacientes.historial.update', $paciente), [
            'tipo_historial' => 'resumido',
            'diagnostico' => 'Control',
            'antecedentes' => "Asma\nAlergia estacional",
            'medicamentos' => 'No debe agregarse al resumen',
        ])->assertRedirect(route('web.pacientes.historial.edit', $paciente));

        $historial = Historial::where('paciente_id', $paciente->id)->firstOrFail();
        $this->assertSame('resumido', $historial->tipo_historial);
        $this->assertSame(['Asma', 'Alergia estacional'], $historial->antecedentes);
        $this->assertNull($historial->medicamentos);

        $this->put(route('web.pacientes.historial.update', $paciente), [
            'tipo_historial' => 'completo',
            'diagnostico' => 'Seguimiento',
            'antecedentes' => 'Asma',
            'medicamentos' => 'Medicamento A, Medicamento B',
            'alergias' => 'Polen',
            'estudios' => 'Radiografía',
        ])->assertRedirect(route('web.pacientes.historial.edit', $paciente));

        $historial->refresh();
        $this->assertSame('completo', $historial->tipo_historial);
        $this->assertSame(['Medicamento A', 'Medicamento B'], $historial->medicamentos);
        $this->assertSame(['Polen'], $historial->alergias);
        $this->assertSame(['Radiografía'], $historial->estudios);
    }
}
