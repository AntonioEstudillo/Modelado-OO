<?php
namespace App\Demo;
interface FabricaHospitalDemo { public function crearPaciente(string $nombre): array; public function crearHistorial(array $paciente): array; }
final class FabricaGeneral implements FabricaHospitalDemo {
 public function crearPaciente(string $nombre): array { return ['nombre'=>$nombre,'tipo'=>'general']; }
 public function crearHistorial(array $paciente): array { return ['paciente'=>$paciente['nombre'],'origen'=>'General']; }
}
final class FabricaPediatrica implements FabricaHospitalDemo {
 public function crearPaciente(string $nombre): array { return ['nombre'=>$nombre,'tipo'=>'pediatrico']; }
 public function crearHistorial(array $paciente): array { return ['paciente'=>$paciente['nombre'],'origen'=>'Pediatría']; }
}
final class FabricaOncologica implements FabricaHospitalDemo {
 public function crearPaciente(string $nombre): array { return ['nombre'=>$nombre,'tipo'=>'oncologico']; }
 public function crearHistorial(array $paciente): array { return ['paciente'=>$paciente['nombre'],'origen'=>'Oncología']; }
}
final class SelectorFabrica {
 public function seleccionar(string $tipo): FabricaHospitalDemo {
  return match($tipo) { 'general'=>new FabricaGeneral(), 'pediatrico'=>new FabricaPediatrica(), 'oncologico'=>new FabricaOncologica(), default=>throw new \InvalidArgumentException('Tipo no válido') };
 }
}
/** Builder: mantiene el producto en construcción. */
final class HistorialBuilderDemo {
    private array $datos = [];

    public function iniciar(string $tipoHistorial): self {
        $this->datos = ['tipo_historial' => $tipoHistorial]; // BREAKPOINT B1: producto inicial
        return $this;
    }
    public function diagnostico(string $valor): self {
        $this->datos['diagnostico'] = $valor; // BREAKPOINT B2: inspeccionar $this->datos
        return $this;
    }
    public function antecedentes(array $valores): self {
        $this->datos['antecedentes'] = $valores;
        return $this;
    }
    public function medicamentos(array $valores): self {
        $this->datos['medicamentos'] = $valores;
        return $this;
    }
    public function alergias(array $valores): self {
        $this->datos['alergias'] = $valores;
        return $this;
    }
    public function estudios(array $valores): self {
        $this->datos['estudios'] = $valores;
        return $this;
    }
    public function construir(): array {
        $resultado = $this->datos; // BREAKPOINT B3: inspeccionar $resultado
        return $resultado;
    }
}

/** Director: controla la secuencia de pasos y el tipo de historial. */
final class DirectorHistorialDemo {
    private function lista(array $datos, string $campo): array {
        return array_values(array_filter(
            array_map('trim', explode(',', $datos[$campo] ?? '')),
            fn (string $valor): bool => $valor !== ''
        ));
    }

    public function construirResumido(HistorialBuilderDemo $builder, array $datos): array {
        $builder->iniciar('resumido'); // BREAKPOINT D1: escoger receta resumida
        $builder->diagnostico($datos['diagnostico']);
        $builder->antecedentes($this->lista($datos, 'antecedentes'));
        return $builder->construir(); // Solo tipo, diagnóstico y antecedentes
    }

    public function construirCompleto(HistorialBuilderDemo $builder, array $datos): array {
        $builder->iniciar('completo'); // BREAKPOINT D2: escoger receta completa
        $builder->diagnostico($datos['diagnostico']);
        $builder->antecedentes($this->lista($datos, 'antecedentes'));
        $builder->medicamentos($this->lista($datos, 'medicamentos'));
        $builder->alergias($this->lista($datos, 'alergias'));
        $builder->estudios($this->lista($datos, 'estudios'));
        return $builder->construir();
    }
}
final class FlujoDemo {
 public function ejecutar(array $datos): array {
  $tipo=$datos['tipo']; // BREAKPOINT 1: inspeccionar $tipo
  $fabrica=(new SelectorFabrica())->seleccionar($tipo); // BREAKPOINT 2: inspeccionar $fabrica
  $paciente=$fabrica->crearPaciente($datos['nombre']); // BREAKPOINT 3: inspeccionar $paciente
  $historialBase=$fabrica->crearHistorial($paciente);
  $tipoHistorial = $datos['tipo_historial']; // BREAKPOINT 4: observar selección
  $director = new DirectorHistorialDemo();
  $builder = new HistorialBuilderDemo();
  if ($tipoHistorial === 'resumido') {
   $historial = $director->construirResumido($builder, $datos); // BREAKPOINT 5A
  } else {
   $historial = $director->construirCompleto($builder, $datos); // BREAKPOINT 5B
  }
  $mensajes=['Abstract Factory: '.get_class($fabrica),'Paciente creado en memoria: '.$paciente['nombre'],'Historial inicial creado por fábrica: '.$historialBase['origen'],'Builder: historial '.$tipoHistorial.' con '.count($historial).' campos construidos','Modelo de predicción: simulación sin cálculo clínico','Base de datos: INSERT simulado, ninguna consulta SQL realizada'];
  return compact('paciente','historialBase','historial','mensajes');
 }
}
