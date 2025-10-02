<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Mantenimientos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Usa Bootstrap solo si tu menú no lo tiene --}}
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
</head>
<body>

<!-- Menú principal -->
@include('layouts.menuPrincipal')

<div class="container mt-4">
    <h4 class="mb-3">Gestión de Mantenimientos</h4>

    <!-- Filtro de secciones -->
    <div class="mb-4">
        <label class="form-label me-3">Selecciona el módulo:</label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="vista" id="vistaProgramacion" value="programacion" checked>
            <label class="form-check-label" for="vistaProgramacion">Programación</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="vista" id="vistaRealizados" value="realizados">
            <label class="form-check-label" for="vistaRealizados">Realizados</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="vista" id="vistaAlertas" value="alertas">
            <label class="form-check-label" for="vistaAlertas">Alertas</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="vista" id="vistaHistorial" value="historial">
            <label class="form-check-label" for="vistaHistorial">Historial</label>
        </div>
    </div>

    <!-- PROGRAMACIÓN DE MANTENIMIENTO -->
    <div id="seccionProgramacion">
        <h5>Programación de Mantenimiento</h5>
        <div class="mb-3">
            <a href="disponibilidadUnidades.php" class="btn btn-info">Ir a tabla de disponibilidad</a>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <label class="form-label">Unidad</label>
                <select class="form-select"></select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tipo de Mantenimiento</label>
                <select class="form-select">
                    <option value="preventivo">Preventivo</option>
                    <option value="correctivo">Correctivo</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Fecha Programada</label>
                <input type="date" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Motivo</label>
                <input type="text" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Kilometraje Actual</label>
                <input type="number" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Operador</label>
                <select class="form-select"></select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Estado de la Unidad</label>
                <input type="text" class="form-control">
            </div>
            <div class="col-md-12">
                <button class="btn btn-primary mt-2">Ingresar Datos</button>
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="table-light">
            <tr>
                <th>Unidad</th>
                <th>Tipo</th>
                <th>Fecha</th>
                <th>Motivo</th>
                <th>Kilometraje</th>
                <th>Operador</th>
                <th>Estado</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- MANTENIMIENTOS REALIZADOS -->
    <div id="seccionRealizados" style="display: none;">
        <h5>Mantenimientos Realizados</h5>
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <label class="form-label">Unidad</label>
                <select class="form-select"></select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Fecha de Mantenimiento</label>
                <input type="date" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tipo de Mantenimiento</label>
                <select class="form-select">
                    <option value="preventivo">Preventivo</option>
                    <option value="correctivo">Correctivo</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Descripción</label>
                <input type="text" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Piezas Reemplazadas</label>
                <input type="text" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Operador</label>
                <select class="form-select"></select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Costo</label>
                <input type="number" class="form-control" step="0.01">
            </div>
            <div class="col-md-3">
                <label class="form-label">Observaciones</label>
                <input type="text" class="form-control">
            </div>
            <div class="col-md-12">
                <button class="btn btn-primary mt-2">Ingresar Datos</button>
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="table-light">
            <tr>
                <th>Unidad</th>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Descripción</th>
                <th>Piezas</th>
                <th>Operador</th>
                <th>Costo</th>
                <th>Observaciones</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- ALERTAS DE MANTENIMIENTO -->
    <div id="seccionAlertas" style="display: none;">
        <h5>Alertas de Mantenimiento</h5>
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <label class="form-label">Unidad</label>
                <select class="form-select"></select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Kilómetro Actual</label>
                <input type="number" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Fecha del Último Mantenimiento</label>
                <input type="date" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Km para Próximo Mantenimiento</label>
                <input type="number" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Fecha del Próximo Mantenimiento</label>
                <input type="date" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Incidentes Reportados</label>
                <input type="text" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Estado de Alerta</label>
                <select class="form-select">
                    <option style="color:red;" value="urgente">Revisión Urgente</option>
                    <option style="color:orange;" value="proximo">Próximo</option>
                    <option style="color:yellow;" value="pendiente">Pendiente</option>
                </select>
            </div>
            <div class="col-md-12">
                <button class="btn btn-primary mt-2">Ingresar Alerta</button>
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="table-light">
            <tr>
                <th>Unidad</th>
                <th>Kilómetro Actual</th>
                <th>Fecha Último</th>
                <th>Km Próximo</th>
                <th>Fecha Próximo</th>
                <th>Incidentes</th>
                <th>Estado</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- HISTORIAL DE MANTENIMIENTO -->
    <div id="seccionHistorial" style="display: none;">
        <h5>Historial de Mantenimiento</h5>
        <div class="mb-3">
            <label class="form-label">Seleccionar Unidad</label>
            <select class="form-select" id="unidadHistorial">
                <option selected disabled>Selecciona unidad</option>
            </select>
        </div>
        <table class="table table-bordered">
            <thead class="table-light">
            <tr>
                <th>Unidad</th>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Descripción</th>
                <th>Piezas</th>
                <th>Kilometraje</th>
                <th>Costo</th>
                <th>Observaciones</th>
                <th>Estado Unidad</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

</div>

<!-- Bootstrap JS (si es necesario) -->
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> --}}

<!-- Script para cambiar secciones -->
<script>
    const secciones = {
        programacion: document.getElementById('seccionProgramacion'),
        realizados: document.getElementById('seccionRealizados'),
        alertas: document.getElementById('seccionAlertas'),
        historial: document.getElementById('seccionHistorial')
    };

    document.querySelectorAll('input[name="vista"]').forEach(radio => {
        radio.addEventListener('change', () => {
            for (const key in secciones) {
                secciones[key].style.display = (radio.value === key) ? 'block' : 'none';
            }
        });
    });

    window.onload = () => {
        document.getElementById('vistaProgramacion').checked = true;
    };
</script>

</body>
</html>

