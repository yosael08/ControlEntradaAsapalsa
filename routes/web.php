<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\ColaEsperaController;

// Rutas para la Cola de Espera
Route::get('/cola-espera', [ColaEsperaController::class, 'index'])->name('cola-espera.index');
Route::get('/cola-espera/nuevo', [ColaEsperaController::class, 'create'])->name('cola-espera.create');
Route::post('/cola-espera/guardar', [ColaEsperaController::class, 'store'])->name('cola-espera.store');

use App\Http\Controllers\MovimientoController;

// Rutas para los Movimientos (Entradas a Planta)
Route::get('/movimientos', [MovimientoController::class, 'index'])->name('movimientos.index');
Route::get('/movimientos/nuevo', [MovimientoController::class, 'create'])->name('movimientos.create');
Route::post('/movimientos/guardar', [MovimientoController::class, 'store'])->name('movimientos.store');
