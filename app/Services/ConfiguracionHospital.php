<?php

namespace App\Services;

use Illuminate\Contracts\Config\Repository;

final class ConfiguracionHospital
{
    public function __construct(private readonly Repository $config) {}

    public function nombre(): string
    {
        return (string) $this->config->get('hospital.nombre', 'SGHI Demo');
    }

    public function prediccionHabilitada(): bool
    {
        return (bool) $this->config->get('hospital.prediccion_habilitada', false);
    }
}
