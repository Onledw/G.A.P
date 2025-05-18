<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ausencia;
use App\Models\SesionElectiva;

class HorarioController extends Controller
{
    public function panel()
    {
        $usuario = Auth::user();

        $horario = $usuario->sesiones()
            ->orderByRaw("FIELD(dia_semana, 'L', 'M', 'X', 'J', 'V')")
            ->orderBy('hora_inicio')
            ->get();

        $ausencias = Ausencia::with('docente')->orderBy('fecha_inicio', 'desc')->get();

        return view('panel', compact('usuario', 'ausencias', 'horario'));
    }
}
