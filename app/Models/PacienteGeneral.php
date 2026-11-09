<?php
namespace App\Models;

/** Fachada de dominio del paciente general; comparte tabla con Paciente. */
class PacienteGeneral extends Paciente
{
    protected static function booted(): void
    {
        static::addGlobalScope('tipo_general', fn ($query) => $query->where('tipo', 'general'));
        static::creating(fn (Paciente $paciente) => $paciente->tipo = 'general');
    }
}
