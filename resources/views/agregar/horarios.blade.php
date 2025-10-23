<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Horarios</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
@include('layouts.menuPrincipal')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-clock me-2"></i>Gestión de Horarios</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarHorario">
            <i class="bi bi-plus-circle"></i> Agregar Horario
        </button>
    </div>

    <!-- Tabla de Horarios -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover display nowrap" id="tablaHorarios" style="width:100%">
                    <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Hora Salida</th>
                        <th>Hora Llegada</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($horarios) > 0)
                        @foreach($horarios as $horario)
                            <tr id="fila-{{ $horario['id_horario'] }}">
                                <td>{{ $horario['id_horario'] }}</td>
                                <td>{{ date('H:i', strtotime($horario['horaSalida'])) }}</td>
                                <td>{{ date('H:i', strtotime($horario['horaLlegada'])) }}</td>
                                <td>{{ date('d/m/Y', strtotime($horario['fecha'])) }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editarHorario({{ $horario['id_horario'] }})">
                                        <i class="bi bi-pencil"></i> Editar
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="eliminarHorario({{ $horario['id_horario'] }})">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                <i class="bi bi-info-circle"></i> No hay horarios registrados
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Agregar/Editar Horario -->
<div class="modal fade" id="modalAgregarHorario" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitulo">Agregar Horario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formHorario">
                @csrf
                <input type="hidden" id="horarioId" name="id_horario">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="horaSalida" class="form-label">Hora Salida *</label>
                        <input type="time" class="form-control" id="horaSalida" name="horaSalida" required>
                        <div class="form-text">Seleccione la hora de salida</div>
                    </div>
                    <div class="mb-3">
                        <label for="horaLlegada" class="form-label">Hora Llegada *</label>
                        <input type="time" class="form-control" id="horaLlegada" name="horaLlegada" required>
                        <div class="form-text">Seleccione la hora de llegada (debe ser posterior a la salida)</div>
                    </div>
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha *</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" required>
                        <div class="form-text">Seleccione la fecha del horario</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarHorario()" id="btnGuardar">
                        <i class="bi bi-check-circle"></i> Guardar
                    </button>
                </div>
            </form>
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
    let modoEdicion = false;
    let tableHorarios;

    $(document).ready(function() {
        // Inicializar DataTable
        tableHorarios = $('#tablaHorarios').DataTable({
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
            order: [[3, 'desc']],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
        });

        // Establecer fecha mínima como hoy
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('fecha').min = today;

        // Validar que la hora de llegada sea mayor que la de salida
        document.getElementById('horaLlegada').addEventListener('change', validarHoras);
        document.getElementById('horaSalida').addEventListener('change', validarHoras);
    });

    function validarHoras() {
        const horaSalida = document.getElementById('horaSalida').value;
        const horaLlegada = document.getElementById('horaLlegada').value;

        if (horaSalida && horaLlegada && horaSalida >= horaLlegada) {
            document.getElementById('horaLlegada').classList.add('is-invalid');
            document.getElementById('horaLlegada').classList.remove('is-valid');
        } else if (horaSalida && horaLlegada) {
            document.getElementById('horaLlegada').classList.remove('is-invalid');
            document.getElementById('horaLlegada').classList.add('is-valid');
        }
    }

    function mostrarAlerta(mensaje, tipo = 'success') {
        Swal.fire({
            position: "center",
            icon: tipo,
            title: mensaje,
            showConfirmButton: false,
            timer: 1500
        });
    }

    function editarHorario(id) {
        const btnGuardar = document.getElementById('btnGuardar');
        const originalText = btnGuardar.innerHTML;
        btnGuardar.innerHTML = '<i class="bi bi-hourglass-split"></i> Cargando...';
        btnGuardar.disabled = true;

        fetch(`/horarios/${id}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                if (ok) {
                    document.getElementById('horarioId').value = data.data.id_horario;
                    document.getElementById('horaSalida').value = data.data.horaSalida;
                    document.getElementById('horaLlegada').value = data.data.horaLlegada;
                    document.getElementById('fecha').value = data.data.fecha;

                    document.getElementById('modalTitulo').textContent = 'Editar Horario';
                    document.getElementById('btnGuardar').innerHTML = '<i class="bi bi-check-circle"></i> Actualizar';
                    modoEdicion = true;

                    validarHoras();

                    const modal = new bootstrap.Modal(document.getElementById('modalAgregarHorario'));
                    modal.show();
                } else {
                    mostrarAlerta('Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error al cargar los datos del horario', 'error');
            })
            .finally(() => {
                btnGuardar.innerHTML = originalText;
                btnGuardar.disabled = false;
            });
    }

    function eliminarHorario(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¿Estás seguro de que deseas eliminar este horario?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const btnEliminar = event.target;
                const originalText = btnEliminar.innerHTML;
                btnEliminar.innerHTML = '<i class="bi bi-hourglass-split"></i> Eliminando...';
                btnEliminar.disabled = true;

                fetch(`/horarios/${id}`, {
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
                        if (ok) {
                            mostrarAlerta(data.message, 'success');
                            tableHorarios.row('#fila-' + id).remove().draw();

                            if (tableHorarios.rows().count() === 0) {
                                location.reload();
                            }
                        } else {
                            mostrarAlerta('Error: ' + data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        mostrarAlerta('Error al eliminar el horario', 'error');
                    })
                    .finally(() => {
                        btnEliminar.innerHTML = originalText;
                        btnEliminar.disabled = false;
                    });
            }
        });
    }

    function guardarHorario() {
        const horarioId = document.getElementById('horarioId').value;
        const horaSalida = document.getElementById('horaSalida').value;
        const horaLlegada = document.getElementById('horaLlegada').value;
        const fecha = document.getElementById('fecha').value;

        if (!horaSalida || !horaLlegada || !fecha) {
            mostrarAlerta('Por favor complete todos los campos obligatorios', 'error');
            return;
        }

        if (horaSalida >= horaLlegada) {
            mostrarAlerta('La hora de llegada debe ser posterior a la hora de salida', 'error');
            return;
        }

        const url = modoEdicion ? `/horarios/${horarioId}` : '/horarios';
        const method = modoEdicion ? 'PUT' : 'POST';

        const btnGuardar = document.getElementById('btnGuardar');
        const originalText = btnGuardar.innerHTML;
        btnGuardar.innerHTML = '<i class="bi bi-hourglass-split"></i> Guardando...';
        btnGuardar.disabled = true;

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                horaSalida: horaSalida,
                horaLlegada: horaLlegada,
                fecha: fecha
            })
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
                if (ok) {
                    mostrarAlerta(data.message, 'success');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalAgregarHorario'));
                    modal.hide();

                    // Recargar la página para mostrar los datos actualizados
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    if (data && data.message) {
                        mostrarAlerta(data.message, 'error');
                    } else {
                        mostrarAlerta('Error al guardar el horario', 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error de conexión al guardar el horario', 'error');
            })
            .finally(() => {
                btnGuardar.innerHTML = originalText;
                btnGuardar.disabled = false;
            });
    }

    // Limpiar el formulario cuando se cierra el modal
    document.getElementById('modalAgregarHorario').addEventListener('hidden.bs.modal', function () {
        document.getElementById('formHorario').reset();
        document.getElementById('horarioId').value = '';
        document.getElementById('modalTitulo').textContent = 'Agregar Horario';
        document.getElementById('btnGuardar').innerHTML = '<i class="bi bi-check-circle"></i> Guardar';
        modoEdicion = false;

        const inputs = document.querySelectorAll('#formHorario input');
        inputs.forEach(input => {
            input.classList.remove('is-invalid');
            input.classList.remove('is-valid');
        });

        const today = new Date().toISOString().split('T')[0];
        document.getElementById('fecha').min = today;
    });

    document.getElementById('formHorario').addEventListener('input', function(e) {
        const input = e.target;
        if (input.value.trim() === '') {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        }
    });

    document.getElementById('formHorario').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            guardarHorario();
        }
    });
</script>
</body>
</html>
