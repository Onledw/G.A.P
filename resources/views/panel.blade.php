<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">ç
    <!-- Bootstrap CSS (v5.x) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Panel del Docente</title>
</head>
    <!-- Bootstrap JS bundle (incluye Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<body>


    <h1>Bienvenido {{ Auth::user()->nombre }}</h1>

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
    @php
    if (!isset($ausenciasHoy) || !($ausenciasHoy instanceof \Illuminate\Support\Collection)) {
        $ausenciasHoy = collect();
    }
@endphp
<!-- =========================
     AUSENCIAS DEL DÍA
========================== -->
@php
    if (!isset($ausenciasHoy) || !($ausenciasHoy instanceof \Illuminate\Support\Collection)) {
        $ausenciasHoy = collect();
    }
@endphp

<h3 class="text-2xl font-semibold mb-4 border-b pb-2">Ausencias del día ({{ $hoy->format('d/m/Y') }})</h3>

@if($ausenciasHoy->isEmpty())
    <p class="text-gray-500 italic">No hay ausencias registradas para hoy.</p>
@else
    <div class="space-y-4">
        @foreach($ausenciasHoy as $ausencia)
            <div class="bg-white shadow rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex justify-between items-center mb-2">
                    <h4 class="text-lg font-bold text-indigo-700">
                        {{ optional($ausencia->docente)->nombre ?? 'Docente desconocido' }}
                    </h4>
                    <span class="text-sm font-medium px-2 py-1 rounded
                        {{ $ausencia->justificada ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                        {{ $ausencia->justificada ? 'Justificada' : 'No Justificada' }}
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-x-6 gap-y-1 text-gray-700">
                    <div>
                        <span class="font-semibold">Desde:</span><br>
                        {{ \Carbon\Carbon::parse($ausencia->fecha_inicio)->format($ausencia->todoeldia ? 'd/m/Y' : 'd/m/Y H:i') }}
                    </div>
                    <div>
                        <span class="font-semibold">Hasta:</span><br>
                        {{ \Carbon\Carbon::parse($ausencia->fecha_fin)->format($ausencia->todoeldia ? 'd/m/Y' : 'd/m/Y H:i') }}
                    </div>
                    <div class="col-span-2">
                        <span class="font-semibold">Motivo:</span><br>
                        {{ $ausencia->motivo ?? 'No especificado' }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif


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
    <p>Hoy es: {{ now()->format('l d/m/Y H:i') }}</p>
    <form method="POST" action="{{ route('registro.inicio') }}">
        @csrf
        <button type="submit">Forzar inicio de jornada (debug)</button>
    </form>


    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <form method="POST" action="{{ route('registro.inicio') }}">
        <!-- Deshabilita el botón si ya hay una jornada iniciada -->
        @csrf
        <button type="submit" {{ $registroHoy ? 'disabled' : '' }}>Iniciar Jornada</button>

    </form>

    <form method="POST" action="{{ route('registro.fin') }}">
        @csrf
        <button type="submit" {{ !$registroHoy || $registroHoy->fin ? 'disabled' : '' }}>Finalizar Jornada</button>
    </form>

    @if($registroHoy)
        <p>Jornada iniciada: {{ \Carbon\Carbon::parse($registroHoy->inicio)->format('H:i') }}</p>
        @if($registroHoy->fin)
            <p>Jornada finalizada: {{ \Carbon\Carbon::parse($registroHoy->fin)->format('H:i') }}</p>
        @endif
    @endif


    <h3>Guardias a cubrir</h3>
    @php
        $ausenciasPorCubrir = $ausenciasPorCubrir ?? collect();
    @endphp

@forelse($ausenciasPorCubrir as $aus)
        <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
            <strong>Docente ausente:</strong> {{ optional($aus->docente)->nombre }}<br>
            <strong>Desde:</strong> {{ \Carbon\Carbon::parse($aus->fecha_inicio)->format($aus->todoeldia ? 'd/m/Y' : 'd/m/Y H:i') }}<br>
            <strong>Hasta:</strong> {{ \Carbon\Carbon::parse($aus->fecha_fin)->format($aus->todoeldia ? 'd/m/Y' : 'd/m/Y H:i') }}<br>

            @if(!empty($aus->sesionesPorCubrir) && is_iterable($aus->sesionesPorCubrir))
            <ul>
            @foreach($aus->sesionesPorCubrir as $sesion)
                <li>
                    {{ $sesion->hora_inicio }} – {{ $sesion->hora_fin }}
                    | Aula: {{ $sesion->aula }}
                    | Materia: {{ $sesion->materia }}

                    <form action="{{ route('guardias.asignar') }}" method="POST" style="display:inline;margin-left:10px;">
                        @csrf
                        <input type="hidden" name="ausencia_id" value="{{ $aus->id }}">
                        <input type="hidden" name="fecha"       value="{{ $hoy->toDateString() }}">
                        <input type="hidden" name="hora"        value="{{ $sesion->hora_inicio }}">
                        <input type="hidden" name="aula"        value="{{ $sesion->aula }}">
                        <button type="submit">Cubrir guardia</button>
                    </form>
                </li>
            @endforeach
            </ul>
        @else
            <em>No hay sesiones que cubrir (o codocencia cubre todas).</em>
        @endif

        </div>
    @empty
        <p>No hay guardias por cubrir hoy.</p>
    @endforelse


    <form action="{{ route('guardias.pendientes') }}" method="GET" style="display: inline;">
        @csrf
        <button type="submit">Ver Guardias Pendientes</button>
    </form>


    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Cerrar sesión</button>
    </form>

    @if(Auth::user()->admin)
        <form action="{{ route('admin.panel') }}" method="GET" style="margin-top: 20px;">
            <button type="submit">Ir al panel de administración</button>
        </form>
    @endif


</body>
</html>
