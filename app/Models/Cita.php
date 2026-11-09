<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cita extends Model
{
    protected $table = 'citas';
    protected $fillable = ['paciente_id', 'fecha', 'motivo', 'estado'];
    protected function casts(): array { return ['fecha' => 'datetime']; }
    public function paciente(): BelongsTo { return $this->belongsTo(Paciente::class); }
}
