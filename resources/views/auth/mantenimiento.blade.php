<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Mantenimientos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
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
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Programación de Mantenimiento</h5>
            <div>
                <a href="{{ route('inicio') }}" class="btn btn-info me-2">
                    <i class="bi bi-table"></i> Ir a tabla de disponibilidad
                </a>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalProgramacion">
                    <i class="bi bi-plus-circle"></i> Programar Mantenimiento
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover display nowrap" id="tablaProgramacion" style="width:100%">
                <thead class="table-dark">
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
                <tbody>
                @isset($mantenimientosProgramados)
                    @foreach($mantenimientosProgramados as $mantenimiento)
                        <tr>
                            <td>{{ $mantenimiento['placa'] ?? 'N/A' }} - {{ $mantenimiento['modelo'] ?? 'N/A' }}</td>
                            <td>{{ $mantenimiento['motivo'] ?? 'N/A' }}</td>
                            <td>{{ $mantenimiento['fecha_programada'] ?? 'N/A' }}</td>
                            <td>{{ $mantenimiento['motivo'] ?? 'N/A' }}</td>
                            <td>{{ isset($mantenimiento['kmActual']) ? number_format($mantenimiento['kmActual'], 0) . ' km' : 'N/A' }}</td>
                            <td>{{ $mantenimiento['licencia'] ?? 'N/A' }}</td>
                            <td>
                                @if(isset($mantenimiento['estado']))
                                    <span class="badge bg-warning">{{ ucfirst($mantenimiento['estado']) }}</span>
                                @else
                                    <span class="badge bg-secondary">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center text-muted">No hay mantenimientos programados</td>
                    </tr>
                @endisset
                </tbody>
            </table>
        </div>
    </div>

    <!-- MANTENIMIENTOS REALIZADOS -->
    <div id="seccionRealizados" style="display: none;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Mantenimientos Realizados</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRealizados">
                <i class="bi bi-plus-circle"></i> Registrar Mantenimiento Realizado
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover display nowrap" id="tablaRealizados" style="width:100%">
                <thead class="table-dark">
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
                <tbody>
                @isset($mantenimientosRealizados)
                    @foreach($mantenimientosRealizados as $mantenimiento)
                        <tr>
                            <td>{{ $mantenimiento['placa'] ?? 'N/A' }} - {{ $mantenimiento['modelo'] ?? 'N/A' }}</td>
                            <td>{{ $mantenimiento['fecha'] ?? 'N/A' }}</td>
                            <td>{{ $mantenimiento['descripcion'] ?? 'N/A' }}</td>
                            <td>{{ $mantenimiento['descripcion'] ?? 'N/A' }}</td>
                            <td>N/A</td>
                            <td>{{ $mantenimiento['licencia'] ?? 'N/A' }}</td>
                            <td>N/A</td>
                            <td>{{ $mantenimiento['descripcion'] ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" class="text-center text-muted">No hay mantenimientos realizados</td>
                    </tr>
                @endisset
                </tbody>
            </table>
        </div>
    </div>

    <!-- ALERTAS DE MANTENIMIENTO -->
    <div id="seccionAlertas" style="display: none;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Alertas de Mantenimiento</h5>
            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalAlertas">
                <i class="bi bi-plus-circle"></i> Crear Alerta
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover display nowrap" id="tablaAlertas" style="width:100%">
                <thead class="table-dark">
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
                <tbody>
                @isset($alertasMantenimiento)
                    @foreach($alertasMantenimiento as $alerta)
                        <tr>
                            <td>{{ $alerta['placa'] ?? 'N/A' }} - {{ $alerta['modelo'] ?? 'N/A' }}</td>
                            <td>{{ isset($alerta['kmActual']) ? number_format($alerta['kmActual'], 0) . ' km' : 'N/A' }}</td>
                            <td>{{ $alerta['fechaUltimoMantenimiento'] ?? 'N/A' }}</td>
                            <td>{{ isset($alerta['kmProxMantenimiento']) ? number_format($alerta['kmProxMantenimiento'], 0) . ' km' : 'N/A' }}</td>
                            <td>{{ $alerta['fechaProxMantenimiento'] ?? 'N/A' }}</td>
                            <td>{{ $alerta['incidenteReportado'] ?? 'Ninguno' }}</td>
                            <td>
                                @if(isset($alerta['estadoAlerta']))
                                    @if($alerta['estadoAlerta'] == 'urgente')
                                        <span class="badge bg-danger">Urgente</span>
                                    @elseif($alerta['estadoAlerta'] == 'proximo')
                                        <span class="badge bg-warning">Próximo</span>
                                    @else
                                        <span class="badge bg-info">Pendiente</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center text-muted">No hay alertas de mantenimiento</td>
                    </tr>
                @endisset
                </tbody>
            </table>
        </div>
    </div>

    <!-- HISTORIAL DE MANTENIMIENTO -->
    <div id="seccionHistorial" style="display: none;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Historial de Mantenimiento</h5>
        </div>
        <div class="mb-3">
            <label class="form-label">Seleccionar Unidad</label>
            <select class="form-select" id="unidadHistorial">
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
        <div class="table-responsive">
            <table class="table table-striped table-hover display nowrap" id="tablaHistorial" style="width:100%">
                <thead class="table-dark">
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
                <tbody>
                @isset($historialMantenimiento)
                    @foreach($historialMantenimiento as $mantenimiento)
                        <tr>
                            <td>{{ $mantenimiento['placa'] ?? 'N/A' }} - {{ $mantenimiento['modelo'] ?? 'N/A' }}</td>
                            <td>{{ $mantenimiento['fecha'] ?? 'N/A' }}</td>
                            <td>{{ $mantenimiento['descripcion'] ?? 'N/A' }}</td>
                            <td>{{ $mantenimiento['descripcion'] ?? 'N/A' }}</td>
                            <td>N/A</td>
                            <td>{{ isset($mantenimiento['kmActual']) ? number_format($mantenimiento['kmActual'], 0) . ' km' : 'N/A' }}</td>
                            <td>N/A</td>
                            <td>{{ $mantenimiento['descripcion'] ?? 'N/A' }}</td>
                            <td>
                                @if(isset($mantenimiento['estado']))
                                    <span class="badge bg-success">{{ ucfirst($mantenimiento['estado']) }}</span>
                                @else
                                    <span class="badge bg-secondary">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="9" class="text-center text-muted">No hay historial de mantenimiento</td>
                    </tr>
                @endisset
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Programación -->
<div class="modal fade" id="modalProgramacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-clipboard-check me-2"></i>Programar Mantenimiento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formProgramacion">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Unidad</label>
                            <select class="form-select" name="unidad" required>
                                <option value="" selected disabled>Selecciona una unidad...</option>
                                @isset($unidades)
                                    @foreach($unidades as $unidad)
                                        <option value="{{ $unidad['id_unidad'] }}">
                                            {{ $unidad['placa'] }} - {{ $unidad['modelo'] }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tipo de Mantenimiento</label>
                            <select class="form-select" name="tipo_mantenimiento" required>
                                <option value="preventivo">Preventivo</option>
                                <option value="correctivo">Correctivo</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha Programada</label>
                            <input type="date" class="form-control" name="fecha_programada" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Motivo</label>
                            <input type="text" class="form-control" name="motivo" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kilometraje Actual</label>
                            <input type="number" class="form-control" name="kmActual" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Operador</label>
                            <select class="form-select" name="operador" required>
                                <option value="" selected disabled>Selecciona un operador...</option>
                                @isset($operadores)
                                    @foreach($operadores as $operador)
                                        <option value="{{ $operador['id_operator'] }}">
                                            {{ $operador['licencia'] }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Estado de la Unidad</label>
                            <input type="text" class="form-control" name="estado_unidad" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" onclick="procesarProgramacion()">
                    <i class="bi bi-check-circle me-1"></i>Guardar Programación
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Realizados -->
<div class="modal fade" id="modalRealizados" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-clipboard-check me-2"></i>Registrar Mantenimiento Realizado
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formRealizados">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Unidad</label>
                            <select class="form-select" name="unidad" required>
                                <option value="" selected disabled>Selecciona una unidad...</option>
                                @isset($unidades)
                                    @foreach($unidades as $unidad)
                                        <option value="{{ $unidad['id_unidad'] }}">
                                            {{ $unidad['placa'] }} - {{ $unidad['modelo'] }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Mantenimiento</label>
                            <input type="date" class="form-control" name="fecha_mantenimiento" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tipo de Mantenimiento</label>
                            <select class="form-select" name="tipo_mantenimiento" required>
                                <option value="preventivo">Preventivo</option>
                                <option value="correctivo">Correctivo</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Descripción</label>
                            <input type="text" class="form-control" name="descripcion" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Piezas Reemplazadas</label>
                            <input type="text" class="form-control" name="piezas_reemplazadas">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Operador</label>
                            <select class="form-select" name="operador" required>
                                <option value="" selected disabled>Selecciona un operador...</option>
                                @isset($operadores)
                                    @foreach($operadores as $operador)
                                        <option value="{{ $operador['id_operator'] }}">
                                            {{ $operador['licencia'] }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Costo</label>
                            <input type="number" class="form-control" name="costo" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Observaciones</label>
                            <input type="text" class="form-control" name="observaciones">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kilometraje Actual</label>
                            <input type="number" class="form-control" name="kmActual" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" onclick="procesarRealizado()">
                    <i class="bi bi-check-circle me-1"></i>Guardar Mantenimiento
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Alertas -->
<div class="modal fade" id="modalAlertas" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-clipboard-check me-2"></i>Crear Alerta de Mantenimiento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formAlertas">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Unidad</label>
                            <select class="form-select" name="unidad" required>
                                <option value="" selected disabled>Selecciona una unidad...</option>
                                @isset($unidades)
                                    @foreach($unidades as $unidad)
                                        <option value="{{ $unidad['id_unidad'] }}">
                                            {{ $unidad['placa'] }} - {{ $unidad['modelo'] }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kilómetro Actual</label>
                            <input type="number" class="form-control" name="km_actual" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha del Último Mantenimiento</label>
                            <input type="date" class="form-control" name="fechaUltimoMantenimiento" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Km para Próximo Mantenimiento</label>
                            <input type="number" class="form-control" name="kmProxMantenimiento" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha del Próximo Mantenimiento</label>
                            <input type="date" class="form-control" name="fechaProxMantenimiento" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Incidentes Reportados</label>
                            <input type="text" class="form-control" name="incidenteReportado">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado de Alerta</label>
                            <select class="form-select" name="estadoAlerta" required>
                                <option style="color:red;" value="urgente">Revisión Urgente</option>
                                <option style="color:orange;" value="proximo">Próximo</option>
                                <option style="color:yellow;" value="pendiente">Pendiente</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" onclick="procesarAlerta()">
                    <i class="bi bi-check-circle me-1"></i>Guardar Alerta
                </button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery + Bootstrap + DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
    // Variables globales para las DataTables
    let tableProgramacion, tableRealizados, tableAlertas, tableHistorial;

    $(document).ready(function() {
        // Configuración común para DataTables
        const configComun = {
            language: {
                "decimal": "",
                "emptyTable": "No hay datos disponibles en la tabla",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
                "infoFiltered": "(filtrado de _MAX_ entradas totales)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ entradas por página",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "No se encontraron registros coincidentes",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "aria": {
                    "sortAscending": ": activar para ordenar columna ascendente",
                    "sortDescending": ": activar para ordenar columna descendente"
                }
            },
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            responsive: true,
            autoWidth: false,
            order: [[2, 'desc']], // Ordenar por fecha descendente por defecto
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
        };

        // Inicializar DataTables para cada sección
        tableProgramacion = $('#tablaProgramacion').DataTable(configComun);
        tableRealizados = $('#tablaRealizados').DataTable(configComun);
        tableAlertas = $('#tablaAlertas').DataTable(configComun);
        tableHistorial = $('#tablaHistorial').DataTable(configComun);

        // Configurar filtro por unidad en el historial
        $('#unidadHistorial').on('change', function() {
            tableHistorial.column(0).search(this.value).draw();
        });
    });

    // Cambiar entre secciones
    $('input[name="vista"]').change(function() {
        actualizarVista();
    });

    function actualizarVista() {
        $('#seccionProgramacion').toggle($('#vistaProgramacion').is(':checked'));
        $('#seccionRealizados').toggle($('#vistaRealizados').is(':checked'));
        $('#seccionAlertas').toggle($('#vistaAlertas').is(':checked'));
        $('#seccionHistorial').toggle($('#vistaHistorial').is(':checked'));

        // Redibujar las tablas cuando se muestren
        setTimeout(() => {
            if (tableProgramacion) tableProgramacion.draw();
            if (tableRealizados) tableRealizados.draw();
            if (tableAlertas) tableAlertas.draw();
            if (tableHistorial) tableHistorial.draw();
        }, 100);
    }

    // Función para procesar programación
    function procesarProgramacion() {
        const formData = new FormData(document.getElementById('formProgramacion'));

        // Validar que todos los campos estén llenos
        let valid = true;
        for (let [key, value] of formData.entries()) {
            if (!value) {
                alert('Por favor completa todos los campos obligatorios.');
                valid = false;
                break;
            }
        }

        if (!valid) return;

        // Aquí iría tu petición AJAX para guardar en la base de datos
        console.log('Datos de programación:', Object.fromEntries(formData));

        alert('Mantenimiento programado correctamente');
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalProgramacion'));
        modal.hide();
        document.getElementById('formProgramacion').reset();

        // Aquí podrías recargar la tabla o agregar el nuevo registro
    }

    // Función para procesar mantenimiento realizado
    function procesarRealizado() {
        const formData = new FormData(document.getElementById('formRealizados'));

        // Validar que todos los campos obligatorios estén llenos
        let valid = true;
        for (let [key, value] of formData.entries()) {
            if (key !== 'piezas_reemplazadas' && key !== 'observaciones' && !value) {
                alert('Por favor completa todos los campos obligatorios.');
                valid = false;
                break;
            }
        }

        if (!valid) return;

        // Aquí iría tu petición AJAX para guardar en la base de datos
        console.log('Datos de mantenimiento realizado:', Object.fromEntries(formData));

        alert('Mantenimiento registrado correctamente');
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalRealizados'));
        modal.hide();
        document.getElementById('formRealizados').reset();

        // Aquí podrías recargar la tabla o agregar el nuevo registro
    }

    // Función para procesar alerta
    function procesarAlerta() {
        const formData = new FormData(document.getElementById('formAlertas'));

        // Validar que todos los campos obligatorios estén llenos
        let valid = true;
        for (let [key, value] of formData.entries()) {
            if (key !== 'incidenteReportado' && !value) {
                alert('Por favor completa todos los campos obligatorios.');
                valid = false;
                break;
            }
        }

        if (!valid) return;

        // Aquí iría tu petición AJAX para guardar en la base de datos
        console.log('Datos de alerta:', Object.fromEntries(formData));

        alert('Alerta registrada correctamente');
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalAlertas'));
        modal.hide();
        document.getElementById('formAlertas').reset();

        // Aquí podrías recargar la tabla o agregar el nuevo registro
    }

    // Limpiar formularios cuando se cierran los modales
    $('#modalProgramacion').on('hidden.bs.modal', function () {
        $('#formProgramacion')[0].reset();
    });

    $('#modalRealizados').on('hidden.bs.modal', function () {
        $('#formRealizados')[0].reset();
    });

    $('#modalAlertas').on('hidden.bs.modal', function () {
        $('#formAlertas')[0].reset();
    });

    // Inicializar vista al cargar
    $(window).on('load', function() {
        actualizarVista();
    });
</script>

</body>
</html>
