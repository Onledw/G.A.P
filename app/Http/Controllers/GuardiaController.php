<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GuardiaController extends Controller
{
    // GET /api/guardias/pendientes
    public function index()
    {
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
              AND s.dia_semana =
                CASE DAYOFWEEK(CURDATE())
                    WHEN 2 THEN 'L'
                    WHEN 3 THEN 'M'
                    WHEN 4 THEN 'X'
                    WHEN 5 THEN 'J'
                    WHEN 6 THEN 'V'
                    ELSE NULL
                END
              AND NOT EXISTS (
                SELECT 1
                FROM sesiones_lectivas s2
                WHERE
                    s2.aula = s.aula AND
                    s2.dia_semana = s.dia_semana AND
                    s2.hora_inicio = s.hora_inicio AND
                    s2.docente_id != s.docente_id AND
                    NOT EXISTS (
                        SELECT 1
                        FROM ausencias a2
                        WHERE
                            a2.docente_id = s2.docente_id AND
                            CURDATE() BETWEEN DATE(a2.fecha_inicio) AND DATE(a2.fecha_fin) AND
                            (
                                a2.todoeldia = 1 OR (
                                    s2.hora_inicio >= TIME(a2.fecha_inicio) AND
                                    s2.hora_fin <= TIME(a2.fecha_fin)
                                )
                            )
                    )
            )
        ");

        return response()->json(['sesiones' => $sesiones], 200);
    }

    // POST /api/guardias
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hora' => 'required|date_format:H:i:s',
            'aula' => 'required|string|max:255',
            'ausencia_id' => 'required|integer|exists:ausencias,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $guardiaId = DB::table('guardias')->insertGetId([
            'fecha' => now()->toDateString(),
            'hora' => $request->hora,
            'aula' => $request->aula,
            'docente_id' => auth()->id(),
            'ausencia_id' => $request->ausencia_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Guardia asignada correctamente',
            'guardia_id' => $guardiaId,
        ], 201);
    }
}
