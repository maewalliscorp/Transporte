<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido - Sistema de Transporte</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: "Open Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #E3F2FD 0%, #F3E5F5 100%);
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .welcome-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 200px);
        }
        .welcome-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 3rem;
            text-align: center;
            max-width: 600px;
            width: 100%;
            border: 1px solid #E1F5FE;
        }
        .welcome-header {
            background: linear-gradient(135deg, #4FC3F7 0%, #7E57C2 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px 15px 0 0;
            margin: -3rem -3rem 2rem -3rem;
            box-shadow: 0 4px 15px rgba(79, 195, 247, 0.3);
        }
        .welcome-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }
        .welcome-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .user-name {
            color: #4FC3F7;
            font-weight: 600;
            font-size: 1.5rem;
        }
        .role-badge {
            display: inline-block;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            background: linear-gradient(135deg, #4FC3F7 0%, #7E57C2 100%);
            color: white;
            font-weight: 600;
            margin-top: 1rem;
            font-size: 1rem;
        }
        .welcome-message {
            color: #666;
            font-size: 1.1rem;
            margin-top: 1.5rem;
            line-height: 1.6;
        }
        .quick-actions {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #E1F5FE;
        }
        .action-btn {
            margin: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(79, 195, 247, 0.3);
        }
    </style>
</head>
<body>

<!-- AQUI MI MENÚ (NAVBAR) -->
@include('layouts.menuPrincipal')

<div class="welcome-container">
    <div class="welcome-card">
        <div class="welcome-header">
            <i class="bi bi-person-circle welcome-icon"></i>
            <h1 class="welcome-title">¡Bienvenido!</h1>
        </div>

        <div class="welcome-content">
            <p class="user-name">{{ $user->name }}</p>
            <span class="role-badge">
                <i class="bi bi-shield-check me-2"></i>{{ $roleName }}
            </span>

            <p class="welcome-message">
                Has iniciado sesión exitosamente en el sistema de gestión de transporte público.
                <br>
                Utiliza el menú de navegación para acceder a las funciones disponibles según tu rol.
            </p>

            <div class="quick-actions">
                <p class="text-muted mb-3"><strong>Accesos rápidos:</strong></p>
                @if($role === 'administrador')
                    <a href="{{ route('asignar') }}" class="btn btn-primary action-btn">
                        <i class="bi bi-clipboard-check me-2"></i>Asignación
                    </a>
                    <a href="{{ route('monitoreo') }}" class="btn btn-info action-btn">
                        <i class="bi bi-graph-up me-2"></i>Monitoreo
                    </a>
                    <a href="{{ route('finanzas') }}" class="btn btn-success action-btn">
                        <i class="bi bi-calculator me-2"></i>Finanzas
                    </a>
                @elseif($role === 'operador')
                    <a href="{{ route('incidentes') }}" class="btn btn-warning action-btn">
                        <i class="bi bi-exclamation-triangle me-2"></i>Registrar Incidentes
                    </a>
                    <a href="{{ route('mantenimiento.m-programado') }}" class="btn btn-info action-btn">
                        <i class="bi bi-calendar-check me-2"></i>Mantenimiento
                    </a>
                @elseif($role === 'supervisor')
                    <a href="{{ route('mantenimiento.m-realizado') }}" class="btn btn-primary action-btn">
                        <i class="bi bi-check-circle me-2"></i>Seguimiento de Incidentes
                    </a>
                    <a href="{{ route('mantenimiento.m-programado') }}" class="btn btn-info action-btn">
                        <i class="bi bi-calendar-check me-2"></i>Mantenimiento
                    </a>
                @elseif($role === 'contador')
                    <a href="{{ route('finanzas') }}" class="btn btn-success action-btn">
                        <i class="bi bi-calculator me-2"></i>Finanzas
                    </a>
                @elseif($role === 'pasajero')
                    <a href="{{ route('pasajero.p-historial') }}" class="btn btn-primary action-btn">
                        <i class="bi bi-clock-history me-2"></i>Historial de Viajes
                    </a>
                    <a href="{{ route('pasajero.p-queja-sugerencia') }}" class="btn btn-warning action-btn">
                        <i class="bi bi-chat-left-text me-2"></i>Quejas y Sugerencias
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
//no tiene ciertos permisos administrador y operador
