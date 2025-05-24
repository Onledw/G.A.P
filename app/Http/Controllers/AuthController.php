<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Docente;
use App\Models\Ausencia;
use App\Models\SesionElctiva;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validar datos
        $request->validate([
            'usuario' => 'required|string',
            'password' => 'required|string',
        ]);

        // Buscar al docente por DNI
        $docente = Docente::where('dni', $request->input('usuario'))->first();
        if ($docente && Hash::check($request->input('password'), $docente->clave)) {
            Auth::login($docente); // Autentica al docente
            return redirect()->route('panel'); // Redirige al panel
        }


        return redirect()->route('login')->withErrors([
            'login' => 'Usuario o contraseña incorrectos.',
        ]);
    }

    public function panel()
    {
        $usuario = Auth::user();

        $ausencias = \App\Models\Ausencia::where('docente_id', $usuario->id)
            ->orderByDesc('fecha_inicio')
            ->get();

        $horario = \App\Models\SesionElectiva::where('docente_id', $usuario->id)
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->get();

        $registroHoy = \App\Models\RegistroJornada::where('docente_id', $usuario->id)
            ->whereDate('inicio', today())
            ->latest()
            ->first();

        return view('panel', compact('ausencias', 'horario', 'registroHoy'));
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }


}
