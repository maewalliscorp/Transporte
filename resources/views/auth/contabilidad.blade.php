<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Financiero</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
@include('layouts.menuPrincipal')

<div class="container mt-4">
    <h4 class="mb-3">Panel de Gestión Financiera</h4>

    <!-- Filtro -->
    <div class="mb-4">
        <label class="form-label me-3">Selecciona el módulo:</label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="vista" id="vistaIngresos" value="ingresos" checked>
            <label class="form-check-label" for="vistaIngresos">Ingresos</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="vista" id="vistaEgresos" value="egresos">
            <label class="form-check-label" for="vistaEgresos">Egresos</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="vista" id="vistaTarifas" value="tarifas">
            <label class="form-check-label" for="vistaTarifas">Tarifas</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="vista" id="vistaBancarios" value="bancarios">
            <label class="form-check-label" for="vistaBancarios">Depósitos / Retiros</label>
        </div>
    </div>

    <!-- SECCIÓN INGRESOS -->
    <div id="seccionIngresos">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Registro de Ingresos</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalIngreso">
                <i class="bi bi-plus-circle"></i> Agregar Ingreso
            </button>
        </div>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
            <tr>
                <th>ID Ingreso</th>
                <th>ID Unidad</th>
                <th>Fecha</th>
                <th>Cantidad</th>
            </tr>
            </thead>
            <tbody id="tabla_ingresos">
            @isset($ingresos)
                @foreach($ingresos as $i)
                    <tr>
                        <td>{{ $i['id_ingreso'] }}</td>
                        <td>{{ $i['id_unidad'] }}</td>
                        <td>{{ $i['fecha'] }}</td>
                        <td>${{ number_format($i['monto'], 2) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center text-muted">No hay ingresos registrados</td>
                </tr>
            @endisset
            </tbody>
        </table>
        <div class="fw-bold text-end">Total Ingresos: $<span id="total_ingresos">0.00</span></div>
    </div>

    <!-- SECCIÓN EGRESOS -->
    <div id="seccionEgresos" style="display: none;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Registro de Egresos</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalEgreso">
                <i class="bi bi-plus-circle"></i> Agregar Egreso
            </button>
        </div>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
            <tr>
                <th>Fecha</th>
                <th>Concepto</th>
                <th>Comprobante</th>
                <th>Cantidad</th>
            </tr>
            </thead>
            <tbody id="tabla_egresos_body">
            @isset($egresos)
                @foreach($egresos as $egreso)
                    <tr>
                        <td>{{ $egreso['fecha'] }}</td>
                        <td>{{ $egreso['concepto'] }}</td>
                        <td>
                            @if($egreso['comprobante'])
                                <a href="{{ asset('storage/' . $egreso['comprobante']) }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> Ver
                                </a>
                            @else
                                <span class="text-muted">Sin comprobante</span>
                            @endif
                        </td>
                        <td>${{ number_format($egreso['cantidad'], 2) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center text-muted">No hay egresos registrados</td>
                </tr>
            @endisset
            </tbody>
        </table>
        <div class="fw-bold text-end">Total Egresos: $<span id="total_egresos">
            @isset($totalEgresos)
                    {{ number_format($totalEgresos, 2) }}
                @else
                    0.00
                @endisset
        </span></div>
    </div>



    <!-- SECCIÓN TARIFAS -->
    <div id="seccionTarifas" style="display: none;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Gestión de Tarifas</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTarifa">
                <i class="bi bi-plus-circle"></i> Agregar Tarifa
            </button>
        </div>

        <!-- Tabla de Tarifas -->
        <div class="card">
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                    <tr>
                        <th>Ruta ID</th>
                        <th>Tipo Pasajero</th>
                        <th>Tarifa Base</th>
                        <th>Descuento %</th>
                        <th>Tarifa Final</th>
                        <th>Notas</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($tarifas) && count($tarifas) > 0)
                        @foreach($tarifas as $tarifa)
                            <tr>
                                <td>{{ $tarifa['id_ruta'] }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $tarifa['tipoPasajero'] }}</span>
                                </td>
                                <td>${{ number_format($tarifa['tarifaBaseRuta'], 2) }}</td>
                                <td>{{ number_format($tarifa['descuentoPasajero'], 1) }}%</td>
                                <td><strong>${{ number_format($tarifa['tarifaFinal'], 2) }}</strong></td>
                                <td>{{ $tarifa['notas'] ?? 'Sin notas' }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                <i class="bi bi-info-circle"></i> No hay tarifas registradas
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>





    <!-- SECCIÓN BANCARIOS -->
    <div id="seccionBancarios" style="display: none;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Depósitos y Retiros Bancarios</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalBancario">
                <i class="bi bi-plus-circle"></i> Agregar Registro
            </button>
        </div>



        <!-- Tabla de Movimientos Bancarios -->
        <div class="card">
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($bancarios) && count($bancarios) > 0)
                        @foreach($bancarios as $movimiento)
                            <tr>
                                <td>{{ $movimiento['id_movimiento'] }}</td>
                                <td>
                                    @if($movimiento['tipoMovimiento'] == 'Depósito')
                                        <span class="badge bg-success">{{ $movimiento['tipoMovimiento'] }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ $movimiento['tipoMovimiento'] }}</span>
                                    @endif
                                </td>
                                <td>${{ number_format($movimiento['monto'], 2) }}</td>
                                <td>{{ $movimiento['fecha'] }}</td>
                                <td>{{ $movimiento['hora'] }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                <i class="bi bi-info-circle"></i> No hay movimientos bancarios registrados
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Total Bancarios -->
        <div class="mt-3">
            <div class="row">
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h6>Total Depósitos</h6>
                            <h4>${{ number_format(collect($bancarios)->where('tipoMovimiento', 'Depósito')->sum('monto'), 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <h6>Total Retiros</h6>
                            <h4>${{ number_format(collect($bancarios)->where('tipoMovimiento', 'Retiro')->sum('monto'), 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h6>Saldo Neto</h6>
                            <h4>
                                @php
                                    $totalDepositos = collect($bancarios)->where('tipoMovimiento', 'Depósito')->sum('monto');
                                    $totalRetiros = collect($bancarios)->where('tipoMovimiento', 'Retiro')->sum('monto');
                                    $saldoNeto = $totalDepositos - $totalRetiros;
                                @endphp
                                ${{ number_format($saldoNeto, 2) }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- MODAL PARA INGRESOS -->
<div class="modal fade" id="modalIngreso" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-cash-coin me-2"></i>Agregar Ingreso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formIngreso">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Unidad de transporte</label>
                            <select class="form-select" name="unidad" required>
                                <option value="" selected disabled>Selecciona una unidad...</option>
                                @isset($unidades)
                                    @foreach($unidades as $u)
                                        <option value="{{ $u['id_unidad'] }}">
                                            {{ $u['placa'] }} - {{ $u['modelo'] }} - (Cap: {{ $u['capacidad'] }})
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Operador</label>
                            <select class="form-select" name="operador" required>
                                <option value="" selected disabled>Selecciona un operador...</option>
                                @foreach($operadores as $o)
                                    <option value="{{ $o['id_operator'] }}">
                                        Operador #{{ $o['id_operator'] }} - Licencia: {{ $o['licencia'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha</label>
                            <input type="date" class="form-control" name="fecha" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cantidad</label>
                            <input type="number" class="form-control" name="monto" step="0.01" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarIngreso()">Guardar Ingreso</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PARA EGRESOS -->
<div class="modal fade" id="modalEgreso" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-cash-stack me-2"></i>Agregar Egreso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEgreso">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha</label>
                            <input type="date" class="form-control" name="fecha" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Concepto</label>
                            <input type="text" class="form-control" name="concepto" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Comprobante (PDF)</label>
                            <input type="file" class="form-control" name="comprobante" accept="application/pdf">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cantidad</label>
                            <input type="number" class="form-control" name="cantidad" step="0.01" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarEgreso()">Guardar Egreso</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PARA TARIFAS -->
<div class="modal fade" id="modalTarifa" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-tag me-2"></i>Agregar Tarifa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formTarifa">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ruta</label>
                            <select class="form-select" name="ruta" required>
                                <option value="" selected disabled>Selecciona una ruta...</option>
                                @isset($rutas)  <!-- AGREGAR ESTA VALIDACIÓN -->
                                @foreach($rutas as $r)
                                    <option value="{{ $r['id_ruta'] }}">
                                        {{ $r['origen'] }} - {{ $r['destino'] }}
                                    </option>
                                @endforeach
                                @else
                                    <option value="" disabled>No hay rutas disponibles</option>
                                @endisset
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo de Pasajero</label>
                            <select class="form-select" name="tipoPasajero" required>
                                <option value="" selected disabled>Selecciona tipo...</option>
                                <option value="Adulto">Adulto</option>
                                <option value="Niño">Niño</option>
                                <option value="Estudiante">Estudiante</option>
                                <option value="Adulto Mayor">Adulto Mayor</option>
                                <option value="Discapacitado">Discapacitado</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tarifa Base ($)</label>
                            <input type="number" class="form-control" name="tarifaBaseRuta" step="0.01" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Descuento (%)</label>
                            <input type="number" class="form-control" name="descuentoPasajero" step="0.01" value="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tarifa Final ($)</label>
                            <input type="number" class="form-control" name="tarifaFinal" step="0.01" readonly>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Notas</label>
                            <textarea class="form-control" name="notas" rows="3" placeholder="Notas adicionales..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarTarifa()">Guardar Tarifa</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PARA BANCARIOS -->
<div class="modal fade" id="modalBancario" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-bank me-2"></i>Registro Bancario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formBancario">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha</label>
                            <input type="date" class="form-control" name="fecha" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo de Movimiento</label>
                            <select class="form-select" name="tipo" required>
                                <option value="Depósito">Depósito</option>
                                <option value="Retiro">Retiro</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Comprobante</label>
                            <input type="file" class="form-control" name="comprobante" accept="application/pdf">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cantidad ($)</label>
                            <input type="number" class="form-control" name="cantidad" step="0.01" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarBancario()">Guardar Registro</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Cambiar entre secciones
    const radios = document.querySelectorAll('input[name="vista"]');
    const secciones = {
        ingresos: document.getElementById("seccionIngresos"),
        egresos: document.getElementById("seccionEgresos"),
        tarifas: document.getElementById("seccionTarifas"),
        bancarios: document.getElementById("seccionBancarios")
    };

    radios.forEach(radio => {
        radio.addEventListener("change", () => {
            for (const key in secciones) {
                secciones[key].style.display = (radio.value === key) ? "block" : "none";
            }
        });
    });

    // Funciones para guardar (pendientes de implementar)
    function guardarIngreso() {
        alert('Funcionalidad para guardar ingreso - Pendiente de implementar');
        // Cerrar modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalIngreso'));
        modal.hide();
    }

    function guardarEgreso() {
        alert('Funcionalidad para guardar egreso - Pendiente de implementar');
        // Cerrar modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalEgreso'));
        modal.hide();
    }

    function guardarTarifa() {
        alert('Funcionalidad para guardar tarifa - Pendiente de implementar');
        // Cerrar modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalTarifa'));
        modal.hide();
    }

    function guardarBancario() {
        alert('Funcionalidad para guardar registro bancario - Pendiente de implementar');
        // Cerrar modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalBancario'));
        modal.hide();
    }

    // Calcular tarifa final automáticamente
    document.addEventListener('DOMContentLoaded', function() {
        const tarifaBase = document.querySelector('input[name="tarifaBaseRuta"]');
        const descuento = document.querySelector('input[name="descuentoPasajero"]');
        const tarifaFinal = document.querySelector('input[name="tarifaFinal"]');

        function calcularTarifaFinal() {
            if (tarifaBase.value && descuento.value) {
                const base = parseFloat(tarifaBase.value);
                const desc = parseFloat(descuento.value);
                const final = base - (base * desc / 100);
                tarifaFinal.value = final.toFixed(2);
            }
        }

        if (tarifaBase && descuento && tarifaFinal) {
            tarifaBase.addEventListener('input', calcularTarifaFinal);
            descuento.addEventListener('input', calcularTarifaFinal);
        }

        // Establecer fecha actual por defecto en los modales
        const now = new Date();
        const fechaActual = now.toISOString().split('T')[0];
        document.querySelectorAll('input[type="date"]').forEach(input => {
            if (!input.value) {
                input.value = fechaActual;
            }
        });
    });
</script>

</body>
</html>
