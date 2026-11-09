<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    public function index(): JsonResponse { return response()->json(Cita::with('paciente')->paginate(15)); }
    public function show(Cita $cita): JsonResponse { return response()->json($cita->load('paciente')); }
    public function store(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'fecha' => ['required', 'date', 'after:now'],
            'motivo' => ['nullable', 'string', 'max:255'],
        ]);
        return response()->json(Cita::create($datos), 201);
    }
    public function update(Request $request, Cita $cita): JsonResponse
    {
        $datos = $request->validate([
            'fecha' => ['sometimes', 'date'],
            'motivo' => ['sometimes', 'nullable', 'string', 'max:255'],
            'estado' => ['sometimes', 'in:programada,completada,cancelada'],
        ]);
        $cita->update($datos);
        return response()->json($cita->refresh());
    }
    public function destroy(Cita $cita): JsonResponse { $cita->delete(); return response()->json(null, 204); }
}
