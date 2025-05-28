<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\SesionElectiva;

class GuardiaController extends Controller
{
    /**
     * Mostrar guardias pendientes a cubrir y el historial.
     */
    public function mostrarPendientes()
{
    $hoy = now();
    $docente = auth()->user();

    if (!$docente) {
        return redirect()->route('login')->with('error', 'Debes iniciar sesión para continuar.');
    }

    $diaSemana = match ($hoy->dayOfWeekIso) {
        1 => 'L', 2 => 'M', 3 => 'X', 4 => 'J', 5 => 'V', default => null,
    };

    if (!$diaSemana) {
        return back()->with('error', 'No hay guardias para hoy.');
    }

    $sesionesGuardiaDocente = SesionElectiva::where('docente_id', $docente->id)
        ->where('dia_semana', $diaSemana)
        ->where('materia', 'Guardia')
        ->get();

    if ($sesionesGuardiaDocente->isEmpty()) {
        return view('guardias.pendientes', [
            'guardiasPendientes' => collect(),
            'guardiasHistorial' => collect(),
            'docente' => $docente,
        ])->with('error', 'No tienes guardias asignadas para hoy.');
    }

    $guardiasPendientes = DB::table('sesiones_lectivas as s')
        ->join('ausencias as a', 's.docente_id', '=', 'a.docente_id')
        ->join('docentes as d', 'd.id', '=', 's.docente_id')
        ->whereDate('a.fecha_inicio', '<=', $hoy->toDateString())
        ->whereDate('a.fecha_fin', '>=', $hoy->toDateString())
        ->where('s.dia_semana', $diaSemana)
        ->where('s.docente_id', '!=', $docente->id)
        ->where(function ($query) {
            $query->where('a.todoeldia', 1)
                  ->orWhere(function ($q) {
                      $q->whereTime('s.hora_inicio', '>=', DB::raw('TIME(a.fecha_inicio)'))
                        ->whereTime('s.hora_fin', '<=', DB::raw('TIME(a.fecha_fin)'));
                  });
        })
        ->whereNotExists(function ($query) use ($hoy) {
            $query->select(DB::raw(1))
                ->from('guardias as g')
                ->where('g.fecha', $hoy->toDateString())
                ->whereColumn('g.hora', 's.hora_inicio')
                ->whereColumn('g.aula', 's.aula');
        })
        ->select(
            's.id as sesion_id',
            's.docente_id as ausente_id',
            'd.nombre as nombre_ausente',
            's.materia',
            's.aula',
            's.hora_inicio',
            's.hora_fin',
            'a.id as ausencia_id'
        )
        ->get();

    // Filtrar según coincidencia en hora de inicio solamente
    $guardiasPermitidas = $guardiasPendientes->filter(function ($guardiaPendiente) use ($sesionesGuardiaDocente) {
        foreach ($sesionesGuardiaDocente as $sesion) {
            if ($guardiaPendiente->hora_inicio == $sesion->hora_inicio) {
                return true;
            }
        }
        return false;
    })->values();

    $guardiasHistorial = DB::table('guardias as g')
        ->join('docentes as d', 'g.docente_id', '=', 'd.id')
        ->join('ausencias as a', 'g.ausencia_id', '=', 'a.id')
        ->select('g.*', 'd.nombre as docente_nombre', 'a.fecha_inicio', 'a.fecha_fin')
        ->where('g.estado', 'cubierta')
        ->orderBy('g.fecha', 'desc')
        ->get();

    return view('guardias.pendientes', [
        'guardiasPendientes' => $guardiasPermitidas,
        'guardiasHistorial' => $guardiasHistorial,
        'docente' => $docente,
    ]);
}

    public function registrar(Request $request)
    {
        $docente = auth()->user();

        if (!$docente) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para continuar.');
        }

        // Validar datos mínimos
        $request->validate([
            'ausencia_id' => 'required|exists:ausencias,id',
            'hora' => 'required',
            'aula' => 'required|string|max:50',
        ]);

        $ausenciaId = $request->input('ausencia_id');
        $hora = $request->input('hora');
        $aula = $request->input('aula');
        $fecha = now()->toDateString();

        // Comprobar si ya existe guardia para esa fecha, hora y aula (evitar duplicados)
        $existeGuardia = DB::table('guardias')
            ->where('fecha', $fecha)
            ->where('hora', $hora)
            ->where('aula', $aula)
            ->exists();

        if ($existeGuardia) {
            return redirect()->back()->with('error', 'Esa guardia ya fue cubierta.');
        }

        // Insertar registro de guardia cubierta
        DB::table('guardias')->insert([
            'docente_id' => $docente->id,
            'ausencia_id' => $ausenciaId,
            'fecha' => $fecha,
            'hora' => $hora,
            'aula' => $aula,
            'estado' => 'cubierta',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('guardias.pendientes')->with('success', 'Guardia cubierta con éxito.');
    }
}