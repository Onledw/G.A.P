@extends('plantilla.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Historial de Ausencias</h3>

    <table class="table table-bordered table-striped">
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
            @forelse($ausencias as $ausencia)
                <tr>
                    <td>{{ $ausencia->docente->nombre }} {{ $ausencia->docente->apellido1 }}</td>
                    <td>{{ $ausencia->fecha_inicio }}</td>
                    <td>{{ $ausencia->fecha_fin }}</td>
                    <td>{{ $ausencia->todoeldia ? 'Sí' : 'No' }}</td>
                    <td>{{ $ausencia->justificada ? 'Sí' : 'No' }}</td>
                    <td>{{ $ausencia->motivo ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">No hay ausencias registradas.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="text-end">
        <a href="{{ route('panel') }}" class="btn btn-secondary">Volver al panel</a>
    </div>
</div>
@endsection
