<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignación de Transporte</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
        }
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1rem;
        }
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }
    </style>
</head>
<body>

<!-- AQUI MI MENÚ (NAVBAR) -->
@include('layouts.menuPrincipal')

<div class="container">

    <!-- Debug Info
    <div class="alert alert-info d-none" id="debugAlert">
        <strong>Estado:</strong> <span id="debugText">Inicializando...</span>
    </div>
    -->

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

    <!-- Botón para abrir el modal de asignación - SOLO se muestra en DISPONIBLES -->
    <div class="mb-4 text-end" id="botonAsignarContainer">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAsignar">
            <i class="bi bi-plus-circle"></i> Asignar
        </button>
    </div>

    <!-- Tabla de DISPONIBLES (se muestra por defecto) -->
    <div id="tablaDisponibles">
        <h5>DISPONIBLES</h5>
        <div class="table-responsive">
            <table class="table table-striped table-hover display nowrap" id="tablaDisponiblesData" style="width:100%">
                <thead class="table-dark">
                <tr>
                    <th>Unidad de transporte</th>
                    <th>Operador</th>
                    <th>Ruta</th>
                    <th>Horario</th>
                </tr>
                </thead>
                <tbody>
                @isset($disponibles)
                    @foreach($disponibles as $d)
                        <tr>
                            <td>{{ $d['placa'] }} - {{ $d['modelo'] }} - {{ $d['capacidad'] }}</td>
                            <td>{{ $d['licencia'] ?? 'No asignado' }}</td>
                            <td>{{ $d['origen'] }} - {{ $d['destino'] }}</td>
                            <td>{{ $d['horaSalida'] ?? 'N/A' }} - {{ $d['horaLlegada'] ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                @endisset
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabla de ASIGNADOS (se oculta por defecto) -->
    <div id="tablaAsignados" style="display: none;">
        <h5>ASIGNADOS</h5>
        <div class="table-responsive">
            <table class="table table-striped table-hover display nowrap" id="tablaAsignadosData" style="width:100%">
                <thead class="table-dark">
                <tr>
                    <th>ID Asignación</th>
                    <th>Unidad de transporte</th>
                    <th>Operador</th>
                    <th>Ruta</th>
                    <th>Horario</th>
                    <th>Fecha Asignación</th>
                </tr>
                </thead>
                <tbody>
                @isset($asignados)
                    @foreach($asignados as $a)
                        <tr>
                            <td>{{ $a['id_asignacion'] }}</td>
                            <td>{{ $a['placa'] ?? 'N/A' }} - {{ $a['modelo'] ?? '' }} - {{ $a['capacidad'] ?? 'N/A' }}</td>
                            <td>{{ $a['licencia'] ?? 'N/A' }}</td>
                            <td>{{ $a['origen'] ?? 'N/A' }} - {{ $a['destino'] ?? 'N/A' }}</td>
                            <td>{{ $a['horaSalida'] ?? 'N/A' }} - {{ $a['horaLlegada'] ?? 'N/A' }}</td>
                            <td>{{ $a['fecha'] ?? 'N/A' }} {{ $a['hora'] ?? '' }}</td>
                        </tr>
                    @endforeach
                @endisset
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal para Asignación -->
<div class="modal fade" id="modalAsignar" tabindex="-1" aria-labelledby="modalAsignarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAsignarLabel">
                    <i class="bi bi-clipboard-check me-2"></i>Asignar Transporte
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAsignar">
                    <!-- Selects desplegables dentro del modal -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="unidadModal" class="form-label">Unidad de transporte</label>
                            <select class="form-select" id="unidadModal" name="unidad" required>
                                <option value="" selected disabled>Selecciona una unidad...</option>
                                @isset($unidades)
                                    @foreach($unidades as $u)
                                        <option value="{{ $u['id_unidad'] }}">
                                            {{ $u['placa'] }} - {{ $u['modelo'] }} - (Cap: {{ $u['capacidad'] }})
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="operadorModal" class="form-label">Operador</label>
                            <select class="form-select" id="operadorModal" name="operador" required>
                                <option value="" selected disabled>Selecciona un operador...</option>
                                @foreach($operadores as $o)
                                    <option value="{{ $o['id_operator'] }}">
                                        Operador #{{ $o['id_operator'] }} - Licencia: {{ $o['licencia'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="rutaModal" class="form-label">Ruta</label>
                            <select class="form-select" id="rutaModal" name="ruta" required>
                                <option value="" selected disabled>Selecciona una ruta...</option>
                                @foreach($rutas as $r)
                                    <option value="{{ $r['id_ruta'] }}">
                                        {{ $r['origen'] }} - {{ $r['destino'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="horarioModal" class="form-label">Horario</label>
                            <select class="form-select" id="horarioModal" name="horario" required>
                                <option value="" selected disabled>Selecciona un horario...</option>
                                @foreach($horarios as $h)
                                    <option value="{{ $h['id_horario'] }}">
                                        {{ $h['horaSalida'] }} - {{ $h['horaLlegada'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Información adicional  -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fechaAsignacion" class="form-label">Fecha de asignación</label>
                            <input type="date" class="form-control" id="fechaAsignacion" name="fecha" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="horaAsignacion" class="form-label">Hora de asignación</label>
                            <input type="time" class="form-control" id="horaAsignacion" name="hora" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" onclick="procesarAsignacion()">
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

        // Mostrar debug
        $('#debugAlert').removeClass('d-none');
        $('#debugText').text('Inicializando DataTables...');

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

            $('#debugText').text('DataTables inicializado correctamente');

            // Ocultar debug después de 3 segundos
            setTimeout(function() {
                $('#debugAlert').fadeOut();
            }, 3000);

        } catch (error) {
            console.error('Error inicializando DataTables:', error);
            $('#debugText').text('Error: ' + error.message);
            $('#debugAlert').removeClass('alert-info').addClass('alert-danger');
        }

        // Establecer fecha y hora actual por defecto
        const now = new Date();
        const fechaActual = now.toISOString().split('T')[0];
        const horaActual = now.toTimeString().split(' ')[0].substring(0, 5);

        document.getElementById('fechaAsignacion').value = fechaActual;
        document.getElementById('horaAsignacion').value = horaActual;
    });

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

    // Inicializar vista al cargar
    $(window).on('load', function() {
        actualizarVista();
    });

    // Función para procesar la asignación
    function procesarAsignacion() {
        // Obtener los valores seleccionados
        const unidad = $('#unidadModal').val();
        const operador = $('#operadorModal').val();
        const ruta = $('#rutaModal').val();
        const horario = $('#horarioModal').val();
        const fecha = $('#fechaAsignacion').val();
        const hora = $('#horaAsignacion').val();

        // Validar que todos los campos estén llenos
        if (!unidad || !operador || !ruta || !horario || !fecha || !hora) {
            alert('Por favor completa todos los campos obligatorios.');
            return;
        }

        // Crear objeto con los datos
        const datosAsignacion = {
            id_unidad: unidad,
            id_operador: operador,
            id_ruta: ruta,
            id_horario: horario,
            fecha: fecha,
            hora: hora
        };

        console.log('Datos a asignar:', datosAsignacion);

        // Aquí iría tu petición AJAX para guardar en la base de datos
        fetch('/asignar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(datosAsignacion)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Asignación realizada correctamente');
                    // Cerrar el modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalAsignar'));
                    modal.hide();
                    // Recargar la página para ver los cambios
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al realizar la asignación');
            });
    }

    // Limpiar el formulario cuando se cierra el modal
    $('#modalAsignar').on('hidden.bs.modal', function () {
        $('#formAsignar')[0].reset();

        // Restablecer fecha y hora actual
        const now = new Date();
        const fechaActual = now.toISOString().split('T')[0];
        const horaActual = now.toTimeString().split(' ')[0].substring(0, 5);

        $('#fechaAsignacion').val(fechaActual);
        $('#horaAsignacion').val(horaActual);
    });
</script>

</body>
</html>
