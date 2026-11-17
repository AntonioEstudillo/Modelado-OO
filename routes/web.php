<?php
use App\Http\Controllers\DemoController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\HistorialWebController;
use App\Http\Controllers\PacienteController;
use Illuminate\Support\Facades\Route;
Route::get('/',[DemoController::class,'index'])->name('demo.index');
Route::post('/demo',[DemoController::class,'ejecutar'])->name('demo.ejecutar');
Route::post('/limpiar',[DemoController::class,'limpiar'])->name('demo.limpiar');
Route::resource('citas', CitaController::class)->only(['index', 'store', 'update', 'destroy'])->names('web.citas');
Route::resource('pacientes', PacienteController::class)->only(['index', 'store', 'update', 'destroy'])->names('web.pacientes');
Route::get('/pacientes/{paciente}/historial', [HistorialWebController::class, 'edit'])->name('web.pacientes.historial.edit');
Route::put('/pacientes/{paciente}/historial', [HistorialWebController::class, 'update'])->name('web.pacientes.historial.update');
