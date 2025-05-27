@extends('plantilla.app')

@section('title', 'Informes de Ausencias')

@section('content')
    <h1>Generar Informe de Ausencias</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('informes.generar') }}" method="POST" class="mb-4">
        @csrf
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="tipo" class="form-label">Tipo de informe</label>
                <select name="tipo" id="tipo" class="form-select" onchange="toggleFechaDocente()">
                    <option value="dia" {{ old('tipo') == 'dia' ? 'selected' : '' }}>Por día</option>
                    <option value="semana" {{ old('tipo') == 'semana' ? 'selected' : '' }}>Por semana</option>
                    <option value="mes" {{ old('tipo') == 'mes' ? 'selected' : '' }}>Por mes</option>
                    <option value="trimestre" {{ old('tipo') == 'trimestre' ? 'selected' : '' }}>Por trimestre</option>
                    <option value="curso" {{ old('tipo') == 'curso' ? 'selected' : '' }}>Curso completo</option>
                    <option value="docente" {{ old('tipo') == 'docente' ? 'selected' : '' }}>Por docente</option>
                </select>
            </div> <div class="col-md-4" id="fechaDiv">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" name="fecha" id="fecha" class="form-control" value="{{ old('fecha') }}">
            </div>

            <div class="col-md-4" id="docenteDiv" style="display: none;">
                <label for="docente_id" class="form-label">Docente</label>
                <select name="docente_id" id="docente_id" class="form-select">
                    <option value="">-- Selecciona un docente --</option>
                    @foreach ($docentes as $docente)
                        <option value="{{ $docente->id }}" {{ old('docente_id') == $docente->id ? 'selected' : '' }}>
                            {{ $docente->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Generar Informe</button>
    </form>

    {{-- Resultados --}}
    @if ($resultados->isNotEmpty())
        <h4>Resultados</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Docente</th>
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
        @if (old('tipo'))
            <p class="text-muted">No se encontraron resultados para los criterios seleccionados.</p>
        @endif
    @endif
</div>


    <script>
        function toggleFechaDocente() {
            const tipo = document.getElementById('tipo').value;
            document.getElementById('fechaDiv').style.display = (tipo !== 'curso') ? 'block' : 'none';
            document.getElementById('docenteDiv').classList.toggle('d-none', tipo !== 'docente');
        }
        window.onload = toggleFechaDocente;
    </script>

@endsection
