<?php

namespace Tests\Feature;

use App\Demo\Estructurales\Prediccion\ProxyModelo;
use App\Demo\Estructurales\Reportes\CompositorReporte;
use App\Demo\Estructurales\HospitalFacade;
use App\Demo\Estructurales\Servicios\{ServicioFarmacia, ServicioLaboratorio, ServicioImagenologia};
use App\Demo\Estructurales\TrazaPatron;
use Tests\TestCase;

class StructuralPatternsDemoTest extends TestCase
{
    public function test_laboratory_loads_without_using_the_database(): void
    {
        $this->get('/patrones-estructurales')
            ->assertOk()
            ->assertSee('Facade')
            ->assertSee('Decorator')
            ->assertSee('Proxy');
    }

    public function test_facade_coordinates_subsystems_and_patterns(): void
    {
        $traza = new TrazaPatron();
        $facade = new HospitalFacade(new ServicioFarmacia(), new ServicioLaboratorio(), new ServicioImagenologia(), new CompositorReporte());
        $resultado = $facade->atenderCaso('CASO-001', $traza);

        $this->assertCount(7, $resultado);
        $this->assertStringContainsString('Farmacia:', $resultado['farmacia']);
        $this->assertStringContainsString('Imagenología:', $resultado['imagenologia']);
        $this->assertStringContainsString('Anexo de laboratorio', $resultado['reporte']);
        $this->assertStringContainsString('No existe una predicción', $resultado['proxy']);
        $this->assertStringContainsString('HospitalFacade::atenderCaso()', implode(';', $traza->pasos()));
    }

    public function test_decorator_can_generate_base_or_extended_report(): void
    {
        $traza = new TrazaPatron();
        $compositor = new CompositorReporte();
        $basico = $compositor->componer('CASO-001', [], $traza)->generarReporte();
        $completo = $compositor->componer('CASO-001', ['laboratorio', 'imagenologia', 'farmacia'], $traza)->generarReporte();
        $this->assertStringNotContainsString('Anexo de laboratorio', $basico);
        $this->assertStringContainsString('Anexo de laboratorio', $completo);
        $this->assertStringContainsString('Anexo de imagenología', $completo);
        $this->assertStringContainsString('Anexo de farmacia', $completo);
    }

    public function test_proxy_checks_access_and_skips_real_subject_on_cache_hit(): void
    {
        $traza1 = new TrazaPatron();
        $proxy1 = new ProxyModelo('medico', [], $traza1);
        $resultado = $proxy1->generarPrediccionRiesgos('CASO-001');
        $this->assertStringContainsString('simulada', $resultado);
        $this->assertStringContainsString('ModeloPrediccionRealDemo::ejecutarModelo()', implode(';', $traza1->pasos()));

        $traza2 = new TrazaPatron();
        $proxy2 = new ProxyModelo('medico', $proxy1->exportarCache(), $traza2);
        $this->assertSame($resultado, $proxy2->generarPrediccionRiesgos('CASO-001'));
        $this->assertStringContainsString('HIT', implode(';', $traza2->pasos()));
        $this->assertStringNotContainsString('ModeloPrediccionRealDemo::ejecutarModelo()', implode(';', $traza2->pasos()));

        $traza3 = new TrazaPatron();
        $proxy3 = new ProxyModelo('visitante', $proxy1->exportarCache(), $traza3);
        $this->assertStringContainsString('Acceso denegado', $proxy3->ejecutarModelo('CASO-001'));
        $this->assertStringNotContainsString('HIT', implode(';', $traza3->pasos()));
    }

    public function test_proxy_cache_survives_across_http_requests_and_denies_visitors(): void
    {
        $this->post('/patrones-estructurales/proxy', ['codigo_caso' => 'CASO-001', 'rol' => 'medico'])
            ->assertRedirect(route('estructurales.index'));
        $this->assertCount(1, session('estructurales_cache', []));

        $this->post('/patrones-estructurales/proxy', ['codigo_caso' => 'CASO-001', 'rol' => 'medico'])
            ->assertRedirect(route('estructurales.index'));
        $this->assertStringContainsString('HIT', implode(';', session('estructural_resultado')['pasos']));

        $this->post('/patrones-estructurales/proxy', ['codigo_caso' => 'CASO-001', 'rol' => 'visitante'])
            ->assertRedirect(route('estructurales.index'));
        $this->assertStringContainsString('denegado', session('estructural_resultado')['salida']['respuesta']);

        $this->post('/patrones-estructurales/limpiar')->assertRedirect(route('estructurales.index'));
        $this->assertSame([], session('estructurales_cache', []));
    }

    public function test_dashboard_counts_structural_operations(): void
    {
        $this->post('/patrones-estructurales/facade', ['codigo_caso' => 'CASO-001'])->assertRedirect();
        $this->post('/patrones-estructurales/decorator', ['codigo_caso' => 'CASO-001', 'extras' => ['farmacia']])->assertRedirect();
        $this->get('/')->assertOk()->assertSee('Patrones estructurales')->assertSee('2 ejecuciones estructurales');
    }
}
