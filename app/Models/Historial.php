<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Historial extends Model
{
    protected $table = 'historiales';
    protected $fillable = ['paciente_id', 'diagnostico', 'antecedentes', 'medicamentos', 'alergias', 'estudios', 'tipo'];
    protected function casts(): array { return ['antecedentes' => 'array', 'medicamentos' => 'array', 'alergias' => 'array', 'estudios' => 'array']; }
    public function paciente(): BelongsTo { return $this->belongsTo(Paciente::class); }
}
