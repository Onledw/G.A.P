<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ausencia;
use App\Models\SesionElectiva;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PanelController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $hoy = Carbon::today();

        // Ausencias del día (incluye las del usuario y otros)
        $ausenciasHoy = Ausencia::whereDate('fecha_inicio', '<=', $hoy)
            ->whereDate('fecha_fin', '>=', $hoy)
            ->with('docente')
            ->orderBy('fecha_inicio', 'asc')
            ->get();

        // Filtrar ausencias POR CUBRIR (excepto las propias del usuario)
        $ausenciasPorCubrir = $ausenciasHoy->filter(function ($aus) use ($user, $hoy) {
            if ($aus->docente_id == $user->id) {
                return false; // no cubro mi propia ausencia
            }

            $mapaDias = ['1'=>'L','2'=>'M','3'=>'X','4'=>'J','5'=>'V'];
            $diaSemana = $mapaDias[$hoy->dayOfWeekIso] ?? null;

            $sesiones = SesionElectiva::where('docente_id', $aus->docente_id)
                ->where('dia_semana', $diaSemana)
                ->get()
                ->filter(function ($sesion) use ($aus, $user) {
                    if (!$aus->todoeldia) {
                        $inicioAus = Carbon::parse($aus->fecha_inicio)->format('H:i:s');
                        $finAus = Carbon::parse($aus->fecha_fin)->format('H:i:s');

                        // La sesión debe estar dentro del rango de ausencia
                        if (!($sesion->hora_inicio >= $inicioAus && $sesion->hora_fin <= $finAus)) {
                            return false;
                        }
                    }

                    // Solo sesiones que NO tenga ya el docente logueado (que cubrirá)
                    $tieneSesion = SesionElectiva::where('docente_id', $user->id)
                        ->where('dia_semana', $sesion->dia_semana)
                        ->where('hora_inicio', $sesion->hora_inicio)
                        ->exists();

                    return !$tieneSesion;
                });

            $aus->sesionesPorCubrir = $sesiones;

            return $sesiones->isNotEmpty();
        });

        // Horario del docente actual
        $horario = SesionElectiva::where('docente_id', $user->id)->get();

        // Registro jornada de hoy si existe
        $registroHoy = $user->jornadas()->whereDate('inicio', $hoy)->first();

        return view('panel', [
            'usuario' => $user,
            'fecha_hoy' => $hoy->toDateString(),
            'ausencias_hoy' => $ausenciasHoy,
            'ausencias_por_cubrir' => $ausenciasPorCubrir->values(),
            'horario' => $horario,
            'registro_jornada_hoy' => $registroHoy,
            'ausencias' => Ausencia::where('docente_id', $user->id)->orderBy('fecha_inicio', 'desc')->get(),
        ]);

    }
}
