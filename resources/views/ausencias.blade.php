
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Tus Ausencias</h1>

    @if($ausencias->isEmpty())
        <p>No tienes ausencias registradas.</p>
    @else
        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>Desde</th>
                    <th>Hasta</th>
                    <th>Todo el día</th>
                    <th>Justificada</th>
                    <th>Motivo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ausencias as $ausencia)
                    <tr>
                        <td>{{ $ausencia->fecha_inicio }}</td>
                        <td>{{ $ausencia->fecha_fin }}</td>
                        <td>{{ $ausencia->todoeldia ? 'Sí' : 'No' }}</td>
                        <td>{{ $ausencia->justificada ? 'Sí' : 'No' }}</td>
                        <td>{{ $ausencia->motivo ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>