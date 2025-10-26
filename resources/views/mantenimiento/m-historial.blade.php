<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Mantenimiento</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
</head>
<body>

<!-- Menú principal -->
@include('layouts.menuPrincipal')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-clock-history me-2"></i>Historial de Mantenimiento</h4>
        <div>
            <button class="btn btn-outline-primary me-2" onclick="exportarPDF()">
                <i class="bi bi-file-pdf"></i> Exportar PDF
            </button>
            <button class="btn btn-outline-success" onclick="exportarExcel()">
                <i class="bi bi-file-excel"></i> Exportar Excel
            </button>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Seleccionar Unidad</label>
                    <select class="form-select" id="unidadHistorial">
                        <option value="">Todas las unidades</option>
                        @isset($unidades)
                            @foreach($unidades as $unidad)
                                <option value="{{ $unidad['id_unidad'] }}">
                                    {{ $unidad['placa'] }} - {{ $unidad['modelo'] }}
                                </option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tipo de Mantenimiento</label>
                    <select class="form-select" id="tipoMantenimiento">
                        <option value="">Todos los tipos</option>
                        <option value="preventivo">Preventivo</option>
                        <option value="correctivo">Correctivo</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Rango de Fechas</label>
                    <input type="month" class="form-control" id="fechaFiltro">
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $totalMantenimientos ?? 0 }}</h4>
                            <p class="mb-0">Total Mantenimientos</p>
                        </div>
                        <i class="bi bi-tools display-6"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $mantenimientosPreventivos ?? 0 }}</h4>
                            <p class="mb-0">Preventivos</p>
                        </div>
                        <i class="bi bi-shield-check display-6"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $mantenimientosCorrectivos ?? 0 }}</h4>
                            <p class="mb-0">Correctivos</p>
                        </div>
                        <i class="bi bi-wrench display-6"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">${{ number_format($costoTotal ?? 0, 2) }}</h4>
                            <p class="mb-0">Costo Total</p>
                        </div>
                        <i class="bi bi-currency-dollar display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de historial -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover display nowrap" id="tablaHistorial" style="width:100%">
                    <thead class="table-dark">
                    <tr>
                        <th>Unidad</th>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Piezas</th>
                        <th>Kilometraje</th>
                        <th>Costo</th>
                        <th>Observaciones</th>
                        <th>Estado Unidad</th>
                    </tr>
                    </thead>
                    <tbody>
                    @isset($historialMantenimiento)
                        @foreach($historialMantenimiento as $mantenimiento)
                            <tr>
                                <td>{{ $mantenimiento['placa'] ?? 'N/A' }} - {{ $mantenimiento['modelo'] ?? 'N/A' }}</td>
                                <td>{{ $mantenimiento['fecha'] ?? 'N/A' }}</td>
                                <td>
                                    @if(isset($mantenimiento['tipo_mantenimiento']))
                                        @if($mantenimiento['tipo_mantenimiento'] == 'preventivo')
                                            <span class="badge bg-success">Preventivo</span>
                                        @else
                                            <span class="badge bg-warning">Correctivo</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $mantenimiento['descripcion'] ?? 'N/A' }}</td>
                                <td>N/A</td>
                                <td>{{ isset($mantenimiento['kmActual']) ? number_format($mantenimiento['kmActual'], 0) . ' km' : 'N/A' }}</td>
                                <td>N/A</td>
                                <td>{{ $mantenimiento['descripcion'] ?? 'N/A' }}</td>
                                <td>
                                    @if(isset($mantenimiento['estado']))
                                        <span class="badge bg-success">{{ ucfirst($mantenimiento['estado']) }}</span>
                                    @else
                                        <span class="badge bg-secondary">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9" class="text-center text-muted">No hay historial de mantenimiento</td>
                        </tr>
                    @endisset
                    </tbody>
                </table>
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
    let tableHistorial;

    $(document).ready(function() {
        tableHistorial = $('#tablaHistorial').DataTable({
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
                }
            },
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            responsive: true,
            autoWidth: false,
            order: [[1, 'desc']],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
        });

        // Configurar filtros
        $('#unidadHistorial').on('change', function() {
            tableHistorial.column(0).search(this.value).draw();
        });

        $('#tipoMantenimiento').on('change', function() {
            tableHistorial.column(2).search(this.value).draw();
        });

        $('#fechaFiltro').on('change', function() {
            tableHistorial.column(1).search(this.value).draw();
        });
    });

    function exportarPDF() {
        alert('Función de exportación PDF en desarrollo');
        // Aquí iría la lógica para exportar a PDF
    }

    function exportarExcel() {
        alert('Función de exportación Excel en desarrollo');
        // Aquí iría la lógica para exportar a Excel
    }
</script>

</body>
</html>
