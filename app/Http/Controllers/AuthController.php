<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Docente;
use App\Models\Ausencia;
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
        $hoy = Carbon::today();

        $ausenciasHoy = \App\Models\Ausencia::with('docente')
            ->whereDate('fecha_inicio', '<=', $hoy)
            ->whereDate('fecha_fin', '>=', $hoy)
            ->orderBy('fecha_inicio', 'desc')
            ->get();
        // Obtener todas las ausencias ordenadas por fecha_inicio descendente
        $ausencias = \App\Models\Ausencia::with('docente')->orderBy('fecha_inicio', 'desc')->get();

        $usuario = Auth::user(); // obtiene al docente logueado
        return view('panel', compact('usuario', 'ausencias'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }


}
