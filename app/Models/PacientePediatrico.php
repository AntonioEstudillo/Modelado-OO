<?php
namespace App\Models;

class PacientePediatrico extends Paciente
{
    protected static function booted(): void
    {
        static::addGlobalScope('tipo_pediatrico', fn ($query) => $query->where('tipo', 'pediatrico'));
        static::creating(fn (Paciente $paciente) => $paciente->tipo = 'pediatrico');
    }
}
