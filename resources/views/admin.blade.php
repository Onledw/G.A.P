<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    <h1>Modulo de Administrador</h1>

    <h2>Dar de Alta a un Docente</h2>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
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

<table border="1" cellpadding="5">
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
                    <button type="submit">Eliminar</button>
                </form>
            </td>
        </tr>
    @endforeach
</tbody>
</table>

        <button>Getion de los horarios</button>
    </form>
</body>
</html>