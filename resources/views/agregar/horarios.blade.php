<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Horarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
            <table class="table table-striped table-hover">
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
                        <tr>
                            <td>{{ $horario['id_horario'] }}</td>
                            <td>{{ $horario['horaSalida'] }}</td>
                            <td>{{ $horario['horaLlegada'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($horario['fecha'])->format('d/m/Y') }}</td>
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
                        <label class="form-label">Hora Salida *</label>
                        <input type="time" class="form-control" id="horaSalida" name="horaSalida" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hora Llegada *</label>
                        <input type="time" class="form-control" id="horaLlegada" name="horaLlegada" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha *</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" required>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let modoEdicion = false;

    function editarHorario(id) {
        fetch(`/horarios/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Llenar el formulario con los datos
                    document.getElementById('horarioId').value = data.data.id_horario;
                    document.getElementById('horaSalida').value = data.data.horaSalida;
                    document.getElementById('horaLlegada').value = data.data.horaLlegada;
                    document.getElementById('fecha').value = data.data.fecha;

                    // Cambiar el modal a modo edición
                    document.getElementById('modalTitulo').textContent = 'Editar Horario';
                    document.getElementById('btnGuardar').innerHTML = '<i class="bi bi-check-circle"></i> Actualizar';
                    modoEdicion = true;

                    // Mostrar el modal
                    const modal = new bootstrap.Modal(document.getElementById('modalAgregarHorario'));
                    modal.show();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar los datos del horario');
            });
    }

    function eliminarHorario(id) {
        if (confirm('¿Estás seguro de que deseas eliminar este horario?')) {
            fetch(`/horarios/${id}`, {
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
                    alert('Error al eliminar el horario');
                });
        }
    }

    function guardarHorario() {
        const horarioId = document.getElementById('horarioId').value;
        const horaSalida = document.getElementById('horaSalida').value;
        const horaLlegada = document.getElementById('horaLlegada').value;
        const fecha = document.getElementById('fecha').value;

        // Validaciones básicas
        if (!horaSalida || !horaLlegada || !fecha) {
            alert('Por favor complete todos los campos obligatorios');
            return;
        }

        // Validar que la hora de llegada sea mayor que la de salida
        if (horaSalida >= horaLlegada) {
            alert('La hora de llegada debe ser posterior a la hora de salida');
            return;
        }

        const url = modoEdicion ? `/horarios/${horarioId}` : '/horarios';
        const method = modoEdicion ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                horaSalida: horaSalida,
                horaLlegada: horaLlegada,
                fecha: fecha
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalAgregarHorario'));
                    modal.hide();
                    location.reload(); // Recargar la página para ver los cambios
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al guardar el horario');
            });
    }

    // Limpiar el formulario cuando se cierra el modal
    document.getElementById('modalAgregarHorario').addEventListener('hidden.bs.modal', function () {
        document.getElementById('formHorario').reset();
        document.getElementById('horarioId').value = '';
        document.getElementById('modalTitulo').textContent = 'Agregar Horario';
        document.getElementById('btnGuardar').innerHTML = '<i class="bi bi-check-circle"></i> Guardar';
        modoEdicion = false;
    });

    // Establecer fecha mínima como hoy
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('fecha').min = today;
    });
</script>
</body>
</html>
