<?php

namespace Tests\Feature;

use Tests\TestCase;

class DashboardDemoTest extends TestCase
{
    public function test_dashboard_and_laboratory_render_without_database_queries(): void
    {
        $this->get('/')->assertOk()->assertSee('Vista general')->assertSee('Simulaciones ejecutadas');
        $this->get('/laboratorio')->assertOk()->assertSee('Ejecutar patrones')->assertSee('Historial resumido');
    }

    public function test_simulation_updates_session_dashboard_and_preserves_builder_output(): void
    {
        $this->post('/demo', [
            'nombre' => 'Paciente prueba',
            'tipo' => 'pediatrico',
            'tipo_historial' => 'resumido',
            'diagnostico' => 'Caso ficticio',
            'antecedentes' => 'Ejemplo A',
        ])->assertRedirect(route('demo.index'));

        $this->assertSame(1, count(session('demo_ejecuciones', [])));
        $this->get('/')->assertOk()->assertSee('Paciente prueba')->assertSee('Pediátrico');
        $this->post('/limpiar')->assertRedirect(route('demo.index'));
        $this->assertSame([], session('demo_ejecuciones', []));
    }
}
