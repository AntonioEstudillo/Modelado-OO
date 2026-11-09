<?php
use App\Http\Controllers\DemoController;
use Illuminate\Support\Facades\Route;
Route::get('/',[DemoController::class,'index'])->name('demo.index');
Route::post('/demo',[DemoController::class,'ejecutar'])->name('demo.ejecutar');
Route::post('/limpiar',[DemoController::class,'limpiar'])->name('demo.limpiar');
