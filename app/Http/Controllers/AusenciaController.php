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
             'justificada' => 'sometimes',
             'todoeldia' => 'sometimes',

         ]);

         try {
            $docente = Auth::user();

            Ausencia::create([
                'docente_id' => $docente->id,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'motivo' => $request->motivo,
                'justificada' => $request->has('justificada') ? 1 : 0,
                'todoeldia' => $request->has('todoeldia') ? 1 : 0,
            ]);

            return redirect()->route('panel')->with('success', 'Ausencia registrada correctamente.');

        } catch (\Exception $e) {
            // Opcionalmente puedes registrar el error: Log::error($e);

            return redirect()->back()->withInput()->with('error', 'Error al registrar la ausencia. Inténtalo de nuevo.');
        }
    }

    public function verAusenciasDia()
{
    $hoy = Carbon::today();

    $ausencias = Ausencia::whereDate('fecha_inicio', '<=', $hoy)
        ->whereDate('fecha_fin', '>=', $hoy)
        ->with('docente')
        ->orderBy('fecha_inicio', 'asc')
        ->get();

    return view('ausenciasDia', compact('ausencias', 'hoy'));
}


    public function historial()
    {
    $ausencias = Ausencia::with('docente')
        ->orderBy('fecha_inicio', 'desc')
        ->get();

    return view('ausencias.historial', compact('ausencias'));
    }

}
