<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Incidentes</title>
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
        }
        .table-actions {
            white-space: nowrap;
        }
    </style>
</head>
<body>
@include('layouts.menuPrincipal')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-exclamation-triangle me-2"></i>Gestión de Incidentes</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarIncidente">
            <i class="bi bi-plus-circle"></i> Registrar Incidente
        </button>
    </div>

    <!-- Tabla de Incidentes -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover display nowrap" id="tablaIncidentes" style="width:100%">
                    <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Asignación</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Estado</th>
                        <th class="table-actions">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($incidentes) > 0)
                        @foreach($incidentes as $incidente)
                            <tr>
                                <td>{{ $incidente['id_incidente'] }}</td>
                                <td>
                                    @if($incidente['placa'] && $incidente['licencia'])
                                        {{ $incidente['placa'] }} - {{ $incidente['licencia'] }}
                                        <br><small class="text-muted">{{ $incidente['origen'] }} - {{ $incidente['destino'] }}</small>
                                    @else
                                        <span class="text-muted">Asignación no disponible</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($incidente['descripcion'], 50) }}</td>
                                <td>{{ $incidente['fecha'] }}</td>
                                <td>{{ $incidente['hora'] }}</td>
                                <td>
                                    @if($incidente['estado'] == 'pendiente')
                                        <span class="badge bg-warning">Pendiente</span>
                                    @else
                                        <span class="badge bg-success">Resuelto</span>
                                    @endif
                                </td>
                                <td class="table-actions">
                                    <button class="btn btn-warning btn-sm" onclick="editarIncidente({{ $incidente['id_incidente'] }})">
                                        <i class="bi bi-pencil"></i> Editar
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="eliminarIncidente({{ $incidente['id_incidente'] }})">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                <i class="bi bi-info-circle"></i> No hay incidentes registrados
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Agregar/Editar Incidente -->
<div class="modal fade" id="modalAgregarIncidente" tabindex="-1">
    <div class="modal-dialog">
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
                            @if(count($asignaciones) > 0)
                                @foreach($asignaciones as $asignacion)
                                    <option value="{{ $asignacion['id_asignacion'] }}"
                                            data-placa="{{ $asignacion['placa'] ?? '' }}"
                                            data-licencia="{{ $asignacion['licencia'] ?? '' }}"
                                            data-ruta="{{ ($asignacion['origen'] ?? '') . ' - ' . ($asignacion['destino'] ?? '') }}">
                                        {{ $asignacion['placa'] ?? 'N/A' }} - {{ $asignacion['licencia'] ?? 'N/A' }}
                                        ({{ $asignacion['origen'] ?? 'N/A' }} - {{ $asignacion['destino'] ?? 'N/A' }})
                                    </option>
                                @endforeach
                            @endif
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
    let tablaIncidentes;

    $(document).ready(function() {
        // Inicializar DataTable
        tablaIncidentes = $('#tablaIncidentes').DataTable({
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
            order: [[0, 'asc']],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
        });

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

        // Establecer fecha y hora actual por defecto
        const now = new Date();
        const fechaActual = now.toISOString().split('T')[0];
        const horas = now.getHours().toString().padStart(2, '0');
        const minutos = now.getMinutes().toString().padStart(2, '0');
        const horaActual = `${horas}:${minutos}`;

        document.getElementById('fecha').value = fechaActual;
        document.getElementById('hora').value = horaActual;
    });

    function editarIncidente(id) {
        fetch(`/tincidente/${id}`)
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

    function eliminarIncidente(id) {
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
            if (result.isConffunction eliminarRuta(id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¿Estás seguro de que deseas eliminar esta ruta?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Obtener el botón que disparó el evento
                        const btnEliminar = document.querySelector(`button[onclick="eliminarRuta(${id})"]`);
                        const originalText = btnEliminar.innerHTML;
                        btnEliminar.innerHTML = '<i class="bi bi-hourglass-split"></i> Eliminando...';
                        btnEliminar.disabled = true;

                        fetch(`/rutas/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                            .then(response => {
                                return response.json().then(data => {
                                    return {
                                        data: data,
                                        status: response.status,
                                        ok: response.ok
                                    };
                                });
                            })
                            .then(({data, status, ok}) => {
                                if (ok && data.success) {
                                    Swal.fire({
                                        position: "top-end",
                                        icon: "success",
                                        title: data.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });

                                    // Si estás usando DataTables
                                    if (typeof tableRutas !== 'undefined') {
                                        tableRutas.row(`#fila-${id}`).remove().draw();

                                        if (tableRutas.rows().count() === 0) {
                                            setTimeout(() => location.reload(), 1500);
                                        }
                                    } else {
                                        // Si no usas DataTables, recargar la página
                                        setTimeout(() => location.reload(), 1500);
                                    }
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: data.message || 'Error al eliminar la ruta',
                                        confirmButtonColor: '#3085d6'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error al eliminar la ruta',
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

        const url = modoEdicion ? `/tincidente/${incidenteId}` : '/tincidente';
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
</script>
</body>
</html>
