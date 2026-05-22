<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ColaEsperaController;
use App\Http\Controllers\MovimientoController;

// --- RUTA RAÍZ INTELIGENTE ---
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('cola-espera.index');
    }
    return redirect()->route('login');
});

// --- RUTAS PÚBLICAS (AUTENTICACIÓN) ---
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// --- RUTAS PROTEGIDAS (SOLO USUARIOS AUTENTICADOS) ---
Route::middleware(['auth'])->group(function () {

    // Cierre de Sesión
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // REGISTRO DE EMPLEADOS (Nombre corregido a usuarios.create para solucionar la caída)
    Route::get('/usuarios/registrar', [AuthController::class, 'showRegistrar'])->name('usuarios.create');
    Route::post('/usuarios/registrar', [AuthController::class, 'registrar'])->name('usuarios.store');

    // MÓDULO DE COLA DE ESPERA
    Route::get('/cola-espera', [ColaEsperaController::class, 'index'])->name('cola-espera.index');
    Route::get('/cola-espera/crear', [ColaEsperaController::class, 'create'])->name('cola-espera.create');
    Route::post('/cola-espera/guardar', [ColaEsperaController::class, 'store'])->name('cola-espera.store');

    // MÓDULO DE ENTRADAS A PLANTA (HISTORIAL)
    Route::get('/movimientos', [MovimientoController::class, 'index'])->name('movimientos.index');
    Route::get('/movimientos/autorizar', [MovimientoController::class, 'create'])->name('movimientos.create');
    Route::post('/movimientos/guardar', [MovimientoController::class, 'store'])->name('movimientos.store');

    // MÓDULO OPERATIVO DE RAMPA
    Route::get('/movimientos/rampa', [MovimientoController::class, 'rampaIndex'])->name('movimientos.rampa');
    Route::post('/movimientos/rampa/descargar/{id}', [MovimientoController::class, 'iniciarDescarga'])->name('movimientos.descargar');
    Route::post('/movimientos/rampa/despachar/{id}', [MovimientoController::class, 'despacharVehiculo'])->name('movimientos.despachar');
});
