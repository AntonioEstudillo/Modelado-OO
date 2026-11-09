<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tratamiento extends Model
{
    protected $table = 'tratamientos';
    protected $fillable = ['paciente_id', 'descripcion', 'estado'];
    public function paciente(): BelongsTo { return $this->belongsTo(Paciente::class); }
}
