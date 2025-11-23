<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Incidentes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="{{ asset('build/assets/estilos.css')}}">

    <style>
        /* Estilos para las columnas de Descripción y Solución */
        .descripcion-col, .solucion-col {
            min-width: 200px;
            max-width: 300px;
            word-wrap: break-word;
            word-break: break-word;
            white-space: normal;
            line-height: 1.4;
        }

        .texto-largo {
            white-space: normal;
            word-wrap: break-word;
            word-break: break-word;
            line-height: 1.4;
        }

        /* Asegurar que las acciones se mantengan visibles */
        .table-actions {
            min-width: 180px;
            white-space: nowrap;
        }

        /* Estilos para el icono de expandir */
        table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control:before,
        table.dataTable.dtr-inline.collapsed > tbody > tr > th.dtr-control:before {
            background-color: #0d6efd;
            border-radius: 50%;
            color: white;
            content: "+";
            font-family: Arial, sans-serif;
            font-weight: bold;
            height: 1.2em;
            line-height: 1.2em;
            text-align: center;
            width: 1.2em;
            box-shadow: 0 0 3px #444;
        }

        table.dataTable.dtr-inline.collapsed > tbody > tr.parent > td.dtr-control:before,
        table.dataTable.dtr-inline.collapsed > tbody > tr.parent > th.dtr-control:before {
            background-color: #d33333;
            content: "-";
        }

        /* Estilos para los detalles expandidos */
        .dtr-details {
            width: 100%;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .dtr-details li {
            border-bottom: 1px solid #eee;
            padding: 8px 0;
            display: flex;
        }

        .dtr-details .dtr-title {
            font-weight: bold;
            min-width: 120px;
            display: inline-block;
        }

        .dtr-details .dtr-data {
            flex: 1;
        }

        /* Mejorar la visualización en dispositivos móviles */
        @media (max-width: 768px) {
            .descripcion-col, .solucion-col {
                min-width: 150px;
                max-width: 200px;
            }

            .table-actions {
                min-width: 140px;
            }

            .dtr-details .dtr-title {
                min-width: 100px;
            }
        }

        /* Forzar el salto de línea en las celdas de texto largo */
        .force-break {
            white-space: pre-line !important;
            word-wrap: break-word !important;
            word-break: break-word !important;
        }
    </style>
</head>
<body>

@include('layouts.menuPrincipal')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-exclamation-triangle me-2"></i>Gestión de Incidentes</h1>
        <button class="btn btn-primary" id="btnRegistrarIncidente" data-bs-toggle="modal" data-bs-target="#modalAgregarIncidente">
            <i class="bi bi-plus-circle"></i> Registrar Incidente
        </button>
    </div>

    <!-- Filtro para seleccionar tipo -->
    <div class="mb-4">
        <label class="form-label me-3">Selecciona tipo de vista:</label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tipoVista" id="vistaRegistro" value="registro" checked>
            <label class="form-check-label" for="vistaRegistro">Todos los Incidentes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tipoVista" id="vistaSolucion" value="solucion">
            <label class="form-check-label" for="vistaSolucion">Solución de Incidentes</label>
        </div>
    </div>

    <!-- REGISTRO DE INCIDENTES -->
    <div id="seccionRegistro">
        <!-- Tabla registro con DataTable -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover display nowrap" id="tablaIncidentes" style="width:100%">
                        <thead class="table-primary">
                        <tr>
                            <th></th> <!-- Columna para el icono de expandir -->
                            <th>Unidad</th>
                            <th class="descripcion-col">Descripción</th>
                            <th class="solucion-col">Solución</th>
                            <th>Estado</th>
                            <th class="table-actions">Acciones</th>
                            <!-- Columnas ocultas para responsive -->
                            <th>ID</th>
                            <th>Operador</th>
                            <th>Ruta</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($incidentes)
                            @foreach($incidentes as $incidente)
                                <tr id="fila-{{ $incidente['id_incidente'] }}">
                                    <td></td> <!-- Celda para el icono de expandir -->
                                    <td>{{ $incidente['placa'] ?? 'N/A' }}</td>
                                    <td class="texto-largo descripcion-col force-break">{{ $incidente['descripcion'] }}</td>
                                    <td class="texto-largo solucion-col force-break">{{ $incidente['solucion'] ? $incidente['solucion'] : 'Sin solución' }}</td>
                                    <td>
                                        @if($incidente['estado'] == 'pendiente')
                                            <span class="badge bg-warning">Pendiente</span>
                                        @else
                                            <span class="badge bg-success">Resuelto</span>
                                        @endif
                                    </td>
                                    <td class="table-actions">
                                        @if($incidente['estado'] == 'pendiente')
                                            <button class="btn btn-outline-warning btn-sm" onclick="editarIncidente({{ $incidente['id_incidente'] }})">
                                                <i class="bi bi-pencil"></i> Editar
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm" onclick="eliminarIncidente({{ $incidente['id_incidente'] }}, event)">
                                                <i class="bi bi-trash"></i> Eliminar
                                            </button>
                                        @else
                                            <button class="btn btn-outline-warning btn-sm" onclick="editarIncidente({{ $incidente['id_incidente'] }})">
                                                <i class="bi bi-pencil"></i> Editar
                                            </button>
                                            <button class="btn btn-outline-info btn-sm" onclick="editarSolucion({{ $incidente['id_incidente'] }})">
                                                <i class="bi bi-pencil"></i> Editar Sol.
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm" onclick="eliminarIncidente({{ $incidente['id_incidente'] }}, event)">
                                                <i class="bi bi-trash"></i> Eliminar
                                            </button>
                                        @endif
                                    </td>
                                    <!-- Datos para responsive -->
                                    <td>{{ $incidente['id_incidente'] }}</td>
                                    <td>{{ $incidente['licencia'] ?? 'N/A' }}</td>
                                    <td>{{ $incidente['origen'] ?? 'N/A' }} - {{ $incidente['destino'] ?? 'N/A' }}</td>
                                    <td>{{ $incidente['fecha'] }}</td>
                                    <td>{{ $incidente['hora'] }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="11" class="text-center text-muted">No hay incidentes registrados</td>
                            </tr>
                        @endisset
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- SOLUCIÓN DE INCIDENTES -->
    <div id="seccionSolucion" style="display: none;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Solución de Incidentes</h5>
        </div>

        <!-- Tabla solución con DataTable RESPONSIVE -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover display nowrap" id="tablaSolucion" style="width:100%">
                        <thead class="table-primary">
                        <tr>
                            <th></th> <!-- Columna para el icono de expandir -->
                            <th>ID</th>
                            <th>Unidad</th>
                            <th class="descripcion-col">Descripción</th>
                            <th class="solucion-col">Solución</th>
                            <th>Estado</th>
                            <th class="table-actions">Acciones</th>
                            <!-- Columnas ocultas para responsive -->
                            <th>Operador</th>

                            <th>Ruta</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($incidentesPendientes)
                            @foreach($incidentesPendientes as $incidente)
                                <tr id="fila-sol-{{ $incidente['id_incidente'] }}">
                                    <td></td> <!-- Celda para el icono de expandir -->
                                    <td>{{ $incidente['id_incidente'] }}</td>
                                    <td>{{ $incidente['placa'] ?? 'N/A' }}</td>
                                    <td class="texto-largo descripcion-col force-break">{{ $incidente['descripcion'] }}</td>
                                    <td class="texto-largo solucion-col force-break">{{ $incidente['solucion'] ? $incidente['solucion'] : 'Sin solución' }}</td>
                                    <td>
                                        <span class="badge bg-warning">Pendiente</span>
                                    </td>
                                    <td class="table-actions">
                                        <button class="btn btn-outline-success btn-sm" onclick="asignarSolucion({{ $incidente['id_incidente'] }})">
                                            <i class="bi bi-check-circle"></i> Solucionar
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" onclick="eliminarIncidente({{ $incidente['id_incidente'] }}, event)">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                    </td>
                                    <!-- Datos para responsive -->

                                    <td>{{ $incidente['licencia'] ?? 'N/A' }}</td>
                                    <td>{{ $incidente['origen'] ?? 'N/A' }} - {{ $incidente['destino'] ?? 'N/A' }}</td>
                                    <td>{{ $incidente['fecha'] }}</td>
                                    <td>{{ $incidente['hora'] }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="11" class="text-center text-muted">No hay incidentes pendientes</td>
                            </tr>
                        @endisset
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PARA AGREGAR INCIDENTE -->
<div class="modal fade" id="modalAgregarIncidente" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitulo">Registrar Incidente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formIncidente">
                @csrf
                <input type="hidden" id="incidenteId" name="id_incidente">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Asignación *</label>
                        <select class="form-select" id="id_asignacion" name="id_asignacion" required>
                            <option value="" selected disabled>Selecciona una asignación...</option>
                            @isset($asignaciones)
                                @foreach($asignaciones as $asignacion)
                                    <option value="{{ $asignacion['id_asignacion'] }}"
                                            data-placa="{{ $asignacion['placa'] ?? '' }}"
                                            data-licencia="{{ $asignacion['licencia'] ?? '' }}"
                                            data-ruta="{{ ($asignacion['origen'] ?? '') . ' - ' . ($asignacion['destino'] ?? '') }}">
                                        {{ $asignacion['placa'] ?? 'N/A' }} - {{ $asignacion['licencia'] ?? 'N/A' }}
                                        ({{ $asignacion['origen'] ?? 'N/A' }} - {{ $asignacion['destino'] ?? 'N/A' }})
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                        <div class="form-text">
                            Información de la asignación:
                            <span id="infoAsignacion" class="text-muted">Ninguna seleccionada</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción del Incidente *</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" required
                                  rows="4" maxlength="500" placeholder="Describe detalladamente el incidente ocurrido..."></textarea>
                        <div class="form-text">
                            <span id="contadorCaracteres">0</span>/500 caracteres
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fecha *</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Hora *</label>
                            <input type="time" class="form-control" id="hora" name="hora" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Estado *</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="pendiente">Pendiente</option>
                                <option value="resuelto">Resuelto</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarIncidente()" id="btnGuardar">
                        <i class="bi bi-check-circle"></i> Guardar
                    </button>
                </div>
            </form>
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
                    @csrf
                    <input type="hidden" id="solucionIncidenteId" name="id_incidente">
                    <div class="row">
                        <div class="col-12 mb-3" id="infoIncidente">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Información del Incidente:</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>ID:</strong> <span id="infoId">-</span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Unidad:</strong> <span id="infoPlaca">-</span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Operador:</strong> <span id="infoLicencia">-</span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Ruta:</strong> <span id="infoRuta">-</span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Fecha:</strong> <span id="infoFecha">-</span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Hora:</strong> <span id="infoHora">-</span>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <strong>Descripción:</strong>
                                        <p id="infoDescripcion" class="mb-0">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Solución del Incidente *</label>
                            <textarea class="form-control" name="solucion" id="solucion" rows="4"
                                      placeholder="Describe la solución aplicada..." required maxlength="500"></textarea>
                            <div class="form-text">
                                <span id="contadorSolucion">0</span>/500 caracteres
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnGuardarSolucion">Guardar Solución</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PARA EDITAR SOLUCIÓN -->
<div class="modal fade" id="modalEditarSolucion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Editar Solución</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarSolucion">
                    @csrf
                    <input type="hidden" id="editarSolucionIncidenteId" name="id_incidente">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Información del Incidente:</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>ID:</strong> <span id="editarInfoId">-</span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Unidad:</strong> <span id="editarInfoPlaca">-</span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Operador:</strong> <span id="editarInfoLicencia">-</span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Ruta:</strong> <span id="editarInfoRuta">-</span>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <strong>Descripción:</strong>
                                        <p id="editarInfoDescripcion" class="mb-0">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Solución del Incidente *</label>
                            <textarea class="form-control" name="solucion" id="editarSolucion" rows="4"
                                      placeholder="Describe la solución aplicada..." required maxlength="500"></textarea>
                            <div class="form-text">
                                <span id="editarContadorSolucion">0</span>/500 caracteres
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnEditarSolucion">Actualizar Solución</button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery + Bootstrap + DataTables + SweetAlert2 JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
    let modoEdicion = false;
    let tablaIncidentes, tablaSolucion;

    $(document).ready(function() {
        // Configuración para DataTables de TODOS LOS INCIDENTES
        const configIncidentes = {
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
            responsive: {
                details: {
                    type: 'column',
                    target: 0 // La primera columna tendrá el control de expandir/colapsar
                }
            },
            autoWidth: false,
            order: [[6, 'desc']], // Ordenar por ID (columna 6)
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            columnDefs: [
                {
                    // Columna del control de expandir (ícono +)
                    targets: 0,
                    className: 'dtr-control',
                    orderable: false,
                    responsivePriority: 1
                },
                {
                    // Columnas que se muestran siempre
                    targets: [1, 2, 3, 4, 5], // Unidad, Descripción, Solución, Estado, Acciones
                    className: 'all',
                    responsivePriority: 2
                },
                {
                    // Columnas que se ocultan en responsive (se muestran al expandir)
                    targets: [6, 7, 8, 9, 10], // ID, Operador, Ruta, Fecha, Hora
                    className: 'none',
                    responsivePriority: 3
                },
                {
                    targets: [2, 3], // Descripción y Solución
                    className: 'texto-largo descripcion-col force-break',
                    render: function (data, type, row) {
                        // Para mostrar texto completo con saltos de línea
                        if (type === 'display' && data) {
                            // Reemplazar saltos de línea reales por <br>
                            return data.replace(/\n/g, '<br>');
                        }
                        return data;
                    }
                },
                {
                    targets: 5, // Acciones
                    className: 'table-actions',
                    orderable: false
                }
            ],
            createdRow: function (row, data, dataIndex) {
                // Aplicar estilos de force-break a las celdas de texto largo
                $('td:eq(2)', row).addClass('force-break');
                $('td:eq(3)', row).addClass('force-break');
            }
        };

        // Configuración para DataTables de SOLUCIÓN DE INCIDENTES
        const configSolucion = {
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
            responsive: {
                details: {
                    type: 'column',
                    target: 0 // La primera columna tendrá el control de expandir/colapsar
                }
            },
            autoWidth: false,
            order: [[1, 'desc']], // Ordenar por ID (columna 1)
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            columnDefs: [
                {
                    // Columna del control de expandir (ícono +)
                    targets: 0,
                    className: 'dtr-control',
                    orderable: false,
                    responsivePriority: 1
                },
                {
                    // Columnas que se muestran siempre
                    targets: [1, 2, 3, 4, 5, 6], // ID, Operador, Descripción, Solución, Estado, Acciones
                    className: 'all',
                    responsivePriority: 2
                },
                {
                    // Columnas que se ocultan en responsive (se muestran al expandir)
                    targets: [7, 8, 9, 10], // Unidad, Ruta, Fecha, Hora
                    className: 'none',
                    responsivePriority: 3
                },
                {
                    targets: [3, 4], // Descripción y Solución
                    className: 'texto-largo descripcion-col force-break',
                    render: function (data, type, row) {
                        // Para mostrar texto completo con saltos de línea
                        if (type === 'display' && data) {
                            // Reemplazar saltos de línea reales por <br>
                            return data.replace(/\n/g, '<br>');
                        }
                        return data;
                    }
                },
                {
                    targets: 6, // Acciones
                    className: 'table-actions',
                    orderable: false
                }
            ],
            createdRow: function (row, data, dataIndex) {
                // Aplicar estilos de force-break a las celdas de texto largo
                $('td:eq(3)', row).addClass('force-break');
                $('td:eq(4)', row).addClass('force-break');
            }
        };

        // Inicializar DataTables
        tablaIncidentes = $('#tablaIncidentes').DataTable(configIncidentes);
        tablaSolucion = $('#tablaSolucion').DataTable(configSolucion);

        // Mostrar información de la asignación seleccionada
        document.getElementById('id_asignacion').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const placa = selectedOption.getAttribute('data-placa');
            const licencia = selectedOption.getAttribute('data-licencia');
            const ruta = selectedOption.getAttribute('data-ruta');

            if (placa && licencia) {
                document.getElementById('infoAsignacion').textContent = `${placa} - ${licencia} (${ruta})`;
            } else {
                document.getElementById('infoAsignacion').textContent = 'Ninguna seleccionada';
            }
        });

        // Contador de caracteres para la descripción
        document.getElementById('descripcion').addEventListener('input', function() {
            document.getElementById('contadorCaracteres').textContent = this.value.length;
        });

        // Contador de caracteres para la solución
        document.getElementById('solucion').addEventListener('input', function() {
            document.getElementById('contadorSolucion').textContent = this.value.length;
        });

        // Contador de caracteres para la edición de solución
        document.getElementById('editarSolucion').addEventListener('input', function() {
            document.getElementById('editarContadorSolucion').textContent = this.value.length;
        });

        // Establecer fecha y hora actual por defecto
        const now = new Date();
        const fechaActual = now.toISOString().split('T')[0];
        const horas = now.getHours().toString().padStart(2, '0');
        const minutos = now.getMinutes().toString().padStart(2, '0');
        const horaActual = `${horas}:${minutos}`;

        document.getElementById('fecha').value = fechaActual;
        document.getElementById('hora').value = horaActual;

        // Event listeners para los botones
        $('#btnGuardarSolucion').on('click', guardarSolucion);
        $('#btnEditarSolucion').on('click', editarSolucionGuardar);
    });

    // Cambiar entre secciones
    $('input[name="tipoVista"]').change(function() {
        actualizarVista();
    });

    function actualizarVista() {
        const esVistaSolucion = $('#vistaSolucion').is(':checked');
        $('#seccionRegistro').toggle(!esVistaSolucion);
        $('#seccionSolucion').toggle(esVistaSolucion);

        // Mostrar/ocultar botón de registrar incidente
        $('#btnRegistrarIncidente').toggle(!esVistaSolucion);

        // Redibujar las tablas cuando se muestren
        setTimeout(() => {
            if (tablaIncidentes) {
                tablaIncidentes.columns.adjust().responsive.recalc();
            }
            if (tablaSolucion) {
                tablaSolucion.columns.adjust().responsive.recalc();
            }
        }, 100);
    }

    // ... (el resto de las funciones JavaScript se mantienen igual)
    function editarIncidente(id) {
        fetch(`/incidentes/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Llenar el formulario con los datos
                    document.getElementById('incidenteId').value = data.data.id_incidente;
                    document.getElementById('id_asignacion').value = data.data.id_asignacion;
                    document.getElementById('descripcion').value = data.data.descripcion;
                    document.getElementById('fecha').value = data.data.fecha;
                    document.getElementById('hora').value = data.data.hora;
                    document.getElementById('estado').value = data.data.estado;

                    // Actualizar información de la asignación
                    const selectedOption = document.querySelector(`#id_asignacion option[value="${data.data.id_asignacion}"]`);
                    if (selectedOption) {
                        const placa = selectedOption.getAttribute('data-placa');
                        const licencia = selectedOption.getAttribute('data-licencia');
                        const ruta = selectedOption.getAttribute('data-ruta');
                        document.getElementById('infoAsignacion').textContent = `${placa} - ${licencia} (${ruta})`;
                    }

                    // Actualizar contador de caracteres
                    document.getElementById('contadorCaracteres').textContent = data.data.descripcion.length;

                    // Cambiar el modal a modo edición
                    document.getElementById('modalTitulo').textContent = 'Editar Incidente';
                    document.getElementById('btnGuardar').innerHTML = '<i class="bi bi-check-circle"></i> Actualizar';
                    modoEdicion = true;

                    // Mostrar el modal
                    const modal = new bootstrap.Modal(document.getElementById('modalAgregarIncidente'));
                    modal.show();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al cargar los datos del incidente',
                        confirmButtonColor: '#3085d6'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar los datos del incidente: ' + error.message,
                    confirmButtonColor: '#3085d6'
                });
            });
    }

    function asignarSolucion(id) {
        // Obtener datos del incidente
        fetch(`/incidentes/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Llenar la información del incidente
                    document.getElementById('solucionIncidenteId').value = data.data.id_incidente;
                    document.getElementById('infoId').textContent = data.data.id_incidente;
                    document.getElementById('infoPlaca').textContent = data.data.placa || 'N/A';
                    document.getElementById('infoLicencia').textContent = data.data.licencia || 'N/A';
                    document.getElementById('infoRuta').textContent = (data.data.origen || 'N/A') + ' - ' + (data.data.destino || 'N/A');
                    document.getElementById('infoFecha').textContent = data.data.fecha;
                    document.getElementById('infoHora').textContent = data.data.hora;
                    document.getElementById('infoDescripcion').textContent = data.data.descripcion;

                    // Limpiar y preparar el campo de solución
                    document.getElementById('solucion').value = '';
                    document.getElementById('contadorSolucion').textContent = '0';

                    const modal = new bootstrap.Modal(document.getElementById('modalSolucion'));
                    modal.show();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al cargar los datos del incidente',
                        confirmButtonColor: '#3085d6'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar los datos del incidente',
                    confirmButtonColor: '#3085d6'
                });
            });
    }

    function editarSolucion(id) {
        // Obtener datos del incidente
        fetch(`/incidentes/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Llenar la información del incidente
                    document.getElementById('editarSolucionIncidenteId').value = data.data.id_incidente;
                    document.getElementById('editarInfoId').textContent = data.data.id_incidente;
                    document.getElementById('editarInfoPlaca').textContent = data.data.placa || 'N/A';
                    document.getElementById('editarInfoLicencia').textContent = data.data.licencia || 'N/A';
                    document.getElementById('editarInfoRuta').textContent = (data.data.origen || 'N/A') + ' - ' + (data.data.destino || 'N/A');
                    document.getElementById('editarInfoDescripcion').textContent = data.data.descripcion;

                    // Llenar el campo de solución
                    document.getElementById('editarSolucion').value = data.data.solucion || '';
                    document.getElementById('editarContadorSolucion').textContent = (data.data.solucion || '').length;

                    const modal = new bootstrap.Modal(document.getElementById('modalEditarSolucion'));
                    modal.show();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al cargar los datos del incidente',
                        confirmButtonColor: '#3085d6'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar los datos del incidente',
                    confirmButtonColor: '#3085d6'
                });
            });
    }

    function eliminarIncidente(id, event) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esta acción!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const btnEliminar = event.target.closest('button');
                const originalText = btnEliminar.innerHTML;
                btnEliminar.innerHTML = '<i class="bi bi-hourglass-split"></i> Eliminando...';
                btnEliminar.disabled = true;

                fetch(`/incidentes/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                position: "center",
                                icon: "success",
                                title: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            });

                            // Eliminar la fila de ambas DataTables
                            tablaIncidentes.row('#fila-' + id).remove().draw();
                            tablaSolucion.row('#fila-sol-' + id).remove().draw();

                            // Verificar si quedan filas
                            if (tablaIncidentes.rows().count() === 0 && tablaSolucion.rows().count() === 0) {
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            }
                        } else {
                            throw new Error(data.message || 'Error al eliminar el incidente');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al eliminar el incidente: ' + error.message,
                            confirmButtonColor: '#3085d6'
                        });
                    })
                    .finally(() => {
                        btnEliminar.innerHTML = originalText;
                        btnEliminar.disabled = false;
                    });
            }
        });
    }

    function guardarIncidente() {
        const incidenteId = document.getElementById('incidenteId').value;
        const idAsignacion = document.getElementById('id_asignacion').value;
        const descripcion = document.getElementById('descripcion').value.trim();
        const fecha = document.getElementById('fecha').value;
        const hora = document.getElementById('hora').value;
        const estado = document.getElementById('estado').value;

        // Validaciones básicas
        if (!idAsignacion || !descripcion || !fecha || !hora || !estado) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Por favor complete todos los campos obligatorios',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        if (descripcion.length > 500) {
            Swal.fire({
                icon: 'warning',
                title: 'Descripción muy larga',
                text: 'La descripción no puede exceder los 500 caracteres',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        const url = modoEdicion ? `/incidentes/${incidenteId}` : '/incidentes';
        const method = modoEdicion ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id_asignacion: parseInt(idAsignacion),
                descripcion: descripcion,
                fecha: fecha,
                hora: hora,
                estado: estado
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('modalAgregarIncidente'));
                        modal.hide();
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                        confirmButtonColor: '#3085d6'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al guardar el incidente',
                    confirmButtonColor: '#3085d6'
                });
            });
    }

    function guardarSolucion() {
        const incidenteId = document.getElementById('solucionIncidenteId').value;
        const solucion = document.getElementById('solucion').value.trim();

        if (!incidenteId) {
            Swal.fire({
                icon: 'warning',
                title: 'Error',
                text: 'No se ha seleccionado un incidente válido',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        if (!solucion) {
            Swal.fire({
                icon: 'warning',
                title: 'Solución requerida',
                text: 'Por favor describe la solución aplicada',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        if (solucion.length > 500) {
            Swal.fire({
                icon: 'warning',
                title: 'Solución muy larga',
                text: 'La solución no puede exceder los 500 caracteres',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        const url = `/incidentes/${incidenteId}/solucionar`;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                solucion: solucion
            })
        })
            .then(response => {
                // Primero verificar si la respuesta es JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    // Si no es JSON, obtener el texto para ver qué pasó
                    return response.text().then(text => {
                        throw new Error('El servidor devolvió HTML en lugar de JSON: ' + text.substring(0, 100));
                    });
                }
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('modalSolucion'));
                        modal.hide();
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al guardar la solución',
                        confirmButtonColor: '#3085d6'
                    });
                }
            })
            .catch(error => {
                console.error('Error completo:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error del servidor',
                    text: 'Error al guardar la solución. Verifique la consola para más detalles.',
                    confirmButtonColor: '#3085d6'
                });
            });
    }

    function editarSolucionGuardar() {
        const incidenteId = document.getElementById('editarSolucionIncidenteId').value;
        const solucion = document.getElementById('editarSolucion').value.trim();

        if (!incidenteId) {
            Swal.fire({
                icon: 'warning',
                title: 'Error',
                text: 'No se ha seleccionado un incidente válido',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        if (!solucion) {
            Swal.fire({
                icon: 'warning',
                title: 'Solución requerida',
                text: 'Por favor describe la solución aplicada',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        if (solucion.length > 500) {
            Swal.fire({
                icon: 'warning',
                title: 'Solución muy larga',
                text: 'La solución no puede exceder los 500 caracteres',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        const url = `/incidentes/${incidenteId}/solucionar`;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                solucion: solucion
            })
        })
            .then(response => {
                // Primero verificar si la respuesta es JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    // Si no es JSON, obtener el texto para ver qué pasó
                    return response.text().then(text => {
                        throw new Error('El servidor devolvió HTML en lugar de JSON: ' + text.substring(0, 100));
                    });
                }
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditarSolucion'));
                        modal.hide();
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al actualizar la solución',
                        confirmButtonColor: '#3085d6'
                    });
                }
            })
            .catch(error => {
                console.error('Error completo:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error del servidor',
                    text: 'Error al actualizar la solución. Verifique la consola para más detalles.',
                    confirmButtonColor: '#3085d6'
                });
            });
    }

    // Limpiar el formulario cuando se cierra el modal
    document.getElementById('modalAgregarIncidente').addEventListener('hidden.bs.modal', function () {
        document.getElementById('formIncidente').reset();
        document.getElementById('incidenteId').value = '';
        document.getElementById('infoAsignacion').textContent = 'Ninguna seleccionada';
        document.getElementById('contadorCaracteres').textContent = '0';
        document.getElementById('modalTitulo').textContent = 'Registrar Incidente';
        document.getElementById('btnGuardar').innerHTML = '<i class="bi bi-check-circle"></i> Guardar';
        modoEdicion = false;

        // Restablecer fecha y hora actual
        const now = new Date();
        const fechaActual = now.toISOString().split('T')[0];
        const horas = now.getHours().toString().padStart(2, '0');
        const minutos = now.getMinutes().toString().padStart(2, '0');
        const horaActual = `${horas}:${minutos}`;

        document.getElementById('fecha').value = fechaActual;
        document.getElementById('hora').value = horaActual;
    });

    // Limpiar el formulario de solución cuando se cierra el modal
    document.getElementById('modalSolucion').addEventListener('hidden.bs.modal', function () {
        document.getElementById('formSolucion').reset();
        document.getElementById('contadorSolucion').textContent = '0';
        document.getElementById('solucionIncidenteId').value = '';
    });

    // Limpiar el formulario de edición de solución cuando se cierra el modal
    document.getElementById('modalEditarSolucion').addEventListener('hidden.bs.modal', function () {
        document.getElementById('formEditarSolucion').reset();
        document.getElementById('editarContadorSolucion').textContent = '0';
        document.getElementById('editarSolucionIncidenteId').value = '';
    });

    // Inicializar vista al cargar
    $(window).on('load', function() {
        actualizarVista();
    });
</script>
</body>
</html>
