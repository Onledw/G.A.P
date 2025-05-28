<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\AusenciaController;
use App\Http\Controllers\GuardiaController;
use App\Http\Controllers\RegistroJornadaController;
use App\Http\Controllers\InformeController;

// ——————————————————————————————
// RUTAS PÚBLICAS
// ——————————————————————————————
Route::get('/', function () {
    return redirect()->route('index');  // redirige a /login
});

Route::get('/login', function () {
    return view('index');  // formulario de login
})->name('index');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');


Route::middleware('auth')->group(function () {

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/panel', [PanelController::class, 'index'])->name('panel');
Route::post('/panel.iniciar', [RegistroJornadaController::class, 'iniciar'])->name('iniciar');
Route::post('/panel.finalizar', [RegistroJornadaController::class, 'finalizar'])->name('finalizar');

Route::post('/ausencias', [AusenciaController::class, 'registrar'])->name('ausencias.store');

Route::get('/ausencias/crear', function () {
        return view('ausencias.crear');
    })->name('ausencias.crear');

Route::get('/ausencias/historial', [AusenciaController::class, 'historial'])->name('ausencias.historial');

Route::post('/guardias', [GuardiaController::class, 'registrar'])->name('guardias.store');
Route::get('/guardias/pendientes', [GuardiaController::class, 'mostrarPendientes'])->name('guardias.pendientes');

Route::get('/admin', [AuthController::class, 'panelAdmin'])->name('admin.panel');
Route::post('/admin/alta', [AuthController::class, 'altaDocente'])->name('admin.altaDocente');
Route::delete('/admin/baja/{id}', [AuthController::class, 'bajaDocente'])->name('admin.bajaDocente');


});

Route::middleware(['auth'])->group(function () {
    Route::get('/informes', [InformeController::class, 'index'])->name('informes.index');
    Route::post('/informes/generar', [InformeController::class, 'generar'])->name('informes.generar');
    Route::post('/informes/txt', [InformeController::class, 'exportarTXT'])->name('informes.txt');
});
