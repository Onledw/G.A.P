<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Docente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection; // para usar collect()

class InformeController extends Controller
{
    public function index()
    {
        $docentes = Docente::orderBy('nombre')->get();

        // Paso resultados como colección vacía para evitar error si no hay resultados aún
        $resultados = collect();

        return view('informes.registros', compact('docentes', 'resultados'));
    }

    public function generar(Request $request)
    {
        $tipo = $request->input('tipo');
        $fecha = $request->input('fecha');
        $docenteId = $request->input('docente_id');

        $query = DB::table('ausencias')
            ->join('docentes', 'ausencias.docente_id', '=', 'docentes.id')
            ->select('docentes.nombre', 'ausencias.fecha_inicio', 'ausencias.fecha_fin', 'ausencias.motivo');

        switch ($tipo) {
            case 'dia':
                if (!$fecha) {
                    return back()->with('error', 'Debe seleccionar una fecha.');
                }
                $query->whereDate('fecha_inicio', '<=', $fecha)
                      ->whereDate('fecha_fin', '>=', $fecha);
                break;

            case 'semana':
                if (!$fecha) {
                    return back()->with('error', 'Debe seleccionar una fecha para la semana.');
                }
                $fechaCarbon = \Carbon\Carbon::parse($fecha);
                $inicioSemana = $fechaCarbon->startOfWeek()->toDateString();
                $finSemana = $fechaCarbon->endOfWeek()->toDateString();
                $query->where(function ($q) use ($inicioSemana, $finSemana) {
                    $q->whereBetween('fecha_inicio', [$inicioSemana, $finSemana])
                      ->orWhereBetween('fecha_fin', [$inicioSemana, $finSemana])
                      ->orWhere(function ($q2) use ($inicioSemana, $finSemana) {
                          $q2->where('fecha_inicio', '<=', $inicioSemana)
                             ->where('fecha_fin', '>=', $finSemana);
                      });
                });
                break;

            case 'mes':
                if (!$fecha) {
                    return back()->with('error', 'Debe seleccionar un mes.');
                }
                $fechaCarbon = \Carbon\Carbon::parse($fecha);
                $inicioMes = $fechaCarbon->startOfMonth()->toDateString();
                $finMes = $fechaCarbon->endOfMonth()->toDateString();
                $query->where(function ($q) use ($inicioMes, $finMes) {
                    $q->whereBetween('fecha_inicio', [$inicioMes, $finMes])
                      ->orWhereBetween('fecha_fin', [$inicioMes, $finMes])
                      ->orWhere(function ($q2) use ($inicioMes, $finMes) {
                          $q2->where('fecha_inicio', '<=', $inicioMes)
                             ->where('fecha_fin', '>=', $finMes);
                      });
                });
                break;

            case 'trimestre':
                if (!$fecha) {
                    return back()->with('error', 'Debe seleccionar una fecha para el trimestre.');
                }
                $fechaCarbon = \Carbon\Carbon::parse($fecha);
                $mes = $fechaCarbon->month;
                // Determinar trimestre
                if ($mes <= 3) {
                    $inicioTrimestre = $fechaCarbon->copy()->startOfYear()->toDateString();
                    $finTrimestre = $fechaCarbon->copy()->month(3)->endOfMonth()->toDateString();
                } elseif ($mes <= 6) {
                    $inicioTrimestre = $fechaCarbon->copy()->month(4)->startOfMonth()->toDateString();
                    $finTrimestre = $fechaCarbon->copy()->month(6)->endOfMonth()->toDateString();
                } elseif ($mes <= 9) {
                    $inicioTrimestre = $fechaCarbon->copy()->month(7)->startOfMonth()->toDateString();
                    $finTrimestre = $fechaCarbon->copy()->month(9)->endOfMonth()->toDateString();
                } else {
                    $inicioTrimestre = $fechaCarbon->copy()->month(10)->startOfMonth()->toDateString();
                    $finTrimestre = $fechaCarbon->copy()->month(12)->endOfMonth()->toDateString();
                }
                $query->where(function ($q) use ($inicioTrimestre, $finTrimestre) {
                    $q->whereBetween('fecha_inicio', [$inicioTrimestre, $finTrimestre])
                      ->orWhereBetween('fecha_fin', [$inicioTrimestre, $finTrimestre])
                      ->orWhere(function ($q2) use ($inicioTrimestre, $finTrimestre) {
                          $q2->where('fecha_inicio', '<=', $inicioTrimestre)
                             ->where('fecha_fin', '>=', $finTrimestre);
                      });
                });
                break;

            case 'curso':
                // Curso completo: asumo año lectivo, 1 Sep a 31 Ago siguiente año
                $year = now()->year;
                $inicioCurso = \Carbon\Carbon::create($year, 9, 1)->toDateString();
                $finCurso = \Carbon\Carbon::create($year + 1, 8, 31)->toDateString();
                $query->where(function ($q) use ($inicioCurso, $finCurso) {
                    $q->whereBetween('fecha_inicio', [$inicioCurso, $finCurso])
                      ->orWhereBetween('fecha_fin', [$inicioCurso, $finCurso])
                      ->orWhere(function ($q2) use ($inicioCurso, $finCurso) {
                          $q2->where('fecha_inicio', '<=', $inicioCurso)
                             ->where('fecha_fin', '>=', $finCurso);
                      });
                });
                break;

            case 'docente':
                if (!$docenteId) {
                    return back()->with('error', 'Debe seleccionar un docente.');
                }
                $query->where('docente_id', $docenteId);
                break;

            default:
                return back()->with('error', 'Tipo de informe no válido.');
        }

        $resultados = $query->orderBy('fecha_inicio')->get();

        $docentes = Docente::orderBy('nombre')->get();
        // dd($resultados[0]);

        return view('informes.registros', compact('resultados', 'docentes'))->withInput();
    }
}
