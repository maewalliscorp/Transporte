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
                            <h4 class="mb-0">{{ $alertasActivas ?? 0 }}</h4>
                            <p class="mb-0">Activas</p>
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
                        <th>Km Próximo Mant</th>
                        <th>Fecha Próximo</th>
                        <th>Incidentes</th>
                        <th>Estado de Alerta</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @isset($alertasMantenimiento)
                        @foreach($alertasMantenimiento as $alerta)
                            <tr id="fila-alerta-{{ $alerta['id_alertaMantenimiento'] }}">
                                <td>{{ $alerta['placa'] ?? 'N/A' }} - {{ $alerta['modelo'] ?? 'N/A' }}</td>
                                <td>{{ isset($alerta['kmActual']) ? number_format($alerta['kmActual'], 0) . ' km' : 'N/A' }}</td>
                                <td>{{ $alerta['fechaUltimoMantenimiento'] ? \Carbon\Carbon::parse($alerta['fechaUltimoMantenimiento'])->format('d/m/Y') : 'N/A' }}</td>
                                <td>{{ isset($alerta['kmProxMantenimiento']) ? number_format($alerta['kmProxMantenimiento'], 0) . ' km' : 'N/A' }}</td>
                                <td>{{ $alerta['fechaProxMantenimiento'] ? \Carbon\Carbon::parse($alerta['fechaProxMantenimiento'])->format('d/m/Y') : 'N/A' }}</td>
                                <td>{{ $alerta['incidenteReportado'] ?? 'Ninguno' }}</td>
                                <td>
                                    @php
                                        $estadoAlerta = strtolower($alerta['estadoAlerta'] ?? '');
                                    @endphp
                                    @switch($estadoAlerta)
                                        @case('urgente')
                                            <span class="badge bg-danger">Urgente</span>
                                            @break
                                        @case('activa')
                                            <span class="badge bg-warning text-dark">Activa</span>
                                            @break
                                        @case('pendiente')
                                            <span class="badge bg-info text-dark">Pendiente</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $alerta['estadoAlerta'] ?? 'N/A' }}</span>
                                    @endswitch
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
                    <input type="hidden" id="id_mantenimiento" name="id_mantenimiento">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Unidad <span class="text-danger">*</span></label>
                            <select class="form-select" id="unidad" name="unidad" required>
                                <option value="" selected disabled>Selecciona una unidad...</option>
                                @isset($unidades)
                                    @foreach($unidades as $unidad)
                                        @if(isset($unidad['id_mantenimiento']) && !empty($unidad['id_mantenimiento']))
                                            <option value="{{ $unidad['id_unidad'] }}"
                                                    data-kmactual="{{ $unidad['kmActual'] ?? 0 }}"
                                                    data-idmantenimiento="{{ $unidad['id_mantenimiento'] }}">
                                                {{ $unidad['placa'] }} - {{ $unidad['modelo'] }}
                                            </option>
                                        @endif
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kilómetro Actual <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="km_actual" name="km_actual" min="0" readonly required>
                            <div class="form-text">Este valor se carga automáticamente según la unidad seleccionada</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha del Último Mantenimiento <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="fechaUltimoMantenimiento" name="fechaUltimoMantenimiento" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Km para Próximo Mantenimiento <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="kmProxMantenimiento" name="kmProxMantenimiento" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha del Próximo Mantenimiento <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="fechaProxMantenimiento" name="fechaProxMantenimiento" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Incidentes Reportados</label>
                            <input type="text" class="form-control" id="incidenteReportado" name="incidenteReportado" placeholder="Describa incidentes si los hay">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado de Alerta <span class="text-danger">*</span></label>
                            <select class="form-select" id="estadoAlerta" name="estadoAlerta" required>
                                <option value="urgente" style="color:red;">Revisión Urgente</option>
                                <option value="activa" style="color:orange;">Activa</option>
                                <option value="pendiente" style="color:green;">Pendiente</option>
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
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            columnDefs: [
                { orderable: false, targets: [7] } // Deshabilitar ordenamiento en columna Acciones
            ]
        });

        // Evento para cargar kmActual cuando se selecciona una unidad
        $('#unidad').change(function() {
            const selectedOption = $(this).find('option:selected');
            // Usar attr() para obtener el valor directamente del atributo data
            const kmActual = selectedOption.attr('data-kmactual') || selectedOption.data('kmactual');
            const idMantenimiento = selectedOption.attr('data-idmantenimiento') || selectedOption.data('idmantenimiento');

            // Solo validar si NO estamos en modo edición (para creación es obligatorio)
            if (!modoEdicionAlerta) {
                // Validar que se haya capturado el id_mantenimiento solo en creación
                if (!idMantenimiento || idMantenimiento === '' || idMantenimiento === '0') {
                    console.error('No se encontró id_mantenimiento para la unidad seleccionada');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Error',
                        text: 'No se pudo obtener el registro de mantenimiento para esta unidad. Por favor seleccione otra unidad.',
                        confirmButtonColor: '#3085d6'
                    });
                    // Resetear el select
                    $(this).val('');
                    return;
                }
            } else {
                // En modo edición, si se cambió la unidad, actualizar el id_mantenimiento
                // Si no se cambió, mantener el existente
                if (idMantenimiento && idMantenimiento !== '' && idMantenimiento !== '0') {
                    $('#id_mantenimiento').val(idMantenimiento);
                    console.log('Unidad cambiada en edición. Nuevo ID Mantenimiento:', idMantenimiento);
                }
            }

            $('#km_actual').val(kmActual || 0);
            // Actualizar el id_mantenimiento solo si hay un valor válido
            if (idMantenimiento && idMantenimiento !== '' && idMantenimiento !== '0') {
                $('#id_mantenimiento').val(idMantenimiento);
                console.log('ID Mantenimiento actualizado:', idMantenimiento);
            }
            console.log('Km Actual capturado:', kmActual);
        });
    });

    function editarAlerta(id) {
        // Mostrar loading
        const btnEditar = event.target.closest('button');
        const originalContent = btnEditar.innerHTML;
        btnEditar.innerHTML = '<i class="bi bi-hourglass-split"></i>';
        btnEditar.disabled = true;

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
                    document.getElementById('km_actual').value = data.data.kmActual || 0;
                    // Establecer el id_mantenimiento existente en el campo oculto
                    document.getElementById('id_mantenimiento').value = data.data.id_mantenimiento || '';
                    document.getElementById('fechaUltimoMantenimiento').value = data.data.fechaUltimoMantenimiento;
                    document.getElementById('kmProxMantenimiento').value = data.data.kmProxMantenimiento;
                    document.getElementById('fechaProxMantenimiento').value = data.data.fechaProxMantenimiento;
                    document.getElementById('incidenteReportado').value = data.data.incidenteReportado || '';
                    document.getElementById('estadoAlerta').value = data.data.estadoAlerta;

                    // Cambiar el modal a modo edición
                    document.getElementById('modalTituloAlerta').innerHTML = '<i class="bi bi-pencil-square me-2"></i>Editar Alerta de Mantenimiento';
                    document.getElementById('btnGuardarAlerta').innerHTML = '<i class="bi bi-check-circle me-1"></i> Actualizar';
                    modoEdicionAlerta = true;

                    // Hacer el campo km_actual de solo lectura en edición
                    document.getElementById('km_actual').readOnly = true;

                    console.log('Datos cargados para edición:', {
                        id_mantenimiento: data.data.id_mantenimiento,
                        id_unidad: data.data.id_unidad
                    });

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
            })
            .finally(() => {
                // Restaurar botón
                btnEditar.innerHTML = originalContent;
                btnEditar.disabled = false;
            });
    }

    function eliminarAlerta(id, element) {
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
                const btnEliminar = element;
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

                            // Actualizar contadores si es necesario
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
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
        const idMantenimiento = document.getElementById('id_mantenimiento').value;
        const fechaUltimoMantenimiento = document.getElementById('fechaUltimoMantenimiento').value;
        const kmProxMantenimiento = document.getElementById('kmProxMantenimiento').value;
        const fechaProxMantenimiento = document.getElementById('fechaProxMantenimiento').value;
        const incidenteReportado = document.getElementById('incidenteReportado').value.trim();
        const estadoAlerta = document.getElementById('estadoAlerta').value;

        // Validaciones básicas - diferentes para creación y edición
        if (modoEdicionAlerta) {
            // En edición, el id_mantenimiento debe existir (puede ser el original o uno nuevo si se cambió la unidad)
            if (!idMantenimiento || idMantenimiento === '' || !fechaUltimoMantenimiento || !kmProxMantenimiento || !fechaProxMantenimiento || !estadoAlerta) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos incompletos',
                    text: 'Por favor complete todos los campos obligatorios.',
                    confirmButtonColor: '#3085d6'
                });
                console.error('Datos faltantes en edición:', {
                    idMantenimiento: idMantenimiento,
                    fechaUltimoMantenimiento: fechaUltimoMantenimiento,
                    kmProxMantenimiento: kmProxMantenimiento,
                    fechaProxMantenimiento: fechaProxMantenimiento,
                    estadoAlerta: estadoAlerta
                });
                return;
            }
        } else {
            // En creación, validar que se haya seleccionado una unidad
            if (!idMantenimiento || idMantenimiento === '' || !fechaUltimoMantenimiento || !kmProxMantenimiento || !fechaProxMantenimiento || !estadoAlerta) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos incompletos',
                    text: 'Por favor complete todos los campos obligatorios. Asegúrese de seleccionar una unidad.',
                    confirmButtonColor: '#3085d6'
                });
                console.error('Datos faltantes en creación:', {
                    idMantenimiento: idMantenimiento,
                    fechaUltimoMantenimiento: fechaUltimoMantenimiento,
                    kmProxMantenimiento: kmProxMantenimiento,
                    fechaProxMantenimiento: fechaProxMantenimiento,
                    estadoAlerta: estadoAlerta
                });
                return;
            }
        }

        // Validar que id_mantenimiento sea un número válido
        if (isNaN(parseInt(idMantenimiento)) || parseInt(idMantenimiento) <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El ID de mantenimiento no es válido. Por favor seleccione una unidad nuevamente.',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        if (kmProxMantenimiento < 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Valores inválidos',
                text: 'El valor de kilometraje próximo no puede ser negativo',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        // Validar que fecha próximo sea mayor o igual a fecha último
        if (new Date(fechaProxMantenimiento) < new Date(fechaUltimoMantenimiento)) {
            Swal.fire({
                icon: 'warning',
                title: 'Fechas inconsistentes',
                text: 'La fecha del próximo mantenimiento debe ser posterior a la fecha del último mantenimiento',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        // Mostrar loading en el botón
        const btnGuardar = document.getElementById('btnGuardarAlerta');
        const originalText = btnGuardar.innerHTML;
        btnGuardar.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Guardando...';
        btnGuardar.disabled = true;

        const url = modoEdicionAlerta ? `/mantenimiento/alerta/${alertaId}` : '/mantenimiento/alerta';
        const method = modoEdicionAlerta ? 'PUT' : 'POST';

        // Preparar los datos según el modo
        let requestData;

        if (modoEdicionAlerta) {
            // En edición, enviar id_mantenimiento directamente (puede ser el original o uno nuevo si se cambió la unidad)
            const idMantenimientoInt = parseInt(idMantenimiento);
            requestData = {
                id_mantenimiento: idMantenimientoInt,
                fechaUltimoMantenimiento: fechaUltimoMantenimiento,
                kmProxMantenimiento: parseInt(kmProxMantenimiento),
                fechaProxMantenimiento: fechaProxMantenimiento,
                incidenteReportado: incidenteReportado || null,
                estadoAlerta: estadoAlerta
            };
            console.log('Datos a enviar (edición):', requestData);
        } else {
            // En creación, enviar id_mantenimiento directamente
            const idMantenimientoInt = parseInt(idMantenimiento);
            requestData = {
                id_mantenimiento: idMantenimientoInt,
                fechaUltimoMantenimiento: fechaUltimoMantenimiento,
                kmProxMantenimiento: parseInt(kmProxMantenimiento),
                fechaProxMantenimiento: fechaProxMantenimiento,
                incidenteReportado: incidenteReportado || null,
                estadoAlerta: estadoAlerta
            };
            console.log('Datos a enviar (creación):', requestData);
        }

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(requestData)
        })
            .then(response => {
                // Verificar si la respuesta es JSON
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.includes("application/json")) {
                    return response.json();
                } else {
                    return response.text().then(text => {
                        throw new Error('Respuesta no válida del servidor: ' + text);
                    });
                }
            })
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
                    text: 'Error al guardar la alerta: ' + error.message,
                    confirmButtonColor: '#3085d6'
                });
            })
            .finally(() => {
                btnGuardar.innerHTML = originalText;
                btnGuardar.disabled = false;
            });
    }

    // Limpiar el formulario cuando se cierra el modal
    document.getElementById('modalAlertas').addEventListener('hidden.bs.modal', function () {
        document.getElementById('formAlertas').reset();
        document.getElementById('alertaId').value = '';
        document.getElementById('id_mantenimiento').value = '';
        document.getElementById('modalTituloAlerta').innerHTML = '<i class="bi bi-clipboard-check me-2"></i>Crear Alerta de Mantenimiento';
        document.getElementById('btnGuardarAlerta').innerHTML = '<i class="bi bi-check-circle me-1"></i> Guardar Alerta';
        document.getElementById('km_actual').readOnly = false; // Hacer editable en creación
        modoEdicionAlerta = false;
    });

    // Validación en tiempo real para fechas
    document.getElementById('fechaUltimoMantenimiento').addEventListener('change', function() {
        const fechaProx = document.getElementById('fechaProxMantenimiento');
        if (this.value && fechaProx.value && new Date(fechaProx.value) < new Date(this.value)) {
            fechaProx.setCustomValidity('La fecha próxima debe ser posterior a la fecha último');
        } else {
            fechaProx.setCustomValidity('');
        }
    });

    document.getElementById('fechaProxMantenimiento').addEventListener('change', function() {
        const fechaUltimo = document.getElementById('fechaUltimoMantenimiento');
        if (this.value && fechaUltimo.value && new Date(this.value) < new Date(fechaUltimo.value)) {
            this.setCustomValidity('La fecha próxima debe ser posterior a la fecha último');
        } else {
            this.setCustomValidity('');
        }
    });
</script>

</body>
</html>
