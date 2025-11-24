<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Viajes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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

<!-- Menú principal -->
@include('layouts.menuPrincipal')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-clock-history me-2"></i>Historial de Viajes</h4>
    </div>

    <!-- Filtros -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Filtrar por Ruta</label>
                    <select class="form-select" id="filtroRuta">
                        <option value="">Todas las rutas</option>
                        @isset($rutas)
                            @foreach($rutas as $ruta)
                                <option value="{{ $ruta['nombre'] }}">{{ $ruta['nombre'] }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Rango de Fechas</label>
                    <input type="date" class="form-control" id="fechaFiltro">
                </div>
                <div class="col-md-4">
                    <button class="btn btn-outline-secondary w-100" onclick="limpiarFiltros()">
                        <i class="bi bi-arrow-clockwise"></i> Limpiar Filtros
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de historial -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover display nowrap" id="tablaHistorial" style="width:100%">
                    <thead class="table-primary">
                    <tr>
                        <th>Unidad</th>
                        <th>Fecha</th>
                        <th>Ruta</th>
                        <th>Hora</th>
                        <th>Monto</th>
                        <th>Medio de pago</th>
                        <th>Origen</th>
                        <th>Destino</th>
                    </tr>
                    </thead>
                    <tbody>
                    @isset($historialViajes)
                        @foreach($historialViajes as $viaje)
                            <tr>
                                <td>{{ $viaje['unidad'] ?? 'N/A' }}</td>
                                <td>{{ $viaje['fecha_viaje'] ? \Carbon\Carbon::parse($viaje['fecha_viaje'])->format('d/m/Y') : 'N/A' }}</td>
                                <td>{{ $viaje['ruta'] ?? 'N/A' }}</td>
                                <td>{{ $viaje['hora'] ?? 'N/A' }}</td>
                                <td>${{ number_format($viaje['monto'] ?? 0, 2) }}</td>
                                <td><span class="badge bg-warning text-dark">{{ $viaje['medio_pago'] ?? 'N/A' }}</span></td>
                                <td>{{ $viaje['origen'] ?? 'N/A' }}</td>
                                <td>{{ $viaje['destino'] ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center text-muted">No hay viajes registrados en tu historial</td>
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

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

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
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            stateSave: false
        });

        // Configurar filtros
        $('#filtroRuta').on('change', function() {
            const valor = $(this).val();
            tableHistorial.column(2).search(valor, true, false).draw();
        });

        $('#fechaFiltro').on('change', function() {
            const valor = $(this).val();
            tableHistorial.column(1).search(valor).draw();
        });

        // REMOVÍ la llamada automática a limpiarFiltros() aquí
        // Los filtros se mantendrán como están al cargar la página
    });

    // Función para limpiar todos los filtros - SOLO se ejecuta al hacer clic
    function limpiarFiltros() {
        $('#filtroRuta').val('');
        $('#fechaFiltro').val('');

        if (tableHistorial) {
            tableHistorial.columns().search('').draw();
        }

        Swal.fire({
            position: "center",
            icon: "success",
            title: "Filtros limpiados",
            showConfirmButton: false,
            timer: 1500
        });
    }
</script>

</body>
</html>
