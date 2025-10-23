    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Gestión de Operadores</title>


        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
    @include('layouts.menuPrincipal')

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-person-badge me-2"></i>Gestión de Operadores</h1>
            <button class="btn btn-primary" id="btnAbrirAgregar">
                <i class="bi bi-plus-circle"></i> Agregar Operador
            </button>
        </div>

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
                            <th>Código</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
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
                                <td>{{ $operador['codigo'] }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="abrirEditarOperador({{ $operador['id_operator'] }})">
                                        <i class="bi bi-pencil"></i> Editar
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="eliminarOperador({{ $operador['id_operator'] }})">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAgregarOperador" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Operador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formAgregar">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Usuario *</label>
                            <select class="form-select" id="id" name="id" required> <option value="" selected disabled>Selecciona un usuario...</option> @if(count($usuarios) > 0) @foreach($usuarios as $usuario) <option value="{{ $usuario['id'] }}" data-email="{{ $usuario['email'] }}"> {{ $usuario['name'] }} ({{ $usuario['email'] }}) </option> @endforeach @endif </select>
                            <small id="infoEmailAgregar" class="text-muted d-block mt-1">Ninguno seleccionado</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Licencia *</label>
                            <input type="text" class="form-control" id="licenciaAgregar" required maxlength="20" placeholder="Ej: ABC123456">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Teléfono *</label>
                            <input type="tel" class="form-control" id="telefonoAgregar" required maxlength="15" placeholder="Ej: 555-123-4567">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Estado *</label>
                            <select class="form-select" id="estadoAgregar" required>
                                <option value="" selected disabled>Selecciona un estado...</option>
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                                <option value="suspendido">Suspendido</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Código</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="codigoAgregar" readonly placeholder="Presiona 'Generar código'">
                                <button type="button" class="btn btn-secondary" id="btnGenerarCodigo">Generar código</button>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="guardarAgregar()">
                            <i class="bi bi-check-circle"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditarOperador" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Operador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formEditar">
                    @csrf
                    <input type="hidden" id="idEditar">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Usuario *</label>
                            <select class="form-select" id="selectEditarUsuario" disabled></select>
                            <small id="infoEmailEditar" class="text-muted d-block mt-1">Ninguno seleccionado</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Licencia *</label>
                            <input type="text" class="form-control" id="licenciaEditar" required maxlength="20">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Teléfono *</label>
                            <input type="tel" class="form-control" id="telefonoEditar" required maxlength="15">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Estado *</label>
                            <select class="form-select" id="estadoEditar" required>
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                                <option value="suspendido">Suspendido</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Código</label>
                            <input type="text" class="form-control" id="codigoEditar" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="guardarEditar()">
                            <i class="bi bi-check-circle"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tablaOperadores').DataTable({
                responsive: true,
                order: [[0, 'asc']],
                pageLength: 10,
                language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" }
            });

            $('#id').on('change', function() {
                const email = $(this).find(':selected').data('email');
                $('#infoEmailAgregar').text(email || 'Ninguno seleccionado');
            });
        });

        let codigoGenerado = null;
        const urlOperadores = "{{ url('/operadores') }}";

        $('#btnAbrirAgregar').click(function() {
            limpiarAgregar();
            new bootstrap.Modal(document.getElementById('modalAgregarOperador')).show();
        });

        $('#btnGenerarCodigo').click(function() {
            codigoGenerado = Math.floor(10000 + Math.random() * 90000);
            $('#codigoAgregar').val(codigoGenerado);
        });

        function limpiarAgregar() {
            $('#formAgregar')[0].reset();
            $('#infoEmailAgregar').text('Ninguno seleccionado');
            $('#codigoAgregar').val('');
            codigoGenerado = null;
        }

        function guardarAgregar() {
            const id = $('#id').val();
            const licencia = $('#licenciaAgregar').val().trim();
            const telefono = $('#telefonoAgregar').val().trim();
            const estado = $('#estadoAgregar').val();
            const codigo = $('#codigoAgregar').val();

            if(!id || !licencia || !telefono || !estado || !codigo){
                Swal.fire('Atención','Completa todos los campos','warning');
                return;
            }

            fetch(urlOperadores, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN':'{{ csrf_token() }}',
                    'Content-Type':'application/json'
                },
                body: JSON.stringify({id, licencia, telefono, estado, codigo})
            })
                .then(res => res.json())
                .then(data => {
                    if(data.success) Swal.fire('Éxito', data.message,'success').then(()=> location.reload());
                    else Swal.fire('Error', data.message,'error');
                })
                .catch(()=> Swal.fire('Error','No se pudo guardar el operador','error'));
        }

        function abrirEditarOperador(id) {
            fetch(`${urlOperadores}/${id}`)
                .then(res => res.json())
                .then(data => {
                    if(!data.success) { Swal.fire('Error',data.message,'error'); return; }

                    const u = data.data;
                    const select = $('#selectEditarUsuario');
                    select.empty().append(`<option value="${u.user_id}" selected>${u.nombre_usuario} (${u.email})</option>`);
                    $('#infoEmailEditar').text(u.email);
                    $('#idEditar').val(u.id_operator);
                    $('#licenciaEditar').val(u.licencia);
                    $('#telefonoEditar').val(u.telefono);
                    $('#estadoEditar').val(u.estado.toLowerCase());
                    $('#codigoEditar').val(u.codigo);

                    new bootstrap.Modal(document.getElementById('modalEditarOperador')).show();
                });
        }

        function guardarEditar() {
            const id_operator = $('#idEditar').val();
            const licencia = $('#licenciaEditar').val().trim();
            const telefono = $('#telefonoEditar').val().trim();
            const estado = $('#estadoEditar').val();
            const codigo = $('#codigoEditar').val();

            if(!licencia || !telefono || !estado || !codigo){
                Swal.fire('Atención','Completa todos los campos','warning');
                return;
            }

            fetch(`${urlOperadores}/${id_operator}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN':'{{ csrf_token() }}',
                    'Content-Type':'application/json'
                },
                body: JSON.stringify({licencia, telefono, estado, codigo})
            })
                .then(res => res.json())
                .then(data => {
                    if(data.success) Swal.fire('Éxito', data.message,'success').then(()=> location.reload());
                    else Swal.fire('Error', data.message,'error');
                })
                .catch(()=> Swal.fire('Error','No se pudo actualizar','error'));
        }

        function eliminarOperador(id){
            Swal.fire({
                title:'¿Deseas eliminar este operador?',
                icon:'warning',
                showCancelButton:true,
                confirmButtonText:'Sí, eliminar',
                cancelButtonText:'Cancelar'
            }).then(result=>{
                if(result.isConfirmed){
                    fetch(`${urlOperadores}/${id}`,{
                        method:'DELETE',
                        headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}
                    })
                        .then(res => res.json())
                        .then(data => {
                            if(data.success) Swal.fire('Eliminado',data.message,'success').then(()=> location.reload());
                            else Swal.fire('Error',data.message,'error');
                        })
                        .catch(()=> Swal.fire('Error','No se pudo eliminar','error'));
                }
            });
        }
    </script>

    </body>
    </html>
