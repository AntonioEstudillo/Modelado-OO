<?php

namespace App\Demo\Estructurales;

use App\Demo\Estructurales\Reportes\CompositorReporte;
use App\Demo\Estructurales\Prediccion\ProxyModelo;
use App\Demo\Estructurales\Servicios\ServicioFarmacia;
use App\Demo\Estructurales\Servicios\ServicioImagenologia;
use App\Demo\Estructurales\Servicios\ServicioLaboratorio;

/** Facade: un punto de entrada simplificado hacia subsistemas independientes. */
final class HospitalFacade
{
    public function __construct(
        private readonly ServicioFarmacia $farmacia,
        private readonly ServicioLaboratorio $laboratorio,
        private readonly ServicioImagenologia $imagenologia,
        private readonly CompositorReporte $compositor,
    ) {}

    public function atenderCaso(string $codigoCaso, TrazaPatron $traza): array
    {
        $traza->agregar('HospitalFacade::atenderCaso() — inicia coordinación'); // BREAKPOINT F1
        $paciente = $this->registrarPaciente($codigoCaso, $traza);
        $cita = $this->programarCita($codigoCaso, $traza);
        $farmacia = $this->solicitarMedicamento($codigoCaso, $traza);
        $laboratorio = $this->solicitarAnalisis($codigoCaso, $traza);
        $imagenologia = $this->generarImagen($codigoCaso, $traza);
        $reporte = $this->generarReporteClinico($codigoCaso, $traza);
        $proxy = $this->consultarRiesgo($codigoCaso, $traza);
        $traza->agregar('HospitalFacade::atenderCaso() — finaliza sin SQL'); // BREAKPOINT F6
        return compact('paciente', 'cita', 'farmacia', 'laboratorio', 'imagenologia', 'reporte', 'proxy');
    }

    public function registrarPaciente(string $codigoCaso, TrazaPatron $traza): string
    {
        $traza->agregar('HospitalFacade::registrarPaciente() — registro simulado, no hay INSERT');
        return "Paciente de prueba vinculado al caso {$codigoCaso} (sin registro real).";
    }

    public function programarCita(string $codigoCaso, TrazaPatron $traza): string
    {
        $traza->agregar('HospitalFacade::programarCita() — cita simulada, no hay INSERT');
        return "Cita demostrativa asociada al caso {$codigoCaso} (sin agenda real).";
    }

    public function solicitarMedicamento(string $codigoCaso, TrazaPatron $traza): string
    {
        return $this->farmacia->solicitarMedicamento($codigoCaso, $traza); // BREAKPOINT F2
    }

    public function solicitarAnalisis(string $codigoCaso, TrazaPatron $traza): string
    {
        return $this->laboratorio->solicitarAnalisis($codigoCaso, $traza);
    }

    public function generarImagen(string $codigoCaso, TrazaPatron $traza): string
    {
        return $this->imagenologia->solicitarEstudio($codigoCaso, $traza);
    }

    /** Acceso demostrativo, sin autenticación clínica real y sin cálculo de riesgos. */
    public function consultarRiesgo(string $codigoCaso, TrazaPatron $traza): string
    {
        $proxy = new ProxyModelo('medico', [], $traza); // BREAKPOINT F7: fachada delega en Proxy
        return $proxy->generarPrediccionRiesgos($codigoCaso);
    }

    public function generarReporteClinico(string $codigoCaso, TrazaPatron $traza): string
    {
        $reporte = $this->compositor->componer($codigoCaso, ['laboratorio', 'imagenologia'], $traza);
        return $reporte->generarReporte();
    }
}
