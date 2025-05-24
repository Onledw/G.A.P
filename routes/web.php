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
Route::get('/', function () {
    return redirect()->route('index');  // redirige a /login
});

Route::get('/login', function () {
    return view('index');  // formulario de login
})->name('index');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');


Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/panel', [PanelController::class, 'index'])->name('panel');
    Route::post('/panel/iniciar', [RegistroJornadaController::class, 'iniciar'])->name('panel.iniciar');
    Route::post('/panel/finalizar', [RegistroJornadaController::class, 'finalizar'])->name('panel.finalizar');

    Route::post('/ausencias', [AusenciaController::class, 'registrar'])->name('ausencias.store');

    Route::get('/ausencias/dia', [AusenciaController::class, 'verAusenciasDia'])->name('ausencias.dia');

    Route::get('/ausencias/historial', [AusenciaController::class, 'historial'])->name('ausencias.historial');


    Route::get('/guardias/pendientes', [GuardiaController::class, 'index'])->name('guardias.index');
    Route::post('/guardias', [GuardiaController::class, 'store'])->name('guardias.store');

    Route::middleware('can:admin')->group(function () {
        Route::get('/admin', [AuthController::class, 'panelAdmin'])->name('admin.panel');
        Route::post('/admin/alta', [AuthController::class, 'altaDocente'])->name('admin.altaDocente');
        Route::delete('/admin/baja/{id}', [AuthController::class, 'bajaDocente'])->name('admin.bajaDocente');
    });
});
