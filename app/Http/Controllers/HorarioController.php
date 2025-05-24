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

        $jornadaActiva = RegistroJornada::where('docente_id', $usuario->id)
            ->whereNull('fin')
            ->first();

        return response()->json([
            'usuario' => $usuario,
            'horario' => $horario,
            'ausencias' => $ausencias,
            'jornada_activa' => $jornadaActiva,
        ]);
    }

    public function iniciar()
    {
        $docente = Auth::user();

        $jornadaActiva = RegistroJornada::where('docente_id', $docente->id)
            ->whereNull('fin')
            ->first();

        if ($jornadaActiva) {
            return response()->json(['error' => 'Ya tienes una jornada activa.'], 400);
        }

        RegistroJornada::create([
            'docente_id' => $docente->id,
            'inicio' => now(),
        ]);

        return response()->json(['success' => 'Jornada iniciada correctamente.']);
    }

    public function finalizar()
    {
        $docente = Auth::user();

        $jornada = RegistroJornada::where('docente_id', $docente->id)
            ->whereNull('fin')
            ->first();

        if (!$jornada) {
            return response()->json(['error' => 'No tienes una jornada activa para finalizar.'], 400);
        }

        $jornada->fin = now();
        $jornada->save();

        return response()->json(['success' => 'Jornada finalizada correctamente.']);
    }
}
