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

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="{{ asset('build/assets/estilos.css') }}">

</head>
<body>

<!-- Menú principal -->
@include('layouts.menuPrincipal')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-calendar-check me-2"></i>Mantenimiento Programado</h4>
        <div>
            <a href="{{ route('asignar') }}" class="btn btn-info me-2">
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
                    <thead class="table-primary">
                    <tr>
                        <th>Unidad</th>
                        <th>Fecha Programada</th>
                        <th>Tipo </th>
                        <th>Motivo</th>
                        <th>Piezas</th>
                        <th>Kilometraje</th>
                        <th>Costo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @isset($mantenimientosProgramados)
                        @foreach($mantenimientosProgramados as $mantenimiento)
                            <tr id="fila-{{ $mantenimiento['id_mantenimiento'] }}">
                                <td>{{ $mantenimiento['placa'] ?? 'N/A' }} - {{ $mantenimiento['modelo'] ?? 'N/A' }}</td>
                                <td>{{ $mantenimiento['fecha_programada'] ?? 'N/A' }}</td>
                                <td>
                                    @if(isset($mantenimiento['tipo_mantenimiento']))
                                        @if($mantenimiento['tipo_mantenimiento'] == 'Preventivo')
                                            <span class="badge bg-success">Preventivo</span>
                                        @else
                                            <span class="badge bg-warning">Correctivo</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $mantenimiento['motivo'] ?? 'N/A' }}</td>
                                <td>
                                    @if(isset($mantenimiento['pieza']) && !empty($mantenimiento['pieza']))
                                        {{ $mantenimiento['pieza'] }}
                                    @else
                                        <span class="text-muted">Sin piezas</span>
                                    @endif
                                </td>
                                <td>{{ isset($mantenimiento['kmActual']) ? number_format($mantenimiento['kmActual'], 0) . ' km' : 'N/A' }}</td>
                                <td>
                                    @if(isset($mantenimiento['costo']) && $mantenimiento['costo'] > 0)
                                        ${{ number_format($mantenimiento['costo'], 2) }}
                                    @else
                                        <span class="text-muted">$0.00</span>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($mantenimiento['estado']))
                                        @if($mantenimiento['estado'] == 'pendiente')
                                            <span class="badge bg-info">Pendiente</span>
                                        @elseif($mantenimiento['estado'] == 'completado')
                                            <span class="badge bg-success">Completado</span>
                                        @elseif($mantenimiento['estado'] == 'urgente')
                                            <span class="badge bg-danger">Urgente</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($mantenimiento['estado']) }}</span>
                                        @endif
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
                                                data-id="{{ $mantenimiento['id_mantenimiento'] }}"
                                                onclick="eliminarProgramado({{ $mantenimiento['id_mantenimiento'] }}, this)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9" class="text-center text-muted">No hay mantenimientos programados</td>
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
                <h5 class="modal-title" id="modalTitulo">
                    <i class="bi bi-clipboard-check me-2"></i>Programar Mantenimiento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formProgramacion">
                    @csrf
                    <input type="hidden" id="mantenimientoId" name="mantenimientoId">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Unidad <span class="text-danger">*</span></label>
                            <select class="form-select" id="unidad" name="unidad" required>
                                <option value="" selected disabled>Selecciona una unidad...</option>
                                @isset($unidades)
                                    @foreach($unidades as $unidad)
                                        <option value="{{ $unidad['id_unidad'] }}"
                                                data-placa="{{ $unidad['placa'] }}"
                                                data-modelo="{{ $unidad['modelo'] }}">
                                            {{ $unidad['placa'] }} - {{ $unidad['modelo'] }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tipo de Mantenimiento <span class="text-danger">*</span></label>
                            <select class="form-select" id="tipo_mantenimiento" name="tipo_mantenimiento" required>
                                <option value="Preventivo">Preventivo</option>
                                <option value="Correctivo">Correctivo</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha Programada <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="fecha_programada" name="fecha_programada" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Motivo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="motivo" name="motivo" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Piezas/Repuestos</label>
                            <input type="text" class="form-control" id="pieza" name="pieza" placeholder="Opcional">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Costo Estimado</label>
                            <input type="number" class="form-control" id="costo" name="costo" step="0.01" min="0" placeholder="0.00">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kilometraje Actual <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="kmActual" name="kmActual" required min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado <span class="text-danger">*</span></label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="pendiente">Pendiente</option>
                                <option value="completado">Completado</option>
                                <option value="urgente">Urgente</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="btnGuardar" onclick="guardarProgramacion()">
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
    let modoEdicion = false;
    let tablaProgramacion;

    $(document).ready(function() {
        // Configuración DataTable
        tablaProgramacion = $('#tablaProgramacion').DataTable({
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
            order: [[1, 'asc']],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
        });

        // Mostrar información de la unidad seleccionada
        document.getElementById('unidad').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const placa = selectedOption.getAttribute('data-placa');
            const modelo = selectedOption.getAttribute('data-modelo');

            if (placa && modelo) {
                console.log(`Unidad seleccionada: ${placa} - ${modelo}`);
            }
        });

        // Establecer fecha mínima como hoy
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('fecha_programada').min = today;

        // Inicializar botones de edición
        inicializarBotonesEdicion();
    });

    function inicializarBotonesEdicion() {
        // Editar mantenimiento programado
        $(document).on('click', '.btn-editar', function() {
            const id = $(this).data('id');
            editarProgramado(id);
        });
    }

    function editarProgramado(id) {
        fetch(`/mantenimiento/programado/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Llenar el formulario con los datos
                    document.getElementById('mantenimientoId').value = data.data.id_mantenimiento;
                    document.getElementById('unidad').value = data.data.id_unidad;
                    document.getElementById('tipo_mantenimiento').value = data.data.tipo_mantenimiento;
                    document.getElementById('fecha_programada').value = data.data.fecha_programada;
                    document.getElementById('motivo').value = data.data.motivo;
                    document.getElementById('pieza').value = data.data.pieza || '';
                    document.getElementById('costo').value = data.data.costo || '';
                    document.getElementById('kmActual').value = data.data.kmActual;
                    document.getElementById('estado').value = data.data.estado;

                    // Cambiar el modal a modo edición
                    document.getElementById('modalTitulo').textContent = 'Editar Mantenimiento Programado';
                    document.getElementById('btnGuardar').innerHTML = '<i class="bi bi-check-circle me-1"></i> Actualizar';
                    modoEdicion = true;

                    // Mostrar el modal
                    const modal = new bootstrap.Modal(document.getElementById('modalProgramacion'));
                    modal.show();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al cargar los datos del mantenimiento',
                        confirmButtonColor: '#3085d6'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar los datos del mantenimiento: ' + error.message,
                    confirmButtonColor: '#3085d6'
                });
            });
    }

    function eliminarProgramado(id, event) {
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

                fetch(`/mantenimiento/programado/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => {
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json().then(data => ({
                                data: data,
                                status: response.status,
                                ok: response.ok
                            }));
                        } else {
                            return response.text().then(text => ({
                                data: { success: response.ok, message: text },
                                status: response.status,
                                ok: response.ok
                            }));
                        }
                    })
                    .then(({data, status, ok}) => {
                        console.log('Respuesta del servidor:', data);

                        if (ok && data.success) {
                            Swal.fire({
                                position: "center",
                                icon: "success",
                                title: data.message || "Mantenimiento eliminado correctamente",
                                showConfirmButton: false,
                                timer: 1500
                            });

                            try {
                                const row = tablaProgramacion.row('#fila-' + id);
                                if (row.length !== 0) {
                                    row.remove().draw();
                                } else {
                                    console.log('No se encontró la fila, recargando página...');
                                    setTimeout(() => {
                                        location.reload();
                                    }, 1500);
                                    return;
                                }
                            } catch (error) {
                                console.error('Error al eliminar fila de DataTable:', error);
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                                return;
                            }

                            if (tablaProgramacion.rows().count() === 0) {
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            }
                        } else {
                            let errorMessage = 'Error al eliminar el mantenimiento';

                            if (data && data.message) {
                                errorMessage = data.message;
                            } else if (data && data.errors) {
                                errorMessage = Object.values(data.errors).join(', ');
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage,
                                confirmButtonColor: '#3085d6'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error en la petición:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de conexión',
                            text: 'No se pudo conectar con el servidor',
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

    function guardarProgramacion() {
        const mantenimientoId = document.getElementById('mantenimientoId').value;
        const unidad = document.getElementById('unidad').value;
        const tipoMantenimiento = document.getElementById('tipo_mantenimiento').value;
        const fechaProgramada = document.getElementById('fecha_programada').value;
        const motivo = document.getElementById('motivo').value.trim();
        const pieza = document.getElementById('pieza').value.trim();
        const costo = document.getElementById('costo').value;
        const kmActual = document.getElementById('kmActual').value;
        const estado = document.getElementById('estado').value;

        // Validaciones básicas
        if (!unidad || !tipoMantenimiento || !fechaProgramada || !motivo || !kmActual || !estado) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Por favor complete todos los campos obligatorios',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        if (kmActual < 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Kilometraje inválido',
                text: 'El kilometraje no puede ser negativo',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        if (costo && costo < 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Costo inválido',
                text: 'El costo no puede ser negativo',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        const url = modoEdicion ? `/mantenimiento/programado/${mantenimientoId}` : '/mantenimiento/programado';
        const method = modoEdicion ? 'PUT' : 'POST';

        const datos = {
            unidad: parseInt(unidad),
            tipo_mantenimiento: tipoMantenimiento,
            fecha_programada: fechaProgramada,
            motivo: motivo,
            kmActual: parseInt(kmActual),
            estado: estado
        };

        // Agregar campos opcionales si tienen valor
        if (pieza) datos.pieza = pieza;
        if (costo) datos.costo = parseFloat(costo);

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(datos)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: data.message || (modoEdicion ? "Mantenimiento actualizado correctamente" : "Mantenimiento programado correctamente"),
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('modalProgramacion'));
                        modal.hide();
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al guardar el mantenimiento',
                        confirmButtonColor: '#3085d6'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al guardar el mantenimiento',
                    confirmButtonColor: '#3085d6'
                });
            });
    }

    // Limpiar el formulario cuando se cierra el modal
    document.getElementById('modalProgramacion').addEventListener('hidden.bs.modal', function () {
        document.getElementById('formProgramacion').reset();
        document.getElementById('mantenimientoId').value = '';
        document.getElementById('modalTitulo').textContent = 'Programar Mantenimiento';
        document.getElementById('btnGuardar').innerHTML = '<i class="bi bi-check-circle me-1"></i> Guardar Programación';
        modoEdicion = false;

        // Restablecer fecha mínima como hoy
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('fecha_programada').min = today;
    });
</script>

</body>
</html>
