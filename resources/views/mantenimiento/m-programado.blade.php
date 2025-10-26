<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mantenimiento Programado</title>
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
        }
    </style>
</head>
<body>

<!-- Menú principal -->
@include('layouts.menuPrincipal')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-calendar-check me-2"></i>Mantenimiento Programado</h4>
        <div>
            <a href="{{ route('inicio') }}" class="btn btn-info me-2">
                <i class="bi bi-table"></i> Ir a tabla de disponibilidad
            </a>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalProgramacion">
                <i class="bi bi-plus-circle"></i> Programar Mantenimiento
            </button>
        </div>
    </div>

    <!-- Tabla de mantenimientos programados -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover display nowrap" id="tablaProgramacion" style="width:100%">
                    <thead class="table-dark">
                    <tr>
                        <th>Unidad</th>
                        <th>Tipo</th>
                        <th>Fecha Programada</th>
                        <th>Motivo</th>
                        <th>Kilometraje</th>
                        <th>Estado</th>
                        <th>Acciones</th>
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

                                <td>
                                    @if(isset($mantenimiento['estado']))
                                        <span class="badge bg-warning">{{ ucfirst($mantenimiento['estado']) }}</span>
                                    @else
                                        <span class="badge bg-secondary">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary btn-editar"
                                                data-id="{{ $mantenimiento['id_mantenimiento'] }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-eliminar"
                                                data-id="{{ $mantenimiento['id_mantenimiento'] }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center text-muted">No hay mantenimientos programados</td>
                        </tr>
                    @endisset
                    </tbody>
                </table>
            </div>
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
                            <label class="form-label">Unidad <span class="text-danger">*</span></label>
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
                            <label class="form-label">Tipo de Mantenimiento <span class="text-danger">*</span></label>
                            <select class="form-select" name="tipo_mantenimiento" required>
                                <option value="preventivo">Preventivo</option>
                                <option value="correctivo">Correctivo</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha Programada <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="fecha_programada" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Motivo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="motivo" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kilometraje Actual <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="kmActual" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado de la Unidad <span class="text-danger">*</span></label>
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

<!-- jQuery + Bootstrap + DataTables + SweetAlert2 JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
    let tableProgramacion;

    $(document).ready(function() {
        // Configuración DataTable
        tableProgramacion = $('#tablaProgramacion').DataTable({
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
            order: [[2, 'asc']]
        });

        // Inicializar botones de acción
        inicializarBotonesAcciones();
    });

    function inicializarBotonesAcciones() {
        // Editar mantenimiento
        $(document).on('click', '.btn-editar', function() {
            const id = $(this).data('id');

            $.ajax({
                url: `/mantenimiento/programado/${id}`,
                method: 'GET',
                success: function(response) {
                    $('#formProgramacion input[name="fecha_programada"]').val(response.fecha_programada);
                    $('#formProgramacion input[name="motivo"]').val(response.motivo);
                    $('#formProgramacion input[name="kmActual"]').val(response.kmActual);
                    $('#formProgramacion select[name="unidad"]').val(response.id_unidad);
                    $('#formProgramacion select[name="tipo_mantenimiento"]').val(response.tipo_mantenimiento);

                    $('#modalProgramacion').modal('show');
                    $('#modalProgramacion .btn-primary').off('click').on('click', function() {
                        actualizarMantenimiento(id);
                    });
                },
                error: function() {
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "Error al cargar los datos del mantenimiento",
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            });
        });

        // Eliminar mantenimiento
        $(document).on('click', '.btn-eliminar', function() {
            const id = $(this).data('id');

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción eliminará el mantenimiento programado",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/mantenimiento/programado/${id}`,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "Mantenimiento eliminado correctamente",
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    position: "center",
                                    icon: "error",
                                    title: "Error al eliminar el mantenimiento",
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                position: "center",
                                icon: "error",
                                title: "Error al eliminar el mantenimiento",
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    });
                }
            });
        });
    }

    function actualizarMantenimiento(id) {
        const formData = new FormData(document.getElementById('formProgramacion'));

        $.ajax({
            url: `/mantenimiento/programado/${id}`,
            method: 'PUT',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Mantenimiento actualizado correctamente",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#modalProgramacion').modal('hide');
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "Error al actualizar el mantenimiento",
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            },
            error: function() {
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Error al actualizar el mantenimiento",
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
    }

    function procesarProgramacion() {
        const formData = new FormData(document.getElementById('formProgramacion'));

        let valid = true;
        for (let [key, value] of formData.entries()) {
            if (!value) {
                Swal.fire({
                    position: "center",
                    icon: "warning",
                    title: "Por favor completa todos los campos obligatorios",
                    showConfirmButton: false,
                    timer: 2000
                });
                valid = false;
                break;
            }
        }

        if (!valid) return;

        $.ajax({
            url: '/mantenimiento/programado',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Mantenimiento programado correctamente",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#modalProgramacion').modal('hide');
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "Error al programar el mantenimiento",
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            },
            error: function() {
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Error al programar el mantenimiento",
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
    }

    $('#modalProgramacion').on('hidden.bs.modal', function () {
        $('#formProgramacion')[0].reset();
    });
</script>

</body>
</html>
