<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Guardia;
use Illuminate\Support\Facades\Log;

class GuardiaController extends Controller
{
    public function index()
    {
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

                $guardias = DB::select("
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

                return view('guardias.pendientes', compact('guardias'));
            }

            public function asignarGuardia(Request $request)
            {
                $request->validate([
                    'sesion_id' => 'required|integer',
                    'ausencia_id' => 'required|integer',
                    'hora' => 'required',
                    'aula' => 'required|string',
                ]);

                $docente_id = auth()->id(); // Asegúrate que el docente esté autenticado

                try {
                    DB::table('guardias')->insert([
                        'docente_id' => $docente_id,
                        'ausencia_id' => $request->ausencia_id,
                        'fecha' => now()->toDateString(),
                        'hora' => $request->hora,
                        'aula' => $request->aula,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    return back()->with('success', 'Guardia asignada correctamente.');
                } catch (\Exception $e) {
                    Log::error('Error al asignar guardia: ' . $e->getMessage());
                    return back()->with('error', 'No se pudo asignar la guardia.');
                }
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
