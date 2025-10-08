<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Incidentes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<!--  Menú -->
@include('layouts.menuPrincipal')

<div class="container mt-4">
    <h4 class="mb-3">Gestión de Incidentes</h4>

    <!--  Filtro para seleccionar tipo -->
    <div class="mb-4">
        <label class="form-label me-3">Selecciona tipo de vista:</label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tipoVista" id="vistaRegistro" value="registro" checked>
            <label class="form-check-label" for="vistaRegistro">Incidentes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tipoVista" id="vistaSolucion" value="solucion">
            <label class="form-check-label" for="vistaSolucion">Solución de Incidentes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tipoVista" id="vistaHistorial" value="historial">
            <label class="form-check-label" for="vistaHistorial">Historial de Incidentes</label>
        </div>
    </div>

    <!-- REGISTRO DE INCIDENTES -->
    <div id="seccionRegistro">
        <div class="row g-3 mb-3">
            <!-- Unidad -->
            <div class="col-md-4">
                <label for="unidad" class="form-label">Unidad de Transporte</label>
                <select id="unidad" class="form-select">
                    <option selected disabled>Selecciona unidad</option>
                </select>
            </div>
            <!-- Operador -->
            <div class="col-md-4">
                <label for="operador" class="form-label">Operador</label>
                <select id="operador" class="form-select">
                    <option selected disabled>Selecciona operador</option>
                </select>
            </div>
            <!-- Ruta -->
            <div class="col-md-4">
                <label for="ruta" class="form-label">Ruta</label>
                <select id="ruta" class="form-select">
                    <option selected disabled>Selecciona ruta</option>
                </select>
            </div>

            <!-- Hora -->
            <div class="col-md-4">
                <label for="hora" class="form-label">Hora del Incidente</label>
                <input type="time" id="hora" class="form-control">
            </div>

            <!-- Fecha -->
            <div class="col-md-4">
                <label for="fecha" class="form-label">Fecha del Incidente</label>
                <input type="date" id="fecha" class="form-control">
            </div>

            <!-- Detalles -->
            <div class="col-md-8">
                <label for="detalles" class="form-label">Detalles del Incidente</label>
                <textarea id="detalles" class="form-control" rows="2" placeholder="Describe el incidente..."></textarea>
            </div>

            <!-- Botón Asignar -->
            <div class="col-12 mt-3">
                <button class="btn btn-primary">Asignar Incidente</button>
            </div>
        </div>

        <!-- Tabla registro -->
        <table class="table table-bordered mt-3">
            <thead class="table-light">
            <tr>
                <th>Unidad</th>
                <th>Operador</th>
                <th>Ruta</th>
                <th>Hora</th>
                <th>Fecha</th>
                <th>Detalles</th>
            </tr>
            <tr>
                <td>Unidad 12</td>
                <td>Laura Iglesias</td>
                <td>Ruta A</td>
                <td>08:00 AM</td>
                <th>02/05/2025</th>
                <th>Focos fundidos</th>

            </tr>
            </thead>
            <tbody>
            <!-- Incidentes registrados aquí -->
            </tbody>
        </table>
    </div>

    <!--  SOLUCIÓN DE INCIDENTES -->
    <div id="seccionSolucion" style="display: none;">
        <div class="mb-3">
            <label for="textoSolucion" class="form-label">Solución del Incidente</label>
            <textarea id="textoSolucion" class="form-control" rows="2" placeholder="Describe la solución..."></textarea>
        </div>
        <button class="btn btn-success mb-3">Asignar Solución</button>

        <table class="table table-bordered">
            <thead class="table-light">
            <tr>
                <th>Unidad</th>
                <th>Operador</th>
                <th>Ruta</th>
                <th>Hora</th>
                <th>Fecha</th>
                <th>Detalles</th>
                <th>Solución</th>
            </tr>
            <tr>
                <td>Unidad 12</td>
                <td>Laura Iglesias</td>
                <td>Ruta A</td>
                <td>08:00 AM</td>
                <th>02/05/2025</th>
                <th>Focos fundidos</th>
                <th>Cambiar focos :)</th>
            </tr>
            </thead>
            <tbody>
            <!-- Incidentes con solución -->
            </tbody>
        </table>
    </div>

    <!--  HISTORIAL DE INCIDENTES -->
    <div id="seccionHistorial" style="display: none;">
        <div class="mb-3">
            <label for="unidadHistorial" class="form-label">Unidad de Transporte</label>
            <select id="unidadHistorial" class="form-select">
                <option selected disabled>Selecciona unidad</option>
            </select>
        </div>

        <table class="table table-bordered">
            <thead class="table-light">
            <tr>
                <th>Unidad</th>
                <th>Operador</th>
                <th>Ruta</th>
                <th>Hora</th>
                <th>Fecha</th>
                <th>Detalles</th>
                <th>Solución</th>
            </tr>
            <tr>
                <td>Unidad 12</td>
                <td>Laura Iglesias</td>
                <td>Ruta A</td>
                <td>08:00 AM</td>
                <th>02/05/2025</th>
                <th>Focos fundidos</th>
                <th>Cambiar focos :)</th>
            </tr>
            </thead>
            <tbody>
            <!-- Historial completo -->
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script para alternar vistas -->
<script>
    const radioRegistro = document.getElementById('vistaRegistro');
    const radioSolucion = document.getElementById('vistaSolucion');
    const radioHistorial = document.getElementById('vistaHistorial');

    const seccionRegistro = document.getElementById('seccionRegistro');
    const seccionSolucion = document.getElementById('seccionSolucion');
    const seccionHistorial = document.getElementById('seccionHistorial');

    function actualizarVista() {
        seccionRegistro.style.display = radioRegistro.checked ? 'block' : 'none';
        seccionSolucion.style.display = radioSolucion.checked ? 'block' : 'none';
        seccionHistorial.style.display = radioHistorial.checked ? 'block' : 'none';
    }

    radioRegistro.addEventListener('change', actualizarVista);
    radioSolucion.addEventListener('change', actualizarVista);
    radioHistorial.addEventListener('change', actualizarVista);

    window.onload = actualizarVista;
</script>
</body>
</html>


