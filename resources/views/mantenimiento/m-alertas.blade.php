<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Alertas de Mantenimiento</title>
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
    <link rel="stylesheet" href="{{ asset('build/assets/estilos.css') }}">

</head>
<body>

<!-- Menú principal -->
@include('layouts.menuPrincipal')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-bell me-2"></i>Alertas de Mantenimiento</h4>
        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalAlertas">
            <i class="bi bi-plus-circle"></i> Crear Alerta
        </button>
    </div>

    <!-- Resumen de alertas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $alertasUrgentes ?? 0 }}</h4>
                            <p class="mb-0">Urgentes</p>
                        </div>
                        <i class="bi bi-exclamation-triangle display-6"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $alertasProximas ?? 0 }}</h4>
                            <p class="mb-0">Próximas</p>
                        </div>
                        <i class="bi bi-clock display-6"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $alertasPendientes ?? 0 }}</h4>
                            <p class="mb-0">Pendientes</p>
                        </div>
                        <i class="bi bi-list-check display-6"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $totalAlertas ?? 0 }}</h4>
                            <p class="mb-0">Total</p>
                        </div>
                        <i class="bi bi-bell display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de alertas -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover display nowrap" id="tablaAlertas" style="width:100%">
                    <thead class="table-primary">
                    <tr>
                        <th>Unidad</th>
                        <th>Kilómetro Actual</th>
                        <th>Fecha Último</th>
                        <th>Km Próximo</th>
                        <th>Fecha Próximo</th>
                        <th>Incidentes</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @isset($alertasMantenimiento)
                        @foreach($alertasMantenimiento as $alerta)
                            <tr id="fila-alerta-{{ $alerta['id_alertaMantenimiento'] }}">
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
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary btn-editar-alerta"
                                                data-id="{{ $alerta['id_alertaMantenimiento'] }}"
                                                onclick="editarAlerta({{ $alerta['id_alertaMantenimiento'] }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-eliminar-alerta"
                                                data-id="{{ $alerta['id_alertaMantenimiento'] }}"
                                                onclick="eliminarAlerta({{ $alerta['id_alertaMantenimiento'] }}, this)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center text-muted">No hay alertas de mantenimiento</td>
                        </tr>
                    @endisset
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Alertas -->
<div class="modal fade" id="modalAlertas" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTituloAlerta">
                    <i class="bi bi-clipboard-check me-2"></i>Crear Alerta de Mantenimiento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formAlertas">
                    @csrf
                    <input type="hidden" id="alertaId" name="alertaId">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Unidad <span class="text-danger">*</span></label>
                            <select class="form-select" id="unidad" name="unidad" required>
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
                            <label class="form-label">Kilómetro Actual <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="km_actual" name="km_actual" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha del Último Mantenimiento <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="fechaUltimoMantenimiento" name="fechaUltimoMantenimiento" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Km para Próximo Mantenimiento <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="kmProxMantenimiento" name="kmProxMantenimiento" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha del Próximo Mantenimiento <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="fechaProxMantenimiento" name="fechaProxMantenimiento" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Incidentes Reportados</label>
                            <input type="text" class="form-control" id="incidenteReportado" name="incidenteReportado">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado de Alerta <span class="text-danger">*</span></label>
                            <select class="form-select" id="estadoAlerta" name="estadoAlerta" required>
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
                <button type="button" class="btn btn-primary" id="btnGuardarAlerta" onclick="guardarAlerta()">
                    <i class="bi bi-check-circle me-1"></i>Guardar Alerta
                </button>
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
    let modoEdicionAlerta = false;
    let tablaAlertas;

    $(document).ready(function() {
        tablaAlertas = $('#tablaAlertas').DataTable({
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
                }
            },
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            responsive: true,
            autoWidth: false,
            order: [[6, 'asc'], [4, 'asc']],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
        });
    });

    function editarAlerta(id) {
        fetch(`/mantenimiento/alerta/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Llenar el formulario con los datos
                    document.getElementById('alertaId').value = data.data.id_alertaMantenimiento;
                    document.getElementById('unidad').value = data.data.id_unidad;
                    document.getElementById('km_actual').value = data.data.km_actual;
                    document.getElementById('fechaUltimoMantenimiento').value = data.data.fechaUltimoMantenimiento;
                    document.getElementById('kmProxMantenimiento').value = data.data.kmProxMantenimiento;
                    document.getElementById('fechaProxMantenimiento').value = data.data.fechaProxMantenimiento;
                    document.getElementById('incidenteReportado').value = data.data.incidenteReportado || '';
                    document.getElementById('estadoAlerta').value = data.data.estadoAlerta;

                    // Cambiar el modal a modo edición
                    document.getElementById('modalTituloAlerta').textContent = 'Editar Alerta de Mantenimiento';
                    document.getElementById('btnGuardarAlerta').innerHTML = '<i class="bi bi-check-circle me-1"></i> Actualizar';
                    modoEdicionAlerta = true;

                    // Mostrar el modal
                    const modal = new bootstrap.Modal(document.getElementById('modalAlertas'));
                    modal.show();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al cargar los datos de la alerta',
                        confirmButtonColor: '#3085d6'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar los datos de la alerta: ' + error.message,
                    confirmButtonColor: '#3085d6'
                });
            });
    }

    function eliminarAlerta(id, event) {
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
                const btnEliminar = event;
                const originalText = btnEliminar.innerHTML;
                btnEliminar.innerHTML = '<i class="bi bi-hourglass-split"></i> Eliminando...';
                btnEliminar.disabled = true;

                fetch(`/mantenimiento/alerta/${id}`, {
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
                                position: "center",
                                icon: "success",
                                title: data.message || "Alerta eliminada correctamente",
                                showConfirmButton: false,
                                timer: 1500
                            });

                            // Eliminar la fila de DataTables
                            tablaAlertas.row('#fila-alerta-' + id).remove().draw();

                            // Verificar si quedan filas
                            if (tablaAlertas.rows().count() === 0) {
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Error al eliminar la alerta',
                                confirmButtonColor: '#3085d6'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al eliminar la alerta',
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

    function guardarAlerta() {
        const alertaId = document.getElementById('alertaId').value;
        const unidad = document.getElementById('unidad').value;
        const kmActual = document.getElementById('km_actual').value;
        const fechaUltimoMantenimiento = document.getElementById('fechaUltimoMantenimiento').value;
        const kmProxMantenimiento = document.getElementById('kmProxMantenimiento').value;
        const fechaProxMantenimiento = document.getElementById('fechaProxMantenimiento').value;
        const incidenteReportado = document.getElementById('incidenteReportado').value.trim();
        const estadoAlerta = document.getElementById('estadoAlerta').value;

        // Validaciones básicas
        if (!unidad || !kmActual || !fechaUltimoMantenimiento || !kmProxMantenimiento || !fechaProxMantenimiento || !estadoAlerta) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Por favor complete todos los campos obligatorios',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        if (kmActual < 0 || kmProxMantenimiento < 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Valores inválidos',
                text: 'Los valores de kilometraje no pueden ser negativos',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        const url = modoEdicionAlerta ? `/mantenimiento/alerta/${alertaId}` : '/mantenimiento/alerta';
        const method = modoEdicionAlerta ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                unidad: parseInt(unidad),
                km_actual: parseInt(kmActual),
                fechaUltimoMantenimiento: fechaUltimoMantenimiento,
                kmProxMantenimiento: parseInt(kmProxMantenimiento),
                fechaProxMantenimiento: fechaProxMantenimiento,
                incidenteReportado: incidenteReportado,
                estadoAlerta: estadoAlerta
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: data.message || (modoEdicionAlerta ? "Alerta actualizada correctamente" : "Alerta creada correctamente"),
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('modalAlertas'));
                        modal.hide();
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al guardar la alerta',
                        confirmButtonColor: '#3085d6'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al guardar la alerta',
                    confirmButtonColor: '#3085d6'
                });
            });
    }

    // Limpiar el formulario cuando se cierra el modal
    document.getElementById('modalAlertas').addEventListener('hidden.bs.modal', function () {
        document.getElementById('formAlertas').reset();
        document.getElementById('alertaId').value = '';
        document.getElementById('modalTituloAlerta').textContent = 'Crear Alerta de Mantenimiento';
        document.getElementById('btnGuardarAlerta').innerHTML = '<i class="bi bi-check-circle me-1"></i> Guardar Alerta';
        modoEdicionAlerta = false;
    });
</script>

</body>
</html>
