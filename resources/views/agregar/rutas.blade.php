<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Rutas</title>
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
@include('layouts.menuPrincipal')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-geo-alt me-2"></i>Gestión de Rutas</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarRuta">
            <i class="bi bi-plus-circle"></i> Agregar Ruta
        </button>
    </div>

    <!-- Tabla de Rutas -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover display nowrap" id="tablaRutas" style="width:100%">
                    <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Origen - Destino</th>
                        <th>Duración</th>
                        <th>Horario</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($rutas) > 0)
                        @foreach($rutas as $ruta)
                            <tr id="fila-{{ $ruta['id_ruta'] }}">
                                <td>{{ $ruta['id_ruta'] }}</td>
                                <td>{{ $ruta['nombre'] }}</td>
                                <td>{{ $ruta['origen'] }} - {{ $ruta['destino'] }}</td>
                                <td>{{ $ruta['duracion_estimada'] }}</td>
                                <td>
                                    @if($ruta['horaSalida'] && $ruta['horaLlegada'])
                                        {{ date('H:i', strtotime($ruta['horaSalida'])) }} - {{ date('H:i', strtotime($ruta['horaLlegada'])) }}
                                    @else
                                        <span class="text-muted">Sin horario</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editarRuta({{ $ruta['id_ruta'] }})">
                                        <i class="bi bi-pencil"></i> Editar
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="eliminarRuta({{ $ruta['id_ruta'] }})">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                <i class="bi bi-info-circle"></i> No hay rutas registradas
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Agregar/Editar Ruta -->
<div class="modal fade" id="modalAgregarRuta" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitulo">Agregar Ruta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formRuta">
                @csrf
                <input type="hidden" id="rutaId" name="id_ruta">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Ruta *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required
                               maxlength="100" placeholder="Ej: Ruta Centro - Norte">
                        <div class="form-text">Ingrese un nombre único para la ruta</div>
                    </div>
                    <div class="mb-3">
                        <label for="origen" class="form-label">Origen *</label>
                        <input type="text" class="form-control" id="origen" name="origen" required
                               maxlength="100" placeholder="Ej: Terminal Central">
                    </div>
                    <div class="mb-3">
                        <label for="destino" class="form-label">Destino *</label>
                        <input type="text" class="form-control" id="destino" name="destino" required
                               maxlength="100" placeholder="Ej: Terminal Norte">
                    </div>
                    <div class="mb-3">
                        <label for="id_horario" class="form-label">Horario *</label>
                        <select class="form-select" id="id_horario" name="id_horario" required>
                            <option value="" selected disabled>Selecciona un horario...</option>
                            @if(count($horarios) > 0)
                                @foreach($horarios as $horario)
                                    <option value="{{ $horario['id_horario'] }}"
                                            data-hora-salida="{{ $horario['horaSalida'] }}"
                                            data-hora-llegada="{{ $horario['horaLlegada'] }}"
                                            data-fecha="{{ $horario['fecha'] }}">
                                        {{ date('H:i', strtotime($horario['horaSalida'])) }} - {{ date('H:i', strtotime($horario['horaLlegada'])) }}
                                        ({{ date('d/m/Y', strtotime($horario['fecha'])) }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <div class="form-text">
                            Horario seleccionado:
                            <span id="infoHorario" class="text-muted">Ninguno</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="duracion_estimada" class="form-label">Duración Estimada *</label>
                        <input type="text" class="form-control" id="duracion_estimada" name="duracion_estimada" required
                               maxlength="8" placeholder="Se calculará automáticamente" readonly>
                        <div class="form-text">La duración se calcula automáticamente basada en el horario seleccionado (formato HH:MM:SS)</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarRuta()" id="btnGuardar">
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
    let tableRutas;
    let horarioActual = null; // Variable para guardar el horario actual al editar

    $(document).ready(function() {
        // Inicializar DataTable
        tableRutas = $('#tablaRutas').DataTable({
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

        // Mostrar información del horario seleccionado y calcular duración
        document.getElementById('id_horario').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const horaSalida = selectedOption.getAttribute('data-hora-salida');
            const horaLlegada = selectedOption.getAttribute('data-hora-llegada');
            const fecha = selectedOption.getAttribute('data-fecha');

            if (horaSalida && horaLlegada && fecha) {
                const fechaFormateada = new Date(fecha).toLocaleDateString('es-ES');
                document.getElementById('infoHorario').textContent =
                    `${horaSalida.substring(0,5)} - ${horaLlegada.substring(0,5)} (${fechaFormateada})`;

                // Calcular y mostrar la duración
                calcularDuracion(horaSalida, horaLlegada);
            } else {
                document.getElementById('infoHorario').textContent = 'Ninguno';
                document.getElementById('duracion_estimada').value = '';
            }
        });
    });

    function calcularDuracion(horaSalida, horaLlegada) {
        // Convertir las horas a objetos Date (usamos una fecha cualquiera)
        const fechaBase = '2000-01-01';
        const salida = new Date(`${fechaBase}T${horaSalida}`);
        const llegada = new Date(`${fechaBase}T${horaLlegada}`);

        // Calcular la diferencia en milisegundos
        const diferenciaMs = llegada - salida;

        // Convertir a horas, minutos y segundos
        const horas = Math.floor(diferenciaMs / (1000 * 60 * 60));
        const minutos = Math.floor((diferenciaMs % (1000 * 60 * 60)) / (1000 * 60));
        const segundos = Math.floor((diferenciaMs % (1000 * 60)) / 1000);

        // Formatear la duración en formato HH:MM:SS
        const duracionFormateada =
            horas.toString().padStart(2, '0') + ':' +
            minutos.toString().padStart(2, '0') + ':' +
            segundos.toString().padStart(2, '0');

        // Actualizar el campo de duración
        document.getElementById('duracion_estimada').value = duracionFormateada;
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

    function editarRuta(id) {
        const btnGuardar = document.getElementById('btnGuardar');
        const originalText = btnGuardar.innerHTML;
        btnGuardar.innerHTML = '<i class="bi bi-hourglass-split"></i> Cargando...';
        btnGuardar.disabled = true;

        fetch(`/rutas/${id}`, {
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
                    document.getElementById('rutaId').value = data.data.id_ruta;
                    document.getElementById('nombre').value = data.data.nombre;
                    document.getElementById('origen').value = data.data.origen;
                    document.getElementById('destino').value = data.data.destino;
                    document.getElementById('duracion_estimada').value = data.data.duracion_estimada;

                    // Guardar el horario actual
                    horarioActual = data.data.id_horario;

                    // Actualizar el select de horarios
                    actualizarSelectHorarios(data.data.id_horario, data.data.horaSalida, data.data.horaLlegada, data.data.fecha);

                    document.getElementById('modalTitulo').textContent = 'Editar Ruta';
                    document.getElementById('btnGuardar').innerHTML = '<i class="bi bi-check-circle"></i> Actualizar';
                    modoEdicion = true;

                    const modal = new bootstrap.Modal(document.getElementById('modalAgregarRuta'));
                    modal.show();
                } else {
                    mostrarAlerta('Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error al cargar los datos de la ruta', 'error');
            })
            .finally(() => {
                btnGuardar.innerHTML = originalText;
                btnGuardar.disabled = false;
            });
    }

    function actualizarSelectHorarios(idHorarioActual, horaSalida, horaLlegada, fecha) {
        const selectHorario = document.getElementById('id_horario');

        // Verificar si el horario actual ya está en las opciones
        const opcionExistente = selectHorario.querySelector(`option[value="${idHorarioActual}"]`);

        if (!opcionExistente) {
            // Si no existe, crear una nueva opción con el horario actual
            const nuevaOpcion = document.createElement('option');
            nuevaOpcion.value = idHorarioActual;
            nuevaOpcion.textContent = `${horaSalida.substring(0,5)} - ${horaLlegada.substring(0,5)} (${new Date(fecha).toLocaleDateString('es-ES')})`;
            nuevaOpcion.setAttribute('data-hora-salida', horaSalida);
            nuevaOpcion.setAttribute('data-hora-llegada', horaLlegada);
            nuevaOpcion.setAttribute('data-fecha', fecha);

            // Agregar la nueva opción al select
            selectHorario.appendChild(nuevaOpcion);
        }

        // Seleccionar el horario actual
        selectHorario.value = idHorarioActual;

        // Actualizar la información del horario
        const fechaFormateada = new Date(fecha).toLocaleDateString('es-ES');
        document.getElementById('infoHorario').textContent =
            `${horaSalida.substring(0,5)} - ${horaLlegada.substring(0,5)} (${fechaFormateada})`;
    }

    function eliminarRuta(id) {
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
                const btnEliminar = event.target;
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
                        if (ok) {
                            mostrarAlerta(data.message, 'success');
                            tableRutas.row('#fila-' + id).remove().draw();

                            if (tableRutas.rows().count() === 0) {
                                location.reload();
                            }
                        } else {
                            mostrarAlerta('Error: ' + data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        mostrarAlerta('Error al eliminar la ruta', 'error');
                    })
                    .finally(() => {
                        btnEliminar.innerHTML = originalText;
                        btnEliminar.disabled = false;
                    });
            }
        });
    }

    function guardarRuta() {
        const rutaId = document.getElementById('rutaId').value;
        const nombre = document.getElementById('nombre').value.trim();
        const origen = document.getElementById('origen').value.trim();
        const destino = document.getElementById('destino').value.trim();
        const duracion = document.getElementById('duracion_estimada').value.trim();
        const idHorario = document.getElementById('id_horario').value;

        if (!nombre || !origen || !destino || !duracion || !idHorario) {
            mostrarAlerta('Por favor complete todos los campos obligatorios', 'error');
            return;
        }

        if (nombre.length < 3) {
            mostrarAlerta('El nombre de la ruta debe tener al menos 3 caracteres', 'error');
            return;
        }

        // Validar formato de duración (HH:MM:SS)
        const formatoDuracion = /^([0-1]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/;
        if (!formatoDuracion.test(duracion)) {
            mostrarAlerta('Formato de duración inválido. Debe ser HH:MM:SS', 'error');
            return;
        }

        const url = modoEdicion ? `/rutas/${rutaId}` : '/rutas';
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
                nombre: nombre,
                origen: origen,
                destino: destino,
                duracion_estimada: duracion,
                id_horario: parseInt(idHorario)
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
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalAgregarRuta'));
                    modal.hide();

                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    if (data && data.message) {
                        mostrarAlerta(data.message, 'error');
                    } else {
                        mostrarAlerta('Error al guardar la ruta', 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error de conexión al guardar la ruta', 'error');
            })
            .finally(() => {
                btnGuardar.innerHTML = originalText;
                btnGuardar.disabled = false;
            });
    }

    // Limpiar el formulario cuando se cierra el modal
    document.getElementById('modalAgregarRuta').addEventListener('hidden.bs.modal', function () {
        document.getElementById('formRuta').reset();
        document.getElementById('rutaId').value = '';
        document.getElementById('infoHorario').textContent = 'Ninguno';
        document.getElementById('modalTitulo').textContent = 'Agregar Ruta';
        document.getElementById('btnGuardar').innerHTML = '<i class="bi bi-check-circle"></i> Guardar';
        modoEdicion = false;
        horarioActual = null;

        // Limpiar opciones adicionales que se hayan agregado al editar
        const selectHorario = document.getElementById('id_horario');
        const opcionesAdicionales = selectHorario.querySelectorAll('option[data-temporal="true"]');
        opcionesAdicionales.forEach(opcion => opcion.remove());

        const inputs = document.querySelectorAll('#formRuta input, #formRuta select');
        inputs.forEach(input => {
            input.classList.remove('is-invalid');
            input.classList.remove('is-valid');
        });
    });

    document.getElementById('formRuta').addEventListener('input', function(e) {
        const input = e.target;
        if (input.value.trim() === '') {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        }
    });

    document.getElementById('formRuta').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            guardarRuta();
        }
    });
</script>
</body>
</html>
