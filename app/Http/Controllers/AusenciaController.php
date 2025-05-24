<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ausencia;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class AusenciaController extends Controller
{
    public function registrar(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'motivo' => 'nullable|string',
            'justificada' => 'nullable|boolean',
            'todoeldia' => 'nullable|boolean',
        ]);

        $docente = Auth::user();

        $ausencia = Ausencia::create([
            'docente_id' => $docente->id,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'motivo' => $request->motivo,
            'justificada' => $request->has('justificada') ? 1 : 0,
            'todoeldia' => $request->has('todoeldia') ? 1 : 0,
        ]);

        return response()->json([
            'message' => 'Ausencia registrada correctamente.',
            'ausencia' => $ausencia,
        ], 201);
    }

    public function verAusenciasDia()
    {
        $hoy = Carbon::today();

        $ausencias = Ausencia::whereDate('fecha_inicio', '<=', $hoy)
            ->whereDate('fecha_fin', '>=', $hoy)
            ->with('docente') // asumiendo relación definida
            ->orderBy('fecha_inicio', 'asc')
            ->get();

        return response()->json([
            'fecha' => $hoy->toDateString(),
            'ausencias' => $ausencias,
        ]);
    }



}
