<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Guardias Pendientes</title>
</head>
<body>
    <h1>Guardias Pendientes para Hoy</h1>

    @if (count($sesiones) > 0)
        <table border="1">
            <thead>
                <tr>
                    <th>Hora Inicio</th>
                    <th>Hora Fin</th>
                    <th>Aula</th>
                    <th>Materia</th>
                    <th>Docente Ausente (ID)</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sesiones as $sesion)
                    <tr>
                        <td>{{ $sesion->hora_inicio }}</td>
                        <td>{{ $sesion->hora_fin }}</td>
                        <td>{{ $sesion->aula }}</td>
                        <td>{{ $sesion->materia }}</td>
                        <td>{{ $sesion->docente_id }}</td>
                        <td>
                            <form action="{{ route('guardias.asignar') }}" method="POST">
                                @csrf
                                <input type="hidden" name="sesion_id" value="{{ $sesion->sesion_id }}">
                                <input type="hidden" name="ausencia_id" value="{{ $sesion->ausencia_id }}">
                                <input type="hidden" name="hora" value="{{ $sesion->hora_inicio }}">
                                <input type="hidden" name="aula" value="{{ $sesion->aula }}">
                                <button type="submit">Cubrir esta guardia</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No hay guardias pendientes para hoy.</p>
    @endif

</body>
</html>
