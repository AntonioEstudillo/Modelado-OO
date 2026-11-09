<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Paciente extends Model
{
    protected $table = 'pacientes';
    protected $fillable = ['nombre', 'tipo', 'fecha_nacimiento'];
    protected function casts(): array { return ['fecha_nacimiento' => 'date']; }
    public function citas(): HasMany { return $this->hasMany(Cita::class); }
    public function tratamientos(): HasMany { return $this->hasMany(Tratamiento::class); }
    public function historial(): HasOne { return $this->hasOne(Historial::class); }
}
