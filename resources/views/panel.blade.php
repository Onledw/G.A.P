<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Docente</title>
</head>
<body>
    <h1>Bienvenido {{ Auth::user()->nombre }}</h1>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Cerrar sesión</button>
    </form>

    <!-- =========================
         REGISTRO DE AUSENCIAS
    ========================== -->
    <h2>Registrar Ausencia</h2>
    <form action="{{ route('ausencia.registrar') }}" method="POST">
        @csrf

        <label for="fecha_inicio">Desde:</label>
        <input type="datetime-local" name="fecha_inicio" id="fecha_inicio" required>

        <label for="fecha_fin">Hasta:</label>
        <input type="datetime-local" name="fecha_fin" id="fecha_fin" required>

        <label>
            <input type="checkbox" name="todoeldia" id="todoeldia" value="1"> Todo el día
        </label><br>

        <label>
            <input type="checkbox" name="justificada" value="1"> Justificada
        </label><br>

        <label for="motivo">Motivo:</label><br>
        <textarea name="motivo" id="motivo" rows="3" cols="30"></textarea><br>

        <button type="submit">Registrar Ausencia</button>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const todoElDiaCheckbox = document.getElementById('todoeldia');
            const fechaInicio = document.getElementById('fecha_inicio');
            const fechaFin = document.getElementById('fecha_fin');

            todoElDiaCheckbox.addEventListener('change', function () {
                if (this.checked) {
                    fechaInicio.type = 'date';
                    fechaFin.type = 'date';
                } else {
                    fechaInicio.type = 'datetime-local';
                    fechaFin.type = 'datetime-local';
                }
            });
        });
    </script>

    <!-- =========================
         AUSENCIAS REGISTRADAS
    ========================== -->
    <h3>Ausencias Registradas</h3>
    @if($ausencias->isEmpty())
        <p>No hay ausencias registradas.</p>
    @else
        <ul>
            @foreach($ausencias as $ausencia)
                <li>
                    Docente: {{ optional($ausencia->docente)->nombre ?? 'Desconocido' }}<br>
                    Desde: {{ \Carbon\Carbon::parse($ausencia->fecha_inicio)->format($ausencia->todoeldia ? 'd/m/Y' : 'd/m/Y H:i') }}<br>
                    Hasta: {{ \Carbon\Carbon::parse($ausencia->fecha_fin)->format($ausencia->todoeldia ? 'd/m/Y' : 'd/m/Y H:i') }}<br>
                    Todo el día: {{ $ausencia->todoeldia ? 'Sí' : 'No' }}<br>
                    Motivo: {{ $ausencia->motivo ?? 'No especificado' }}<br>
                    Justificada: {{ $ausencia->justificada ? 'Sí' : 'No' }}
                </li>
            @endforeach
        </ul>
    @endif
    <a href="{{ route('ausencias') }}">Ver mis ausencias</a>

    <!-- =========================
         HORARIO DEL DOCENTE
    ========================== -->
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

    <!-- =========================
         REGISTRO DE JORNADA
    ========================== -->
    <h2>Registro de Jornada</h2>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <form method="POST" action="{{ route('registro.inicio') }}">
        <!-- Deshabilita el botón si ya hay una jornada iniciada -->
        @csrf
        <button type="submit" {{ $registroHoy ? 'disabled' : '' }}>Iniciar Jornada</button>

    </form>

    <form method="POST" action="{{ route('registro.fin') }}">
        <!-- Deshabilita si no hay jornada o ya fue finalizada -->
        @csrf
        <<button type="submit" {{ !$registroHoy || $registroHoy->fin ? 'disabled' : '' }}>Finalizar Jornada</button>
    </form>

    @if($registroHoy)
        <p>Jornada iniciada: {{ \Carbon\Carbon::parse($registroHoy->inicio)->format('H:i') }}</p>
        @if($registroHoy->fin)
            <p>Jornada finalizada: {{ \Carbon\Carbon::parse($registroHoy->fin)->format('H:i') }}</p>
        @endif
    @endif

</body>
</html>
