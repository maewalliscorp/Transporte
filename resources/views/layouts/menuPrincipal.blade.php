<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menú Principal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('inicio') }}">
            Gestión de transporte público
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">

                <!-- Menú desplegable de AGREGAR -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="agregarDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-plus-circle me-1"></i>Agregar
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="agregarDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('agregar.unidades') }}">
                                <i class="bi bi-bus-front me-2"></i>Unidades
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('agregar.operadores') }}">
                                <i class="bi bi-person-badge me-2"></i>Operadores
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('agregar.rutas') }}">
                                <i class="bi bi-signpost-split me-2"></i>Rutas
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('agregar.horarios') }}">
                                <i class="bi bi-clock me-2"></i>Horarios
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('agregar.tincidente') }}">
                                <i class="bi bi-exclamation-triangle me-2"></i>Incidentes
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('inicio') }}">
                        <i class="bi bi-clipboard-check me-1"></i>Asignación
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('finanzas') }}">
                        <i class="bi bi-calculator me-1"></i>Finanzas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('incidentes') }}">
                        <i class="bi bi-exclamation-triangle me-1"></i>Incidentes
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('mantenimiento') }}">
                        <i class="bi bi-tools me-1"></i>Mantenimiento
                    </a>
                </li>

                <!-- Menú desplegable de MANTENIMIENTO -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="mantenimientoDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-tools me-1"></i>Mantenimiento
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="mantenimientoDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('mantenimiento.m-programado') }}">
                                <i class="bi bi-calendar-check me-2"></i>Mantenimiento Programado
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('mantenimiento.m-realizado') }}">
                                <i class="bi bi-check-circle me-2"></i>Mantenimiento Realizado
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('mantenimiento.m-alertas') }}">
                                <i class="bi bi-bell me-2"></i>Alertas de Mantenimiento
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('mantenimiento.m-historial') }}">
                                <i class="bi bi-clock-history me-2"></i>Historial de Mantenimiento
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('monitoreo') }}">
                        <i class="bi bi-graph-up me-1"></i>Monitoreo
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('pasajerof') }}">
                        <i class="bi bi-people me-1"></i>Pasajero
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>

</body>
</html>
