<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Unidades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
@include('layouts.menuPrincipal')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-bus-front me-2"></i>Gestión de Unidades</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarUnidad">
            <i class="bi bi-plus-circle"></i> Agregar Unidad
        </button>
    </div>

    <!-- Tabla de Unidades -->
    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Placa</th>
                    <th>Modelo</th>
                    <th>Capacidad</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @if(count($unidades) > 0)
                    @foreach($unidades as $unidad)
                        <tr>
                            <td>{{ $unidad['id_unidad'] }}</td>
                            <td>{{ $unidad['placa'] }}</td>
                            <td>{{ $unidad['modelo'] }}</td>
                            <td>{{ $unidad['capacidad'] }} personas</td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editarUnidad({{ $unidad['id_unidad'] }})">
                                    <i class="bi bi-pencil"></i> Editar
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarUnidad({{ $unidad['id_unidad'] }})">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            <i class="bi bi-info-circle"></i> No hay unidades registradas
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para Agregar/Editar Unidad -->
<div class="modal fade" id="modalAgregarUnidad" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitulo">Agregar Unidad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formUnidad">
                @csrf
                <input type="hidden" id="unidadId" name="id_unidad">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Placa *</label>
                        <input type="text" class="form-control" id="placa" name="placa" required
                               maxlength="10" placeholder="Ej: ABC123">
                        <div class="form-text">Ingrese la placa de la unidad</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Modelo *</label>
                        <input type="text" class="form-control" id="modelo" name="modelo" required
                               maxlength="100" placeholder="Ej: Volvo 2023">
                        <div class="form-text">Ingrese el modelo de la unidad</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Capacidad *</label>
                        <input type="number" class="form-control" id="capacidad" name="capacidad" required
                               min="1" max="100" placeholder="Ej: 45">
                        <div class="form-text">Número de personas que puede transportar</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarUnidad()" id="btnGuardar">
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

    function editarUnidad(id) {
        fetch(`/unidades/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Llenar el formulario con los datos
                    document.getElementById('unidadId').value = data.data.id_unidad;
                    document.getElementById('placa').value = data.data.placa;
                    document.getElementById('modelo').value = data.data.modelo;
                    document.getElementById('capacidad').value = data.data.capacidad;

                    // Cambiar el modal a modo edición
                    document.getElementById('modalTitulo').textContent = 'Editar Unidad';
                    document.getElementById('btnGuardar').innerHTML = '<i class="bi bi-check-circle"></i> Actualizar';
                    modoEdicion = true;

                    // Mostrar el modal
                    const modal = new bootstrap.Modal(document.getElementById('modalAgregarUnidad'));
                    modal.show();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar los datos de la unidad');
            });
    }

    function eliminarUnidad(id) {
        if (confirm('¿Estás seguro de que deseas eliminar esta unidad?')) {
            fetch(`/unidades/${id}`, {
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
                    alert('Error al eliminar la unidad');
                });
        }
    }

    function guardarUnidad() {
        const formData = new FormData(document.getElementById('formUnidad'));
        const unidadId = document.getElementById('unidadId').value;

        // Validaciones básicas
        const placa = document.getElementById('placa').value.trim();
        const modelo = document.getElementById('modelo').value.trim();
        const capacidad = document.getElementById('capacidad').value;

        if (!placa || !modelo || !capacidad) {
            alert('Por favor complete todos los campos obligatorios');
            return;
        }

        const url = modoEdicion ? `/unidades/${unidadId}` : '/unidades';
        const method = modoEdicion ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                placa: placa,
                modelo: modelo,
                capacidad: parseInt(capacidad)
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalAgregarUnidad'));
                    modal.hide();
                    location.reload(); // Recargar la página para ver los cambios
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al guardar la unidad');
            });
    }

    // Limpiar el formulario cuando se cierra el modal
    document.getElementById('modalAgregarUnidad').addEventListener('hidden.bs.modal', function () {
        document.getElementById('formUnidad').reset();
        document.getElementById('unidadId').value = '';
        document.getElementById('modalTitulo').textContent = 'Agregar Unidad';
        document.getElementById('btnGuardar').innerHTML = '<i class="bi bi-check-circle"></i> Guardar';
        modoEdicion = false;
    });

    // Validar capacidad mínima
    document.getElementById('capacidad').addEventListener('change', function() {
        if (this.value < 1) {
            this.value = 1;
        }
    });
</script>
</body>
</html>
