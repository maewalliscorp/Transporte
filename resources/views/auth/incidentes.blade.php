<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Incidentes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
        /* Estilos para los badges de estado */
        .badge-pendiente { background-color: #ffc107; color: #000; }
        .badge-resuelto { background-color: #198754; color: #fff; }

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
    </div>

    <!-- REGISTRO DE INCIDENTES -->
    <div id="seccionRegistro">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Registro de Incidentes</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalIncidente">
                <i class="bi bi-plus-circle"></i> Registrar Incidente
            </button>
        </div>

        <!-- Tabla registro con DataTable -->
        <div class="table-responsive">
            <table class="table table-striped table-hover display nowrap" id="tablaIncidentes" style="width:100%">
                <thead class="table-primary">
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
                                @if($incidente['estado'] == 'Pendiente')
                                    <span class="badge badge-pendiente">{{ $incidente['estado'] }}</span>
                                @else
                                    <span class="badge badge-resuelto">{{ $incidente['estado'] }}</span>
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
    </div>

    <!-- SOLUCIÓN DE INCIDENTES -->
    <div id="seccionSolucion" style="display: none;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Solución de Incidentes</h5>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalSolucion">
                <i class="bi bi-check-circle"></i> Asignar Solución
            </button>
        </div>

        <!-- Tabla solución con DataTable -->
        <div class="table-responsive">
            <table class="table table-striped table-hover display nowrap" id="tablaSolucion" style="width:100%">
                <thead class="table-primary">
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
                                <span class="badge badge-pendiente">Pendiente</span>
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
                    @csrf
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
                        <!-- CAMPO DE ESTADO AGREGADO - SOLO 2 OPCIONES -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Estado del Incidente</label>
                            <select class="form-select" name="estado" required>
                                <option value="Pendiente" selected>Pendiente</option>
                                <option value="Resuelto">Resuelto</option>
                            </select>
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
                <button type="button" class="btn btn-primary" id="btnGuardarIncidente">Guardar Incidente</button>
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
                    @csrf
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
                <button type="button" class="btn btn-success" id="btnGuardarSolucion">Guardar Solución</button>
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
    // Variables globales para las DataTables
    let tableIncidentes, tableSolucion;

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
            order: [[3, 'desc']], // Ordenar por fecha descendente por defecto
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
        };

        // Inicializar DataTables
        tableIncidentes = $('#tablaIncidentes').DataTable(configComun);
        tableSolucion = $('#tablaSolucion').DataTable(configComun);

        // Establecer fecha y hora actual por defecto en modales
        const now = new Date();
        const fechaActual = now.toISOString().split('T')[0];

        // Formatear hora actual (HH:MM)
        const horas = now.getHours().toString().padStart(2, '0');
        const minutos = now.getMinutes().toString().padStart(2, '0');
        const horaActual = `${horas}:${minutos}`;

        $('input[type="date"]').each(function() {
            if (!$(this).val()) {
                $(this).val(fechaActual);
            }
        });

        $('input[type="time"]').each(function() {
            if (!$(this).val()) {
                $(this).val(horaActual);
            }
        });

        // Event listeners para los botones
        $('#btnGuardarIncidente').on('click', guardarIncidente);
        $('#btnGuardarSolucion').on('click', guardarSolucion);
    });

    // Cambiar entre secciones
    $('input[name="tipoVista"]').change(function() {
        actualizarVista();
    });

    function actualizarVista() {
        $('#seccionRegistro').toggle($('#vistaRegistro').is(':checked'));
        $('#seccionSolucion').toggle($('#vistaSolucion').is(':checked'));

        // Redibujar las tablas cuando se muestren
        setTimeout(() => {
            if (tableIncidentes) tableIncidentes.draw();
            if (tableSolucion) tableSolucion.draw();
        }, 100);
    }

    // Función para guardar incidente
    async function guardarIncidente() {
        const formData = new FormData(document.getElementById('formIncidente'));

        try {
            const response = await fetch('{{ route("incidentes.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                // Mostrar alerta de éxito
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Incidente guardado correctamente",
                    showConfirmButton: false,
                    timer: 1500
                });

                // Cerrar modal y limpiar formulario
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalIncidente'));
                modal.hide();
                document.getElementById('formIncidente').reset();

                // Recargar la página después de un tiempo para ver los cambios
                setTimeout(() => {
                    window.location.reload();
                }, 1600);
            } else {
                throw new Error(data.message || 'Error al guardar el incidente');
            }
        } catch (error) {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: error.message,
                showConfirmButton: true
            });
        }
    }

    // Función para guardar solución - TEMPORALMENTE DESHABILITADA
    function guardarSolucion() {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "Función en desarrollo",
            text: "La función de guardar solución estará disponible pronto",
            showConfirmButton: true
        });
    }

    // Función para guardar solución
    async function guardarSolucion() {
        const formData = new FormData(document.getElementById('formSolucion'));

        try {
            const response = await fetch('{{ route("incidentes.solucionar") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                // Mostrar alerta de éxito
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Solución guardada correctamente",
                    showConfirmButton: false,
                    timer: 1500
                });

                // Cerrar modal y limpiar formulario
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalSolucion'));
                modal.hide();
                document.getElementById('formSolucion').reset();

                // Recargar la página después de un tiempo para ver los cambios
                setTimeout(() => {
                    window.location.reload();
                }, 1600);
            } else {
                throw new Error(data.message || 'Error al guardar la solución');
            }
        } catch (error) {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: error.message,
                showConfirmButton: true
            });
        }
    }

    // Inicializar vista al cargar
    $(window).on('load', function() {
        actualizarVista();
    });
</script>
</body>
</html>
