<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignación de Transporte</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .filter-section {
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<!-- AQUI MI MENÚ (NAVBAR) -->

  @include('layouts.menuPrincipal')


<div class="container">

    <!-- Filtro de vista -->
    <div class="filter-section">
        <label class="form-label me-3">Ver:</label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="vista" id="verDisponibles" value="disponibles" checked>
            <label class="form-check-label" for="verDisponibles">Disponibles</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="vista" id="verAsignados" value="asignados">
            <label class="form-check-label" for="verAsignados">Asignados</label>
        </div>
    </div>

    <!-- Selects desplegables -->
    <div class="row mb-4" id="seccionSeleccion">
        <div class="col-md-3 mb-2">
            <label for="unidad" class="form-label">Unidad de transporte</label>
            <select class="form-select" id="unidad">
                <option selected disabled>Selecciona...</option>
                <!-- Opciones aquí -->
            </select>
        </div>
        <div class="col-md-3 mb-2">
            <label for="operador" class="form-label">Operador</label>
            <select class="form-select" id="operador">
                <option selected disabled>Selecciona...</option>
                <!-- Opciones aquí -->
            </select>
        </div>
        <div class="col-md-3 mb-2">
            <label for="ruta" class="form-label">Ruta</label>
            <select class="form-select" id="ruta">
                <option selected disabled>Selecciona...</option>
                <!-- Opciones aquí -->
            </select>
        </div>
        <div class="col-md-3 mb-2">
            <label for="horario" class="form-label">Horario</label>
            <select class="form-select" id="horario">
                <option selected disabled>Selecciona...</option>
                <!-- Opciones aquí -->
            </select>
        </div>
    </div>

    <!--  Botón de asignar -->
    <div class="mb-4 text-end">
        <button class="btn btn-primary" id="btnAsignar">
            Asignar
        </button>
    </div>

    <!--  Tabla de DISPONIBLES (se muestra por defecto) -->
    <div id="tablaDisponibles">
        <h5>DISPONIBLES</h5>
        <table class="table table-bordered">
            <thead class="table-light">
            <tr>
                <th>Unidad de transporte</th>
                <th>Operador</th>
                <th>Ruta</th>
                <th>Horario</th>
            </tr>
            </thead>
            <tbody>
            <!-- Aquí se llenará la tabla desde la base de datos -->
            </tbody>
        </table>
    </div>

    <!--  Tabla de ASIGNADOS (se oculta por defecto) -->
    <div id="tablaAsignados" style="display: none;">
        <h5>ASIGNADOS</h5>
        <table class="table table-bordered">
            <thead class="table-light">
            <tr>
                <th>Unidad de transporte</th>
                <th>Operador</th>
                <th>Ruta</th>
                <th>Horario</th>
            </tr>
            </thead>
            <tbody>
            <!-- Aquí se llenará la tabla desde la base de datos -->
            </tbody>
        </table>
    </div>

</div>

<!-- Bootstrap JS + Funcionalidad -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Cambiar entre tablas de 'disponibles' y 'asignados'
    const radioDisponibles = document.getElementById('verDisponibles');
    const radioAsignados = document.getElementById('verAsignados');
    const tablaDisponibles = document.getElementById('tablaDisponibles');
    const tablaAsignados = document.getElementById('tablaAsignados');
    const seccionSeleccion = document.getElementById('seccionSeleccion');

    function actualizarVista() {
        if (radioDisponibles.checked) {
            tablaDisponibles.style.display = 'block';
            tablaAsignados.style.display = 'none';
            seccionSeleccion.style.display = 'flex';
        } else {
            tablaDisponibles.style.display = 'none';
            tablaAsignados.style.display = 'block';
            seccionSeleccion.style.display = 'none';
        }
    }

    radioDisponibles.addEventListener('change', actualizarVista);
    radioAsignados.addEventListener('change', actualizarVista);

    // Ejecutar al cargar la página
    window.onload = actualizarVista;
</script>

</body>
</html>
