@extends('plantilla.app')

@section('title', 'Panel Docente')

@section('content')
    <div class="mb-4">
        <h2>Bienvenid@, {{ $usuario->nombre }} {{ $usuario->apellido1 }}</h2>
    </div>

    {{-- Botones de jornada --}}
    @if($registro_jornada_hoy && !$registro_jornada_hoy->fin)
    <form action="{{ route('panel.finalizar') }}" method="POST">
        @csrf
        <button class="btn btn-danger">Finalizar jornada</button>
    </form>
@else
    <form action="{{ route('panel.iniciar') }}" method="POST">
        @csrf
        <button class="btn btn-success">Iniciar jornada</button>
    </form>
@endif


    {{-- Horario --}}
    @php
    $dias = ['L' => 'Lunes', 'M' => 'Martes', 'X' => 'Miércoles', 'J' => 'Jueves', 'V' => 'Viernes'];

    // Definimos bloques horarios como pares de inicio => fin
    $bloques = [
        '08:00:00' => '08:00:00',
        '08:00:00' => '09:00:00',
        '09:00:00' => '10:00:00',
        '10:00:00' => '11:00:00',
        '11:00:00' => '12:00:00',
        '12:00:00' => '13:00:00',
        '13:00:00' => '14:00:00',
        '14:00:00' => '15:00:00',
    ];

    $horarioMap = [];
    foreach ($horario as $sesion) {
        $horarioMap[$sesion->hora_inicio][$sesion->dia_semana] = $sesion;
    }
@endphp

<div class="mb-5">
    <h4>Horario semanal</h4>
    <table class="table table-bordered text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>Hora</th>
                @foreach ($dias as $codigo => $nombre)
                    <th>{{ $nombre }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($bloques as $inicio => $fin)
                <tr>
                    <td><strong>{{ substr($inicio, 0, 5) }} - {{ substr($fin, 0, 5) }}</strong></td>
                    @foreach ($dias as $codigo => $nombre)
                        @php
                            $sesion = $horarioMap[$inicio][$codigo] ?? null;
                        @endphp
                        <td>
                            @if ($sesion)
                                <div><strong>{{ $sesion->materia }}</strong></div>
                                <div>{{ $sesion->aula }}</div>
                            @else
                                -
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>



    {{-- Ausencias --}}
    <div class="mb-5">
        <h4 class="mb-3">Ausencias del día ({{ $fecha_hoy }})</h4>

        {{-- AUSENCIAS DEL DÍA --}}
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Docente</th>
                    <th>Desde</th>
                    <th>Hasta</th>
                    <th>Todo el día</th>
                    <th>Justificada</th>
                    <th>Motivo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ausencias_hoy as $ausencia)
                    <tr>
                        <td>{{ $ausencia->docente->nombre }} {{ $ausencia->docente->apellido1 }}</td>
                        <td>{{ $ausencia->fecha_inicio }}</td>
                        <td>{{ $ausencia->fecha_fin }}</td>
                        <td>{{ $ausencia->todoeldia ? 'Sí' : 'No' }}</td>
                        <td>{{ $ausencia->justificada ? 'Sí' : 'No' }}</td>
                        <td>{{ $ausencia->motivo ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">No hay ausencias registradas hoy.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- AUSENCIAS DEL USUARIO --}}
    <div class="mb-4 text-end">
        <a href="{{ route('ausencias.historial') }}" class="btn btn-outline-primary">
            Ver historial de ausencias
        </a>
    </div>

    <div class="mb-5">
        <h4 class="mb-3">Tus ausencias</h4>
        <table class="table table-striped">
            <thead class="table-light">
                <tr>
                    <th>Desde</th>
                    <th>Hasta</th>
                    <th>Todo el día</th>
                    <th>Justificada</th>
                    <th>Motivo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ausencias as $ausencia)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($ausencia->fecha_inicio)->format('d/m/Y H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($ausencia->fecha_fin)->format('d/m/Y H:i') }}</td>
                        <td>
                            <span class="badge {{ $ausencia->todoeldia ? 'bg-success' : 'bg-secondary' }}">
                                {{ $ausencia->todoeldia ? 'Sí' : 'No' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $ausencia->justificada ? 'bg-info' : 'bg-warning' }}">
                                {{ $ausencia->justificada ? 'Sí' : 'No' }}
                            </span>
                        </td>
                        <td>{{ $ausencia->motivo ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No tienes ausencias registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>


    {{-- BOTÓN HISTORIAL --}}
    <div class="text-end">
        <a href="{{ route('ausencias.historial') }}" class="btn btn-outline-primary">Ver historial completo</a>
    </div>

@endsection
