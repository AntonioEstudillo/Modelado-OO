<?php
use App\Http\Controllers\Api\{CitaController, HistorialController, PacienteController};
use Illuminate\Support\Facades\Route;

// DEMOSTRACIÓN: en producción proteger con auth:sanctum, políticas y permisos.
Route::apiResource('pacientes', PacienteController::class);
Route::apiResource('citas', CitaController::class);
Route::get('pacientes/{paciente}/historial', [HistorialController::class, 'show']);
