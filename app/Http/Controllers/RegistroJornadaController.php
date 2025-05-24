<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RegistroJornada;
use App\Models\Ausencia;
use App\Models\SesionLectiva;
use Carbon\Carbon;

class RegistroJornadaController extends Controller
{
    // Devuelve datos relevantes para el panel del docente
    public function panel()
    {
        $usuario = Auth::user();

        $ausencias = Ausencia::where('docente_id', $usuario->id)
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        $horario = SesionLectiva::where('docente_id', $usuario->id)
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->get();

        $registroHoy = RegistroJornada::where('docente_id', $usuario->id)
            ->whereDate('inicio', today())
            ->latest('inicio')
            ->first();

        return response()->json([
            'ausencias' => $ausencias,
            'horario' => $horario,
            'registroHoy' => $registroHoy,
        ]);
    }

    // Inicia jornada
    public function iniciar()
    {
        $docente = Auth::user();

        // Cierra automáticamente jornadas anteriores sin cerrar
        $jornadaAnteriorSinCerrar = RegistroJornada::where('docente_id', $docente->id)
            ->whereNull('fin')
            ->whereDate('inicio', '<', Carbon::today())
            ->first();

        if ($jornadaAnteriorSinCerrar) {
            $jornadaAnteriorSinCerrar->fin = Carbon::parse($jornadaAnteriorSinCerrar->inicio)->endOfDay();
            $jornadaAnteriorSinCerrar->save();
        }

        // Verifica jornada activa hoy
        $jornadaHoyActiva = RegistroJornada::where('docente_id', $docente->id)
            ->whereNull('fin')
            ->whereDate('inicio', Carbon::today())
            ->first();

        if ($jornadaHoyActiva) {
            return response()->json(['error' => 'Ya tienes una jornada activa hoy.'], 409);
        }

        $nuevaJornada = RegistroJornada::create([
            'docente_id' => $docente->id,
            'inicio' => Carbon::now(),
        ]);

        return response()->json([
            'message' => 'Jornada iniciada.',
            'jornada' => $nuevaJornada,
        ], 201);
    }

    // Finaliza jornada
    public function finalizar()
    {
        $docente = Auth::user();

        $jornadaActiva = RegistroJornada::where('docente_id', $docente->id)
            ->whereNull('fin')
            ->orderBy('inicio', 'desc')
            ->first();

        if (!$jornadaActiva) {
            return response()->json(['error' => 'No tienes ninguna jornada activa.'], 404);
        }

        $jornadaActiva->fin = Carbon::now();
        $jornadaActiva->save();

        return response()->json([
            'message' => 'Jornada finalizada.',
            'jornada' => $jornadaActiva,
        ]);
    }
}
