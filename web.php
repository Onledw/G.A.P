<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AusenciaController;
use App\Http\Controllers\HorarioController;



// Vista de login
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('panel');
    }
    return view('index');
})->name('login');

// Procesar login
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Panel de usuario autenticado
Route::get('/panel', [AuthController::class, 'panel'])->middleware('auth')->name('panel');

// Logout
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Ausencias
Route::get('/ausencia', [AuthController::class, 'formAusencia'])->name('ausencia.form')->middleware('auth');

Route::post('/ausencia', [AuthController::class, 'registrarAusencia'])->name('ausencia.registrar')->middleware('auth');

// Horario
Route::get('/horario', [AuthController::class, 'verHorario'])->name('horario')->middleware('auth');
// Admin

Route::get('/admin', [AuthController::class, 'panelAdmin'])->name('admin.panel')->middleware('auth');
//Ausencias

Route::post('/registrar-ausencia', [AusenciaController::class, 'registrar'])->middleware('auth')->name('ausencia.registrar');

Route::get('/ausencias', [AusenciaController::class, 'verAusencias'])->middleware('auth')->name('ausencias');

Route::get('/panel', [HorarioController::class, 'panel'])->name('panel');

