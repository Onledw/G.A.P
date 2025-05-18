<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Bienvenido {{ Auth::user()->nombre }}</h1>

    <p>Has iniciado sesión correctamente.</p>

    <form action="{{ route('logout') }}" method="POST">
        <p><a href="{{ route('logout') }}">Cerrar sesión</a></p>
    </form>

    <form action="{{ route('ausencia.registrar') }}" method="POST">
        @csrf

        <label for="fecha_inicio">Desde:</label>
        <input type="datetime-local" name="fecha_inicio" required>

        <label for="fecha_fin">Hasta:</label>
        <input type="datetime-local" name="fecha_fin" required>

        <label for="todoeldia">
            <input type="checkbox" name="todoeldia" value="1"> Todo el día
        </label>
        <br>

        <label for="justificada">
            <input type="checkbox" name="justificada" value="1"> Justificada
        </label>
        <br>

        <label for="motivo">Motivo:</label>
        <textarea name="motivo"></textarea>

        <button type="submit">Registrar Ausencia</button>
    </form>
    <h3>Ausencias Registradas</h3>

    @if($ausencias->isEmpty())
    <p>No hay ausencias registradas.</p>
    @else
    <ul>
        @foreach($ausencias as $ausencia)
            <li>
                Docente: {{ optional($ausencia->docente)->nombre ?? 'Desconocido' }} <br>
                Desde: {{ \Carbon\Carbon::parse($ausencia->fecha_inicio)->format('d/m/Y H:i') }} <br>
                Hasta: {{ \Carbon\Carbon::parse($ausencia->fecha_fin)->format('d/m/Y H:i') }} <br>
                Todo el día: {{ $ausencia->todoeldia ? 'Sí' : 'No' }} <br>
                Motivo: {{ $ausencia->motivo ?? 'No especificado' }} <br>
                Justificada: {{ $ausencia->justificada ? 'Sí' : 'No' }}
            </li>
        @endforeach
    </ul>
    @endif
    <a href="{{ route('ausencias') }}">Ver mis ausencias</a>
    <h2>Mi Horario</h2>
    @if($horario->isEmpty())
        <p>No tienes sesiones asignadas.</p>
    @else
        <ul>
        @foreach($horario as $sesion)
            <li>
                Día: {{ $sesion->dia_semana }},
                De {{ \Carbon\Carbon::parse($sesion->hora_inicio)->format('H:i') }}
                a {{ \Carbon\Carbon::parse($sesion->hora_fin)->format('H:i') }},
                Aula: {{ $sesion->aula }},
                Materia: {{ $sesion->materia }}
            </li>
        @endforeach
        </ul>
    @endif


</body>
</html>