<?php

namespace Tests\Feature;

use App\Models\Cita;
use App\Models\Paciente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PacienteModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_can_be_registered_updated_and_deleted_from_the_web_module(): void
    {
        $this->get('/pacientes')
            ->assertOk()
            ->assertSee('Registrar paciente')
            ->assertSee(config('hospital.nombre'))
            ->assertSee(route('web.pacientes.store'), false);

        $this->post('/pacientes', [
            'nombre' => 'Ana Pérez',
            'tipo' => 'pediatrico',
            'fecha_nacimiento' => '2015-05-10',
        ])->assertRedirect('/pacientes');

        $paciente = Paciente::with('historial')->firstOrFail();
        $this->assertSame('pediatrico', $paciente->tipo);
        $this->assertSame('pediatrico', $paciente->historial->tipo);

        $this->put("/pacientes/{$paciente->id}", [
            'nombre' => 'Ana Ruiz',
            'fecha_nacimiento' => '2015-05-10',
        ])->assertRedirect('/pacientes');
        $this->assertDatabaseHas('pacientes', ['id' => $paciente->id, 'nombre' => 'Ana Ruiz']);

        Cita::create([
            'paciente_id' => $paciente->id,
            'fecha' => now()->addDay(),
            'motivo' => 'Consulta',
        ]);
        $this->delete("/pacientes/{$paciente->id}")->assertRedirect('/pacientes');

        $this->assertDatabaseMissing('pacientes', ['id' => $paciente->id]);
        $this->assertDatabaseMissing('historiales', ['paciente_id' => $paciente->id]);
        $this->assertDatabaseMissing('citas', ['paciente_id' => $paciente->id]);
    }

    public function test_patient_rejects_invalid_type_and_future_birth_date(): void
    {
        $this->from('/pacientes')->post('/pacientes', [
            'nombre' => 'Paciente inválido',
            'tipo' => 'desconocido',
            'fecha_nacimiento' => now()->addDay()->toDateString(),
        ])->assertRedirect('/pacientes')
            ->assertSessionHasErrors(['tipo', 'fecha_nacimiento']);

        $this->assertDatabaseCount('pacientes', 0);
    }
}
