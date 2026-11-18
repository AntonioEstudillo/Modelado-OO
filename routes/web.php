<?php

use App\Http\Controllers\CitaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\HistorialWebController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\PatronesEstructuralesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/laboratorio', [DemoController::class, 'index'])->name('demo.index');
Route::post('/demo', [DemoController::class, 'ejecutar'])->name('demo.ejecutar');
Route::post('/limpiar', [DemoController::class, 'limpiar'])->name('demo.limpiar');

// Laboratorio independiente: Facade, Decorator y Proxy (sin base de datos).
Route::get('/patrones-estructurales', [PatronesEstructuralesController::class, 'index'])->name('estructurales.index');
Route::post('/patrones-estructurales/facade', [PatronesEstructuralesController::class, 'facade'])->name('estructurales.facade');
Route::post('/patrones-estructurales/decorator', [PatronesEstructuralesController::class, 'decorator'])->name('estructurales.decorator');
Route::post('/patrones-estructurales/proxy', [PatronesEstructuralesController::class, 'proxy'])->name('estructurales.proxy');
Route::post('/patrones-estructurales/limpiar', [PatronesEstructuralesController::class, 'limpiar'])->name('estructurales.limpiar');

Route::resource('citas', CitaController::class)
    ->only(['index', 'store', 'update', 'destroy'])->names('web.citas');
Route::resource('pacientes', PacienteController::class)
    ->only(['index', 'store', 'update', 'destroy'])->names('web.pacientes');
Route::get('/pacientes/{paciente}/historial', [HistorialWebController::class, 'edit'])
    ->name('web.pacientes.historial.edit');
Route::put('/pacientes/{paciente}/historial', [HistorialWebController::class, 'update'])
    ->name('web.pacientes.historial.update');
