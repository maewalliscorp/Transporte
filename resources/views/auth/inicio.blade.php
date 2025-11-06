<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignación de Transporte</title>
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

    <style>
        body {
            font-family: "Open Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #E3F2FD 0%, #F3E5F5 100%);
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .container-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            border: 1px solid #E1F5FE;
        }
        .header-section {
            background: linear-gradient(135deg, #4FC3F7 0%, #7E57C2 100%);
            color: white;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 12px rgba(79, 195, 247, 0.3);
        }
        .header-section h4 {
            margin: 0;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .content-section {
            padding: 2rem;
        }
        .filter-section {
            background: #F8F9FA;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            border: 1px solid #E3F2FD;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #E1F5FE;
            transition: all 0.3s ease;
            background: white;
        }
        .form-control:focus, .form-select:focus {
            border-color: #4FC3F7;
            box-shadow: 0 0 0 0.2rem rgba(79, 195, 247, 0.25);
        }
        .btn-outline-secondary {
            border: 2px solid #4FC3F7;
            color: #4FC3F7;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-outline-secondary:hover {
            background: #4FC3F7;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 195, 247, 0.3);
        }
        /* Estilos para la tabla oscura */
        .table-dark {
            background: linear-gradient(135deg, #2C3E50 0%, #34495E 100%);
            border-radius: 10px 10px 0 0;
            overflow: hidden;
        }
        .table-dark th {
            background: rgba(0, 0, 0, 0.2);
            border: none;
            padding: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        .table-hover tbody tr:hover {
            background: rgba(79, 195, 247, 0.1);
            transform: scale(1.01);
            transition: all 0.2s ease;
        }
        .table-hover tbody tr td {
            padding: 1rem;
            border-color: #E3F2FD;
            vertical-align: middle;
        }
        /* Badges mejorados */
        .badge.bg-success {
            background: linear-gradient(135deg, #66BB6A 0%, #4CAF50 100%) !important;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
        }
        .badge.bg-warning {
            background: linear-gradient(135deg, #FFB74D 0%, #FF9800 100%) !important;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            color: white !important;
        }
        .table-responsive {
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .form-label {
            font-weight: 600;
            color: #37474F;
            margin-bottom: 0.5rem;
        }
    </style>

</head>
<body>

<!-- AQUI MI MENÚ (NAVBAR) -->
@include('layouts.menuPrincipal')

<div class="container mt-4">

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="section-title">
                <i class="bi bi-bus-front me-2"></i>Gestión de Asignaciones de Transporte
            </h1>
            <p class="text-muted">Administra las asignaciones de unidades, operadores y rutas</p>
        </div>
    </div>

    <!-- Filtro de vista -->
    <div class="filter-section">
        <div class="row align-items-center">
            <div class="col-md-6">
                <label class="form-label me-3 fw-bold">Vista actual:</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="vista" id="verDisponibles" value="disponibles" checked>
                    <label class="form-check-label" for="verDisponibles">
                        <i class="bi bi-check-circle text-success me-1"></i>Disponibles
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="vista" id="verAsignados" value="asignados">
                    <label class="form-check-label" for="verAsignados">
                        <i class="bi bi-clipboard-check text-primary me-1"></i>Asignados
                    </label>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <div id="botonAsignarContainer">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAsignar">
                        <i class="bi bi-plus-circle me-1"></i> Nueva Asignación
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Tabla de DISPONIBLES (se muestra por defecto) -->
    <div id="tablaDisponibles">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-check-circle me-2"></i>RECURSOS DISPONIBLES
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover display nowrap" id="tablaDisponiblesData" style="width:100%">
                        <thead class="table-primary">
                        <tr>
                            <th><i class="bi bi-bus-front me-1"></i>Unidades de Transporte</th>
                            <th><i class="bi bi-person-badge me-1"></i>Operadores</th>
                            <th><i class="bi bi-geo-alt me-1"></i>Rutas</th>
                            <th><i class="bi bi-clock me-1"></i>Horarios</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $unidades = $disponibles['unidades'] ?? [];
                            $operadores = $disponibles['operadores'] ?? [];
                            $rutas = $disponibles['rutas'] ?? [];
                            $horarios = $disponibles['horarios'] ?? [];
                            $maxRows = max(count($unidades), count($operadores), count($rutas), count($horarios));
                        @endphp

                        @if($maxRows > 0)
                            @for($i = 0; $i < $maxRows; $i++)
                                <tr>
                                    <!-- Columna 1: Unidades de Transporte -->
                                    <td>
                                        @if(isset($unidades[$i]))
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-bus-front text-primary me-2"></i>
                                                <div>
                                                    <strong class="text-primary">{{ $unidades[$i]['placa'] ?? 'N/A' }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $unidades[$i]['modelo'] ?? '' }}</small>
                                                    <br>
                                                    <span class="badge bg-info">Cap: {{ $unidades[$i]['capacidad'] ?? 'N/A' }} personas</span>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <!-- Columna 2: Operadores -->
                                    <td>
                                        @if(isset($operadores[$i]))
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-person-badge text-info me-2"></i>
                                                <div>
                                                    <strong class="text-info">{{ $operadores[$i]['licencia'] ?? 'N/A' }}</strong>
                                                    @if(isset($operadores[$i]['telefono']))
                                                        <br>
                                                        <small class="text-muted"><i class="bi bi-telephone me-1"></i>{{ $operadores[$i]['telefono'] }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <!-- Columna 3: Rutas -->
                                    <td>
                                        @if(isset($rutas[$i]))
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-geo-alt text-warning me-2"></i>
                                                <div>
                                                    <strong>{{ $rutas[$i]['origen'] ?? 'N/A' }}</strong>
                                                    <i class="bi bi-arrow-right mx-1 text-muted"></i>
                                                    <strong>{{ $rutas[$i]['destino'] ?? 'N/A' }}</strong>
                                                    @if(isset($rutas[$i]['nombre']))
                                                        <br>
                                                        <small class="text-muted">{{ $rutas[$i]['nombre'] }}</small>
                                                    @endif
                                                    @if(isset($rutas[$i]['duracion_estimada']))
                                                        <br>
                                                        <span class="badge bg-secondary">{{ $rutas[$i]['duracion_estimada'] }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <!-- Columna 4: Horarios -->
                                    <td>
                                        @if(isset($horarios[$i]))
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-clock text-secondary me-2"></i>
                                                <div>
                                                    <span class="badge bg-primary">
                                                        <i class="bi bi-play-circle me-1"></i>{{ $horarios[$i]['horaSalida'] ?? 'N/A' }}
                                                    </span>
                                                    <i class="bi bi-arrow-right mx-2 text-muted"></i>
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-stop-circle me-1"></i>{{ $horarios[$i]['horaLlegada'] ?? 'N/A' }}
                                    </span>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endfor
                        @else
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-4"></i>
                                        <h5 class="mt-3">No hay recursos disponibles</h5>
                                        <p>Todos los recursos están asignados para hoy.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de ASIGNADOS  -->
    <div id="tablaAsignados" style="display: none;">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-clipboard-check me-2"></i>ASIGNACIONES ACTIVAS
                    <span class="badge bg-light text-dark ms-2">{{ count($asignados ?? []) }}</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover display nowrap" id="tablaAsignadosData" style="width:100%">
                        <thead class="table-primary">
                        <tr>
                            <th>Id Asignación</th>
                            <th>Unidad de Transporte</th>
                            <th>Operador</th>
                            <th>Ruta</th>
                            <th>Horario</th>
                            <th>Fecha/Hora Asignación</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($asignados) && count($asignados) > 0)
                            @foreach($asignados as $a)
                                <tr>
                                    <td>
                                        <strong class="text-primary">#{{ $a['id_asignacion'] }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $a['placa'] ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $a['modelo'] ?? '' }}</small>
                                        <br>
                                        <span class="badge bg-info badge-custom">Cap: {{ $a['capacidad'] ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        @if(!empty($a['licencia']))
                                            <span class="badge bg-primary">{{ $a['licencia'] }}</span>
                                        @else
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($a['origen']) && !empty($a['destino']))
                                            <strong>{{ $a['origen'] }}</strong>
                                            <i class="bi bi-arrow-right mx-2 text-muted"></i>
                                            <strong>{{ $a['destino'] }}</strong>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($a['horaSalida']) && !empty($a['horaLlegada']))
                                            <span class="badge bg-primary">
                                                {{ $a['horaSalida'] }} - {{ $a['horaLlegada'] }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <strong>{{ $a['fecha'] ?? 'N/A' }}</strong>
                                            <br>
                                            @if(!empty($a['hora']))
                                                <span class="badge bg-dark">{{ $a['hora'] }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-clock me-1"></i>Asignado
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-clipboard-x display-4"></i>
                                        <h5 class="mt-3">No hay asignaciones activas</h5>
                                        <p>No se encontraron asignaciones para mostrar.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal para Asignación -->
<div class="modal fade" id="modalAsignar" tabindex="-1" aria-labelledby="modalAsignarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalAsignarLabel">
                    <i class="bi bi-clipboard-check me-2"></i>Nueva Asignación de Transporte
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAsignar">
                    <!-- Selects desplegables dentro del modal -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="unidadModal" class="form-label">
                                <i class="bi bi-bus-front me-1"></i>Unidad de transporte *
                            </label>
                            <select class="form-select" id="unidadModal" name="unidad" required>
                                <option value="" selected disabled>Selecciona una unidad...</option>
                                @if(isset($unidades) && count($unidades) > 0)
                                    @foreach($unidades as $u)
                                        <option value="{{ $u['id_unidad'] }}">
                                            {{ $u['placa'] }} - {{ $u['modelo'] }} - (Cap: {{ $u['capacidad'] }} personas)
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>No hay unidades disponibles</option>
                                @endif
                            </select>
                            <div class="form-text">Selecciona la unidad de transporte a asignar</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="operadorModal" class="form-label">
                                <i class="bi bi-person-badge me-1"></i>Operador *
                            </label>
                            <select class="form-select" id="operadorModal" name="operador" required>
                                <option value="" selected disabled>Selecciona un operador...</option>
                                @if(isset($operadores) && count($operadores) > 0)
                                    @foreach($operadores as $o)
                                        <option value="{{ $o['id_operator'] }}">
                                            Operador #{{ $o['id_operator'] }} - Licencia: {{ $o['licencia'] }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>No hay operadores disponibles</option>
                                @endif
                            </select>
                            <div class="form-text">Selecciona el operador responsable</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="rutaModal" class="form-label">
                                <i class="bi bi-geo-alt me-1"></i>Ruta *
                            </label>
                            <select class="form-select" id="rutaModal" name="ruta" required>
                                <option value="" selected disabled>Selecciona una ruta...</option>
                                @if(isset($rutas) && count($rutas) > 0)
                                    @foreach($rutas as $r)
                                        <option value="{{ $r['id_ruta'] }}">
                                            {{ $r['origen'] }} - {{ $r['destino'] }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>No hay rutas disponibles</option>
                                @endif
                            </select>
                            <div class="form-text">Selecciona la ruta a asignar</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="fechaAsignacion" class="form-label">
                                <i class="bi bi-calendar me-1"></i>Fecha de asignación *
                            </label>
                            <input type="date" class="form-control" id="fechaAsignacion" name="fecha" required>
                            <div class="form-text">Fecha en la que se realiza la asignación</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="horaAsignacion" class="form-label">
                                <i class="bi bi-clock me-1"></i>Hora de asignación *
                            </label>
                            <input type="time" class="form-control" id="horaAsignacion" name="hora" required>
                            <div class="form-text">Hora en la que se realiza la asignación</div>
                        </div>
                    </div>

                    <!-- Resumen de asignación -->
                    <div class="card mt-3 resumen-card" id="resumenAsignacion" style="display: none;">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Resumen de Asignación</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <strong><i class="bi bi-bus-front me-1"></i>Unidad:</strong>
                                    <span id="resumenUnidad" class="text-primary">-</span>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <strong><i class="bi bi-person-badge me-1"></i>Operador:</strong>
                                    <span id="resumenOperador" class="text-primary">-</span>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <strong><i class="bi bi-geo-alt me-1"></i>Ruta:</strong>
                                    <span id="resumenRuta" class="text-primary">-</span>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <strong><i class="bi bi-calendar me-1"></i>Fecha/Hora:</strong>
                                    <span id="resumenFechaHora" class="text-primary">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" onclick="procesarAsignacion()" id="btnConfirmar">
                    <i class="bi bi-check-circle me-1"></i>Confirmar Asignación
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
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Variables globales para las DataTables
    let tableDisponibles, tableAsignados;

    $(document).ready(function() {
        console.log('Documento listo, inicializando DataTables...');

        try {
            // Configuración común para ambas tablas
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
                order: [], // No ordenar por defecto
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
            };

            // Inicializar DataTable para Disponibles
            tableDisponibles = $('#tablaDisponiblesData').DataTable(configComun);
            console.log('Tabla Disponibles inicializada');

            // Inicializar DataTable para Asignados
            tableAsignados = $('#tablaAsignadosData').DataTable({
                ...configComun,
                // Ordenar por ID de asignación descendente por defecto (más recientes primero)
                order: [[0, 'desc']]
            });
            console.log('Tabla Asignados inicializada');

        } catch (error) {
            console.error('Error inicializando DataTables:', error);
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: 'Error inicializando las tablas: ' + error.message,
                showConfirmButton: false,
                timer: 1500
            });
        }

        // Establecer fecha y hora actual por defecto
        const now = new Date();
        const fechaActual = now.toISOString().split('T')[0];
        const horaActual = now.toTimeString().split(' ')[0].substring(0, 5);

        document.getElementById('fechaAsignacion').value = fechaActual;
        document.getElementById('horaAsignacion').value = horaActual;

        // Inicializar vista
        actualizarVista();
    });

    // Función para mostrar alertas con SweetAlert2
    function mostrarAlerta(mensaje, tipo = 'success') {
        const icon = tipo === 'success' ? 'success' : 'error';

        Swal.fire({
            position: "top-end",
            icon: icon,
            title: mensaje,
            showConfirmButton: false,
            timer: 1500
        });
    }

    // Cambiar entre tablas de 'disponibles' y 'asignados'
    $('#verDisponibles, #verAsignados').change(function() {
        actualizarVista();
    });

    function actualizarVista() {
        if ($('#verDisponibles').is(':checked')) {
            $('#tablaDisponibles').show();
            $('#tablaAsignados').hide();
            $('#botonAsignarContainer').show();

            // Redibujar la tabla de disponibles si es necesario
            if (tableDisponibles) {
                tableDisponibles.draw();
            }
        } else {
            $('#tablaDisponibles').hide();
            $('#tablaAsignados').show();
            $('#botonAsignarContainer').hide();

            // Redibujar la tabla de asignados si es necesario
            if (tableAsignados) {
                tableAsignados.draw();
            }
        }
    }

    // Actualizar resumen cuando cambien los selects
    $('#unidadModal, #operadorModal, #rutaModal, #fechaAsignacion, #horaAsignacion').change(function() {
        actualizarResumen();
    });

    function actualizarResumen() {
        const unidad = $('#unidadModal option:selected').text();
        const operador = $('#operadorModal option:selected').text();
        const ruta = $('#rutaModal option:selected').text();
        const fecha = $('#fechaAsignacion').val();
        const hora = $('#horaAsignacion').val();

        if (unidad && operador && ruta && fecha && hora) {
            $('#resumenUnidad').text(unidad);
            $('#resumenOperador').text(operador);
            $('#resumenRuta').text(ruta);
            $('#resumenFechaHora').text(fecha + ' ' + hora);
            $('#resumenAsignacion').show();
        } else {
            $('#resumenAsignacion').hide();
        }
    }

    // Función para procesar la asignación
    function procesarAsignacion() {
        // Obtener los valores seleccionados
        const unidad = $('#unidadModal').val();
        const operador = $('#operadorModal').val();
        const ruta = $('#rutaModal').val();
        const fecha = $('#fechaAsignacion').val();
        const hora = $('#horaAsignacion').val();

        // Validar que todos los campos estén llenos
        if (!unidad || !operador || !ruta || !fecha || !hora) {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Por favor completa todos los campos obligatorios.",
                showConfirmButton: false,
                timer: 1500
            });
            return;
        }

        // Obtener textos para mostrar en confirmación
        const unidadText = $('#unidadModal option:selected').text();
        const operadorText = $('#operadorModal option:selected').text();
        const rutaText = $('#rutaModal option:selected').text();

        // Mostrar confirmación con SweetAlert2
        Swal.fire({
            title: '¿Confirmar asignación?',
            html: `
                <div class="text-start">
                    <p><strong>Unidad:</strong> ${unidadText}</p>
                    <p><strong>Operador:</strong> ${operadorText}</p>
                    <p><strong>Ruta:</strong> ${rutaText}</p>
                    <p><strong>Fecha:</strong> ${fecha}</p>
                    <p><strong>Hora:</strong> ${hora}</p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4FC3F7',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Sí, confirmar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            // Continuar con la asignación
            realizarAsignacion(unidad, operador, ruta, fecha, hora);
        });
    }

    // Función para realizar la asignación
    function realizarAsignacion(unidad, operador, ruta, fecha, hora) {

        // Crear objeto con los datos
        const datosAsignacion = {
            id_unidad: unidad,
            id_operador: operador,
            id_ruta: ruta,
            fecha: fecha,
            hora: hora
        };

        console.log('Datos a asignar:', datosAsignacion);

        // Mostrar loading en el botón
        const btnConfirmar = document.getElementById('btnConfirmar');
        const originalText = btnConfirmar.innerHTML;
        btnConfirmar.innerHTML = '<i class="bi bi-hourglass-split"></i> Asignando...';
        btnConfirmar.disabled = true;

        // Petición AJAX para guardar en la base de datos
        fetch('/asignar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(datosAsignacion)
        })
            .then(response => {
                console.log('Respuesta del servidor:', response);
                if (!response.ok) {
                    // Si hay error, obtener más detalles
                    return response.text().then(text => {
                        throw new Error(`HTTP ${response.status}: ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Datos recibidos:', data);
                if (data.success) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Cerrar el modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('modalAsignar'));
                        modal.hide();
                        // Recargar la página para ver los cambios
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            })
            .catch(error => {
                console.error('Error completo:', error);
                Swal.fire({
                    position: "top-end",
                    icon: "error",
                    title: 'Error al realizar la asignación: ' + error.message,
                    showConfirmButton: false,
                    timer: 1500
                });
            })
            .finally(() => {
                // Restaurar botón
                btnConfirmar.innerHTML = originalText;
                btnConfirmar.disabled = false;
            });
    }

    // Limpiar el formulario cuando se cierra el modal
    $('#modalAsignar').on('hidden.bs.modal', function () {
        $('#formAsignar')[0].reset();
        $('#resumenAsignacion').hide();

        // Restablecer fecha y hora actual
        const now = new Date();
        const fechaActual = now.toISOString().split('T')[0];
        const horaActual = now.toTimeString().split(' ')[0].substring(0, 5);

        $('#fechaAsignacion').val(fechaActual);
        $('#horaAsignacion').val(horaActual);
    });

    // Validación en tiempo real de los campos del formulario
    $('#formAsignar input, #formAsignar select').on('input change', function() {
        const input = $(this);
        if (input.val().trim() === '') {
            input.removeClass('is-valid').addClass('is-invalid');
        } else {
            input.removeClass('is-invalid').addClass('is-valid');
        }
    });

    // Prevenir envío del formulario con Enter
    $('#formAsignar').on('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            procesarAsignacion();
        }
    });
</script>

</body>
</html>
