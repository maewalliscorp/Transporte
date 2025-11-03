<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Historial de Pasajeros</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="{{ asset('build/assets/estilos.css') }}">

</head>
<body>

@include('layouts.menuPrincipal')

<div class="container mt-4">
    <div class="container-card">
        <div class="header-section">
            <h4 class="mb-0">Historial de Viajes</h4>
        </div>

        <div class="content-section">
            <!-- Filtros -->
            <div class="filter-section">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="fechaHistorial" class="form-label">Selecciona Fecha</label>
                        <input type="date" id="fechaHistorial" class="form-control" onchange="filtrarHistorial()" />
                    </div>
                    <div class="col-md-4">
                        <label for="filtroRuta" class="form-label">Filtrar por Ruta</label>
                        <select id="filtroRuta" class="form-select" onchange="filtrarHistorial()">
                            <option value="">Todas las rutas</option>
                            <option value="Ruta A">Ruta A</option>
                            <option value="Ruta B">Ruta B</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-outline-secondary w-100" onclick="limpiarFiltros()">
                            Limpiar Filtros
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabla de Historial -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
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
                    <tbody id="tablaHistorial">
                    <!-- Datos de ejemplo -->
                    <tr>
                        <td>Unidad 12</td>
                        <td>2025-09-01</td>
                        <td>Ruta A</td>
                        <td>08:00</td>
                        <td>$25.00</td>
                        <td><span class="badge bg-success">Tarjeta</span></td>
                        <td>Centro</td>
                        <td>Plaza</td>
                    </tr>
                    <tr>
                        <td>Unidad 15</td>
                        <td>2025-09-01</td>
                        <td>Ruta B</td>
                        <td>09:30</td>
                        <td>$30.00</td>
                        <td><span class="badge bg-warning text-dark">Efectivo</span></td>
                        <td>Plaza</td>
                        <td>Centro</td>
                    </tr>
                    <tr>
                        <td>Unidad 12</td>
                        <td>2025-09-02</td>
                        <td>Ruta A</td>
                        <td>08:00</td>
                        <td>$25.00</td>
                        <td><span class="badge bg-success">Tarjeta</span></td>
                        <td>Centro</td>
                        <td>Plaza</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
    // Filtrar historial por fecha y ruta
    function filtrarHistorial() {
        const fechaFiltro = document.getElementById('fechaHistorial').value;
        const rutaFiltro = document.getElementById('filtroRuta').value;
        const filas = document.querySelectorAll('#tablaHistorial tr');

        let resultadosEncontrados = 0;

        filas.forEach(fila => {
            const fecha = fila.cells[1].textContent;
            const ruta = fila.cells[2].textContent;

            const coincideFecha = !fechaFiltro || fecha === fechaFiltro;
            const coincideRuta = !rutaFiltro || ruta === rutaFiltro;

            if (coincideFecha && coincideRuta) {
                fila.style.display = '';
                resultadosEncontrados++;
            } else {
                fila.style.display = 'none';
            }
        });

        if (resultadosEncontrados === 0 && (fechaFiltro || rutaFiltro)) {
            Swal.fire({
                icon: 'info',
                title: 'Sin resultados',
                text: 'No se encontraron viajes con los filtros aplicados.',
                confirmButtonColor: '#667eea'
            });
        }
    }

    function limpiarFiltros() {
        document.getElementById('fechaHistorial').value = '';
        document.getElementById('filtroRuta').value = '';
        filtrarHistorial();

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
