<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Rutas</title>

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
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
                    <thead class="table-dark">
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
                            <tr>
                                <td>{{ $ruta['id_ruta'] }}</td>
                                <td>{{ $ruta['nombre'] }}</td>
                                <td>{{ $ruta['origen'] }} - {{ $ruta['destino'] }}</td>
                                <td>{{ $ruta['duracion_estimada'] }}</td>
                                <td>
                                    @if($ruta['horaSalida'] && $ruta['horaLlegada'])
                                        {{ $ruta['horaSalida'] }} - {{ $ruta['horaLlegada'] }}
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
                        <label class="form-label">Nombre de la Ruta *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required
                               maxlength="100" placeholder="Ej: Ruta Centro - Norte">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Origen *</label>
                        <input type="text" class="form-control" id="origen" name="origen" required
                               maxlength="100" placeholder="Ej: Terminal Central">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Destino *</label>
                        <input type="text" class="form-control" id="destino" name="destino" required
                               maxlength="100" placeholder="Ej: Terminal Norte">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Duración Estimada *</label>
                        <input type="text" class="form-control" id="duracion_estimada" name="duracion_estimada" required
                               maxlength="50" placeholder="Ej: 2 horas 30 minutos">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Horario *</label>
                        <select class="form-select" id="id_horario" name="id_horario" required>
                            <option value="" selected disabled>Selecciona un horario...</option>
                            @if(count($horarios) > 0)
                                @foreach($horarios as $horario)
                                    <option value="{{ $horario['id_horario'] }}"
                                            data-hora-salida="{{ $horario['horaSalida'] }}"
                                            data-hora-llegada="{{ $horario['horaLlegada'] }}">
                                        {{ $horario['horaSalida'] }} - {{ $horario['horaLlegada'] }}
                                        ({{ \Carbon\Carbon::parse($horario['fecha'])->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <div class="form-text">
                            Horario seleccionado:
                            <span id="infoHorario" class="text-muted">Ninguno</span>
                        </div>
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

<script>
    let modoEdicion = false;
    let tableRutas;

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
            order: [[0, 'asc']], // Ordenar por ID ascendente por defecto
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
        });

        // Mostrar información del horario seleccionado
        document.getElementById('id_horario').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const horaSalida = selectedOption.getAttribute('data-hora-salida');
            const horaLlegada = selectedOption.getAttribute('data-hora-llegada');

            if (horaSalida && horaLlegada) {
                document.getElementById('infoHorario').textContent = `${horaSalida} - ${horaLlegada}`;
            } else {
                document.getElementById('infoHorario').textContent = 'Ninguno';
            }
        });
    });

    function editarRuta(id) {
        fetch(`/rutas/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Llenar el formulario con los datos
                    document.getElementById('rutaId').value = data.data.id_ruta;
                    document.getElementById('nombre').value = data.data.nombre;
                    document.getElementById('origen').value = data.data.origen;
                    document.getElementById('destino').value = data.data.destino;
                    document.getElementById('duracion_estimada').value = data.data.duracion_estimada;
                    document.getElementById('id_horario').value = data.data.id_horario;

                    // Actualizar información del horario
                    const selectedOption = document.querySelector(`#id_horario option[value="${data.data.id_horario}"]`);
                    if (selectedOption) {
                        const horaSalida = selectedOption.getAttribute('data-hora-salida');
                        const horaLlegada = selectedOption.getAttribute('data-hora-llegada');
                        document.getElementById('infoHorario').textContent = `${horaSalida} - ${horaLlegada}`;
                    }

                    // Cambiar el modal a modo edición
                    document.getElementById('modalTitulo').textContent = 'Editar Ruta';
                    document.getElementById('btnGuardar').innerHTML = '<i class="bi bi-check-circle"></i> Actualizar';
                    modoEdicion = true;

                    // Mostrar el modal
                    const modal = new bootstrap.Modal(document.getElementById('modalAgregarRuta'));
                    modal.show();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar los datos de la ruta');
            });
    }

    function eliminarRuta(id) {
        if (confirm('¿Estás seguro de que deseas eliminar esta ruta?')) {
            fetch(`/rutas/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload(); // Recargar la página para ver los cambios
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar la ruta');
                });
        }
    }

    function guardarRuta() {
        const rutaId = document.getElementById('rutaId').value;
        const nombre = document.getElementById('nombre').value.trim();
        const origen = document.getElementById('origen').value.trim();
        const destino = document.getElementById('destino').value.trim();
        const duracion = document.getElementById('duracion_estimada').value.trim();
        const idHorario = document.getElementById('id_horario').value;

        // Validaciones básicas
        if (!nombre || !origen || !destino || !duracion || !idHorario) {
            alert('Por favor complete todos los campos obligatorios');
            return;
        }

        const url = modoEdicion ? `/rutas/${rutaId}` : '/rutas';
        const method = modoEdicion ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                nombre: nombre,
                origen: origen,
                destino: destino,
                duracion_estimada: duracion,
                id_horario: parseInt(idHorario)
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalAgregarRuta'));
                    modal.hide();
                    location.reload(); // Recargar la página para ver los cambios
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al guardar la ruta');
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
    });
</script>
</body>
</html>
