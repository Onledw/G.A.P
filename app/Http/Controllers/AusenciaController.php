<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ausencia;
use Illuminate\Support\Facades\Auth;

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

        $docente = Auth::user(); // Asegúrate que tiene 'id'

        Ausencia::create([
            'docente_id' => $docente->id,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'motivo' => $request->motivo,
            'justificada' => $request->has('justificada') ? 1 : 0,
            'todoeldia' => $request->has('todoeldia') ? 1 : 0,
        ]);

        return redirect()->route('panel')->with('success', 'Ausencia registrada correctamente.');
    }

    public function verAusencias()
    {
        $docente = Auth::user();
        $ausencias = $docente->ausencias()->orderBy('fecha_inicio', 'desc')->get();

    return view('ausencias', compact('ausencias'));
    }


}
