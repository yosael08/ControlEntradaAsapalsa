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

// Rutas para los Movimientos (Entrada a Plantel y Rampa)
Route::get('/movimientos', [MovimientoController::class, 'index'])->name('movimientos.index');
Route::get('/movimientos/rampa', [MovimientoController::class, 'rampa'])->name('movimientos.rampa');
Route::patch('/movimientos/{id}/descargar', [MovimientoController::class, 'descargar'])->name('movimientos.descargar');
Route::patch('/movimientos/{id}/despachar', [MovimientoController::class, 'despachar'])->name('movimientos.despachar');

use App\Http\Controllers\AuthController;

// Rutas Públicas de Autenticación
Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas Protegidas (Deben estar logueados para entrar)
Route::middleware(['auth'])->group(function () {

    // Gestión de usuarios (Solo para el Admin)
    Route::get('/usuarios/crear', [AuthController::class, 'mostrarRegistro'])->name('usuarios.create');
    Route::post('/usuarios/guardar', [AuthController::class, 'registrar'])->name('usuarios.store');

    // Aquí abajo quedan tus rutas de Cola y Movimientos que ya teníamos protegidas
});
