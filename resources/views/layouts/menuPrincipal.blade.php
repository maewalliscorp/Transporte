<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menú Principal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Estilos para el menú de usuario */
        #userDropdown {
            color: #4FC3F7 !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        #userDropdown:hover {
            color: #7E57C2 !important;
            transform: scale(1.05);
        }
        .dropdown-menu {
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: 1px solid #E1F5FE;
        }
        .dropdown-item:hover {
            background: linear-gradient(135deg, #4FC3F7 0%, #7E57C2 100%);
            color: white;
        }
        .dropdown-item-text {
            padding: 0.75rem 1rem;
        }
    </style>
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
                @auth
                    @php
                        $user = Auth::user();
                        $role = $user->getRole();
                    @endphp

                    @if($role === 'administrador')
                        <!-- Menú desplegable de AGREGAR - Solo Administrador -->
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
                            <a class="nav-link" href="{{ route('asignar') }}">
                                <i class="bi bi-clipboard-check me-1"></i>Asignación
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('monitoreo') }}">
                                <i class="bi bi-graph-up me-1"></i>Monitoreo
                            </a>
                        </li>

                          <li class="nav-item">
                            <a class="nav-link" href="{{ route('finanzas') }}">
                                <i class="bi bi-calculator me-1"></i>Finanzas
                            </a>
                        </li>


                        <!-- Menú desplegable de MANTENIMIENTO - Solo Administrador -->
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
                                    <a class="dropdown-item" href="{{ route('mantenimiento.m-historial') }}">
                                        <i class="bi bi-clock-history me-2"></i>Historial de Mantenimiento
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('mantenimiento.m-alertas') }}">
                                        <i class="bi bi-bell me-2"></i>Alertas de Mantenimiento
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pasajero.p-registro') }}">
                                <i class="bi bi-person-plus me-1"></i>Registro de Pasajeros
                            </a>
                        </li>

                    @elseif($role === 'operador')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('incidentes') }}">
                                <i class="bi bi-exclamation-triangle me-1"></i>Registrar Incidentes
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('mantenimiento.m-programado') }}">
                                <i class="bi bi-calendar-check me-1"></i>Mantenimiento Programado
                            </a>
                        </li>

                    @elseif($role === 'supervisor')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('mantenimiento.m-realizado') }}">
                                <i class="bi bi-check-circle me-1"></i>Seguimiento de Incidentes
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('mantenimiento.m-programado') }}">
                                <i class="bi bi-calendar-check me-1"></i>Mantenimiento Programado
                            </a>
                        </li>

                    @elseif($role === 'contador')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('finanzas') }}">
                                <i class="bi bi-calculator me-1"></i>Finanzas
                            </a>
                        </li>

                    @elseif($role === 'pasajero')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pasajero.p-registro') }}">
                                <i class="bi bi-person-plus me-1"></i>Registro de Pasajero
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pasajero.p-historial') }}">
                                <i class="bi bi-clock-history me-1"></i>Historial de Viajes
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pasajero.p-queja-sugerencia') }}">
                                <i class="bi bi-chat-left-text me-1"></i>Quejas y Sugerencias
                            </a>
                        </li>
                    @endif
                @endauth

                <!-- Menú desplegable de USUARIO -->
                @auth
                    <li class="nav-item dropdown ms-3">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-2" style="font-size: 1.5rem;"></i>
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <div class="dropdown-item-text">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-circle me-2" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <strong>{{ Auth::user()->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Auth::user()->email }}</small>
                                            <br>
                                            @php
                                                $role = Auth::user()->getRole();
                                                $roleNames = [
                                                    'administrador' => 'Administrador',
                                                    'contador' => 'Contador',
                                                    'operador' => 'Operador',
                                                    'supervisor' => 'Supervisor',
                                                    'pasajero' => 'Pasajero'
                                                ];
                                            @endphp
                                            <small class="badge bg-primary mt-1">{{ $roleNames[$role] ?? 'Usuario' }}</small>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

</body>
</html>
