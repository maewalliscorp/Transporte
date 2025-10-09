<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Financiero</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>

<!-- Menú principal -->
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

    <!-- Selects desplegables de contabilidad -->
    <div id="seccionIngresos">
        <h5>Registro de Ingresos</h5>
        <div class="row g-3 mb-3">
            <div class="col-md-3 mb-2">
                <label for="unidad" class="form-label">Unidad de transporte</label>
                <select class="form-select" id="unidad">
                    <option selected disabled>Selecciona...</option>
                    <!-- Opciones aquí -->
                    @isset($unidades)
                        @foreach($unidades as $u)
                            <option value="{{ $u['id_unidad'] }}">
                                {{ $u['placa'] }} - {{ $u['modelo'] }} - (Cap: {{ $u['capacidad'] }})
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>


            <div class="col-md-3 mb-2">
                <label for="operador" class="form-label">Operador</label>
                <select class="form-select" id="operador">
                    <option selected disabled>Selecciona...</option>
                    <!-- Opciones aquí -->

                </select>
            </div>


            <div class="col-md-3 mb-2">
                <label class="form-label">Fecha</label>
                <input type="date" class="form-control" id="fecha_ingreso">
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label">Cantidad</label>
                <input type="number" class="form-control" id="cantidad_ingreso" step="0.01">
            </div>
            <div class="col-md-12">
                <button class="btn btn-primary mt-2" onclick="agregarIngreso()">Agregar Ingreso</button>
            </div>
        </div>

        <!-- Sección: INGRESOS -->
        <table class="table table-bordered">
            <thead class="table-light">
            <tr>
                <th>Unidad</th>
                <th>Operador</th>
                <th>Fecha</th>
                <th>Cantidad</th>
            </tr>
            </thead>

            <tbody id="tabla_ingresos">
            @isset($ingresos)
            @foreach($ingresos as $i)
                <tr>
                    <td>{{ $i['placa'] }} - {{ $i['modelo'] }} - {{ $i['capacidad'] }}</td>
                    <td>{{ $i['licencia'] ?? 'No asignado' }}</td>
                    <td>{{ $i['fecha'] }}</td>
                    <td>${{ number_format($i['monto'], 2) }}</td>

                </tr>
            @endforeach
            @endisset

            </tbody>
        </table>
        <div class="fw-bold">Total Ingresos: $<span id="total_ingresos">0.00</span></div>
    </div>

    <!-- Sección: EGRESOS -->
    <div id="seccionEgresos" style="display: none;">
        <h5>Registro de Egresos</h5>
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <label class="form-label">Fecha</label>
                <input type="date" class="form-control" id="fecha_egreso">
            </div>
            <div class="col-md-3">
                <label class="form-label">Concepto</label>
                <input type="text" class="form-control" id="concepto_egreso">
            </div>
            <div class="col-md-3">
                <label class="form-label">Comprobante (PDF)</label>
                <input type="file" class="form-control" id="comprobante_egreso" accept="application/pdf">
            </div>
            <div class="col-md-3">
                <label class="form-label">Cantidad</label>
                <input type="number" class="form-control" id="cantidad_egreso" step="0.01">
            </div>
            <div class="col-md-12">
                <button class="btn btn-primary mt-2" onclick="agregarEgreso()">Agregar Egreso</button>
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="table-light">
            <tr>
                <th>Fecha</th>
                <th>Concepto</th>
                <th>Comprobante</th>
                <th>Cantidad</th>
            </tr>
            </thead>
            <tbody id="tabla_egresos_body"></tbody>
        </table>
        <div class="fw-bold">Total Egresos: $<span id="total_egresos">0.00</span></div>
    </div>

    <!-- Sección: TARIFAS -->
    <div id="seccionTarifas" style="display: none;">
        <h5>Tarifas</h5>
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <label class="form-label">Ruta</label>
                <select class="form-select" id="ruta_tarifa"></select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tipo de Pasajero</label>
                <input type="text" class="form-control" id="tipo_pasajero_tarifa">
            </div>
            <div class="col-md-2">
                <label class="form-label">Tarifa Base</label>
                <input type="number" class="form-control" id="tarifa_base" step="0.01">
            </div>
            <div class="col-md-2">
                <label class="form-label">Ajuste Unidad</label>
                <input type="number" class="form-control" id="ajuste_unidad" step="0.01">
            </div>
            <div class="col-md-2">
                <label class="form-label">Descuento</label>
                <input type="number" class="form-control" id="descuento_tarifa" step="0.01">
            </div>
            <div class="col-md-12">
                <label class="form-label">Notas</label>
                <textarea class="form-control" id="notas_tarifa"></textarea>
                <button class="btn btn-primary mt-2" onclick="agregarTarifa()">Agregar Tarifa</button>
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="table-light">
            <tr>
                <th>Ruta</th>
                <th>Tipo de Pasajero</th>
                <th>Tarifa Base</th>
                <th>Ajuste</th>
                <th>Descuento</th>
                <th>Notas</th>
            </tr>
            </thead>
            <tbody id="tabla_tarifas_body"></tbody>
        </table>
    </div>

    <!-- Sección: DEPÓSITOS Y RETIROS -->
    <div id="seccionBancarios" style="display: none;">
        <h5>Depósitos y Retiros Bancarios</h5>
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <label class="form-label">Fecha</label>
                <input type="date" class="form-control" id="fecha_bancario">
            </div>
            <div class="col-md-3">
                <label class="form-label">Registro de</label>
                <select class="form-select" id="registro_bancario">
                    <option value="Depósito">Depósito</option>
                    <option value="Retiro">Retiro</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Comprobante</label>
                <input type="file" class="form-control" id="comprobante_bancario" accept="application/pdf">
            </div>
            <div class="col-md-3">
                <label class="form-label">Cantidad</label>
                <input type="number" class="form-control" id="cantidad_bancario" step="0.01">
            </div>
            <div class="col-md-12">
                <button class="btn btn-primary mt-2" onclick="agregarBancario()">Agregar Registro</button>
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="table-light">
            <tr>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Comprobante</th>
                <th>Cantidad</th>
            </tr>
            </thead>
            <tbody id="tabla_bancarios_body"></tbody>
        </table>
        <div class="fw-bold">Total Bancarios: $<span id="total_bancarios">0.00</span></div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script para alternar secciones -->
<script>
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

    // Aquí puedes agregar tus funciones JS como agregarIngreso(), agregarEgreso(), etc.
</script>

</body>
</html>
