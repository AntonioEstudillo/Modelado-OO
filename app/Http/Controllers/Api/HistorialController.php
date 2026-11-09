<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use Illuminate\Http\JsonResponse;

class HistorialController extends Controller
{
    public function show(Paciente $paciente): JsonResponse
    {
        return response()->json($paciente->historial ?? ['mensaje' => 'Sin historial']);
    }
}
