@extends('plantilla.app')

@section('title', 'Panel Docente')

@section('content')
    <h1>Guardias Pendientes para Hoy</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <ul class="alert alert-danger">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    {{-- Guardias pendientes --}}
    @forelse ($guardiasPendientes as $guardia)
        <div class="guardia-item border p-3 mb-3">
            <p><strong>Docente ausente:</strong> {{ $guardia->nombre_ausente }}</p>
            <p><strong>Materia:</strong> {{ $guardia->materia }}</p>
            <p><strong>Aula:</strong> {{ $guardia->aula }}</p>
            <p><strong>Hora:</strong> {{ $guardia->hora_inicio }} - {{ $guardia->hora_fin }}</p>

            <form method="POST" action="{{ route('guardias.store') }}">
                @csrf
                <input type="hidden" name="ausencia_id" value="{{ $guardia->ausencia_id }}">
                <input type="hidden" name="hora" value="{{ $guardia->hora_inicio }}">
                <input type="hidden" name="aula" value="{{ $guardia->aula }}">
                <button type="submit" class="btn btn-success">Cubrir guardia</button>
            </form>
        </div>
    @empty
        <p>No hay guardias pendientes para hoy.</p>
    @endforelse

    <hr>
    <h2>Historial de Guardias Cubiertas</h2>
    @if($guardiasHistorial->isEmpty())
        <p>No hay guardias cubiertas aún.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Docente que cubrió</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Aula</th>
                    <th>Estado</th>
                    <th>Ausencia desde</th>
                    <th>Ausencia hasta</th>
                </tr>
            </thead>
            <tbody>
                @foreach($guardiasHistorial as $h)
                    <tr>
                        <td>{{ $h->docente_nombre }}</td>
                        <td>{{ $h->fecha }}</td>
                        <td>{{ $h->hora }}</td>
                        <td>{{ $h->aula }}</td>
                        <td>{{ $h->estado }}</td>
                        <td>{{ $h->fecha_inicio }}</td>
                        <td>{{ $h->fecha_fin }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
