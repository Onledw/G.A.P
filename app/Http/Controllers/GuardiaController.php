<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Guardia;

class GuardiaController extends Controller
{

    /**
     * Mostrar guardias pendientes a cubrir.
     */
    public function mostrarPendientes()
    {
        $hoy = now();
        $diaSemana = match ($hoy->dayOfWeekIso) {
            1 => 'L', 2 => 'M', 3 => 'X', 4 => 'J', 5 => 'V', default => null,
        };

        $guardiasPendientes = DB::select("
            SELECT
                s.id AS sesion_id,
                s.docente_id AS ausente_id,
                d.nombre AS nombre_ausente,
                s.materia,
                s.aula,
                s.hora_inicio,
                s.hora_fin,
                a.id AS ausencia_id
            FROM sesiones_lectivas s
            JOIN ausencias a ON s.docente_id = a.docente_id
            JOIN docentes d ON d.id = s.docente_id
            WHERE CURDATE() BETWEEN DATE(a.fecha_inicio) AND DATE(a.fecha_fin)
                AND s.dia_semana = ?
                AND (
                    a.todoeldia = 1 OR (
                        s.hora_inicio >= TIME(a.fecha_inicio)
                        AND s.hora_fin <= TIME(a.fecha_fin)
                    )
                )
                AND NOT EXISTS (
                    SELECT 1 FROM guardias g
                    WHERE g.fecha = CURDATE()
                      AND g.hora = s.hora_inicio
                      AND g.aula = s.aula
                )
                AND EXISTS (
                    SELECT 1
                    FROM sesiones_lectivas libres
                    WHERE libres.dia_semana = s.dia_semana
                      AND libres.hora_inicio = s.hora_inicio
                      AND libres.docente_id != s.docente_id
                      AND libres.materia = 'Guardia'
                      AND NOT EXISTS (
                          SELECT 1 FROM ausencias a2
                          WHERE a2.docente_id = libres.docente_id
                            AND CURDATE() BETWEEN DATE(a2.fecha_inicio) AND DATE(a2.fecha_fin)
                            AND (
                                a2.todoeldia = 1 OR (
                                    libres.hora_inicio >= TIME(a2.fecha_inicio)
                                    AND libres.hora_fin <= TIME(a2.fecha_fin)
                                )
                            )
                      )
                )
        ", [$diaSemana]);

        $guardiasHistorial = DB::table('guardias as g')
        ->join('docentes as d', 'g.docente_id', '=', 'd.dni')
        ->join('ausencias as a', 'g.ausencia_id', '=', 'a.id')
            ->select('g.*', 'd.nombre as docente_nombre', 'a.fecha_inicio', 'a.fecha_fin')
            ->where('g.estado', 'cubierta') // <- Esto es opcional si solo quieres las cubiertas
            ->orderBy('g.fecha', 'desc')
            ->get();

        return view('guardias.pendientes', compact('guardiasPendientes', 'guardiasHistorial'));
    }


    /**
     * Registrar una guardia cubierta.
     */
    public function registrar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hora' => 'required|date_format:H:i',
            'aula' => 'required|string|max:255',
            'ausencia_id' => 'required|integer|exists:ausencias,id',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Fallo en la validación del formulario.');
        }

        try {
            DB::table('guardias')->insert([
                'fecha' => now()->toDateString(),
                'hora' => $request->hora,
                'aula' => $request->aula,
                'docente_id' => auth()->id(),
                'ausencia_id' => $request->ausencia_id,
                'estado' => 'cubierta',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()
                ->route('guardias.pendientes')
                ->with('success', 'Guardia asignada correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al asignar guardia: ' . $e->getMessage());
            return back()->with('error', 'Hubo un problema al guardar la guardia.');
        }
    }
}
