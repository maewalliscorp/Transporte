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

<!-- Modal para Agregar Horario -->
<div class="modal fade" id="modalAgregarHorario" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Horario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formHorario">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Hora Salida</label>
                        <input type="time" class="form-control" name="horaSalida" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hora Llegada</label>
                        <input type="time" class="form-control" name="horaLlegada" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha</label>
                        <input type="date" class="form-control" name="fecha" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarHorario()">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function editarHorario(id) {
        alert('Editar horario ID: ' + id);
        // Aquí irá la lógica para editar
    }

    function eliminarHorario(id) {
        if (confirm('¿Estás seguro de que deseas eliminar este horario?')) {
            alert('Eliminar horario ID: ' + id);
            // Aquí irá la lógica para eliminar
        }
    }

    function guardarHorario() {
        // Aquí irá la lógica para guardar
        alert('Guardar horario - Pendiente de implementar');
    }
</script>
</body>
</html>
