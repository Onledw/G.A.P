<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AusenciaController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\RegistroJornadaController;

// Vista de login
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('panel');
    }
    return view('index');
})->name('login');

// Procesar login
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Panel principal (solo uno)
Route::get('/panel', [AuthController::class, 'panel'])->name('panel')->middleware('auth');

// =============================
// AUSENCIAS
// =============================
Route::middleware('auth')->group(function () {
    Route::get('/ausencia', [AusenciaController::class, 'form'])->name('ausencia.form');
    Route::post('/ausencia', [AusenciaController::class, 'registrar'])->name('ausencia.registrar');
    Route::get('/ausencias', [AusenciaController::class, 'verAusencias'])->name('ausencias');
});

// =============================
// HORARIO
// =============================
Route::get('/horario', [HorarioController::class, 'verHorario'])->name('horario')->middleware('auth');

// =============================
// REGISTRO JORNADA
// =============================
Route::middleware('auth')->group(function () {
    Route::post('/registro/inicio', [RegistroJornadaController::class, 'iniciar'])->name('registro.inicio');
    Route::post('/registro/fin', [RegistroJornadaController::class, 'finalizar'])->name('registro.fin');
});

// =============================
// ADMIN
// =============================
Route::get('/admin', [AuthController::class, 'panelAdmin'])->name('admin.panel')->middleware('auth');
