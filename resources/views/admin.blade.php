@extends('plantilla.app')

@section('content')
<h1 class="pt-4 mb-5 text-center">Módulo de Administrador</h1>

<div class="container my-5" style="max-width: 800px;">
    <div class="p-4 border rounded shadow-sm bg-white">

        <h2 class="mb-3">Dar de Alta a un Docente</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.altaDocente') }}" method="POST" class="mb-5">
            @csrf
            <div class="mb-3">
                <label for="dni" class="form-label">DNI:</label>
                <input type="text" name="dni" id="dni" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="apellido1" class="form-label">Primer Apellido:</label>
                <input type="text" name="apellido1" id="apellido1" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="apellido2" class="form-label">Segundo Apellido:</label>
                <input type="text" name="apellido2" id="apellido2" class="form-control">
            </div>

            <div class="mb-3">
                <label for="clave" class="form-label">Contraseña:</label>
                <input type="password" name="clave" id="clave" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso:</label>
                <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento:</label>
                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="sexo" class="form-label">Sexo:</label>
                <select name="sexo" id="sexo" class="form-select" required>
                    <option value="H">Hombre</option>
                    <option value="M">Mujer</option>
                    <option value="O">Otro</option>
                </select>
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="admin" id="admin" value="1">
                <label class="form-check-label" for="admin">¿Es administrador?</label>
            </div>

            <button type="submit" class="btn btn-primary">Dar de Alta</button>
        </form>

        @if(session('borrado'))
            <div class="alert alert-success">
                {{ session('borrado') }}
            </div>
        @endif

        <h2 class="mb-3">Listado de Docentes</h2>
        <table class="table table-bordered table-hover">
            <thead class="table-light">
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
    </div>
</div>

@endsection
