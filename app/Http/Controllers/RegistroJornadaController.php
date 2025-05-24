<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RegistroJornada;
use App\Models\Ausencia;
use App\Models\SesionLectiva;
use Carbon\Carbon;

class RegistroJornadaController extends Controller
{
    public function iniciar()
    {
        $docente = Auth::user();

        // Cierra jornadas antiguas sin cerrar
        $jornadaAnteriorSinCerrar = RegistroJornada::where('docente_id', $docente->id)
            ->whereNull('fin')
            ->whereDate('inicio', '<', Carbon::today())
            ->first();

        if ($jornadaAnteriorSinCerrar) {
            $jornadaAnteriorSinCerrar->fin = Carbon::parse($jornadaAnteriorSinCerrar->inicio)->endOfDay();
            $jornadaAnteriorSinCerrar->save();
        }

        // Ya hay jornada activa hoy
        $jornadaHoyActiva = RegistroJornada::where('docente_id', $docente->id)
            ->whereNull('fin')
            ->whereDate('inicio', Carbon::today())
            ->first();

        if ($jornadaHoyActiva) {
            return redirect()->back()->with('error', 'Ya tienes una jornada activa hoy.');
        }

        RegistroJornada::create([
            'docente_id' => $docente->id,
            'inicio' => now(),
        ]);

        return redirect()->back()->with('success', 'Jornada iniciada correctamente.');
    }

    public function finalizar()
    {
        $docente = Auth::user();

        $jornadaActiva = RegistroJornada::where('docente_id', $docente->id)
            ->whereNull('fin')
            ->orderBy('inicio', 'desc')
            ->first();

        if (!$jornadaActiva) {
            return redirect()->back()->with('error', 'No tienes una jornada activa.');
        }

        $jornadaActiva->fin = now();
        $jornadaActiva->save();

        return redirect()->back()->with('success', 'Jornada finalizada correctamente.');
    }
}
