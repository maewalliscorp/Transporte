<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Operadores</title>

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
        <h1><i class="bi bi-person-badge me-2"></i>Gestión de Operadores</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarOperador">
            <i class="bi bi-plus-circle"></i> Agregar Operador
        </button>
    </div>

    <!-- Tabla de Operadores -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover display nowrap" id="tablaOperadores" style="width:100%">
                    <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Licencia</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($operadores) > 0)
                        @foreach($operadores as $operador)
                            <tr>
                                <td>{{ $operador['id_operator'] }}</td>
                                <td>{{ $operador['nombre_usuario'] ?? 'N/A' }}</td>
                                <td>{{ $operador['email'] ?? 'N/A' }}</td>
                                <td>{{ $operador['licencia'] }}</td>
                                <td>{{ $operador['telefono'] }}</td>
                                <td>
                                    @php
                                        $estadoClass = [
                                            'activo' => 'bg-success',
                                            'inactivo' => 'bg-secondary',
                                            'suspendido' => 'bg-warning'
                                        ][$operador['estado']] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $estadoClass }}">
                                        {{ ucfirst($operador['estado']) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editarOperador({{ $operador['id_operator'] }})">
                                        <i class="bi bi-pencil"></i> Editar
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="eliminarOperador({{ $operador['id_operator'] }})">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                <i class="bi bi-info-circle"></i> No hay operadores registrados
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Agregar/Editar Operador -->
<div class="modal fade" id="modalAgregarOperador" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitulo">Agregar Operador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formOperador">
                @csrf
                <input type="hidden" id="operadorId" name="id_operator">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Usuario *</label>
                        <select class="form-select" id="id" name="id" required>
                            <option value="" selected disabled>Selecciona un usuario...</option>
                            @if(count($usuarios) > 0)
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario['id'] }}"
                                            data-email="{{ $usuario['email'] }}">
                                        {{ $usuario['name'] }} ({{ $usuario['email'] }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <div class="form-text">
                            Email del usuario:
                            <span id="infoEmail" class="text-muted">Ninguno seleccionado</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Licencia *</label>
                        <input type="text" class="form-control" id="licencia" name="licencia" required
                               maxlength="20" placeholder="Ej: ABC123456">
                        <div class="form-text">Número de licencia del operador</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono *</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono" required
                               maxlength="15" placeholder="Ej: 555-123-4567">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estado *</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="" selected disabled>Selecciona un estado...</option>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                            <option value="suspendido">Suspendido</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarOperador()" id="btnGuardar">
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
    let tableOperadores;

    $(document).ready(function() {
        // Inicializar DataTable
        tableOperadores = $('#tablaOperadores').DataTable({
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

        // Mostrar email del usuario seleccionado
        document.getElementById('id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const email = selectedOption.getAttribute('data-email');

            if (email) {
                document.getElementById('infoEmail').textContent = email;
            } else {
                document.getElementById('infoEmail').textContent = 'Ninguno seleccionado';
            }
        });

        // Formatear teléfono mientras se escribe
        document.getElementById('telefono').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 3 && value.length <= 6) {
                value = value.replace(/(\d{3})(\d+)/, '$1-$2');
            } else if (value.length > 6) {
                value = value.replace(/(\d{3})(\d{3})(\d+)/, '$1-$2-$3');
            }
            e.target.value = value;
        });
    });

    function editarOperador(id) {
        fetch(`/operadores/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Llenar el formulario con los datos
                    document.getElementById('operadorId').value = data.data.id_operator;
                    document.getElementById('licencia').value = data.data.licencia;
                    document.getElementById('telefono').value = data.data.telefono;
                    document.getElementById('estado').value = data.data.estado;

                    // En modo edición, no se puede cambiar el usuario
                    document.getElementById('id').disabled = true;
                    document.getElementById('id').value = data.data.user_id;

                    // Actualizar información del email
                    document.getElementById('infoEmail').textContent = data.data.email || 'N/A';

                    // Cambiar el modal a modo edición
                    document.getElementById('modalTitulo').textContent = 'Editar Operador';
                    document.getElementById('btnGuardar').innerHTML = '<i class="bi bi-check-circle"></i> Actualizar';
                    modoEdicion = true;

                    // Mostrar el modal
                    const modal = new bootstrap.Modal(document.getElementById('modalAgregarOperador'));
                    modal.show();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar los datos del operador');
            });
    }

    function eliminarOperador(id) {
        if (confirm('¿Estás seguro de que deseas eliminar este operador?')) {
            fetch(`/operadores/${id}`, {
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
                    alert('Error al eliminar el operador');
                });
        }
    }

    function guardarOperador() {
        const operadorId = document.getElementById('operadorId').value;
        const usuarioId = document.getElementById('id').value;
        const licencia = document.getElementById('licencia').value.trim();
        const telefono = document.getElementById('telefono').value.trim();
        const estado = document.getElementById('estado').value;

        // Validaciones básicas
        if ((!modoEdicion && !usuarioId) || !licencia || !telefono || !estado) {
            alert('Por favor complete todos los campos obligatorios');
            return;
        }

        const url = modoEdicion ? `/operadores/${operadorId}` : '/operadores';
        const method = modoEdicion ? 'PUT' : 'POST';

        const datos = {
            licencia: licencia,
            telefono: telefono,
            estado: estado
        };

        // Solo incluir el ID del usuario cuando no estemos editando
        if (!modoEdicion) {
            datos.id = parseInt(usuarioId);
        }

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
                    alert(data.message);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalAgregarOperador'));
                    modal.hide();
                    location.reload(); // Recargar la página para ver los cambios
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al guardar el operador');
            });
    }

    // Limpiar el formulario cuando se cierra el modal
    document.getElementById('modalAgregarOperador').addEventListener('hidden.bs.modal', function () {
        document.getElementById('formOperador').reset();
        document.getElementById('operadorId').value = '';
        document.getElementById('id').disabled = false;
        document.getElementById('infoEmail').textContent = 'Ninguno seleccionado';
        document.getElementById('modalTitulo').textContent = 'Agregar Operador';
        document.getElementById('btnGuardar').innerHTML = '<i class="bi bi-check-circle"></i> Guardar';
        modoEdicion = false;
    });
</script>
</body>
</html>
