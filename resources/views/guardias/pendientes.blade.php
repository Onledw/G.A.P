@extends('plantilla.app')

@section('title', 'Panel Docente')

@section('content')
    <h1>Guardias Pendientes para Hoy</h1>

    @forelse ($guardias as $guardia)
        <tr>
            <td>{{ $guardia->hora_inicio }}</td>
            <td>{{ $guardia->hora_fin }}</td>
            <td>{{ $guardia->aula }}</td>
            <td>{{ $guardia->materia }}</td>
            <td>{{ $guardia->nombre_ausente }}</td>
            <td>
                <form action="{{ route('guardias.asignar') }}" method="POST">
                    @csrf
                    <input type="hidden" name="sesion_id" value="{{ $guardia->sesion_id }}">
                    <input type="hidden" name="ausencia_id" value="{{ $guardia->ausencia_id }}">
                    <input type="hidden" name="hora" value="{{ $guardia->hora_inicio }}">
                    <input type="hidden" name="aula" value="{{ $guardia->aula }}">
                    <button type="submit">Cubrir esta guardia</button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6">No hay guardias pendientes para hoy.</td>
        </tr>
    @endforelse
@endsection
