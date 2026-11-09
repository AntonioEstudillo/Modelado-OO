<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use App\Services\ServicioWebApi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function index(): JsonResponse { return response()->json(Paciente::paginate(15)); }
    public function show(Paciente $paciente): JsonResponse { return response()->json($paciente->load('historial', 'citas', 'tratamientos')); }
    public function store(Request $request, ServicioWebApi $servicio): JsonResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'tipo' => ['required', 'in:general,pediatrico,oncologico'],
            'fecha_nacimiento' => ['nullable', 'date', 'before_or_equal:today'],
        ]);
        return response()->json($servicio->registrarPaciente($datos), 201);
    }
    public function update(Request $request, Paciente $paciente): JsonResponse
    {
        $datos = $request->validate([
            'nombre' => ['sometimes', 'required', 'string', 'max:150'],
            'fecha_nacimiento' => ['sometimes', 'nullable', 'date', 'before_or_equal:today'],
        ]);
        $paciente->update($datos);
        return response()->json($paciente->refresh());
    }
    public function destroy(Paciente $paciente): JsonResponse
    {
        $paciente->delete();
        return response()->json(null, 204);
    }
}
