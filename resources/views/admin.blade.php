@extends('plantilla.app')

@section('content')

<h1>Módulo de Administrador</h1>

<h2>Dar de Alta a un Docente</h2>

@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.altaDocente') }}" method="POST">
    @csrf
    <label for="dni">DNI:</label>
    <input type="text" name="dni" required><br>

    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" required><br>

    <label for="apellido1">Primer Apellido:</label>
    <input type="text" name="apellido1" required><br>

    <label for="apellido2">Segundo Apellido:</label>
    <input type="text" name="apellido2"><br>

    <label for="clave">Contraseña:</label>
    <input type="password" name="clave" required><br>

    <label for="fecha_ingreso">Fecha de Ingreso:</label>
    <input type="date" name="fecha_ingreso" required><br>

    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
    <input type="date" name="fecha_nacimiento" required><br>

    <label for="sexo">Sexo:</label>
    <select name="sexo" required>
        <option value="H">Hombre</option>
        <option value="M">Mujer</option>
        <option value="O">Otro</option>
    </select><br>

    <label for="admin">¿Es administrador?</label>
    <input type="checkbox" name="admin" value="1"><br>

    <button type="submit">Dar de Alta</button>
</form>

@if(session('borrado'))
    <p style="color: green;">{{ session('borrado') }}</p>
@endif

<h2>Listado de Docentes</h2>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>DNI</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        @foreach($docentes as $docente)
            <tr>
                <td>{{ $docente->dni }}</td>
                <td>{{ $docente->nombre }}</td>
                <td>{{ $docente->apellido1 }} {{ $docente->apellido2 }}</td>
                <td>
                    <form action="{{ route('admin.bajaDocente', $docente->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar al docente?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    <a href="#" class="btn btn-secondary">Gestión de los horarios</a> {{-- Ajusta la ruta --}}
</div>

@endsection
