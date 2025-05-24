<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\AusenciaController;
use App\Http\Controllers\GuardiaController;
use App\Http\Controllers\RegistroJornadaController;

// ——————————————————————————————
// RUTAS PÚBLICAS
// ——————————————————————————————
Route::post('/login', [AuthController::class, 'login'])->name('login');

// ——————————————————————————————
// RUTAS PROTEGIDAS
// ——————————————————————————————
Route::middleware('auth:sanctum')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Panel de usuario (info general)
    Route::get('/panel', [PanelController::class, 'index'])->name('panel.index');

    // Registro de jornada (iniciar y finalizar)
    Route::post('/registro-jornada/start', [RegistroJornadaController::class, 'iniciar'])->name('registro-jornada.start');
    Route::post('/registro-jornada/stop', [RegistroJornadaController::class, 'finalizar'])->name('registro-jornada.stop');

    // Ausencias (crear)
    Route::post('/ausencias', [AusenciaController::class, 'registrar'])->name('ausencias.store');

    // Guardias
    Route::get('/guardias/pendientes', [GuardiaController::class, 'index'])->name('guardias.index'); // listado guardias pendientes
    Route::post('/guardias', [GuardiaController::class, 'store'])->name('guardias.store'); // asignar guardia

    // Administración (solo admins)
    Route::middleware('can:admin')->group(function () {
        Route::get('/admin/docentes', [AuthController::class, 'panelAdmin'])->name('admin.docentes.index');
        Route::post('/admin/docentes', [AuthController::class, 'altaDocente'])->name('admin.docentes.store');
        Route::delete('/admin/docentes/{id}', [AuthController::class, 'bajaDocente'])->name('admin.docentes.destroy');
    });

});
