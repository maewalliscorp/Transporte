<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mantenimiento Realizado</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="{{ asset('build/assets/estilos.css') }}">

</head>
<body>

<!-- Menú principal -->
@include('layouts.menuPrincipal')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-check-circle me-2"></i>Mantenimientos Realizados</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRealizados">
            <i class="bi bi-plus-circle"></i> Registrar Mantenimiento Realizado
        </button>
    </div>

    <!-- Tabla de mantenimientos realizados -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover display nowrap" id="tablaRealizados" style="width:100%">
                    <thead class="table-primary">
                    <tr>
                        <th>Unidad</th>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Piezas</th>
                        <th>Operador</th>
                        <th>Costo</th>
                        <th>Observaciones</th>
                        <th>Kilometraje</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @isset($mantenimientosRealizados)
                        @foreach($mantenimientosRealizados as $mantenimiento)
                            <tr>
                                <td>{{ $mantenimiento['placa'] ?? 'N/A' }} - {{ $mantenimiento['modelo'] ?? 'N/A' }}</td>
                                <td>{{ $mantenimiento['fecha'] ?? 'N/A' }}</td>
                                <td>{{ $mantenimiento['descripcion'] ?? 'N/A' }}</td>
                                <td>N/A</td>
                                <td>{{ $mantenimiento['licencia'] ?? 'N/A' }}</td>
                                <td>N/A</td>
                                <td>{{ $mantenimiento['descripcion'] ?? 'N/A' }}</td>
                                <td>{{ isset($mantenimiento['kmActual']) ? number_format($mantenimiento['kmActual'], 0) . ' km' : 'N/A' }}</td>
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
                            <td colspan="9" class="text-center text-muted">No hay mantenimientos realizados</td>
                        </tr>
                    @endisset
                    </tbody>
                </table>
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
                            <label class="form-label">Fecha de Mantenimiento <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="fecha_mantenimiento" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Descripción <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="descripcion" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Piezas Reemplazadas</label>
                            <input type="text" class="form-control" name="piezas_reemplazadas">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Operador <span class="text-danger">*</span></label>
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
                            <label class="form-label">Costo <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="costo" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kilometraje Actual <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="kmActual" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control" name="observaciones" rows="3"></textarea>
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

<!-- jQuery + Bootstrap + DataTables + SweetAlert2 JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

<script>
    let tableRealizados;

    $(document).ready(function() {
        tableRealizados = $('#tablaRealizados').DataTable({
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
            order: [[1, 'desc']]
        });

        inicializarBotonesAcciones();
    });

    function inicializarBotonesAcciones() {
        $(document).on('click', '.btn-editar', function() {
            const id = $(this).data('id');

            $.ajax({
                url: `/mantenimiento/realizado/${id}`,
                method: 'GET',
                success: function(response) {
                    $('#formRealizados input[name="fecha_mantenimiento"]').val(response.fecha_programada);
                    $('#formRealizados input[name="descripcion"]').val(response.motivo);
                    $('#formRealizados input[name="kmActual"]').val(response.kmActual);
                    $('#formRealizados select[name="unidad"]').val(response.id_unidad);
                    $('#formRealizados input[name="costo"]').val(response.costo || '');
                    $('#formRealizados textarea[name="observaciones"]').val(response.observaciones || '');

                    $('#modalRealizados').modal('show');
                    $('#modalRealizados .btn-primary').off('click').on('click', function() {
                        actualizarMantenimiento(id);
                    });
                },
                error: function() {
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "Error al cargar los datos del mantenimiento",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });

        $(document).on('click', '.btn-eliminar', function() {
            const id = $(this).data('id');

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/mantenimiento/realizado/${id}`,
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
                                    timer: 1500
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                position: "center",
                                icon: "error",
                                title: "Error al eliminar el mantenimiento",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    });
                }
            });
        });
    }

    function actualizarMantenimiento(id) {
        const formData = new FormData(document.getElementById('formRealizados'));

        $.ajax({
            url: `/mantenimiento/realizado/${id}`,
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
                        $('#modalRealizados').modal('hide');
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "Error al actualizar el mantenimiento",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            },
            error: function() {
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Error al actualizar el mantenimiento",
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    }

    function procesarRealizado() {
        const formData = new FormData(document.getElementById('formRealizados'));

        let valid = true;
        for (let [key, value] of formData.entries()) {
            if (key !== 'piezas_reemplazadas' && key !== 'observaciones' && !value) {
                Swal.fire({
                    position: "center",
                    icon: "warning",
                    title: "Por favor completa todos los campos obligatorios",
                    showConfirmButton: false,
                    timer: 1500
                });
                valid = false;
                break;
            }
        }

        if (!valid) return;

        $.ajax({
            url: '/mantenimiento/realizado',
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
                        title: "Mantenimiento registrado correctamente",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#modalRealizados').modal('hide');
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "Error al registrar el mantenimiento",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            },
            error: function() {
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Error al registrar el mantenimiento",
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    }

    $('#modalRealizados').on('hidden.bs.modal', function () {
        $('#formRealizados')[0].reset();
    });
</script>
</body>
</html>
