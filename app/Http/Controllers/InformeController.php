<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Docente;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InformeController extends Controller
{
    public function index()
    {
        $docentes = Docente::orderBy('nombre')->get();
        $resultados = collect(); // colección vacía al principio
        return view('informes.registros', compact('docentes', 'resultados'));
    }

    public function generar(Request $request)
    {
        // Validación dinámica según el tipo
        $rules = [
            'tipo' => 'required|in:dia,semana,mes,trimestre,curso,docente',
        ];

        if (in_array($request->tipo, ['dia', 'semana', 'mes', 'trimestre'])) {
            $rules['fecha'] = 'required|date';
        }

        if ($request->tipo === 'docente') {
            $rules['docente_id'] = 'required|exists:docentes,id';
        }

        $request->validate($rules);

        // Construcción de la consulta
        $query = DB::table('ausencias')
            ->join('docentes', 'ausencias.docente_id', '=', 'docentes.id')
            ->select('docentes.nombre', 'ausencias.fecha_inicio', 'ausencias.fecha_fin', 'ausencias.motivo');

        switch ($request->tipo) {
            case 'dia':
                $query->whereDate('fecha_inicio', '<=', $request->fecha)
                      ->whereDate('fecha_fin', '>=', $request->fecha);
                break;

            case 'semana':
                $carbon = Carbon::parse($request->fecha);
                $query->where(function ($q) use ($carbon) {
                    $q->whereBetween('fecha_inicio', [$carbon->copy()->startOfWeek(), $carbon->copy()->endOfWeek()])
                      ->orWhereBetween('fecha_fin', [$carbon->startOfWeek(), $carbon->endOfWeek()])
                      ->orWhere(function ($q2) use ($carbon) {
                          $q2->where('fecha_inicio', '<=', $carbon->startOfWeek())
                             ->where('fecha_fin', '>=', $carbon->endOfWeek());
                      });
                });
                break;

            case 'mes':
                $carbon = Carbon::parse($request->fecha);
                $inicio = $carbon->startOfMonth()->toDateString();
                $fin = $carbon->endOfMonth()->toDateString();
                $query->where(function ($q) use ($inicio, $fin) {
                    $q->whereBetween('fecha_inicio', [$inicio, $fin])
                      ->orWhereBetween('fecha_fin', [$inicio, $fin])
                      ->orWhere(function ($q2) use ($inicio, $fin) {
                          $q2->where('fecha_inicio', '<=', $inicio)
                             ->where('fecha_fin', '>=', $fin);
                      });
                });
                break;

            case 'trimestre':
                $carbon = Carbon::parse($request->fecha);
                $mes = $carbon->month;
                if ($mes <= 3) {
                    $inicio = Carbon::create($carbon->year, 1, 1)->toDateString();
                    $fin = Carbon::create($carbon->year, 3, 31)->toDateString();
                } elseif ($mes <= 6) {
                    $inicio = Carbon::create($carbon->year, 4, 1)->toDateString();
                    $fin = Carbon::create($carbon->year, 6, 30)->toDateString();
                } elseif ($mes <= 9) {
                    $inicio = Carbon::create($carbon->year, 7, 1)->toDateString();
                    $fin = Carbon::create($carbon->year, 9, 30)->toDateString();
                } else {
                    $inicio = Carbon::create($carbon->year, 10, 1)->toDateString();
                    $fin = Carbon::create($carbon->year, 12, 31)->toDateString();
                }

                $query->where(function ($q) use ($inicio, $fin) {
                    $q->whereBetween('fecha_inicio', [$inicio, $fin])
                      ->orWhereBetween('fecha_fin', [$inicio, $fin])
                      ->orWhere(function ($q2) use ($inicio, $fin) {
                          $q2->where('fecha_inicio', '<=', $inicio)
                             ->where('fecha_fin', '>=', $fin);
                      });
                });
                break;

            case 'curso':
                $year = now()->year;
                $inicio = Carbon::create($year, 9, 1)->toDateString();
                $fin = Carbon::create($year + 1, 8, 31)->toDateString();
                $query->where(function ($q) use ($inicio, $fin) {
                    $q->whereBetween('fecha_inicio', [$inicio, $fin])
                      ->orWhereBetween('fecha_fin', [$inicio, $fin])
                      ->orWhere(function ($q2) use ($inicio, $fin) {
                          $q2->where('fecha_inicio', '<=', $inicio)
                             ->where('fecha_fin', '>=', $fin);
                      });
                });
                break;

            case 'docente':
                $query->where('docente_id', $request->docente_id);
                break;
        }

        $resultados = $query->orderBy('fecha_inicio')->get();
        $docentes = Docente::orderBy('nombre')->get();

        return view('informes.registros', compact('resultados', 'docentes'));
    }
}
