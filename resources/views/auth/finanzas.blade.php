<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestión Financiera</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        body {
            font-family: "Open Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #E3F2FD 0%, #F3E5F5 100%);
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #E1F5FE;
            transition: all 0.3s ease;
            background: white;
        }
        .form-control:focus, .form-select:focus {
            border-color: #4FC3F7;
            box-shadow: 0 0 0 0.2rem rgba(79, 195, 247, 0.25);
        }
        .table-hover tbody tr:hover {
            background: rgba(79, 195, 247, 0.1);
            transition: all 0.2s ease;
        }
        .table-responsive {
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .form-label {
            font-weight: 600;
            color: #37474F;
            margin-bottom: 0.5rem;
        }
        .btn-sm {
            margin: 0 2px;
        }
    </style>

</head>
<body>

@include('layouts.menuPrincipal')

<div class="container mt-4">
    <h4 class="mb-3">Gestión Financiera</h4>

    <!-- Seleccionar tipo -->
    <div class="mb-4">
        <label class="form-label me-3">Selecciona tipo de vista:</label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tipoVista" id="vistaIngresos" value="ingresos" checked>
            <label class="form-check-label" for="vistaIngresos">Ingresos</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tipoVista" id="vistaEgresos" value="egresos">
            <label class="form-check-label" for="vistaEgresos">Egresos</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tipoVista" id="vistaTarifas" value="tarifas">
            <label class="form-check-label" for="vistaTarifas">Tarifas</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tipoVista" id="vistaConciliaciones" value="conciliaciones">
            <label class="form-check-label" for="vistaConciliaciones">Conciliaciones</label>
        </div>
    </div>

    <!-- INGRESOS -->
    <div id="seccionIngresos">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Registro de Ingresos</h5>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalIngreso" onclick="limpiarFormIngreso()">
                <i class="bi bi-plus-circle"></i> Registrar Ingreso
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover display nowrap" id="tablaIngresos" style="width:100%">
                <thead class="table-success">
                <tr>
                    <th>ID</th>
                    <th>Concepto</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Contador</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @isset($ingresos)
                    @foreach($ingresos as $ing)
                        <tr>
                            <td>{{ $ing['id_movimiento'] }}</td>
                            <td>{{ $ing['concepto'] }}</td>
                            <td>${{ number_format($ing['monto'], 2) }}</td>
                            <td>{{ $ing['fecha'] }}</td>
                            <td>{{ $ing['hora'] }}</td>
                            <td>{{ $ing['contador'] ?? 'N/A' }}</td>
                            <td>
                                <button class="btn btn-outline-primary btn-editar" onclick="editarIngreso({{ $ing['id_movimiento'] }}, '{{ $ing['concepto'] }}', {{ $ing['monto'] }})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-eliminar" onclick="eliminarIngreso({{ $ing['id_movimiento'] }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-conciliacion"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalConciliacion"
                                        data-id="{{ $ing['id_movimiento'] }}">
                                    <i class="bi bi-bank"></i> Conciliación
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center text-muted">No hay ingresos registrados</td>
                    </tr>
                @endisset
                </tbody>
            </table>
        </div>
    </div>

    <!-- EGRESOS -->
    <div id="seccionEgresos" style="display: none;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Registro de Egresos</h5>
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalEgreso" onclick="limpiarFormEgreso()">
                <i class="bi bi-dash-circle"></i> Registrar Egreso
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover display nowrap" id="tablaEgresos" style="width:100%">
                <thead class="table-danger">
                <tr>
                    <th>ID</th>
                    <th>Concepto</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Contador</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @isset($egresos)
                    @foreach($egresos as $eg)
                        <tr>
                            <td>{{ $eg['id_movimiento'] }}</td>
                            <td>{{ $eg['concepto'] }}</td>
                            <td>${{ number_format($eg['monto'], 2) }}</td>
                            <td>{{ $eg['fecha'] }}</td>
                            <td>{{ $eg['hora'] }}</td>
                            <td>{{ $eg['contador'] ?? 'N/A' }}</td>
                            <td>
                                <button class="btn btn-outline-primary btn-editar" onclick="editarEgreso({{ $eg['id_movimiento'] }}, '{{ $eg['concepto'] }}', {{ $eg['monto'] }})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-eliminar" onclick="eliminarEgreso({{ $eg['id_movimiento'] }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-conciliacion"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalConciliacion"
                                        data-id="{{ $eg['id_movimiento'] }}">
                                    <i class="bi bi-bank"></i> Conciliación
                                </button>



                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center text-muted">No hay egresos registrados</td>
                    </tr>
                @endisset
                </tbody>
            </table>
        </div>
    </div>

    <!-- TARIFAS -->
    <div id="seccionTarifas" style="display: none;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Gestión de Tarifas</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTarifa" onclick="limpiarFormTarifa()">
                <i class="bi bi-cash-stack"></i> Registrar Tarifa
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover display nowrap" id="tablaTarifas" style="width:100%">
                <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Ruta</th>
                    <th>Tarifa Base</th>
                    <th>Tipo Pasajero</th>
                    <th>Descuento (%)</th>
                    <th>Tarifa Final</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @isset($tarifas)
                    @foreach($tarifas as $t)
                        <tr>
                            <td>{{ $t['id_tarifa'] }}</td>
                            <td>{{ $t['ruta'] }}</td>
                            <td>${{ number_format($t['tarifaBaseRuta'], 2) }}</td>
                            <td>{{ $t['tipoPasajero'] }}</td>
                            <td>{{ $t['descuentoPasajero'] }}</td>
                            <td>${{ number_format($t['tarifaFinal'], 2) }}</td>
                            <td>
                                <button class="btn btn-outline-primary btn-editar" onclick="editarTarifa({{ $t['id_tarifa'] }}, {{ $t['id_ruta'] ?? 'null' }}, {{ $t['tarifaBaseRuta'] }}, '{{ $t['tipoPasajero'] }}', {{ $t['descuentoPasajero'] }})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-eliminar" onclick="eliminarTarifa({{ $t['id_tarifa'] }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center text-muted">No hay tarifas registradas</td>
                    </tr>
                @endisset
                </tbody>
            </table>
        </div>
    </div>

    <!-- CONCILIACIONES -->
    <div id="seccionConciliaciones" style="display: none;">
        <div class="table-responsive">
            <table class="table table-striped table-hover display nowrap" id="tablaConciliaciones" style="width:100%">
                <thead class="table-secondary">
                <tr>
                    <th>ID Comprobante</th>
                    <th>Fecha Registro</th>
                    <th>ID Movimiento</th>
                    <th>Tipo Movimiento</th>
                    <th>Monto</th>
                    <th>Concepto</th>
                </tr>
                </thead>
                <tbody>
                @isset($conciliaciones)
                    @foreach($conciliaciones as $c)
                        <tr>
                            <td>{{ $c['id_comprobante'] }}</td>
                            <td>{{ $c['fechaRegistro'] }}</td>
                            <td>{{ $c['id_movimiento'] }}</td>
                            <td>{{ $c['tipoMovimiento'] }}</td>
                            <td>${{ number_format($c['monto'], 2) }}</td>
                            <td>{{ $c['concepto'] }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hay conciliaciones registradas</td>
                    </tr>
                @endisset
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= MODALES ================= -->

<!-- Ingreso -->
<div class="modal fade" id="modalIngreso" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="tituloModalIngreso">Registrar Ingreso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formIngreso">
                    <input type="hidden" id="ingresoId" name="id">
                    <div class="mb-3">
                        <label class="form-label">Concepto</label>
                        <input type="text" class="form-control" id="ingresoConcepto" name="concepto" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Monto</label>
                        <input type="number" step="0.01" class="form-control" id="ingresoMonto" name="monto" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="guardarIngreso()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Egreso -->
<div class="modal fade" id="modalEgreso" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="tituloModalEgreso">Registrar Egreso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEgreso">
                    <input type="hidden" id="egresoId" name="id">
                    <div class="mb-3">
                        <label class="form-label">Concepto</label>
                        <input type="text" class="form-control" id="egresoConcepto" name="concepto" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Monto</label>
                        <input type="number" step="0.01" class="form-control" id="egresoMonto" name="monto" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="guardarEgreso()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Tarifa -->
<div class="modal fade" id="modalTarifa" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="tituloModalTarifa">Registrar Tarifa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formTarifa">
                    <input type="hidden" id="tarifaId" name="id">
                    <div class="mb-3">
                        <label class="form-label">Ruta</label>
                        <select class="form-select" id="tarifaRuta" name="id_ruta" required>
                            <option value="">Seleccione una ruta</option>
                            @isset($rutas)
                                @foreach($rutas as $ruta)
                                    <option value="{{ $ruta['id_ruta'] }}">{{ $ruta['nombre'] }} ({{ $ruta['origen'] }} - {{ $ruta['destino'] }})</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tarifa Base</label>
                        <input type="number" step="0.01" class="form-control" id="tarifaBase" name="tarifaBaseRuta" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo Pasajero</label>
                        <input type="text" class="form-control" id="tarifaTipoPasajero" name="tipoPasajero" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descuento (%)</label>
                        <input type="number" step="0.1" class="form-control" id="tarifaDescuento" name="descuentoPasajero" value="0">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarTarifa()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Conciliación -->
<div class="modal fade" id="modalConciliacion" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title">Registrar Conciliación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formConciliacion">
                    <div class="mb-3">
                        <label class="form-label">ID Movimiento Bancario</label>
                        <input type="number" class="form-control" id="movimientoBancarioIdFK" name="movimientoBancarioIdFK" required readonly>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-secondary" onclick="guardarConciliacion()">Guardar</button>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let tableIngresos, tableEgresos, tableTarifas, tableConciliaciones;
    let modoEdicionIngreso = false;
    let modoEdicionEgreso = false;
    let modoEdicionTarifa = false;

    $(document).ready(function() {
        const configComun = {
            language: {
                emptyTable: "No hay datos disponibles",
                search: "Buscar:",
                paginate: { next: "Siguiente", previous: "Anterior" }
            },
            pageLength: 10,
            responsive: true,
            autoWidth: false
        };

        tableIngresos = $('#tablaIngresos').DataTable(configComun);
        tableEgresos = $('#tablaEgresos').DataTable(configComun);
        tableTarifas = $('#tablaTarifas').DataTable(configComun);
        tableConciliaciones = $('#tablaConciliaciones').DataTable(configComun);

        $('input[name="tipoVista"]').change(actualizarVista);
        actualizarVista();

        // Configurar modal de conciliación
        const modalConciliacion = document.getElementById('modalConciliacion');
        if (modalConciliacion) {
            modalConciliacion.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                const movimientoId = button.getAttribute('data-id');
                const inputId = modalConciliacion.querySelector('#movimientoBancarioIdFK');
                if (inputId) inputId.value = movimientoId;
            });
        }
    });

    function actualizarVista() {
        $('#seccionIngresos').toggle($('#vistaIngresos').is(':checked'));
        $('#seccionEgresos').toggle($('#vistaEgresos').is(':checked'));
        $('#seccionTarifas').toggle($('#vistaTarifas').is(':checked'));
        $('#seccionConciliaciones').toggle($('#vistaConciliaciones').is(':checked'));
    }

    // ========== INGRESOS ==========
    function limpiarFormIngreso() {
        modoEdicionIngreso = false;
        $('#ingresoId').val('');
        $('#ingresoConcepto').val('');
        $('#ingresoMonto').val('');
        $('#tituloModalIngreso').text('Registrar Ingreso');
    }

    function editarIngreso(id, concepto, monto) {
        modoEdicionIngreso = true;
        $('#ingresoId').val(id);
        $('#ingresoConcepto').val(concepto);
        $('#ingresoMonto').val(monto);
        $('#tituloModalIngreso').text('Editar Ingreso');
        $('#modalIngreso').modal('show');
    }

    function guardarIngreso() {
        const concepto = $('#ingresoConcepto').val();
        const monto = $('#ingresoMonto').val();
        const id = $('#ingresoId').val();

        if (!concepto || !monto) {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Por favor completa todos los campos obligatorios.",
                showConfirmButton: false,
                timer: 1500
            });
            return;
        }

        const url = modoEdicionIngreso
            ? `/finanzas/ingresos/${id}`
            : '/finanzas/ingresos';
        const method = modoEdicionIngreso ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ concepto, monto })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#modalIngreso').modal('hide');
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: 'Error: ' + error.message,
                    showConfirmButton: false,
                    timer: 1500
                });
            });
    }

    function eliminarIngreso(id) {
        Swal.fire({
            title: '¿Eliminar ingreso?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/finanzas/ingresos/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                position: "center",
                                icon: "success",
                                title: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                position: "center",
                                icon: "error",
                                title: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            position: "center",
                            icon: "error",
                            title: 'Error: ' + error.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    });
            }
        });
    }

    // ========== EGRESOS ==========
    function limpiarFormEgreso() {
        modoEdicionEgreso = false;
        $('#egresoId').val('');
        $('#egresoConcepto').val('');
        $('#egresoMonto').val('');
        $('#tituloModalEgreso').text('Registrar Egreso');
    }

    function editarEgreso(id, concepto, monto) {
        modoEdicionEgreso = true;
        $('#egresoId').val(id);
        $('#egresoConcepto').val(concepto);
        $('#egresoMonto').val(monto);
        $('#tituloModalEgreso').text('Editar Egreso');
        $('#modalEgreso').modal('show');
    }

    function guardarEgreso() {
        const concepto = $('#egresoConcepto').val();
        const monto = $('#egresoMonto').val();
        const id = $('#egresoId').val();

        if (!concepto || !monto) {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Por favor completa todos los campos obligatorios.",
                showConfirmButton: false,
                timer: 1500
            });
            return;
        }

        const url = modoEdicionEgreso
            ? `/finanzas/egresos/${id}`
            : '/finanzas/egresos';
        const method = modoEdicionEgreso ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ concepto, monto })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#modalEgreso').modal('hide');
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: 'Error: ' + error.message,
                    showConfirmButton: false,
                    timer: 1500
                });
            });
    }

    function eliminarEgreso(id) {
        Swal.fire({
            title: '¿Eliminar egreso?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/finanzas/egresos/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                position: "center",
                                icon: "success",
                                title: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                position: "center",
                                icon: "error",
                                title: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            position: "center",
                            icon: "error",
                            title: 'Error: ' + error.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    });
            }
        });
    }

    // ========== TARIFAS ==========
    function limpiarFormTarifa() {
        modoEdicionTarifa = false;
        $('#tarifaId').val('');
        $('#tarifaRuta').val('');
        $('#tarifaBase').val('');
        $('#tarifaTipoPasajero').val('');
        $('#tarifaDescuento').val('0');
        $('#tituloModalTarifa').text('Registrar Tarifa');
    }

    function editarTarifa(id, idRuta, tarifaBase, tipoPasajero, descuento) {
        modoEdicionTarifa = true;
        $('#tarifaId').val(id);
        $('#tarifaRuta').val(idRuta || '');
        $('#tarifaBase').val(tarifaBase);
        $('#tarifaTipoPasajero').val(tipoPasajero);
        $('#tarifaDescuento').val(descuento || 0);
        $('#tituloModalTarifa').text('Editar Tarifa');
        $('#modalTarifa').modal('show');
    }

    function guardarTarifa() {
        const idRuta = $('#tarifaRuta').val();
        const tarifaBase = $('#tarifaBase').val();
        const tipoPasajero = $('#tarifaTipoPasajero').val();
        const descuento = $('#tarifaDescuento').val() || 0;
        const id = $('#tarifaId').val();

        if (!idRuta || !tarifaBase || !tipoPasajero) {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Por favor completa todos los campos obligatorios.",
                showConfirmButton: false,
                timer: 1500
            });
            return;
        }

        const url = modoEdicionTarifa
            ? `/finanzas/tarifas/${id}`
            : '/finanzas/tarifas';
        const method = modoEdicionTarifa ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ id_ruta: idRuta, tarifaBaseRuta: tarifaBase, tipoPasajero, descuentoPasajero: descuento })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#modalTarifa').modal('hide');
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: 'Error: ' + error.message,
                    showConfirmButton: false,
                    timer: 1500
                });
            });
    }

    function eliminarTarifa(id) {
        Swal.fire({
            title: '¿Eliminar tarifa?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/finanzas/tarifas/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                position: "center",
                                icon: "success",
                                title: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                position: "center",
                                icon: "error",
                                title: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            position: "center",
                            icon: "error",
                            title: 'Error: ' + error.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    });
            }
        });
    }

    // ========== CONCILIACIONES ==========
    function guardarConciliacion() {
        const movimientoId = $('#movimientoBancarioIdFK').val();

        if (!movimientoId) {
            Swal.fire({
                position: "center",
                position: "center",
                icon: "error",
                title: "Por favor selecciona un movimiento.",
                showConfirmButton: false,
                timer: 1500
            });
            return;
        }

        fetch('/finanzas/conciliaciones', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ movimientoBancarioIdFK: movimientoId })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#modalConciliacion').modal('hide');
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: 'Error: ' + error.message,
                    showConfirmButton: false,
                    timer: 1500
                });
            });
    }
</script>

</body>
</html>
