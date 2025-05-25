@extends('plantilla.app')

@section('content')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkbox = document.getElementById('todoeldia');
        const inicioInput = document.getElementById('fecha_inicio');
        const finInput = document.getElementById('fecha_fin');

        checkbox.addEventListener('change', function () {
            if (checkbox.checked) {
                const hoy = new Date();
                const fecha = hoy.toISOString().split('T')[0]; // 'YYYY-MM-DD'
                const hora = '00:00'; // Puedes poner cualquier hora si es irrelevante

                const datetime = `${fecha}T${hora}`;

                inicioInput.value = datetime;
                finInput.value = datetime;

                inicioInput.readOnly = true;
                finInput.readOnly = true;
            } else {
                inicioInput.readOnly = false;
                finInput.readOnly = false;

                inicioInput.value = '';
                finInput.value = '';
            }
        });
    });
</script>

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="container">
    <h2>Registrar Ausencia</h2>

    <form action="{{ route('ausencias.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
            <input type="datetime-local" class="form-control" name="fecha_inicio" id="fecha_inicio" required>
        </div>

        <div class="mb-3">
            <label for="fecha_fin" class="form-label">Fecha de fin</label>
            <input type="datetime-local" class="form-control" name="fecha_fin" id="fecha_fin" required>
        </div>

        <div class="mb-3">
            <label for="motivo" class="form-label">Motivo</label>
            <input type="text" class="form-control" name="motivo" id="motivo">
        </div>

        <div class="form-check mb-2">
            <input type="checkbox" class="form-check-input" name="justificada" id="justificada">
            <label class="form-check-label" for="justificada">Justificada</label>
        </div>

        <div class="form-check mb-4">
            <input type="checkbox" class="form-check-input" name="todoeldia" id="todoeldia">
            <label class="form-check-label" for="todoeldia">Todo el día</label>
        </div>

        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div>
@endsection
