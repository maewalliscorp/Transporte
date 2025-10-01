<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menú Principal</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container-fluid">

        <!-- Nombre de la empresa -->
        <a class="navbar-brand d-flex align-items-center" href="#">
            Gestión de transporte público
        </a>

        <!-- Botón para menú responsive -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Enlaces del menú -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="pasajero.html" target="_blank">
                        <img src="/imagenes/pasajero.png" alt="Bus" width="20" height="20" class="me-1">
                    </a>
                </li>
                <li class="nav-item">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('asignacion') }}">
                        Asignación
                    </a>
                </li>


                </li>
                <li class="nav-item">
                    <a class="nav-link" href="apartado3.html" target="_blank">
                        <img src="/imagenes/dinero.png" alt="Bus" width="20" height="20" class="me-1">
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="apartado4.html" target="_blank">
                        <img src="/imagenes/mantenimiento.png" alt="Bus" width="20" height="20" class="me-1">
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="apartado5.html" target="_blank">
                        Monitoreo de unidades
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="apartado6.html" target="_blank">
                        Incidentes
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
