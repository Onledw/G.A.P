<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'G.A.P')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap 5 CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            padding-top: 60px;
        }
        /* Ajustamos sidebar para que ocupe altura completa y sea fijo */
        .sidebar {
            position: fixed;
            top: 60px; /* altura navbar */
            bottom: 0;
            left: 0;
            width: 220px;
            padding: 1rem;
            background-color: #f8f9fa;
            overflow-y: auto;
            border-right: 1px solid #dee2e6;
        }
        .content-area {
            margin-left: 220px;
            padding: 1rem;
        }
        @media (max-width: 768px) {
            .sidebar {
                position: static;
                width: 100%;
                height: auto;
                border-right: none;
            }
            .content-area {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('panel') }}">G.A.P</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-outline-light btn-sm" type="submit">Cerrar sesión</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <aside class="sidebar">
        <div class="list-group">
            <a href="{{ route('panel') }}" class="list-group-item list-group-item-action">Panel Principal</a>
            <a href="{{ route('guardias.pendientes') }}" class="list-group-item list-group-item-action">Guardias Pendientes</a>
            <a href="{{ route('informes.index') }}" class="list-group-item list-group-item-action">Informes</a>
            {{-- Agrega más enlaces si quieres --}}
        </div>
    </aside>

    <main class="content-area">
        <div class="container-fluid">
            @yield('content')
        </div>
    </main>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
