<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Monitoreo de unidades</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    .filter-section {
    margin-top: 20px;
      margin-bottom: 20px;
    }
    #map {
height: 400px;
width: 100%;
      background-color: #eaeaea;
}
.estado-tiempo {
    color: white;
    background-color: green;
      padding: 5px 10px;
      border-radius: 5px;
    }
    .estado-retardo {
    color: white;
    background-color: red;
      padding: 5px 10px;
      border-radius: 5px;
    }
  </style>
</head>
<body>

<!-- AQUI MI MENÚ (NAVBAR) -->
  @include('layouts.menuPrincipal')

<div class="container">
    <!--  Filtro de monitoreo -->
    <div class="filter-section">
        <label class="form-label me-3">Tipo de Monitoreo:</label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tipoMonitoreo" id="monitoreoGps" value="gps" checked>
            <label class="form-check-label" for="monitoreoGps">Monitoreo GPS</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tipoMonitoreo" id="monitoreoPuntualidad" value="puntualidad">
            <label class="form-check-label" for="monitoreoPuntualidad">Monitoreo de Puntualidad</label>
        </div>
    </div>

    <!--  Filtro: Unidad de transporte -->
    <div class="row mb-4" id="filtroUnidad">
        <div class="col-md-4">
            <label for="unidadSelect" class="form-label">Unidad de Transporte</label>
            <select class="form-select" id="unidadSelect">
                <option selected disabled>Selecciona una unidad</option>
                <!-- Opciones por agregar desde base de datos -->
            </select>
        </div>
    </div>

    <!--  Sección Monitoreo GPS -->
    <div id="seccionGPS">
        <h5>Recorrido en tiempo real (GPS)</h5>
        <div id="map">
            <!-- Aquí se mostrará el mapa -->
            <p class="text-center pt-5">[ Mapa en vivo aquí ]</p>
        </div>
    </div>

    <!-- ⏱ Sección Monitoreo Puntualidad -->
    <div id="seccionPuntualidad" style="display: none;">
        <h5>Monitoreo de Puntualidad</h5>
        <table class="table table-bordered mt-3">
            <thead class="table-light">
            <tr>
                <th>Unidad de Transporte</th>
                <th>Operador</th>
                <th>Ruta</th>
                <th>Horario</th>
                <th>Estado</th>
            </tr>
            </thead>
            <tbody>
            <!-- Ejemplo de filas; se llenarán desde la base de datos -->
            <tr>
                <td>Unidad 12</td>
                <td>Laura Iglesias</td>
                <td>Ruta A</td>
                <td>08:00 AM</td>
                <td><span class="estado-tiempo">A Tiempo</span></td>
            </tr>
            <tr>
                <td>Unidad 24</td>
                <td>Gabriel Bravo</td>
                <td>Ruta B</td>
                <td>09:15 AM</td>
                <td><span class="estado-retardo">Con Retardo</span></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Lógica para alternar entre monitoreos -->
<script>
    const radioGps = document.getElementById('monitoreoGps');
    const radioPuntualidad = document.getElementById('monitoreoPuntualidad');
    const seccionGPS = document.getElementById('seccionGPS');
    const seccionPuntualidad = document.getElementById('seccionPuntualidad');
    const filtroUnidad = document.getElementById('filtroUnidad');

    function actualizarMonitoreo() {
        if (radioGps.checked) {
            seccionGPS.style.display = 'block';
            seccionPuntualidad.style.display = 'none';
            filtroUnidad.style.display = 'block';
        } else {
            seccionGPS.style.display = 'none';
            seccionPuntualidad.style.display = 'block';
            filtroUnidad.style.display = 'block';
        }
    }

    radioGps.addEventListener('change', actualizarMonitoreo);
    radioPuntualidad.addEventListener('change', actualizarMonitoreo);
    window.onload = actualizarMonitoreo;
</script>
</body>
</html>

