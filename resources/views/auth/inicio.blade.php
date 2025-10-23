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

    <style>
        .filter-section {
            margin-top: 20px;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #0d6efd;
        }
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1rem;
        }
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .resumen-card {
            border-left: 4px solid #0d6efd;
            background-color: #f8f9fa;
        }
        .section-title {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .badge-custom {
            font-size: 0.75em;
        }
    </style>
</head>
<body>

<!-- AQUI MI MENÚ (NAVBAR) -->
@include('layouts.menuPrincipal')

<div class="container mt-4">

    <!-- Alertas -->
    <div id="alertContainer"></div>

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
                    <span class="badge bg-light text-dark ms-2">{{ count($disponibles ?? []) }}</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover display nowrap" id="tablaDisponiblesData" style="width:100%">
                        <thead class="table-dark">
                        <tr>
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th>Información</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($disponibles) && count($disponibles) > 0)
                            @foreach($disponibles as $d)
                                <tr>
                                    <td>
                                        @if($d['tipo'] == 'Unidad de Transporte')
                                            <span class="badge bg-primary">
                                            <i class="bi bi-bus-front me-1"></i>{{ $d['tipo'] }}
                                        </span>
                                        @elseif($d['tipo'] == 'Operador')
                                            <span class="badge bg-info">
                                            <i class="bi bi-person-badge me-1"></i>{{ $d['tipo'] }}
                                        </span>
                                        @elseif($d['tipo'] == 'Ruta')
                                            <span class="badge bg-warning text-dark">
                                            <i class="bi bi-geo-alt me-1"></i>{{ $d['tipo'] }}
                                        </span>
                                        @else
                                            <span class="badge bg-secondary">
                                            <i class="bi bi-clock me-1"></i>{{ $d['tipo'] }}
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $d['descripcion'] ?? 'N/A' }}</strong>
                                    </td>
                                    <td>
                                        {{ $d['informacion'] ?? 'N/A' }}
                                    </td>
                                    <td>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>{{ $d['estado'] }}
                                    </span>
                                    </td>
                                </tr>
                            @endforeach
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
                        <thead class="table-dark">
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
            mostrarAlerta('Error inicializando las tablas: ' + error.message, 'danger');
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

    // Función para mostrar alertas
    function mostrarAlerta(mensaje, tipo = 'success') {
        const alertContainer = document.getElementById('alertContainer');
        const alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';

        alertContainer.innerHTML = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="bi ${tipo === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle'} me-2"></i>
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        setTimeout(() => {
            const alert = alertContainer.querySelector('.alert');
            if (alert) {
                bootstrap.Alert.getOrCreateInstance(alert).close();
            }
        }, 5000);
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
            mostrarAlerta('Por favor completa todos los campos obligatorios.', 'danger');
            return;
        }

        // Obtener textos para mostrar en confirmación
        const unidadText = $('#unidadModal option:selected').text();
        const operadorText = $('#operadorModal option:selected').text();
        const rutaText = $('#rutaModal option:selected').text();

        // Mostrar confirmación
        const confirmacion = confirm(`¿Confirmar asignación?\n\nUnidad: ${unidadText}\nOperador: ${operadorText}\nRuta: ${rutaText}\nFecha: ${fecha}\nHora: ${hora}`);

        if (!confirmacion) {
            return;
        }

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
                    mostrarAlerta(data.message, 'success');
                    // Cerrar el modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalAsignar'));
                    modal.hide();
                    // Recargar la página para ver los cambios
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    mostrarAlerta(data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Error completo:', error);
                mostrarAlerta('Error al realizar la asignación: ' + error.message, 'danger');
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
