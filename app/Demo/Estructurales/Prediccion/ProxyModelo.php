<?php

namespace App\Demo\Estructurales\Prediccion;

use App\Demo\Estructurales\TrazaPatron;

/** Proxy de protección + caché. Roles seleccionados en UI son sólo demostrativos. */
final class ProxyModelo implements ModeloPrediccionContrato
{
    private ?ModeloPrediccionRealDemo $modeloReal = null;

    /** @param array<string,string> $cache Estado recuperado de la sesión. */
    public function __construct(
        private readonly string $rolActual,
        private array $cache,
        private readonly TrazaPatron $traza,
    ) {}

    public function autorizar(): bool
    {
        $autorizado = $this->rolActual === 'medico'; // BREAKPOINT P2: ejemplo, NO autenticación real
        $this->traza->agregar('ProxyModelo::autorizar() — rol '.$this->rolActual.': '.($autorizado ? 'permitido' : 'denegado'));
        return $autorizado;
    }

    public function buscarEnCache(string $codigoCaso): ?string
    {
        $resultado = $this->cache[$codigoCaso] ?? null; // BREAKPOINT P3: caché previo a RealSubject
        $this->traza->agregar('ProxyModelo::buscarEnCache() — '.($resultado === null ? 'MISS' : 'HIT'));
        return $resultado;
    }

    public function generarPrediccionRiesgos(string $codigoCaso): string
    {
        $this->traza->agregar('ProxyModelo::generarPrediccionRiesgos() — intercepta petición'); // BREAKPOINT P1
        if (! $this->autorizar()) {
            return 'Acceso denegado en la demostración. El sujeto real no fue ejecutado.';
        }
        $resultado = $this->buscarEnCache($codigoCaso);
        if ($resultado !== null) {
            return $resultado; // BREAKPOINT P4a: HIT: no se instancia RealSubject
        }
        $this->modeloReal ??= new ModeloPrediccionRealDemo($this->traza); // BREAKPOINT P4b: creación diferida
        $resultado = $this->modeloReal->generarPrediccionRiesgos($codigoCaso);
        $this->cache[$codigoCaso] = $resultado;
        $this->traza->agregar('ProxyModelo — resultado ficticio guardado en caché de sesión');
        return $resultado;
    }

    public function ejecutarModelo(string $codigoCaso): string
    {
        // La otra operación del contrato tampoco puede saltarse el control de acceso.
        return $this->generarPrediccionRiesgos($codigoCaso);
    }

    public function exportarCache(): array
    {
        return $this->cache;
    }
}
