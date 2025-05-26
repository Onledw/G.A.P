@extends('plantilla.app')

@section('title', 'Informes de Ausencias')

@section('content')
    <h1>Generar Informe de Ausencias</h1>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="GET" action="{{ route('informes.generar') }}" class="mb-4">
        @csrf

        <div class="form-group mb-2">
            <label for="tipo">Tipo de informe:</label>
            <select name="tipo" id="tipo" class="form-control" onchange="toggleFechaDocente()">
                <option value="dia">Por día</option>
                <option value="semana">Por semana</option>
                <option value="mes">Por mes</option>
                <option value="trimestre">Por trimestre</option>
                <option value="curso">Curso completo</option>
                <option value="docente">Por docente</option>
            </select>
        </div>

        <div class="form-group mb-2" id="fechaDiv">
            <label for="fecha">Fecha (o fecha de inicio):</label>
            <input type="date" name="fecha" id="fecha" class="form-control">
        </div>

        <div class="form-group mb-2 d-none" id="docenteDiv">
            <label for="docente_id">Docente:</label>
            <select name="docente_id" id="docente_id" class="form-control">
                <option value="">-- Selecciona un docente --</option>
                @foreach ($docentes as $docente)
                    <option value="{{ $docente->id }}">{{ $docente->nombre }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Generar Informe</button>
    </form>

    @if(isset($resultados) && $resultados->isNotEmpty())
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Motivo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($resultados as $item)
                <tr>
                    <td>{{ $item->nombre }}</td>
                    <td>{{ $item->fecha_inicio }}</td>
                    <td>{{ $item->fecha_fin }}</td>
                    <td>{{ $item->motivo }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No hay registros para mostrar.</p>
@endif


    <script>
        function toggleFechaDocente() {
            const tipo = document.getElementById('tipo').value;
            document.getElementById('fechaDiv').style.display = (tipo !== 'curso') ? 'block' : 'none';
            document.getElementById('docenteDiv').classList.toggle('d-none', tipo !== 'docente');
        }
        window.onload = toggleFechaDocente;

    </script>
@endsection
