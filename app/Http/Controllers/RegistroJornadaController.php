<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RegistroJornada;
use Carbon\Carbon;

class RegistroJornadaController extends Controller
{
    public function panel()
    {
    $usuario = Auth::user();

    $ausencias = Ausencia::where('docente_id', $usuario->id)->orderBy('fecha_inicio', 'desc')->get();
    $horario = SesionLectiva::where('docente_id', $usuario->id)->orderBy('dia_semana')->orderBy('hora_inicio')->get();
    $registroHoy = RegistroJornada::where('docente_id', $usuario->id)
        ->whereDate('inicio', today())
        ->latest('inicio')
        ->first();

    return view('panel', compact('ausencias', 'horario', 'registroHoy'));
    }

    // Iniciar jornada
    public function iniciar()
    {

        $docente = Auth::user();

        $jornadaActiva = RegistroJornada::where('docente_id', $docente->id)
            ->whereNull('fin')
            ->first();

        if ($jornadaActiva) {
            return redirect()->back()->with('error', 'Ya tienes una jornada activa.');
        }

        RegistroJornada::create([
            'docente_id' => $docente->id,
            'inicio' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Jornada iniciada.');
    }

    // Finalizar jornada
    public function finalizar()
    {
        $docente = Auth::user();

        $jornadaActiva = RegistroJornada::where('docente_id', $docente->id)
            ->whereNull('fin')
            ->orderBy('inicio', 'desc')
            ->first();

        if (!$jornadaActiva) {
            return redirect()->back()->with('error', 'No tienes ninguna jornada activa.');
        }

        $jornadaActiva->fin = Carbon::now();
        $jornadaActiva->save();

        return redirect()->back()->with('success', 'Jornada finalizada.');
    }
}
