<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ausencia;
use App\Models\SesionElectiva;
use App\Models\RegistroJornada;


class HorarioController extends Controller
{

    public function panel()
    {
        $usuario = Auth::user();

        $horario = $usuario->sesiones()
            ->orderByRaw("FIELD(dia_semana, 'L', 'M', 'X', 'J', 'V')")
            ->orderBy('hora_inicio')
            ->get();

        $ausencias = Ausencia::with('docente')
            ->where('docente_id', $usuario->id)
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        // Buscar si tiene una jornada activa (sin fin)
        $jornadaActiva = RegistroJornada::where('docente_id', $usuario->id)
            ->whereNull('fin')
            ->first();

        return view('panel', compact('usuario', 'ausencias', 'horario', 'jornadaActiva'));
    }
    public function iniciar()
{
    $docente = Auth::user();

    // Si ya hay una jornada activa, no permitir iniciar otra
    $jornadaActiva = RegistroJornada::where('docente_id', $docente->id)
        ->whereNull('fin')
        ->first();

    if ($jornadaActiva) {
        return back()->with('error', 'Ya tienes una jornada activa.');
    }

    RegistroJornada::create([
        'docente_id' => $docente->id,
        'inicio' => now(),
    ]);

    return back()->with('success', 'Jornada iniciada correctamente.');
}

public function finalizar()
{
    $docente = Auth::user();

    $jornada = RegistroJornada::where('docente_id', $docente->id)
        ->whereNull('fin')
        ->first();

    if (!$jornada) {
        return back()->with('error', 'No tienes una jornada activa para finalizar.');
    }

    $jornada->fin = now();
    $jornada->save();

    return back()->with('success', 'Jornada finalizada correctamente.');
}


}
