<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Docente;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required',
            'password' => 'required',
        ]);

        $docente = Docente::where('dni', $request->usuario)->first();

        if (!$docente || !Hash::check($request->password, $docente->clave)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        $token = $docente->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $docente,
            'token' => $token,
        ]);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente.']);
    }

    public function panelAdmin()
    {
        if (!Auth::user()->admin) {
            return response()->json(['error' => 'Acceso no autorizado'], 403);
        }

        $docentes = Docente::all();

        return response()->json([
            'docentes' => $docentes
        ]);
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

    return response()->json([
        'message' => 'Docente dado de alta correctamente.',
        'docente' => $docente
    ], 201);
    }

    public function bajaDocente($id)
    {
    $docente = Docente::findOrFail($id);

    if (auth()->user()->id == $docente->id) {
        return response()->json(['error' => 'No puedes eliminar tu propio usuario.'], 403);
    }

    $docente->delete();

    return response()->json(['message' => 'Docente eliminado correctamente.']);
    }


}
