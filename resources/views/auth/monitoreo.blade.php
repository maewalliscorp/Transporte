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
        body {
            font-family: "Open Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #E3F2FD 0%, #F3E5F5 100%);
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .container-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            border: 1px solid #E1F5FE;
            margin: 0 auto;
            max-width: 1400px;
        }

        .header-section {
            background: linear-gradient(135deg, #4FC3F7 0%, #7E57C2 100%);
            color: white;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 12px rgba(79, 195, 247, 0.3);
        }

        .header-section h4 {
            margin: 0;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .content-section {
            padding: 2rem;
        }

        .filter-section {
            background: #F8F9FA;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            border: 1px solid #E3F2FD;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #E1F5FE;
            transition: all 0.3s ease;
            background: white;
            padding: 10px 15px;
        }

        .form-control:focus, .form-select:focus {
            border-color: #4FC3F7;
            box-shadow: 0 0 0 0.3rem rgba(79, 195, 247, 0.2);
        }

        .form-label {
            font-weight: 600;
            color: #37474F;
            margin-bottom: 0.8rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4FC3F7 0%, #7E57C2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
            box-shadow: 0 4px 15px rgba(79, 195, 247, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 195, 247, 0.4);
            background: linear-gradient(135deg, #29B6F6 0%, #6A45B2 100%);
        }

        .btn-outline-secondary {
            border: 2px solid #4FC3F7;
            color: #4FC3F7;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin: 0 5px;
        }

        .btn-outline-secondary:hover {
            background: #4FC3F7;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 195, 247, 0.3);
        }

        .btn-outline-info {
            border: 2px solid #7E57C2;
            color: #7E57C2;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-info:hover {
            background: #7E57C2;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(126, 87, 194, 0.3);
        }

        /* Estilos para el mapa */
        #map {
            height: 500px;
            width: 100%;
            border-radius: 12px;
            border: 2px solid #E1F5FE;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        /* Estilos para las tarjetas de información */
        .card {
            border: 1px solid #E1F5FE;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }

        .card-header {
            background: linear-gradient(135deg, #4FC3F7 0%, #7E57C2 100%);
            color: white;
            border: none;
            font-weight: 600;
            padding: 1rem 1.25rem;
        }

        /* Estados de tiempo */
        .estado-tiempo {
            color: white;
            background: linear-gradient(135deg, #66BB6A 0%, #4CAF50 100%);
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .estado-retardo {
            color: white;
            background: linear-gradient(135deg, #FF7043 0%, #F4511E 100%);
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        /* Tabla estilizada */
        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .table-dark {
            background: linear-gradient(135deg, #2C3E50 0%, #34495E 100%);
        }

        .table-dark th {
            background: rgba(0, 0, 0, 0.2);
            border: none;
            padding: 1rem;
            font-weight: 600;
        }

        .table-hover tbody tr:hover {
            background: rgba(79, 195, 247, 0.1);
            transition: all 0.2s ease;
        }

        /* Radio buttons personalizados */
        .form-check-input:checked {
            background-color: #4FC3F7;
            border-color: #4FC3F7;
        }

        .form-check-label {
            font-weight: 500;
            color: #37474F;
        }

        /* Ventana de información del mapa */
        .info-window {
            padding: 15px;
            min-width: 250px;
        }

        .info-window h6 {
            color: #4FC3F7;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .info-window p {
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        /* Controles del mapa */
        #controls {
            margin-bottom: 15px;
            padding: 15px;
            background: #F8F9FA;
            border-radius: 10px;
            border: 1px solid #E3F2FD;
        }

        .filter-section {
            margin-top: 20px;
            margin-bottom: 20px;
            padding: 1.5rem;
            background: #F8F9FA;
            border-radius: 10px;
            border: 1px solid #E3F2FD;
        }
    </style>
</head>
<body>

<!-- AQUI MI MENÚ (NAVBAR) -->
@include('layouts.menuPrincipal')

<div class="container mt-4">
    <div class="container-card">
        <div class="header-section">
            <h4 class="mb-0">Sistema de Monitoreo de Unidades</h4>
            <p class="mb-0 mt-2">Seguimiento en tiempo real y control de puntualidad</p>
        </div>

        <div class="content-section">
            <!-- Filtro de monitoreo -->
            <div class="filter-section">
                <label class="form-label me-3 fw-bold">Tipo de Monitoreo:</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tipoMonitoreo" id="monitoreoGps" value="gps" checked>
                    <label class="form-check-label" for="monitoreoGps">Monitoreo GPS</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tipoMonitoreo" id="monitoreoPuntualidad" value="puntualidad">
                    <label class="form-check-label" for="monitoreoPuntualidad">Monitoreo de Puntualidad</label>
                </div>
            </div>

            <!-- Sección Monitoreo GPS -->
            <div id="seccionGPS">
                <!-- Filtro: Unidad de transporte SOLO para GPS -->
                <div class="row mb-4 align-items-end" id="filtroUnidad">
                    <div class="col-md-4">
                        <label for="unidadSelect" class="form-label">Unidad de Transporte</label>
                        <select class="form-select" id="unidadSelect">
                            <option value="" selected disabled>Selecciona una unidad</option>
                            <!-- Las opciones se cargarán dinámicamente -->
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="fechaSelect" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fechaSelect" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100" onclick="cargarUnidadEnMapa()">
                            <i class="bi bi-geo-alt"></i> Ver en Mapa
                        </button>
                    </div>
                </div>

                <!-- Controles del mapa -->
                <div id="controls" class="mb-3">
                    <button class="btn btn-outline-secondary btn-sm" onclick="mostrarTodasUnidades()">
                        <i class="bi bi-geo"></i> Mostrar todas las unidades
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="limpiarMapa()">
                        <i class="bi bi-trash"></i> Limpiar mapa
                    </button>
                    <button class="btn btn-outline-info btn-sm" onclick="centrarMapaCDMX()">
                        <i class="bi bi-geo-alt"></i> Centrar en CDMX
                    </button>
                </div>

                <h5 class="mb-3" style="color: #4FC3F7; font-weight: 600;">
                    <i class="bi bi-geo-alt-fill"></i> Recorrido en tiempo real (GPS)
                </h5>
                <div id="map">
                    <!-- Aquí se cargará Google Maps -->
                </div>
                <div class="mt-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Información de la Unidad Seleccionada</h6>
                                </div>
                                <div class="card-body" id="infoUnidad">
                                    Selecciona una unidad para ver su información
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="bi bi-graph-up"></i> Estadísticas</h6>
                                </div>
                                <div class="card-body" id="estadisticas">
                                    Unidades en mapa: <span id="contadorUnidades" class="fw-bold" style="color: #4FC3F7;">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección Monitoreo Puntualidad -->
            <div id="seccionPuntualidad" style="display: none;">
                <h5 class="mb-3" style="color: #7E57C2; font-weight: 600;">
                    <i class="bi bi-clock-fill"></i> Monitoreo de Puntualidad
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover mt-3">
                        <thead class="table-dark">
                        <tr>
                            <th>Unidad de Transporte</th>
                            <th>Operador</th>
                            <th>Ruta</th>
                            <th>Horario</th>
                            <th>Estado</th>
                            <th>Tiempo Retardo</th>
                        </tr>
                        </thead>
                        <tbody id="tablaPuntualidad">
                        <!-- Los datos se cargarán dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyApCh2ySGjVCuRiLan4-KhxTCqqkKt8GP8&callback=initMap" async defer></script>

<script>
    // Tu código JavaScript original permanece igual aquí...
    // Variables globales
    let map;
    let markers = [];
    let infoWindow;

    // Datos de ejemplo (simulados) - SOLO para GPS
    const unidadesGPS = [
        {
            id: 1,
            placa: "ABC123",
            modelo: "Volvo 2023",
            operador: "Juan Pérez",
            latitud: 19.4326,
            longitud: -99.1332,
            velocidad: 45,
            estado: "En ruta",
            ruta: "Central - Norte",
            ultimaActualizacion: "2024-01-15 10:30:00"
        },
        {
            id: 2,
            placa: "DEF456",
            modelo: "Mercedes 2022",
            operador: "María García",
            latitud: 19.4426,
            longitud: -99.1432,
            velocidad: 0,
            estado: "Detenido",
            ruta: "Sur - Este",
            ultimaActualizacion: "2024-01-15 10:25:00"
        },
        {
            id: 3,
            placa: "GHI789",
            modelo: "Scania 2023",
            operador: "Carlos López",
            latitud: 19.4226,
            longitud: -99.1232,
            velocidad: 60,
            estado: "En ruta",
            ruta: "Oeste - Centro",
            ultimaActualizacion: "2024-01-15 10:28:00"
        }
    ];

    // Datos de ejemplo para Puntualidad - SEPARADOS
    const datosPuntualidad = [
        {
            placa: "ABC123",
            modelo: "Volvo 2023",
            operador: "Juan Pérez",
            ruta: "Central - Norte",
            horario: "08:00 - 10:00",
            estado: "a_tiempo",
            retardo: 0
        },
        {
            placa: "DEF456",
            modelo: "Mercedes 2022",
            operador: "María García",
            ruta: "Sur - Este",
            horario: "09:00 - 11:00",
            estado: "retardo",
            retardo: 15
        },
        {
            placa: "GHI789",
            modelo: "Scania 2023",
            operador: "Carlos López",
            ruta: "Oeste - Centro",
            horario: "10:00 - 12:00",
            estado: "a_tiempo",
            retardo: 0
        },
        {
            placa: "JKL012",
            modelo: "Volvo 2022",
            operador: "Ana Rodríguez",
            ruta: "Norte - Sur",
            horario: "07:00 - 09:00",
            estado: "retardo",
            retardo: 8
        },
        {
            placa: "MNO345",
            modelo: "Mercedes 2023",
            operador: "Pedro Sánchez",
            ruta: "Este - Oeste",
            horario: "11:00 - 13:00",
            estado: "a_tiempo",
            retardo: 0
        }
    ];

    // Inicializar el mapa de Google
    function initMap() {
        // Centro inicial (Ciudad de México)
        const center = { lat: 19.4326, lng: -99.1332 };

        // Configuración del mapa
        const mapOptions = {
            zoom: 12,
            center: center,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: [
                {
                    featureType: "poi",
                    elementType: "labels",
                    stylers: [{ visibility: "off" }]
                }
            ]
        };

        // Crear el mapa
        map = new google.maps.Map(document.getElementById('map'), mapOptions);

        // Crear ventana de información
        infoWindow = new google.maps.InfoWindow();

        // Cargar unidades en el select
        cargarUnidadesEnSelect();

        // Mostrar todas las unidades al inicio
        mostrarTodasUnidades();

        // Cargar datos de puntualidad
        cargarDatosPuntualidad();
    }

    // Cargar unidades en el select
    function cargarUnidadesEnSelect() {
        const select = document.getElementById('unidadSelect');
        select.innerHTML = '<option value="" selected disabled>Selecciona una unidad</option>';

        unidadesGPS.forEach(unidad => {
            const option = document.createElement('option');
            option.value = unidad.id;
            option.textContent = `${unidad.placa} - ${unidad.modelo} - ${unidad.operador}`;
            option.setAttribute('data-lat', unidad.latitud);
            option.setAttribute('data-lng', unidad.longitud);
            select.appendChild(option);
        });
    }

    // Mostrar todas las unidades en el mapa
    function mostrarTodasUnidades() {
        limpiarMapa();

        unidadesGPS.forEach(unidad => {
            crearMarcador(unidad);
        });

        actualizarContadorUnidades();
        centrarMapaEnUnidades();
    }

    // Crear un marcador en el mapa
    function crearMarcador(unidad) {
        const position = { lat: unidad.latitud, lng: unidad.longitud };

        // Icono personalizado según el estado
        let iconUrl = 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png';
        if (unidad.velocidad === 0) {
            iconUrl = 'http://maps.google.com/mapfiles/ms/icons/red-dot.png';
        } else if (unidad.velocidad > 50) {
            iconUrl = 'http://maps.google.com/mapfiles/ms/icons/green-dot.png';
        }

        const marker = new google.maps.Marker({
            position: position,
            map: map,
            title: `${unidad.placa} - ${unidad.operador}`,
            icon: {
                url: iconUrl,
                scaledSize: new google.maps.Size(32, 32)
            }
        });

        // Contenido de la ventana de información
        const contentString = `
      <div class="info-window">
        <h6><strong>${unidad.placa} - ${unidad.modelo}</strong></h6>
        <p><strong>Operador:</strong> ${unidad.operador}</p>
        <p><strong>Estado:</strong> ${unidad.estado}</p>
        <p><strong>Velocidad:</strong> ${unidad.velocidad} km/h</p>
        <p><strong>Ruta:</strong> ${unidad.ruta}</p>
        <p><strong>Última actualización:</strong><br>${unidad.ultimaActualizacion}</p>
        <button class="btn btn-sm btn-primary mt-1" onclick="seleccionarUnidad(${unidad.id})">
          Seguir esta unidad
        </button>
      </div>
    `;

        // Evento click para mostrar información
        marker.addListener('click', () => {
            infoWindow.setContent(contentString);
            infoWindow.open(map, marker);
            mostrarInfoUnidad(unidad);
        });

        markers.push(marker);
        return marker;
    }

    // Cargar unidad específica en el mapa
    function cargarUnidadEnMapa() {
        const select = document.getElementById('unidadSelect');
        const unidadId = parseInt(select.value);

        if (!unidadId) {
            alert('Por favor selecciona una unidad');
            return;
        }

        const unidad = unidadesGPS.find(u => u.id === unidadId);
        if (unidad) {
            limpiarMapa();
            const marker = crearMarcador(unidad);

            // Centrar el mapa en la unidad seleccionada
            map.setCenter(marker.getPosition());
            map.setZoom(15);

            // Mostrar información
            infoWindow.setContent(`
        <div class="info-window">
          <h6><strong>${unidad.placa} - ${unidad.modelo}</strong></h6>
          <p><strong>Operador:</strong> ${unidad.operador}</p>
          <p><strong>Estado:</strong> ${unidad.estado}</p>
          <p><strong>Velocidad:</strong> ${unidad.velocidad} km/h</p>
        </div>
      `);
            infoWindow.open(map, marker);

            mostrarInfoUnidad(unidad);
            actualizarContadorUnidades();
        }
    }

    // Seleccionar unidad desde el mapa
    function seleccionarUnidad(unidadId) {
        document.getElementById('unidadSelect').value = unidadId;
        cargarUnidadEnMapa();
    }

    // Mostrar información de la unidad en el panel
    function mostrarInfoUnidad(unidad) {
        const infoDiv = document.getElementById('infoUnidad');
        infoDiv.innerHTML = `
      <h6><strong>${unidad.placa} - ${unidad.modelo}</strong></h6>
      <p><strong>Operador:</strong> ${unidad.operador}</p>
      <p><strong>Estado:</strong> ${unidad.estado}</p>
      <p><strong>Velocidad:</strong> ${unidad.velocidad} km/h</p>
      <p><strong>Ruta:</strong> ${unidad.ruta}</p>
      <p><strong>Última actualización:</strong><br>${unidad.ultimaActualizacion}</p>
      <p><strong>Ubicación:</strong><br>Lat: ${unidad.latitud.toFixed(6)}<br>Lng: ${unidad.longitud.toFixed(6)}</p>
    `;
    }

    // Centrar mapa en CDMX
    function centrarMapaCDMX() {
        const cdmx = { lat: 19.4326, lng: -99.1332 };
        map.setCenter(cdmx);
        map.setZoom(12);
    }

    // Centrar mapa para mostrar todas las unidades
    function centrarMapaEnUnidades() {
        if (markers.length === 0) return;

        const bounds = new google.maps.LatLngBounds();
        markers.forEach(marker => {
            bounds.extend(marker.getPosition());
        });

        map.fitBounds(bounds);
    }

    // Actualizar contador de unidades
    function actualizarContadorUnidades() {
        document.getElementById('contadorUnidades').textContent = markers.length;
    }

    // Limpiar el mapa
    function limpiarMapa() {
        markers.forEach(marker => marker.setMap(null));
        markers = [];
        infoWindow.close();
        document.getElementById('infoUnidad').innerHTML = 'Selecciona una unidad para ver su información';
        actualizarContadorUnidades();
    }

    // Cargar datos de puntualidad
    function cargarDatosPuntualidad() {
        const tbody = document.getElementById('tablaPuntualidad');
        tbody.innerHTML = '';

        datosPuntualidad.forEach(unidad => {
            const tr = document.createElement('tr');
            const estadoClass = unidad.estado === 'a_tiempo' ? 'estado-tiempo' : 'estado-retardo';
            const estadoText = unidad.estado === 'a_tiempo' ? 'A Tiempo' : 'Con Retardo';

            tr.innerHTML = `
        <td>${unidad.placa} - ${unidad.modelo}</td>
        <td>${unidad.operador}</td>
        <td>${unidad.ruta}</td>
        <td>${unidad.horario}</td>
        <td><span class="${estadoClass}">${estadoText}</span></td>
        <td>${unidad.retardo} min</td>
      `;
            tbody.appendChild(tr);
        });
    }

    // Alternar entre monitoreos
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
            filtroUnidad.style.display = 'none';
        }
    }

    // Event listeners
    radioGps.addEventListener('change', actualizarMonitoreo);
    radioPuntualidad.addEventListener('change', actualizarMonitoreo);

    // Inicializar cuando cargue la página
    window.onload = actualizarMonitoreo;
</script>

</body>
</html>
