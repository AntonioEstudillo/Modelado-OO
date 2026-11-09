<?php
namespace App\Models;

class PacienteOncologico extends Paciente
{
    protected static function booted(): void
    {
        static::addGlobalScope('tipo_oncologico', fn ($query) => $query->where('tipo', 'oncologico'));
        static::creating(fn (Paciente $paciente) => $paciente->tipo = 'oncologico');
    }
}
