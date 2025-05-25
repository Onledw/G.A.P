<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Guardia;

class GuardiaController extends Controller
{
    public function index()
    {
        // Ejemplo de consulta con condición 'estado = pendiente' (si tienes esa columna)
        $guardiasPendientes = Guardia::where('estado', 'pendiente')->get();

        return view('guardias.pendientes', [
            'sesiones' => $sesiones ?? []
        ]);
            }

    public function mostrarPendientes()
    {
        $hoy = now();

        $diaSemana = match ($hoy->dayOfWeekIso) {
            1 => 'L',
            2 => 'M',
            3 => 'X',
            4 => 'J',
            5 => 'V',
            default => null,
        };

        $sesiones = DB::select("
        SELECT
        s.id AS sesion_id,
        s.docente_id,
        s.dia_semana,
        s.hora_inicio,
        s.hora_fin,
        s.aula,
        s.materia,
        a.id AS ausencia_id,
        DATE(a.fecha_inicio) AS fecha
        FROM sesiones_lectivas s
        JOIN ausencias a ON s.docente_id = a.docente_id
        WHERE CURDATE() BETWEEN DATE(a.fecha_inicio) AND DATE(a.fecha_fin)
        AND (
        a.todoeldia = 1 OR (
            s.hora_inicio >= TIME(a.fecha_inicio) AND
            s.hora_fin <= TIME(a.fecha_fin)
        )
        )
        AND s.dia_semana = ?
        AND NOT EXISTS (
        SELECT 1
        FROM guardias g
        WHERE
            g.fecha = CURDATE()
            AND g.hora = s.hora_inicio
            AND g.aula = s.aula
        )
        AND EXISTS (
        SELECT 1
        FROM sesiones_lectivas s2
        WHERE
            s2.dia_semana = s.dia_semana
            AND s2.hora_inicio = s.hora_inicio
            AND s2.docente_id != s.docente_id
            -- El docente sustituto NO debe estar ausente
            AND NOT EXISTS (
                SELECT 1
                FROM ausencias a2
                WHERE
                    a2.docente_id = s2.docente_id
                    AND CURDATE() BETWEEN DATE(a2.fecha_inicio) AND DATE(a2.fecha_fin)
                    AND (
                        a2.todoeldia = 1 OR (
                            s2.hora_inicio >= TIME(a2.fecha_inicio)
                            AND s2.hora_fin <= TIME(a2.fecha_fin)
                        )
                    )
            )
        )
        ", [$diaSemana]);
        dd($diaSemana);

        return view('guardias.pendientes', compact('sesiones'));
    }

    public function registrar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hora' => 'required|date_format:H:i:s',
            'aula' => 'required|string|max:255',
            'ausencia_id' => 'required|integer|exists:ausencias,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

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


        return redirect()->route('guardias.pendientes')->with('success', 'Guardia asignada correctamente.');
    }
}
