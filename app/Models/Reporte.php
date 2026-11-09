<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reporte extends Model
{
    protected $table = 'reportes';
    protected $fillable = ['historial_id', 'contenido'];
    public function historial(): BelongsTo { return $this->belongsTo(Historial::class); }
}
