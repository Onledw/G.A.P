<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Docente;

class AuthController extends Controller
{
    public function mostrarLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required',
            'password' => 'required',
        ]);

        $docente = Docente::where('dni', $request->usuario)->first();

        if (!$docente || !Hash::check($request->password, $docente->clave)) {
            return back()->withErrors(['usuario' => 'Credenciales inválidas'])->withInput();
        }

        Auth::login($docente);

        return redirect()->route('panel');
    }

    public function logout(Request $request)
    {
        auth()->logout(); // válido solo para sesión tradicional

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('index');
    }



    public function panelAdmin()
    {
        if (!Auth::user()->admin) {
            abort(403, 'Acceso no autorizado');
        }

        $docentes = Docente::all();
        return view('admin', compact('docentes'));
    }

    public function altaDocente(Request $request)
    {
        $request->validate([
            'dni' => 'required|unique:docentes,dni',
            'nombre' => 'required',
            'apellido1' => 'required',
            'clave' => 'required|min:4',
            'fecha_ingreso' => 'required|date',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:H,M,O',
        ]);

        $docente = Docente::create([
            'dni' => $request->dni,
            'nombre' => $request->nombre,
            'apellido1' => $request->apellido1,
            'apellido2' => $request->apellido2,
            'clave' => Hash::make($request->clave),
            'admin' => $request->has('admin'),
            'fecha_ingreso' => $request->fecha_ingreso,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'sexo' => $request->sexo,
        ]);

        return redirect()->route('admin.panel')->with('success', 'Docente dado de alta correctamente.');
    }

    public function bajaDocente($id)
    {
        $docente = Docente::findOrFail($id);

        if (auth()->user()->id == $docente->id) {
            return redirect()->route('admin.panel')->withErrors(['error' => 'No puedes eliminar tu propio usuario.']);
        }

        $docente->delete();

        return redirect()->route('admin.panel')->with('success', 'Docente eliminado correctamente.');
    }
}
