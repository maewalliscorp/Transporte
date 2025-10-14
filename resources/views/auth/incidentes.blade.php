<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Incidentes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

@include('layouts.menuPrincipal')

<div class="container mt-4">
    <h4 class="mb-3">Gestión de Incidentes</h4>

    <!-- Filtro para seleccionar tipo -->
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
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Registro de Incidentes</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalIncidente">
                <i class="bi bi-plus-circle"></i> Registrar Incidente
            </button>
        </div>

        <!-- Tabla registro -->
        <table class="table table-striped table-hover">
            <thead class="table-dark">
            <tr>
                <th>Unidad</th>
                <th>Operador</th>
                <th>Ruta</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Descripción</th>
                <th>Estado</th>
            </tr>
            </thead>
            <tbody>
            @isset($incidentes)
                @foreach($incidentes as $incidente)
                    <tr>
                        <td>{{ $incidente['placa'] ?? 'N/A' }}</td>
                        <td>{{ $incidente['licencia'] ?? 'N/A' }}</td>
                        <td>{{ $incidente['origen'] ?? 'N/A' }} - {{ $incidente['destino'] ?? 'N/A' }}</td>
                        <td>{{ $incidente['fecha'] }}</td>
                        <td>{{ $incidente['hora'] }}</td>
                        <td>{{ $incidente['descripcion'] }}</td>
                        <td>
                            @if($incidente['estado'] == 'pendiente')
                                <span class="badge bg-warning">Pendiente</span>
                            @else
                                <span class="badge bg-success">Solucionado</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center text-muted">No hay incidentes registrados</td>
                </tr>
            @endisset
            </tbody>
        </table>
    </div>

    <!-- SOLUCIÓN DE INCIDENTES -->
    <div id="seccionSolucion" style="display: none;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Solución de Incidentes</h5>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalSolucion">
                <i class="bi bi-check-circle"></i> Asignar Solución
            </button>
        </div>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
            <tr>
                <th>Unidad</th>
                <th>Operador</th>
                <th>Ruta</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Descripción</th>
                <th>Solución</th>
                <th>Estado</th>
            </tr>
            </thead>
            <tbody>
            @isset($incidentesPendientes)
                @foreach($incidentesPendientes as $incidente)
                    <tr>
                        <td>{{ $incidente['placa'] ?? 'N/A' }}</td>
                        <td>{{ $incidente['licencia'] ?? 'N/A' }}</td>
                        <td>{{ $incidente['origen'] ?? 'N/A' }} - {{ $incidente['destino'] ?? 'N/A' }}</td>
                        <td>{{ $incidente['fecha'] }}</td>
                        <td>{{ $incidente['hora'] }}</td>
                        <td>{{ $incidente['descripcion'] }}</td>
                        <td>{{ $incidente['solucion'] ?? 'Sin solución' }}</td>
                        <td>
                            <span class="badge bg-warning">Pendiente</span>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center text-muted">No hay incidentes pendientes</td>
                </tr>
            @endisset
            </tbody>
        </table>
    </div>

    <!-- HISTORIAL DE INCIDENTES -->
    <div id="seccionHistorial" style="display: none;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Historial de Incidentes</h5>
            <div class="col-md-4">
                <label for="unidadHistorial" class="form-label">Filtrar por Unidad</label>
                <select id="unidadHistorial" class="form-select" onchange="filtrarHistorial()">
                    <option value="">Todas las unidades</option>
                    @isset($unidades)
                        @foreach($unidades as $unidad)
                            <option value="{{ $unidad['id_unidad'] }}">
                                {{ $unidad['placa'] }} - {{ $unidad['modelo'] }}
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>
        </div>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
            <tr>
                <th>Unidad</th>
                <th>Operador</th>
                <th>Ruta</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Descripción</th>
                <th>Solución</th>
                <th>Estado</th>
            </tr>
            </thead>
            <tbody id="tablaHistorial">
            @isset($historialIncidentes)
                @foreach($historialIncidentes as $incidente)
                    <tr data-unidad="{{ $incidente['id_unidad'] }}">
                        <td>{{ $incidente['placa'] ?? 'N/A' }}</td>
                        <td>{{ $incidente['licencia'] ?? 'N/A' }}</td>
                        <td>{{ $incidente['origen'] ?? 'N/A' }} - {{ $incidente['destino'] ?? 'N/A' }}</td>
                        <td>{{ $incidente['fecha'] }}</td>
                        <td>{{ $incidente['hora'] }}</td>
                        <td>{{ $incidente['descripcion'] }}</td>
                        <td>{{ $incidente['solucion'] ?? 'Sin solución' }}</td>
                        <td>
                            @if($incidente['estado'] == 'pendiente')
                                <span class="badge bg-warning">Pendiente</span>
                            @else
                                <span class="badge bg-success">Solucionado</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center text-muted">No hay historial de incidentes</td>
                </tr>
            @endisset
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL PARA REGISTRAR INCIDENTE -->
<div class="modal fade" id="modalIncidente" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Registrar Incidente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formIncidente">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Asignación</label>
                            <select class="form-select" name="id_asignacion" required>
                                <option value="" selected disabled>Selecciona una asignación...</option>
                                @isset($asignaciones)
                                    @foreach($asignaciones as $asignacion)
                                        <option value="{{ $asignacion['id_asignacion'] }}">
                                            {{ $asignacion['placa'] }} - {{ $asignacion['licencia'] }} ({{ $asignacion['origen'] }} - {{ $asignacion['destino'] }})
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha del Incidente</label>
                            <input type="date" class="form-control" name="fecha" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hora del Incidente</label>
                            <input type="time" class="form-control" name="hora" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Descripción del Incidente</label>
                            <textarea class="form-control" name="descripcion" rows="4" placeholder="Describe detalladamente el incidente..." required></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarIncidente()">Guardar Incidente</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PARA ASIGNAR SOLUCIÓN -->
<div class="modal fade" id="modalSolucion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-check-circle me-2"></i>Asignar Solución</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formSolucion">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Incidente</label>
                            <select class="form-select" name="id_incidente" required>
                                <option value="" selected disabled>Selecciona un incidente...</option>
                                @isset($incidentesPendientes)
                                    @foreach($incidentesPendientes as $incidente)
                                        <option value="{{ $incidente['id_incidente'] }}">
                                            {{ $incidente['placa'] }} - {{ $incidente['fecha'] }} {{ $incidente['hora'] }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Solución del Incidente</label>
                            <textarea class="form-control" name="solucion" rows="4" placeholder="Describe la solución aplicada..." required></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="guardarSolucion()">Guardar Solución</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Cambiar entre secciones
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

    // Filtrar historial por unidad
    function filtrarHistorial() {
        const unidadId = document.getElementById('unidadHistorial').value;
        const filas = document.querySelectorAll('#tablaHistorial tr');

        filas.forEach(fila => {
            if (!unidadId || fila.getAttribute('data-unidad') === unidadId) {
                fila.style.display = '';
            } else {
                fila.style.display = 'none';
            }
        });
    }

    // Funciones para guardar (pendientes de implementar)
    function guardarIncidente() {
        alert('Funcionalidad para guardar incidente - Pendiente de implementar');
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalIncidente'));
        modal.hide();
    }

    function guardarSolucion() {
        alert('Funcionalidad para guardar solución - Pendiente de implementar');
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalSolucion'));
        modal.hide();
    }

    // Establecer fecha actual por defecto
    document.addEventListener('DOMContentLoaded', function() {
        const now = new Date();
        const fechaActual = now.toISOString().split('T')[0];
        document.querySelectorAll('input[type="date"]').forEach(input => {
            if (!input.value) {
                input.value = fechaActual;
            }
        });
    });
</script>
</body>
</html>
