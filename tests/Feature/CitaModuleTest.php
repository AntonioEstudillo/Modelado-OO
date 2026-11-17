<?php

namespace Tests\Feature;

use App\Models\Cita;
use App\Models\Paciente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CitaModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_citas_can_be_created_updated_and_deleted_from_the_web_module(): void
    {
        $paciente = Paciente::create(['nombre' => 'Ana Pérez', 'tipo' => 'general']);
        $fecha = now()->addDays(2)->setTime(12, 0)->format('Y-m-d\TH:i');

        $this->get('/citas')
            ->assertOk()
            ->assertSee('Programar cita')
            ->assertSee(route('web.citas.store'), false);
        $this->post('/citas', [
            'paciente_id' => $paciente->id,
            'fecha' => $fecha,
            'motivo' => 'Consulta',
        ])->assertRedirect('/citas');

        $cita = Cita::firstOrFail();
        $this->assertSame('programada', $cita->estado);

        $this->put("/citas/{$cita->id}", [
            'paciente_id' => $paciente->id,
            'fecha' => now()->addDays(3)->setTime(12, 0)->format('Y-m-d\TH:i'),
            'motivo' => 'Seguimiento',
            'estado' => 'completada',
        ])->assertRedirect('/citas');
        $this->assertDatabaseHas('citas', ['id' => $cita->id, 'motivo' => 'Seguimiento', 'estado' => 'completada']);

        $this->delete("/citas/{$cita->id}")->assertRedirect('/citas');
        $this->assertDatabaseMissing('citas', ['id' => $cita->id]);
    }

    public function test_cita_requires_a_registered_patient_and_a_future_date(): void
    {
        $this->from('/citas')->post('/citas', [
            'paciente_id' => 999,
            'fecha' => now()->subDay()->format('Y-m-d\TH:i'),
        ])->assertRedirect('/citas')
            ->assertSessionHasErrors(['paciente_id', 'fecha']);

        $this->assertDatabaseCount('citas', 0);
    }

    public function test_past_cita_can_be_marked_completed_without_rescheduling(): void
    {
        $paciente = Paciente::create(['nombre' => 'Luis Ruiz', 'tipo' => 'general']);
        $cita = Cita::create([
            'paciente_id' => $paciente->id,
            'fecha' => now()->subDay(),
            'motivo' => 'Consulta',
        ]);

        $this->put("/citas/{$cita->id}", [
            'paciente_id' => $paciente->id,
            'fecha' => $cita->fecha->format('Y-m-d\TH:i'),
            'motivo' => 'Consulta',
            'estado' => 'completada',
        ])->assertRedirect('/citas');

        $this->assertDatabaseHas('citas', ['id' => $cita->id, 'estado' => 'completada']);
    }
}
