<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión Financiera</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
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
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalIngreso">
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
                            <td>{{ $ing['contador'] }}</td>
                            <td>
                                <button class="btn btn-secondary"
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
                        <td colspan="6" class="text-center text-muted">No hay ingresos registrados</td>
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
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalEgreso">
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
                            <td>{{ $eg['contador'] }}</td>
                            <td>
                                <button class="btn btn-secondary"
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
                        <td colspan="6" class="text-center text-muted">No hay egresos registrados</td>
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
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTarifa">
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
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hay tarifas registradas</td>
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
                <h5 class="modal-title">Registrar Ingreso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formIngreso">
                    <div class="mb-3">
                        <label class="form-label">Concepto</label>
                        <input type="text" class="form-control" name="concepto" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Monto</label>
                        <input type="number" step="0.01" class="form-control" name="monto" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha</label>
                            <input type="date" class="form-control" name="fecha" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hora</label>
                            <input type="time" class="form-control" name="hora" required>
                        </div>
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
                <h5 class="modal-title">Registrar Egreso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEgreso">
                    <div class="mb-3">
                        <label class="form-label">Concepto</label>
                        <input type="text" class="form-control" name="concepto" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Monto</label>
                        <input type="number" step="0.01" class="form-control" name="monto" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha</label>
                            <input type="date" class="form-control" name="fecha" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hora</label>
                            <input type="time" class="form-control" name="hora" required>
                        </div>
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
                <h5 class="modal-title">Registrar Tarifa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formTarifa">
                    <div class="mb-3">
                        <label class="form-label">Ruta</label>
                        <input type="text" class="form-control" name="ruta" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tarifa Base</label>
                        <input type="number" step="0.01" class="form-control" name="tarifaBaseRuta" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo Pasajero</label>
                        <input type="text" class="form-control" name="tipoPasajero" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descuento (%)</label>
                        <input type="number" step="0.1" class="form-control" name="descuentoPasajero">
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
                        <input type="number" class="form-control" id="movimientoBancarioIdFK" name="movimientoBancarioIdFK" required disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha Registro</label>
                        <input type="date" class="form-control" name="fechaRegistro" required>
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

<!-- jQuery + Bootstrap + DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
    let tableIngresos, tableEgresos, tableTarifas, tableConciliaciones;

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
    });

    function actualizarVista() {
        $('#seccionIngresos').toggle($('#vistaIngresos').is(':checked'));
        $('#seccionEgresos').toggle($('#vistaEgresos').is(':checked'));
        $('#seccionTarifas').toggle($('#vistaTarifas').is(':checked'));
        $('#seccionConciliaciones').toggle($('#vistaConciliaciones').is(':checked'));
    }

    // Funciones pendientes de implementación
    function guardarIngreso() { alert('Guardar ingreso (pendiente de implementar)'); }
    function guardarEgreso() { alert('Guardar egreso (pendiente de implementar)'); }
    function guardarTarifa() { alert('Guardar tarifa (pendiente de implementar)'); }
    function guardarConciliacion() { alert('Guardar conciliación (pendiente de implementar)'); }
</script>

<script>
    const modalConciliacion = document.getElementById('modalConciliacion');

    modalConciliacion.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const movimientoId = button.getAttribute('data-id');
        const inputId = modalConciliacion.querySelector('#movimientoBancarioIdFK');
        inputId.value = movimientoId;
    });
</script>

</body>
</html>
