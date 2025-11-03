<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Unidades</title>
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
        <h1><i class="bi bi-bus-front me-2"></i>Gestión de Unidades</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarUnidad">
            <i class="bi bi-plus-circle"></i> Agregar Unidad
        </button>
    </div>

    <!-- Tabla de Unidades -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover display nowrap" id="tablaUnidades" style="width:100%">
                    <thead class="table-primary">
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
                            <tr id="fila-{{ $unidad['id_unidad'] }}">
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
                        <label for="placa" class="form-label">Placa *</label>
                        <input type="text" class="form-control" id="placa" name="placa" required
                               maxlength="10" placeholder="Ej: ABC123">
                        <div class="form-text">Ingrese la placa de la unidad</div>
                    </div>
                    <div class="mb-3">
                        <label for="modelo" class="form-label">Modelo *</label>
                        <input type="text" class="form-control" id="modelo" name="modelo" required
                               maxlength="100" placeholder="Ej: Volvo 2023">
                        <div class="form-text">Ingrese el modelo de la unidad</div>
                    </div>
                    <div class="mb-3">
                        <label for="capacidad" class="form-label">Capacidad *</label>
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
    let tableUnidades;

    $(document).ready(function() {
        // Inicializar DataTable
        tableUnidades = $('#tablaUnidades').DataTable({
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

        // Validar capacidad mínima
        document.getElementById('capacidad').addEventListener('change', function() {
            if (this.value < 1) {
                this.value = 1;
            }
        });

        // Convertir placa a mayúsculas automáticamente
        document.getElementById('placa').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    });

    function mostrarAlerta(mensaje, tipo = 'success') {
        Swal.fire({
            position: "center",
            icon: tipo,
            title: mensaje,
            showConfirmButton: false,
            timer: 1500
        });
    }

    function editarUnidad(id) {
        // Mostrar loading
        const btnGuardar = document.getElementById('btnGuardar');
        const originalText = btnGuardar.innerHTML;
        btnGuardar.innerHTML = '<i class="bi bi-hourglass-split"></i> Cargando...';
        btnGuardar.disabled = true;

        fetch(`/unidades/${id}`, {
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
                    mostrarAlerta('Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error al cargar los datos de la unidad', 'error');
            })
            .finally(() => {
                // Restaurar botón
                btnGuardar.innerHTML = originalText;
                btnGuardar.disabled = false;
            });
    }

    function eliminarUnidad(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¿Estás seguro de que deseas eliminar esta unidad?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Para mostrar loading en el botón
                const btnEliminar = event.target;
                const originalText = btnEliminar.innerHTML;
                btnEliminar.innerHTML = '<i class="bi bi-hourglass-split"></i> Eliminando...';
                btnEliminar.disabled = true;

                fetch(`/unidades/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            mostrarAlerta(data.message, 'success');
                            // Eliminar la fila de la tabla sin recargar
                            tableUnidades.row('#fila-' + id).remove().draw();

                            // Verificar si la tabla quedó vacía
                            if (tableUnidades.rows().count() === 0) {
                                location.reload();
                            }
                        } else {
                            mostrarAlerta('Error: ' + data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        mostrarAlerta('Error al eliminar la unidad', 'error');
                    })
                    .finally(() => {
                        // Restaurar botón
                        btnEliminar.innerHTML = originalText;
                        btnEliminar.disabled = false;
                    });
            }
        });
    }

    function guardarUnidad() {
        const unidadId = document.getElementById('unidadId').value;

        // Validaciones básicas
        const placa = document.getElementById('placa').value.trim();
        const modelo = document.getElementById('modelo').value.trim();
        const capacidad = document.getElementById('capacidad').value;

        if (!placa || !modelo || !capacidad) {
            mostrarAlerta('Por favor complete todos los campos obligatorios', 'error');
            return;
        }

        if (placa.length < 3) {
            mostrarAlerta('La placa debe tener al menos 3 caracteres', 'error');
            return;
        }

        if (capacidad < 1 || capacidad > 100) {
            mostrarAlerta('La capacidad debe estar entre 1 y 100 personas', 'error');
            return;
        }

        const url = modoEdicion ? `/unidades/${unidadId}` : '/unidades';
        const method = modoEdicion ? 'PUT' : 'POST';

        // Mostrar loading en el botón
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
                placa: placa.toUpperCase(),
                modelo: modelo,
                capacidad: parseInt(capacidad)
            })
        })
            .then(response => {
                // Primero intentamos parsear la respuesta como JSON
                return response.json().then(data => {
                    // Creamos un objeto con la respuesta y el status
                    return {
                        data: data,
                        status: response.status,
                        ok: response.ok
                    };
                });
            })
            .then(({data, status, ok}) => {
                if (ok) {
                    // Éxito (status 200-299)
                    mostrarAlerta(data.message, 'success');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalAgregarUnidad'));
                    modal.hide();

                    // Recargar la página después de un breve delay
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    // Error del servidor (status 400-599)
                    if (data && data.message) {
                        // Mostrar el mensaje específico del servidor
                        mostrarAlerta(data.message, 'error');
                    } else {
                        mostrarAlerta('Error al guardar la unidad', 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error de conexión al guardar la unidad', 'error');
            })
            .finally(() => {
                // Restaurar botón
                btnGuardar.innerHTML = originalText;
                btnGuardar.disabled = false;
            });
    }

    // Limpiar el formulario cuando se cierra el modal :3
    document.getElementById('modalAgregarUnidad').addEventListener('hidden.bs.modal', function () {
        document.getElementById('formUnidad').reset();
        document.getElementById('unidadId').value = '';
        document.getElementById('modalTitulo').textContent = 'Agregar Unidad';
        document.getElementById('btnGuardar').innerHTML = '<i class="bi bi-check-circle"></i> Guardar';
        modoEdicion = false;

        // Limpiar clases de validación
        const inputs = document.querySelectorAll('#formUnidad input');
        inputs.forEach(input => {
            input.classList.remove('is-invalid');
            input.classList.remove('is-valid');
        });
    });

    // Validación en tiempo real
    document.getElementById('formUnidad').addEventListener('input', function(e) {
        const input = e.target;
        if (input.value.trim() === '') {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        }
    });

    // Prevenir envío del formulario con Enter
    document.getElementById('formUnidad').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            guardarUnidad();
        }
    });
</script>
</body>
</html>
